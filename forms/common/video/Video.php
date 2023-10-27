<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/26
 * Time: 14:50
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\video;


class Video
{
    public static function getUrl($url)
    {
        $url = trim($url);
        if (strpos($url, 'v.qq.com') != -1) {
            $model = new TxVideo();
            return $model->getVideoUrl($url);
        } else {
            return $url;
        }
    }
}
