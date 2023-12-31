<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\models\Model;

class DiyBannerForm extends Model
{
    public function getNewBanner($data)
    {
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        $permissionFlip = array_flip($permission);

        foreach ($data['banners'] as $index => &$banner) {
            if (isset($banner['key']) && $banner['key'] && !isset($permissionFlip[$banner['key']])) {
                unset($data['banners'][$index]);
                continue;
            }
            $banner['page_url'] = $banner['url'];
            $banner['open_type'] = $banner['openType'];
            $banner['pic_url'] = $banner['picUrl'];
        }
        unset($banner);
        $data['banners'] = array_values($data['banners']);

        return $data;
    }
}
