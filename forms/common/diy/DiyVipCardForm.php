<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\models\Model;
use app\plugins\check_in\models\CheckInAwardConfig;
use app\plugins\check_in\models\CheckInUser;
use app\plugins\vip_card\Plugin;

class DiyVipCardForm extends Model
{
    public function getVipCard()
    {
        $plugin = new Plugin();
        return $plugin->getAppConfig();
    }
}
