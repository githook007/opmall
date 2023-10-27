<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/21
 * Time: 15:44
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\fxhb\events;


use app\models\Mall;
use app\plugins\fxhb\models\FxhbUserActivity;
use yii\base\Event;

/**
 * @property FxhbUserActivity $userActivity
 * @property FxhbUserActivity $parentActivity
 * @property Mall $mall
 */
class JoinActivityEvent extends Event
{
    public $userActivity;
    public $parentActivity;
    public $mall;
}
