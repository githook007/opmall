<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/13 14:13
 */

namespace app\controllers\api;

use app\bootstrap\response\ApiCode;
use app\forms\pc\passport\LoginForm;
use app\models\pc\UserRegister;

class LoginController extends ApiController
{
    public function actionIndex()
    {
        $form = new LoginForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        return $form->login();
    }

    // 获取用户信息用于pc注册
    public function actionUserInfo(){
        try {
            $params = \Yii::$app->request->get();
            $params = array_merge($params, \Yii::$app->request->post());
            if (empty($params['token'])) {
                throw new \Exception("token参数缺失");
            }
            $model = UserRegister::findOne(["token" => $params['token'], "mall_id" => \Yii::$app->mall->id]);
            if (!$model) {
                throw new \Exception("错误，信息不存在");
            }
            $data = \Yii::$app->getWechat()->decryptData($params['encryptedData'], $params['iv'], $params['code']);
            $database = (array)json_decode($model->data, true);
            $database['user_info'] = $data;
            $model->data = \Yii::$app->serializer->encode($database);
            $model->status = 1; // 完成了
            $model->save();
            return ["code" => 0, "msg" => "获取用户信息成功"];
        }catch (\Exception $e){
            return ["code" => ApiCode::CODE_ERROR, "msg" => $e->getMessage()];
        }
    }
}
