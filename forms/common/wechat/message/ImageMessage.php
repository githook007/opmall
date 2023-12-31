<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/26
 * Time: 5:35 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\wechat\message;

use app\models\WechatSubscribeReply;

class ImageMessage extends BaseMessage
{
    public $name = '回复图片消息';
    public $type = 'image';
    public $url;

    public function rules()
    {
        return [
            [['url'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => '图片地址链接'
        ];
    }

    public function reply($model)
    {
        return [
            'Image' => [
                'MediaId' => $model->media_id
            ]
        ];
    }

    public function checkMedia()
    {
        preg_match('/\w*\.(jpg|png|jpeg)/', $this->url, $fileName);
        if (empty($fileName)) {
            throw new \Exception('上传图片的格式不正确，仅支持jpg|png|jpeg格式的图片');
        }
        return $fileName;
    }

    /**
     * @@param WechatSubscribeReply $model
     * @return mixed
     * 消息发送
     */
    public function custom($model)
    {
        return [
            'msgtype' => $this->type,
            'image' => [
                'media_id' => $model->media_id
            ]
        ];
    }
}