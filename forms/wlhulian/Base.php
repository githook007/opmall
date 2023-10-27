<?php
/**
 * @copyright ©2018
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/5 13:49
 */

namespace app\forms\wlhulian;

/**
 * @property string $attribute
 */
abstract class Base
{
    public function __construct($array = [])
    {
        $this->attribute = $array;
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }
    }

    public function setAttribute($array = [])
    {
        foreach ($array as $key => $item) {
            if (property_exists($this, $key)) {
                $this->$key = $item;
            }
        }
    }

    abstract function getAttribute();

    public function response($response){
        if($response['code'] == 200){
            return $response['data'];
        }
        \Yii::error("对接聚合配送异常结果：");
        \Yii::error($response);
        throw new \Exception($response['message']);
    }

    public function supportStoreId(): bool
    {
        return true;
    }
}
