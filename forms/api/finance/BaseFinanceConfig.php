<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/7/1
 * Time: 15:33
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\api\finance;


use app\models\Model;

abstract class BaseFinanceConfig extends Model
{
    /**
     * @throws \Exception
     * @return array
     */
    abstract public function config();
}
