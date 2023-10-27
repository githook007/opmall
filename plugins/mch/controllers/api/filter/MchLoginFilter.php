<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/6/21
 * Time: 19:28
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\mch\controllers\api\filter;


use app\bootstrap\response\ApiCode;
use app\models\User;
use yii\base\ActionFilter;

class MchLoginFilter extends ActionFilter
{
    public $ignore;
    public $only;

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $route = \Yii::$app->requestedRoute;
        if (is_array($this->ignore) && in_array($route, $this->ignore)) {
            return true;
        }

        $mchToken = \Yii::$app->request->headers['Mch-Access-Token'];
        if (!$mchToken) {
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_MCH_NOT_LOGIN,
                'msg' => '请先登录。1',
            ];
            return false;
        }

        if (\Yii::$app->request->isPost) {
            $mchId = \Yii::$app->request->post('mch_id');
        } else {
            $mchId = \Yii::$app->request->get('mch_id');
        }

        $user = User::find()->andWhere([
            'mall_id' => \Yii::$app->mall->id,
            'mch_id' => $mchId,
            'is_delete' => 0
        ])->one();

        $newMchToken = $user->access_token;

        if (!$newMchToken || $newMchToken != $mchToken) {
            \Yii::$app->response->data = [
                'code' => ApiCode::CODE_MCH_NOT_LOGIN,
                'msg' => '请先登录。2',
            ];
            return false;
        }

        return true;
    }
}
