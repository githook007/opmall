<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/17
 * Time: 16:41
 */

namespace app\forms\pc\share;

use app\bootstrap\response\ApiCode;
use app\forms\common\share\CommonShare;
use app\models\Mall;
use app\models\Share;
use app\models\ShareSetting;
use app\models\User;

/**
 * @property User $user
 * @property Mall $mall
 */
class ShareApplyForm extends \app\forms\api\share\ShareApplyForm
{
    public $agree = 1;
//    public
    /**
     * @return array
     */
    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            $this->mall = \Yii::$app->mall;
            $this->user = \Yii::$app->user->identity;
            if (!$this->checkApply()) {
                throw new \Exception('不满足条件无法申请');
            }
            if ($this->agree == 0) {
                throw new \Exception('请先查看分销协议并同意');
            }
            $attributes = [
                'status' => 0,
                'name' => $this->name,
                'mobile' => $this->mobile,
                'apply_at' => mysql_timestamp(),
            ];
            $commonShare = CommonShare::getCommon();
            $commonShare->becomeShare($this->user, $attributes);
            $t->commit();
            return [
                'code' => 0,
                'msg' => '申请分销商成功'
            ];
        } catch (\Exception $e) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }

    /**
     * @return array
     * status展示的状态 0--分销商申请中 1--已经是分销商 2--可以申请分销商 3--条件未满足 4--分销商申请被拒绝 5--分销商被删除
     */
    public function getShareStatus()
    {
        $model = Share::findOne([
            'mall_id' => \Yii::$app->mall->id, 'user_id' => \Yii::$app->user->id
        ]);
        if ($model && $model->is_delete == 0 && $model->status != 2) {
            $status = $model->status;
        } else {
            if (!$model) {
                $status = 3;
            } else {
                if ($model->delete_first_show == 0) {
                    if ($model->status == 1 && $model->is_delete == 1) {
                        $status = 5;
                    } else {
                        $status = 4;
                    }
                    $model->delete_first_show = 1;
                    $model->save();
                } else {
                    $status = 3;
                }
            }
            try {
                $isCanApply = $this->checkApply();
                if ($status == 3 && $isCanApply) {
                    $status = 2;
                }
            } catch (\Exception $exception) {}
        }
        $agree = ShareSetting::get($this->mall->id, ShareSetting::AGREE);
        return [
            'code' => 0,
            'msg' => '数据请求成功',
            'data' => [
                'share' => [],
                'status' => $status,
                "agree_content" => $agree,
            ]
        ];
    }
}
