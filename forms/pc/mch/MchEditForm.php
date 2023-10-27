<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */

namespace app\forms\pc\mch;

use app\bootstrap\response\ApiCode;
use app\plugins\mch\forms\common\MchEditFormBase;
use app\plugins\mch\models\Mch;

class MchEditForm extends MchEditFormBase
{
    public $form_data;
    public $is_update_apply;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['form_data'], 'safe'],
            [['is_update_apply'], 'integer'],
        ]);
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->checkData();
            $this->setMch();
            $this->setStore();
            $this->setMallMchSetting();
            $this->setMchSetting();
            $this->setUser();

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    protected function setMch()
    {
        if ($this->id) {
            $mch = $this->getMch();
            // 用户重新申请入驻
            if ($this->is_update_apply == 1) {
                $mch->review_status = 0;
                $mch->review_remark = '用户重新申请:' . \Yii::$app->user->identity->nickname;
            }
        } else {
            $mch = Mch::find()->where([
                'user_id' => \Yii::$app->user->id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id,
            ])->andWhere(['review_status' => 1])->one();

            if ($mch) {
                throw new \Exception('已是入驻商户！请登录');
            }
            $mch = new Mch();
            $mch->mall_id = \Yii::$app->mall->id;
        }

        $mch->user_id = $this->user_id ?: \Yii::$app->user->id;
        $mch->realname = $this->realname;
        $mch->mobile = $this->mobile;
        $mch->mch_common_cat_id = $this->mch_common_cat_id;
        $mch->wechat = $this->wechat;
        $mch->form_data = $this->form_data;

        $res = $mch->save();
        if (!$res) {
            throw new \Exception($this->getErrorMsg($mch));
        }
        $this->mch = $mch;
    }

    private function checkData()
    {
        if ($this->form_data) {
            $formData = \Yii::$app->serializer->decode($this->form_data);
            foreach ($formData as $item) {
                if ($item['required'] && !$item['value']) {
                    throw new \Exception($item['label'] . '不能为空');
                }
            }
        } else {
            $this->form_data = \Yii::$app->serializer->encode([]);
        }
    }
}
