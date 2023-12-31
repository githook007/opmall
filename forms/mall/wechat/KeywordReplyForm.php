<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/2
 * Time: 2:30 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\mall\wechat;

use app\bootstrap\response\ApiCode;
use app\forms\common\wechat\WechatFactory;
use app\models\Model;
use app\models\WechatSubscribeReply;

class KeywordReplyForm extends Model
{
    public $type;
    public $content;
    public $title;
    public $picurl;
    public $url;
    public $id;

    public function rules()
    {
        return [
            [['type', 'id'], 'integer'],
            ['type', 'in', 'range' => [0, 1, 2, 3, 4]],
            [['title', 'content', 'picurl', 'url'], 'trim'],
            [['title', 'content', 'picurl', 'url'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => '消息类型',
            'content' => '消息内容',
            'title' => '图文消息标题',
            'picurl' => '图文消息图片链接',
            'url' => '链接',
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $model = WechatSubscribeReply::findOne([
                'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'id' => $this->id,
                'status' => 1
            ]);
            if (!$model) {
                $model = new WechatSubscribeReply();
                $model->mall_id = \Yii::$app->mall->id;
                $model->is_delete = 0;
                $model->status = 1;
            }
            $model->type = $this->type;
            $message = WechatFactory::createMessage($this->type);
            $message->attributes = $this->attributes;
            if (!$message->validate()) {
                throw new \Exception($this->getErrorMsg($message));
            }
            $model->media_id = $message->getMedia($model);
            $model->attributes = $this->attributes;
            if (!$model->save()) {
                throw new \Exception($this->getErrorMsg($model));
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
                'data' => $model->id
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'errors' => $exception
            ];
        }
    }
}
