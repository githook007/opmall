<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/17
 * Time: 3:06 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\filter;

use app\bootstrap\response\ApiCode;
use app\plugins\minishop\forms\CheckForm;
use app\plugins\wxapp\Plugin;
use yii\base\ActionFilter;

class WxappFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (\Yii::$app->request->isAjax) {
            try {
                $form = new CheckForm();
                $form->check();
            } catch (\Exception $exception) {
                \Yii::$app->response->data = [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => [
                        'can_use' => false,
                        'content' => $exception->getMessage()
                    ]
                ];
                return false;
            }
        }
        return true;
    }
}
