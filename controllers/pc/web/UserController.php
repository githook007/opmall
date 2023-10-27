<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/5 16:13
 */

namespace app\controllers\pc\web;

use app\controllers\pc\web\filters\LoginFilter;
use app\forms\api\AddressForm;
use app\forms\pc\user\UserEditForm;
use app\forms\pc\user\UserInfoForm;

class UserController extends CommonController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
//                'ignore' => ['config']
            ],
        ]);
    }

    public function actionUserInfo()
    {
        $form = new UserInfoForm();
        return $form->getInfo();
    }

    public function actionUserEdit(){
        $form = new UserEditForm();
        $form->attributes = $this->getParams();
        $form->user = \Yii::$app->user->identity;
        return $form->edit();
    }

    //收货地址列表
    public function actionAddressList()
    {
        $form = new AddressForm();
        $form->hasCity = \Yii::$app->request->get('hasCity');
        $form->type = intval(\Yii::$app->request->get('type'));
        return $form->getList();
    }

    public function actionAddressDefault()
    {
        $form = new AddressForm();
        $form->attributes = $this->getParams();
        $form->is_default = 1;
        $form->type = $this->getParams("type", 0);
        return $form->default();
    }

    public function actionAddressDetail()
    {
        $form = new AddressForm();
        $form->id = $this->getParams("id");
        return $form->detail();
    }

    public function actionAddressDestroy()
    {
        $form = new AddressForm();
        $form->attributes = $this->getParams();
        return $form->destroy();
    }

    public function actionAddressSave()
    {
        $form = new AddressForm();
        $form->attributes = \Yii::$app->request->post();
        return $form->save();
    }
}
