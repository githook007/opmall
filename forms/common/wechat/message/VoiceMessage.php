<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/26
 * Time: 5:43 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\wechat\message;

class VoiceMessage extends BaseMessage
{
    public $name = '回复音频消息';
    public $type = 'voice';
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
            'url' => '音频地址链接'
        ];
    }

    public function reply($model)
    {
        return [
            'Voice' => [
                'MediaId' => $model->media_id
            ]
        ];
    }

    public function checkMedia()
    {
        preg_match('/\w*\.mp3/', $this->url, $fileName);
        if (empty($fileName)) {
            throw new \Exception('上传音频的格式不正确，仅支持mp3格式的音频');
        }
        return $fileName;
    }

    public function custom($model)
    {
        return [
            'msgtype' => $this->type,
            'voice' => [
                'media_id' => $model->media_id
            ]
        ];
    }
}
