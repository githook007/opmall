<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/6/17
 * Time: 11:33
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\collect\collect_data;


use app\forms\common\collect\collect_api\CollectApi;
use app\models\Model;
use yii\helpers\Json;

/**
 * Class BaseData
 * @package app\forms\common\collect\collect_data
 * @property CollectApi $api
 */
abstract class BaseData extends Model
{
    use CollectData;
    public $api_key;
    public $api;
    public $type;


    abstract public function getItemId($url);
    abstract public function handleData($data);

    public function getData($url)
    {
        $itemId = $this->getItemId($url);
        $this->api->api_key = $this->api_key;
        $data = $this->api->getData($itemId);
        $data = Json::decode($data, true);
        $this->saveData($this->type, $itemId, $data);
        return $this->handleData($data);
    }
}
