<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\mch\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\CouponCenter;
use app\models\Model;
use app\plugins\mch\models\MchMallSetting;

class MchMallSettingEditForm extends Model
{
    public $id;
    public $is_share;
    public $is_coupon; // @czs
    public $mch_id;

    public function rules()
    { // @czs
        return [
            [['id', 'is_share', 'mch_id', 'is_coupon'], 'integer'],
            [['is_share', 'mch_id', 'is_coupon'], 'required'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            if ($this->id) {
                $model = MchMallSetting::findOne($this->id);

                if (!$model) {
                    throw new \Exception('商户设置异常');
                }
            } else {
                $model = new MchMallSetting();
                $model->mall_id = \Yii::$app->mall->id;
                $model->mch_id = $this->mch_id;
            }

            $model->is_share = $this->is_share;
            $model->is_coupon = $this->is_coupon; // @czs
            $res = $model->save();

            if (!$res) {
                throw new \Exception($this->getErrorMsg($model));
            }
            if($model->is_coupon != 1){ // 把商户优惠券从领券中心移除  @czs
                \Yii::$app->db->createCommand()
                    ->setSql("UPDATE " . CouponCenter::tableName() . " SET `is_delete` = 1 where `mall_id` = {$model->mall_id} and `mch_id` = {$this->mch_id}")
                    ->execute();
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }
}
