<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/6/21
 * Time: 19:28
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\controllers\open_api\filter;

use app\bootstrap\response\ApiCode;
use app\forms\open_api\DockingForm;
use yii\base\ActionFilter;

class SignFilter extends ActionFilter
{
    public $ignore;

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $route = $action->id;
        if (is_array($this->ignore) && in_array($route, $this->ignore)) {
            return true;
        }

        $sign = \Yii::$app->request->headers['CommonOpen-Sign'];
        if(\Yii::$app->request->isPost){
            $params = \Yii::$app->request->post();
        }else{
            $params = \Yii::$app->request->get();
            unset($params['r']);
        }

        if (!DockingForm::checkSign($params, $sign)){
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '验证签名失败',
            ];
            return false;
        }

        return true;
    }
}
