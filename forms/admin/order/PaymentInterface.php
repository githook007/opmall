<?php

namespace app\forms\admin\order;

abstract class PaymentInterface
{
    abstract public function getService();

    abstract public function getNotifyUrl();

    public function getGeneralQrcode($args = []) {
        $token = $args['token'];

        $imgName = md5(strtotime('now')) . '.jpg';
        // 获取图片存储的路径
        $res = file_uri('/web/temp/');
        $localUri = $res['local_uri'];
        $webUri = $res['web_uri'];
        $save_path = $localUri . $imgName;
        $args['width'] = $args['width'] ?? 430;
        $size = floor($args['width'] / 37 * 100) / 100 + 0.01;
        \QRcode::png($token, $save_path, QR_ECLEVEL_L, $size, 2);

        return $webUri . $imgName;
    }
}