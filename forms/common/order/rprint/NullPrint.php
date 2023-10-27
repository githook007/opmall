<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\forms\common\order\rprint;

class NullPrint extends BaseForm
{
    public function track(...$params)
    {
        throw new \Exception('未知错误');
    }
}
