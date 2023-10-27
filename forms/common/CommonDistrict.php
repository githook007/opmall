<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common;

use app\models\DistrictArr;

class CommonDistrict
{
    public function search()
    {
        $d = new DistrictArr();
        $arr = $d->getArr();
        return $d->getList($arr);
    }
}
