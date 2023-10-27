<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/20
 * Time: 9:32
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\template;


use app\models\Model;

abstract class TemplateSender extends Model
{
    public $is_need_form_id = true;
    abstract public function sendTemplate($arg = array());
}
