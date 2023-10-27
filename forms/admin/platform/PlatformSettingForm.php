<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/10/13
 * Time: 10:09
 */

namespace app\forms\admin\platform;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\helpers\ArrayHelper;
use app\models\Model;
use app\models\Option;
use app\models\WxappPlatform;

class PlatformSettingForm extends Model
{
    public function search()
    {
        $platform = WxappPlatform::getPlatform();
        $platform = ArrayHelper::toArray($platform);
        if (isset($platform['domain'])) {
            $domain = json_decode($platform['domain'], true);
            $platform['downloaddomain'] = $domain['downloaddomain'];
            $platform['uploaddomain'] = $domain['uploaddomain'];
            $platform['webviewdomain'] = $domain['webviewdomain'];
        } else {
            $platform['downloaddomain'] = '';
            $platform['uploaddomain'] = '';
            $platform['webviewdomain'] = '';
        }
        $web = CommonOption::get(Option::NAME_WX_PLATFORM_WEB, 0, Option::GROUP_ADMIN);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'platform' => $platform,
                'web' => $web,
            ]
        ];
    }
}
