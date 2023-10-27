<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/10/19
 * Time: 13:53
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\mall\share;


use app\bootstrap\response\ApiCode;
use app\forms\common\share\CommonShareLevel;
use app\models\Model;
use app\models\ShareLevel;
use app\models\ShareLevelGoods;
use app\models\ShareSetting;

class LevelForm extends Model
{
    public $keyword;
    public $page;

    public $id;

    public function rules()
    {
        return [
            [['keyword'], 'string'],
            [['keyword'], 'trim'],
            [['page', 'id'], 'integer'],
            [['page'], 'default', 'value' => 1]
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $list = ShareLevel::find()->where([
            'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
        ])->keyword($this->keyword, ['like', 'name', $this->keyword])
            ->page($pagination, 20, $this->page)->orderBy(['level' => SORT_ASC])->all();
        $level = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::LEVEL, 0);
        array_walk($list, function (&$item) use ($level) {
            $item->condition = round($item->condition, 2);
            switch ($level) {
                case 0:
                    $item->first = -1;
                    $item->second = -1;
                    $item->third = -1;
                    break;
                case 1:
                    $item->second = -1;
                    $item->third = -1;
                    break;
                case 2:
                    $item->third = -1;
                    break;
                default:
            }
        });
        unset($item);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'list' => $list,
                'pagination' => $pagination
            ]
        ];
    }

    // czs 等级支持购买商品升级
    public function detail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $become_condition = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::BECOME_CONDITION, 0);

        $detail = CommonShareLevel::getInstance()->getDetail(\Yii::$app->request->get('id'));
        $goods_list = [];
        if(in_array($become_condition, [2, 3])){
            $model = ShareLevelGoods::find()->where([
                "mall_id" => \Yii::$app->mall->id,
                "is_delete" => 0,
                "level_id" => $detail['id']
            ])->with("goodsWarehouse")->all();
            /** @var ShareLevelGoods $levelGoods */
            foreach ($model as $levelGoods){
                $goods_list[] = [
                    'id' => $levelGoods->goodsWarehouse->id,
                    'name' => $levelGoods->goodsWarehouse->name,
                    'cover_pic' => $levelGoods->goodsWarehouse->cover_pic,
                ];
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'detail' => $detail,
                "goods_list" => $goods_list
            ]
        ];
    }
}
