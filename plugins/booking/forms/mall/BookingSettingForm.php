<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\booking\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOptionP;
use app\models\Model;
use app\plugins\booking\forms\common\CommonBooking;
use app\plugins\booking\forms\common\CommonOption;
use app\plugins\booking\models\BookingSetting;

class BookingSettingForm extends Model
{
    public function getList()
    {
        /** @var BookingSetting  $setting */
        $setting = CommonBooking::getSetting();

        $newFormDataList = [];
        foreach ($setting['form_data'] as $item) {
            $newItem = $item;
            $newItem['is_required'] = $newItem['is_required'] == 1;
            $newFormDataList[] = $newItem;
        }

        $poster = (new CommonOptionP())->poster($setting['goods_poster'], CommonOption::getPosterDefault());

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'setting' => $setting,
                'form_data' => $newFormDataList,
                'poster' => $poster
            ]
        ];
    }
}
