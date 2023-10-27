<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/11/4
 * Time: 10:16 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\common;

use app\forms\common\qrcode\BdQrcode;

class QrcodeServe extends BdQrcode
{
    public function getQrcode($args = [])
    {
        // $args['scene']  参数
        $text = $this->buildParams('/pages/index/index', $args['scene'] ?? []);
        $imgName = md5(strtotime('now')) . '.jpg';
        // 获取图片存储的路径
        $res = file_uri('/web/temp/');
        $localUri = $res['local_uri'];
        $webUri = $res['web_uri'];
        $save_path = $localUri . $imgName;
        $args['width'] = $args['width'] ?? 430;
        $args['page'] = $args['page'] ?? 'pages/index/index';
        $args['scene'] = $args['scene'] ?? '';
        $size = floor($args['width'] / 37 * 100) / 100 + 0.01;
        \QRcode::png($text, $save_path, QR_ECLEVEL_L, $size, 2);
        return $webUri . $imgName;
    }
}
