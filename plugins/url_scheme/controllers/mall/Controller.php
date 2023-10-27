<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/26
 * Time: 13:47
 * @copyright: ©2019 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\url_scheme\controllers\mall;

use app\plugins\url_scheme\Plugin;

class Controller extends \app\plugins\Controller
{
    public $sign;

    public function init()
    {
        parent::init();
        $this->sign = (new Plugin())->getName();
    }
}
