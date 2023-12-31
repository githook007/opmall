<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 16:56
 */

namespace app\commands;

use app\forms\common\CommonOption;
use app\forms\common\order\weixin\OrderForm;
use app\forms\open3rd\ExtAppForm;
use app\models\Attachment;
use app\models\Goods;
use app\models\Mall;
use app\models\MallExtend;
use app\models\MallGoods;
use app\models\Model;
use app\models\ModelActiveRecord;
use app\models\Option;
use app\models\Order;
use app\models\PaymentOrderUnion;
use app\plugins\wxapp\forms\wx_app_config\WxAppConfigForm;
use app\plugins\wxapp\models\WxappWxminiprogramAudit;
use yii\console\Controller;
use yii\db\Expression;

class JavascriptController extends Controller
{
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        set_time_limit(0); // 取消脚本运行时间的超时上限
        ignore_user_abort(true); // 后台运行
        ini_set('memory_limit',-1); //没有内存限制
    }

    /**
     * 第三方平台自动给商城发布小程序
     * @return void
     */
    public function actionPublishWxapp($hostInfo, $baseUrl){
        try {
            $ext = ExtAppForm::instance(null, 1);
            $list = $ext->templateList();
            $template = null;
            if (!empty($list->template_list)) {
                $temp = array_column($list->template_list, 'create_time');
                array_multisort($temp, SORT_ASC, $list->template_list);
                foreach ($list->template_list as $item) {
                    if ($item->template_type == 0) { // 目前支持普通模板库 @czs
                        $template = $item;
                    }
                }
            }
            if(!$template){
                echo "没有模板";
                return;
            }
        }catch (\Exception $e){
            echo "第三方未配置";
            return;
        }

        \Yii::$app->hostInfo = $hostInfo ?: "https://gomall.opmall.com";
        \Yii::$app->baseUrl = $baseUrl ?: "/web/shoproot.php";
        $text = mysql_timestamp() . '===模板id：'.$template->template_id;
        $orderQuery = Order::find()->where([
            'is_delete' => '0',
            'is_pay' => 1
        ])->andWhere(new Expression("mall_id = m.id"))->select("count(*)");
        $mallList = Mall::find()->alias("m")->where([
            'and', [
                'm.is_delete' => 0,
                'm.is_recycle' => 0,
                'm.is_disable' => 0,
//                'm.id' => [17, 20]
            ], [
                'or', ['>', "m.expired_at", mysql_timestamp()], ['=', "m.expired_at", '0000-00-00 00:00:00']
            ]
        ])->select(["m.*", "_count" => $orderQuery])
            ->asArray()->orderBy(['_count' => SORT_DESC])->all();
        foreach ($mallList as $mall){
            $mall = Mall::findOne($mall['id']);
            \Yii::$app->setMall($mall);
            try {
                $ext = ExtAppForm::instance();
                /**@var WxappWxminiprogramAudit $audit**/
                $audit = WxappWxminiprogramAudit::find()
                    ->where(['appid' => $ext->authorizer_appid, "is_delete" => 0])
                    ->orderBy('id desc')
                    ->one();
                if($audit && !in_array($audit->status, [1,3]) && $audit->template_id == $template->template_id){
                    echo "{$mall->id} 已处理";
                    $text .= "{$mall->id} 已处理\r\n";
                    continue;
                }
                $publish = false;
                /** @var WxappWxminiprogramAudit $lastAudit */
                $lastAudit = WxappWxminiprogramAudit::find()
                    ->where(['appid' => $ext->authorizer_appid, 'status' => 4])
                    ->orderBy('id desc')
                    ->one();
                if(!$lastAudit) {
                    $publish = true;
                }else{
                    $version = explode(".", $lastAudit->version);
                    $t_V = explode(".", $template->user_version);
                    for($i = 0;$i < count($t_V);$i++) {
                        if($t_V[$i] > $version[$i]) {
                            $publish = true;
                            break;
                        }
                    }
                }
                if(!$publish){
                    echo "{$mall->id} 无新版本更新";
                    $text .= "{$mall->id} 无新版本更新\r\n";
                    continue;
                }
                $ext->uploadCode($template->template_id, 0, $template->user_version, \Yii::$app->mall->name);

                do {
                    try {
                        $submit = $ext->submitReview();
                        echo "{$mall->id} 审核id：{$submit}";
                        $text .= "{$mall->id} 审核id：{$submit}\r\n";
                        break;
                    } catch (\Exception $e) {
                        if ($e->getCode() == 61039) {
                            sleep(1);
                        }else{
                            throw $e;
                        }
                    }
                }while(true);

                $audit = new WxappWxminiprogramAudit();
                $audit->appid = $ext->authorizer_appid;
                $audit->template_id = $template->template_id;
                $audit->version = $template->user_version;
                $audit->auditid = (string)$submit;
                $audit->status = 2;
                if (!$audit->save()) {
                    throw new \Exception((new Model())->getErrorMsg($audit));
                }
            }catch (\Exception $e){
                echo "{$mall->id} 错误消息" . $e->getMessage()."===".$e->getCode();
                $text .= "{$mall->id} 错误消息" . $e->getMessage() . "\r\n";
                continue;
            }
        }
        if($text){
            file_put_contents(__DIR__."/log.txt", $text, FILE_APPEND);
        }
    }

    public function actionPriv(){

        $text = mysql_timestamp();
        $orderQuery = Order::find()->where([
            'is_delete' => '0',
            'is_pay' => 1
        ])->andWhere(new Expression("mall_id = m.id"))->select("count(*)");
        $mallList = Mall::find()->alias("m")->where([
            'and', [
                'm.is_delete' => 0,
                'm.is_recycle' => 0,
                'm.is_disable' => 0,
            ], [
                'or', ['>', "m.expired_at", mysql_timestamp()], ['=', "m.expired_at", '0000-00-00 00:00:00']
            ]
        ])->select(["m.*", "_count" => $orderQuery])
            ->asArray()->orderBy(['_count' => SORT_DESC])->all();
        foreach ($mallList as $mall){
            $mall = Mall::findOne($mall['id']);
            \Yii::$app->setMall($mall);
            try {
                $ext = ExtAppForm::instance();

                $form = new WxAppConfigForm();
                $res = $form->getPrivacyInfo();
                $listData = $res['data']['list'];
                $add = true;
                foreach ($listData['setting_list'] as $item){
                    if($item['privacy_key'] == 'UserInfo'){
                        $add = false;
                        break;
                    }
                }
                if($add){
                    $listData['setting_list'][] = [
                        'privacy_key' => 'UserInfo',
                        'privacy_text' => '帮助用户完善并展示个人信息',
                    ];
                }
                $ext->setPrivacySetting($listData['owner_setting'], $listData['setting_list'], 2);
                CommonOption::set(Option::NAME_WX_MINI_PRIVACY, $listData, \Yii::$app->mall->id, Option::GROUP_APP);
            }catch (\Exception $e){
                echo "{$mall->id} 错误消息" . $e->getMessage()."===".$e->getCode();
                $text .= "{$mall->id} 错误消息" . $e->getMessage() . "\r\n";
                continue;
            }
        }
    }

    public function actionTradeManage(){
        $mallList = Mall::find()->alias("m")->where([
            'and', [
                'm.is_delete' => 0,
                'm.is_recycle' => 0,
                'm.is_disable' => 0,
//                'm.id' => [1,109]
            ], [
                'or', ['>', "m.expired_at", mysql_timestamp()], ['=', "m.expired_at", '0000-00-00 00:00:00']
            ]
        ])->select(["m.*"])
            ->all();
        foreach ($mallList as $mall){
            try {
                \Yii::$app->setMall($mall);
                $wechat = \Yii::$app->plugin->getPlugin('wxapp')->getWechat(true);
                foreach ([1, 2] as $index){
                    $last_index = '';
                    do{
                        $res = OrderForm::getCommon(['accessToken' => $wechat->getAccessToken(), 'appId' => $wechat->appId])
                            ->setTradeManaged()->getOrderList(50, $index, $last_index);
                        if($res){
                            $last_index = $res->last_index ?: '';
                            if($res->order_list){
                                foreach ($res->order_list as $item){
                                    $paymentOrderUnion = PaymentOrderUnion::findOne(['order_no' => $item->merchant_trade_no]);
                                    if($paymentOrderUnion) {
                                        OrderForm::getCommon(['paymentOrderUnion' => $paymentOrderUnion])
                                            ->saveOrder($item);
                                    }
                                }
                            }
                        }else{
                            break;
                        }
                    }while($last_index);
                }

            }catch (\Exception $e){
                echo $e->getMessage();
            }
        }
    }

    /**
     * 系统更新处理数据
     * @return void
     */
    public function actionHandle1(){
        ModelActiveRecord::$log = false;

        $goodsList = Goods::find()
            ->where(['is_delete' => 1, "sign" => ['', 'mch']])
            ->with("goodsWarehouse")
            ->all();

        /** @var Goods $goods */
        foreach ($goodsList as $goods){
            // 删除商城商品
            MallGoods::updateAll([
                'is_delete' => 1,
                'deleted_at' => mysql_timestamp()
            ], [
                'goods_id' => $goods->id,
                'is_delete' => 0,
            ]);
            $goods->goodsWarehouse->is_delete = 1;
            $goods->goodsWarehouse->save();

            Goods::updateAll([
                'is_delete' => 1,
                'deleted_at' => mysql_timestamp()
            ], [
                'goods_warehouse_id' => $goods->goods_warehouse_id,
                'is_delete' => 0
            ]);
        }
        $mallList = Mall::find()->where(['is_delete' => 0])->with("extend")->all();
        /** @var Mall $mall */
        foreach ($mallList as $mall){
            $attachmentSize = Attachment::find()->where(['mall_id' => $mall->id, "is_delete" => 0])->sum("size");
            $extend = $mall->extend;
            if(!$extend){
                $extend = new MallExtend();
                $extend->mall_id = $mall->id;
            }
            $extend->used_memory = round($attachmentSize / 1024 / 1024, 8);
            $extend->save();
        }

        \Yii::warning("Handle1 处理完成");
    }
}
