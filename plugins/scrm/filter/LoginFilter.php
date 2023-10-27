<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/21
 * Time: 5:53 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\filter;

use app\plugins\scrm\forms\common\Token;
use yii\base\ActionFilter;

class LoginFilter extends ActionFilter
{
    public $ignore;

    public function beforeAction($action)
    {
        if (is_array($this->ignore) && in_array($action->id, $this->ignore)) {
            return true;
        }
        $model = new Token();
        return $model->validateAccessToken();
    }
}
