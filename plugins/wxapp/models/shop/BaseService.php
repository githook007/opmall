<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/5
 * Time: 9:29 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\wxapp\models\shop;

use app\helpers\CurlHelper;
use app\models\Model;

class BaseService extends Model
{
    public $accessToken;

    public function getClient()
    {
        return CurlHelper::getInstance();
    }

    public function getResult($result)
    {
        if (!isset($result['errcode'])) {
            return $result;
        }
        switch ($result['errcode']) {
            case 0:
                return $result;
            case 48001:
                \Yii::warning($result);
                throw new \Exception('接口没有权限');
            default:
                \Yii::warning($result);
                throw new \Exception($result['errmsg']);
        }
    }
}
