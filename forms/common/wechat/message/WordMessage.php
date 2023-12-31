<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/26
 * Time: 5:25 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\common\wechat\message;


use app\models\WechatSubscribeReply;

class WordMessage extends BaseMessage
{
    public $name = '回复文本消息';
    public $type = 'text';

    public $content;

    public function rules()
    {
        return [
            [['content'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => '回复内容'
        ];
    }

    public function reply($model)
    {
        return [
            'Content' => $model->content
        ];
    }

    public function checkMedia()
    {
        return false;
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
            'text' => [
                'content' => $model->content
            ]
        ];
    }
}
