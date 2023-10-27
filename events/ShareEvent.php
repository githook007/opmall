<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/22
 * Time: 15:05
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\events;


use app\models\Share;
use yii\base\Event;

/**
 * @property Share $share
 */
class ShareEvent extends Event
{
    public $share;
}
