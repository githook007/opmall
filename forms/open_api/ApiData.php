<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/5/16
 * Time: 16:26
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\open_api;

use app\forms\AttachmentUploadForm;
use app\models\Model;

class ApiData extends Model
{
    /**
     * @param $fileUrl string 原始地址
     * @param $saveTo string 保存后的地址
     * @throws \Exception
     * 下载图片
     */
    public function downloadFile($fileUrl, $saveTo)
    {
        $in = fopen($fileUrl, "rb");
        if ($in === false) {
            throw new \Exception('发布失败,请检查站点目录是否有写入权限');
        }
        $out = fopen($saveTo, "wb");
        if ($out === false) {
            throw new \Exception('发布失败,请检查站点目录是否有写入权限');
        }
        while ($chunk = fread($in, 8192)) {
            fwrite($out, $chunk, 8192);
        }
        fclose($in);
        fclose($out);
    }

    /**
     * @param $path
     * @return string
     * @throws \Exception
     * 上传图片到设置好的云存储
     */
    protected function uploadFile($path)
    {
        $form = new AttachmentUploadForm();
        $form->file = AttachmentUploadForm::getInstanceFromFile($path);
        $res = $form->save();
        if ($res['code'] == 0) {
            $attachment = $res['data'];
            return $attachment->url;
        } else {
            throw new \Exception($res['msg']);
        }
    }

    /**
     * @param $url
     * @return mixed
     * @throws \Exception
     * 获取图片后缀
     */
    protected function getImageExtension($url)
    {
        if (!function_exists('getimagesize')) {
            throw new \Exception('getimagesize函数无法使用');
        }
        $imgInfo = getimagesize($url);
        if (!$imgInfo) {
            throw new \Exception('无效的图片链接');
        }
        $arr = [
            1 => 'gif',
            2 => 'jpg',
            3 => 'png',
        ];
        if (!isset($arr[$imgInfo[2]])) {
            throw new \Exception('仅支持jpg、png格式的图片');
        }
        return $arr[$imgInfo[2]];
    }

    static $imgList;

    // 处理图片
    public function handleImg($url)
    {
        if(!$url){
            return '';
        }
        if (substr($url, 0, 4) != 'http') {
            $url = 'http:' . $url;
        }
        if(isset(self::$imgList[$url])){
            return self::$imgList[$url];
        }
        $temp = \Yii::$app->basePath . '/web/temp/';
        if (!is_dir($temp)) {
            mkdir($temp);
        }
        $temp = $temp . 'open_api/';
        if (!is_dir($temp)) {
            mkdir($temp);
        }
        try {
            $file = substr(md5($url), 16, 16) . '.' . $this->getImageExtension($url);
            $saveTo = $temp . $file;
            // 1、先将网络图片下载到本地临时存储
            $this->downloadFile($url, $saveTo);
            // 2、在上传到系统设置的存储上
            $newUrl = $this->uploadFile($saveTo);
            // 3、删除临时图片
            unlink($saveTo);
        } catch (\Exception $exception) {
            \Yii::warning($exception);
            $newUrl = $url;
        }
        self::$imgList[$url] = $newUrl;
        return $newUrl;
    }

    // 处理富文本
    public function handleDesc($detail)
    {
        preg_match_all('/(http|https):{1}\D{2,4}.*?\.(jpeg|jpg|png|gif|bmp|webp)/', $detail, $res);
        $desc = [];
        foreach ($res[0] as $item) {
            $desc[] = $this->handleImg($item);
        }
        $res = str_replace($res[0], $desc, $detail);
        $res = str_replace('src2', 'src', $detail);
        return $res;
    }
}
