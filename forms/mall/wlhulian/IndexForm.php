<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
namespace app\forms\mall\wlhulian;

use app\bootstrap\response\ApiCode;
use app\forms\admin\logistics\WlForm;
use app\forms\wlhulian\api\ShopSupplierQuery;
use app\forms\wlhulian\api\SupplierQuery;
use app\forms\wlhulian\ApiForm;
use app\helpers\ArrayHelper;
use app\models\Model;
use app\models\WlhulianWalletLog;

class IndexForm extends Model
{
    public $order_no;
    public $page_size;
    public $keyword;
    public $type;
    public $time;
    public $tab_name;

    public function rules()
    {
        return [
            [['page_size', 'type'], 'integer'],
            [['order_no', 'keyword', 'tab_name'], 'string'],
            [['time'], 'safe'],
            [['page_size'], 'default', 'value' => 10],
        ];
    }

    //GET
    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if($this->tab_name == 'second'){ // 日志列表
            $query = WlhulianWalletLog::find()->where([
                'mall_id' => \Yii::$app->mall->id,
            ])->keyword($this->type, ['type' => $this->type]);

            if($this->time){
                $query->andWhere(['>=', 'created_at', $this->time[0]]);
                $query->andWhere(['<=', 'created_at', $this->time[1]]);
            }

            $list = $query->with("user.identity")
                ->orderBy('id DESC')
                ->page($pagination)
                ->all();

            $newList = [];
            /** @var WlhulianWalletLog $item */
            foreach ($list as $item){
                $new = ArrayHelper::toArray($item);
                if($item->user->identity->is_super_admin){
                    $new['nickname'] = '超级管理员';
                }else{
                    if(\Yii::$app->user->id == $item->user_id){
                        $new['nickname'] = '本人';
                    }else{
                        $new['nickname'] = $item->user->nickname;
                    }
                }
                $newList[] = $new;
            }
            $data = [
                'list' => $newList,
                'pagination' => $pagination,
            ];
        }else{
            $model = \Yii::$app->mall->wlHulian;
            if(!$model){
                throw new \Exception('不存在');
            }
            $setting = \Yii::$app->mall->getMallSetting(['longitude', 'latitude', 'contact_tel', 'quick_map_address']);
            $setting['delivery_supplier_list'] = $model->delivery_supplier_list ? \Yii::$app->serializer->decode($model->delivery_supplier_list) : [];
            $setting['industry_type'] = $model->industry_type;

            $form = new WlForm();
            $api = new ApiForm($form->getOption());
            $api->object = (new SupplierQuery());
            $delivery_supplier = $api->request();

            $isAudit = false;
            if($model->shop_id) {
                $object = new ShopSupplierQuery();
                $object->outShopId = $model->shop_id;
                $api->object = $object;
                $res = $api->request();
                foreach ($res as $item){
                    if($item['checkStatus'] == 1){
                        $isAudit = true;
                        break;
                    }
                }
            }

            $data = [
                'list' => $setting,
                'info' => [
                    'balance' => $model->balance,
                    'is_audit' => $isAudit
                ],
                'industry_type' => $form->getIndustryType(),
                'delivery_supplier' => $delivery_supplier,
                'sdf' => $res ?? []
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $data
        ];
    }

    public function queryOrder(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $exists = WlhulianWalletLog::find()->where(['mall_id' => \Yii::$app->mall->id, "order_no" => $this->order_no])->exists();

        if($exists){
             return [
                 'code' => ApiCode::CODE_SUCCESS,
                 'data' => [
                     'is_pay' => 1
                 ]
             ];
        }else{
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '未支付'
            ];
        }
    }
}
