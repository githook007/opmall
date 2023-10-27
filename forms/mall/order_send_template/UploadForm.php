<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\order_send_template;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use GuzzleHttp\Client;

class UploadForm extends Model
{
    public $img_list = [];

    public function save()
    {
        $newUrls = [];
        $baseWebUrl = \Yii::$app->request->hostInfo . '/web/uploads/send-template/' . date('Y') . date('m') . date('d') . '/';
        foreach ($this->img_list as $item) {
            // 本地图片无需重新保存
            if (strpos($item['url'], \Yii::$app->request->hostInfo) !== false) {
                //
                $item['local_url'] = $item['url'];
                $newUrls[] = $item;
                continue;
            }
            $filename = md5($item['url']) . '.jpg';
            $path = \Yii::$app->basePath . '/web/uploads/send-template/' . date('Y') . date('m') . date('d') . '/';

            if (file_exists($path . $filename)) {
                // 已存在则直接调用本地图片
                $item['local_url'] = $baseWebUrl . $filename;
                $newUrls[] = $item;
                continue;
            }

            $response = (new Client([
                'verify' => false,
                'Content-Type' => 'image/jpeg'
            ]))->get($item["url"]); // 修复安全漏洞 @czs
            $content = $response->getBody()->getContents();

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $fp = fopen($path . $filename, "a"); //将文件绑定到流
            fwrite($fp, $content); //写入文件
            $item['local_url'] = $baseWebUrl . $filename;
            $newUrls[] = $item;
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => "请求成功",
            'data' => [
                'list' => $newUrls
            ]
        ];
    }
}