<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/6/19
 * Time: 19:25
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\prints\config;

use app\forms\common\prints\Exceptions\PrintException;

abstract class BaseConfig
{
    public function __construct($config = [])
    {
        foreach ($config as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * @param string $content
     * @return array
     * @throws PrintException
     */
    abstract public function print($content);
}
