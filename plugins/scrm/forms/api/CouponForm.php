<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/28
 * Time: 2:50 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\common\coupon\CommonCoupon;
use app\forms\common\coupon\CouponMallRelation;
use app\forms\common\template\order_pay_template\ActivitySuccessInfo;
use app\forms\common\template\TemplateList;
use app\models\Coupon;
use app\models\User;
use app\models\UserIdentity;
use app\models\UserInfo;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;

class CouponForm extends Model
{
    public $user_id;
    public $coupon_id;
    public $coupon_num;

    public function rules()
    {
        return [
            [['user_id', 'coupon_id'], 'required'],
            [['user_id', 'coupon_id', 'coupon_num'], 'integer'],
            [['coupon_num'], 'default', 'value' => 1]
        ];
    }

    public function getList()
    {
        /** @var Coupon[] $list */
        $list = Coupon::find()->alias('c')->where([
            'c.mall_id' => \Yii::$app->mall->id,
            'c.is_delete' => 0,
        ])->page($pagination)->orderBy('c.sort ASC,c.id DESC')->asArray()->all();
        return $this->success([
            'list' => $list
        ]);
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $model = Coupon::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'id' => $this->coupon_id,
                'is_delete' => 0,
            ]);

            if (!$model) {
                throw new ScrmException('优惠券不存在');
            }

            if ($this->coupon_num == 0) {
                throw new ScrmException('发送数量不能为空');
            }

            if ($model->total_count < $this->coupon_num && $model->total_count != -1) {
                throw new ScrmException('优惠券数量不足');
            }

            $user = User::find()->where([
                'id' => $this->user_id,
                'mall_id' => \Yii::$app->mall->id
            ])->one();
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }

            $common = new CommonCoupon(['coupon_id' => $this->coupon_id], false);
            $common->user = $user;
            $class = new CouponMallRelation($model, 0, 'scrm');

            for ($i = 0; $i < $this->coupon_num; $i++) {
                if (!$common->receive($model, $class, '企业微信客户管理发放')) {
                    throw new ScrmException('优惠券数量不够');
                }
            }
            try {
                TemplateList::getInstance()->getTemplateClass(ActivitySuccessInfo::TPL_NAME)->send([
                    'activityName' => '优惠券发放',
                    'name' => $model->name,
                    'remark' => '您有新的优惠券待查收',
                    'user' => $user,
                    'page' => 'pages/coupon/index/index'
                ]);
            } catch (\Exception $exception) {
                \Yii::error('模板消息发送: ' . $exception->getMessage());
            }
            return $this->success([
                'msg' => '发送成功'
            ]);
        } catch (\Exception $e) {
            return $this->failByException($e);
        }
    }
}
