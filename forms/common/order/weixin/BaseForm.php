<?php
/**
 * @copyright ©2018
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/5 13:49
 */

namespace app\forms\common\order\weixin;

use app\forms\open3rd\OpenErrorCode;
use app\helpers\Json;
use app\models\Model;
use Curl\Curl;

class BaseForm extends Model
{
    public $log = true;
    public $accessToken;
    public $appId;

    public function rules()
    {
        return [
            [['log'], 'safe'],
            [['accessToken', 'appId'], 'string'],
        ];
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $curl;
    }

    protected function errorLog($ret)
    {
        if($this->log) {
            \Yii::error((array)$ret);
        }
        $msg = OpenErrorCode::errorMsg($ret->errcode, $ret->errmsg);
        throw new \Exception($msg, $ret->errcode);
    }

    protected function getAccessToken(){
        if(!$this->accessToken){
            $this->accessToken = \Yii::$app->wechat->getAccessToken();
        }
        return $this->accessToken;
    }

    protected function getAppId(){
        if(!$this->appId){
            $this->appId = \Yii::$app->wechat->appId;
        }
        return $this->appId;
    }

    protected $isTradeManaged;

    public function setTradeManaged($val = null){
        $this->isTradeManaged = $val;
        return $this;
    }

    /**
     * 查询小程序是否已开通发货信息管理服务
     * https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/business-capabilities/order-shipping/order-shipping.html
     */
    protected function isTradeManaged(){
        if($this->isTradeManaged === null){
            try {
                $accessToken = $this->getAccessToken();
                $url = "https://api.weixin.qq.com/wxa/sec/order/is_trade_managed?access_token={$accessToken}";
                $ret = Json::decode($this->getCurl()->post($url, Json::encode(['appid' => $this->getAppId()]))->response, false);
                if ($ret->errcode != 0) {
                    $this->errorLog($ret);
                }
                $this->isTradeManaged = $ret->is_trade_managed;
            }catch (\Exception $e){
                $this->isTradeManaged = 0;
            }
        }
        return $this->isTradeManaged;
    }
}
