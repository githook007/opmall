<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\commands\express;

abstract class Base
{
    use ECommon;

    abstract function create();

    public function select($name)
    {
        $arrList = $this->decrypt(static::FILE);
        $map = static::MAP;
        $rname = isset($map[$name]) ? $map[$name] : $name;

        if ($rname === '') {
            $this->err(static::class . " 不存在 " . $name);
            return $rname;
        }
        if (!isset($arrList[$rname])) {
            $this->err(static::class . " 映射出错 " . $name);
            exit;
        }
        return $arrList[$rname];
    }
}
