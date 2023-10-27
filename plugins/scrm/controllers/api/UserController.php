<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/23
 * Time: 9:30 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\controllers\api;

use app\plugins\scrm\filter\LoginFilter;
use app\plugins\scrm\forms\api\CartListForm;
use app\plugins\scrm\forms\api\CouponForm;
use app\plugins\scrm\forms\api\FootprintForm;
use app\plugins\scrm\forms\api\MemberForm;
use app\plugins\scrm\forms\api\MemberListForm;
use app\plugins\scrm\forms\api\OrderDetailForm;
use app\plugins\scrm\forms\api\OrderListForm;
use app\plugins\scrm\forms\api\UserInfoForm;

class UserController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionUserInfo()
    {
        $form = new UserInfoForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getDetail());
    }

    public function actionOrder()
    {
        $form = new OrderListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->search());
    }

    public function actionOrderDetail()
    {
        $form = new OrderDetailForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->search());
    }

    public function actionMember()
    {
        $form = new MemberListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getList());
    }

    public function actionMemberOne()
    {
        $form = new MemberListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getOne());
    }

    public function actionCart()
    {
        $form = new CartListForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->search());
    }

    public function actionSetMember()
    {
        $form = new MemberForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->setMember());
    }

    public function actionGoods()
    {
        $form = new FootprintForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->footprint());
    }

    public function actionCouponList()
    {
        $form = new CouponForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getList());
    }

    public function actionSendCoupon()
    {
        $form = new CouponForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->send());
    }
}
