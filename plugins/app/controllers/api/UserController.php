<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/10/17
 * Time: 9:54 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\controllers\api;

use app\controllers\api\filters\LoginFilter;
use app\forms\api\FavoriteForm;
use app\plugins\app\forms\api\UserForm;
use app\plugins\app\forms\api\UserLogOffForm;
use app\plugins\app\models\LoginForm;

class UserController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
                'ignore' => ['password', 'wx-config']
            ],
        ]);
    }

    // 修改密码
    public function actionPassword()
    {
        $form = new UserForm();
        $form->scenario = \Yii::$app->request->post('type');
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->password());
    }

    // 修改昵称
    public function actionNickname()
    {
        $form = new UserForm();
        $form->scenario = 'u_nickname';
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->nickname());
    }

    public function actionFavoriteAdd()
    {
        $form = new FavoriteForm();
        $form->attributes = \Yii::$app->request->get();
        $data = $form->create();
        if(isset($data['data'])) {
            $data['data'] = ['result' => $data['data']];
        }
        return $this->asJson($data);
    }

    public function actionFavoriteRemove()
    {
        $form = new FavoriteForm();
        $form->attributes = \Yii::$app->request->get();
        $data = $form->delete();
        if(isset($data['data'])) {
            $data['data'] = ['result' => $data['data']];
        }
        return $this->asJson($data);
    }

    public function actionFavoriteBatchRemove()
    {
        $form = new FavoriteForm();
        $form->goods_ids = json_decode(\Yii::$app->request->post('goods_ids'), true);
        $data = $form->batchRemove();
        if(isset($data['data'])) {
            $data['data'] = ['result' => $data['data']];
        }
        return $this->asJson($data);
    }

    // 用户注销
    public function actionUserLogOff(){
        $form = new UserLogOffForm();
        return $this->asJson($form->handle());
    }

    // 获取微信第三方appid
    public function actionWxConfig()
    {
        $form = new LoginForm();
        return $this->asJson($form->wxConfig());
    }


    // 以下暂时无用
    // 修改头像
    public function actionAvatar()
    {
        $form = new UserForm();
        $form->scenario = 'u_avatar';
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->avatar());
    }

    // 修改手机号
    public function actionMobile()
    {
        $form = new UserForm();
        $form->scenario = 'u_mobile';
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->mobile());
    }

    // 验证身份
    public function actionValidateIdentity()
    {
        $form = new UserForm();
        $form->scenario = \Yii::$app->request->post('type');
        $form->attributes = \Yii::$app->request->post();
        $form->mobile = \Yii::$app->user->identity->mobile;
        return $this->asJson($form->validateIdentity());
    }
}
