<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\mptemplate;

use app\forms\common\wechat\WechatFactory;
use app\models\Model;
use GuzzleHttp\Client;

class MpTplMessage extends Model
{
    public function senderMsg($args = array())
    {
        if (!isset($args['touser']) || !$args['touser']) {
            throw new \Exception('touser字段缺失，请填写接收者（用户）的 openid');
        }

        if (!isset($args['template_id']) || !$args['template_id']) {
            throw new \Exception('template_id字段缺失，请填写所需下发的模板消息的id');
        }
        if (!isset($args['data']) || !$args['data']) {
            throw new \Exception('data字段缺失，请填写所需下发的模板消息的id');
        }


        $accessToken = WechatFactory::create('template')->wechat->getAccessToken();
        $api = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
        $res = $this->post($api, $args);

        if ($res['errcode'] == 0) {
            return $res;
        } else {
            \Yii::error($res);
            throw new \Exception($res['errmsg']);
        }
    }

    private function getClient()
    {
        return new Client([
            'verify' => false,
        ]);
    }

    private function post($url, $body = array())
    {
        $response = $this->getClient()->post($url, [
            'body' => json_encode($body)
        ]);
        return json_decode($response->getBody(), true);
    }
}
