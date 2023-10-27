<?php
/**
 * Created by PhpStorm.
 * User: chenzs
 * Date: 2019/10/19
 * Time: 14:17
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\mall\mall_member;

use app\bootstrap\response\ApiCode;
use app\models\GoodsWarehouse;
use app\models\MallMemberGoods;
use app\models\Model;

class GoodsForm extends Model
{
    public $search;

    public function rules()
    {
        return [
            [['search'], 'safe'],
        ];
    }

    public function getGoods()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $search = (array)\Yii::$app->serializer->decode($this->search);

            $memberGoods = MallMemberGoods::find()->where([
                "mall_id" => \Yii::$app->mall->id,
                "is_delete" => 0
            ])
                ->keyword(!empty($search['id']), ["!=", "member_id", $search['id']])
                ->select("goods_warehouse_id");

            $goodsList = GoodsWarehouse::find()->where([
                'is_delete' => 0, 'mall_id' => \Yii::$app->mall->id,
            ])
                ->keyword(!empty($search['keyword']), ["like", "name", $search['keyword']])
                ->andWhere(['not in', 'id', $memberGoods])
                ->page($pagination)
                ->all();
            $data = [];
            /** @var GoodsWarehouse $goods */
            foreach ($goodsList as $goods) {
                $data[] = [
                    'id' => $goods->id,
                    'name' => $goods->name,
                    'cover_pic' => $goods->cover_pic,
                ];
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '成功',
                'data' => [
                    "list" => $data,
                    "pagination" => $pagination
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }
}