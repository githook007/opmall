<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\bootstrap\express;

class ExpressFactory
{
    public function create(string $model, array $config = [])
    {
        $model = 'app\bootstrap\express\factory\\' . lcfirst($model) . '\\' . $model;
        if (class_exists($model)) {
            return new $model($config);
        }
        throw new \Exception('调用错误');
    }
}