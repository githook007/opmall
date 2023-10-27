<?php

namespace app\plugins\erp\forms\common\api;

use yii\base\BaseObject;

class BaseApi extends BaseObject
{
    use Client;
    use Util;
    use Auth;

    const DEV = 'dev';
    const PROD = 'prod';

    /** @var int */
    public $status;

    /** @var string */
    public $app_key;

    /** @var string */
    public $app_secret;

    /** @var string */
    public $access_token;

    /** @var string */
    public $refresh_token;

    /** @var string */
    public $expires_in;

    /** @var int */
    public $shop_id;

    /** @var string */
    public $env = self::DEV;

    /** @var string URL */
    protected $product_url = 'https://openapi.jushuitan.com';

    /** @var string URL */
    protected $dev_url = 'https://dev-api.jushuitan.com';

    /** @var string 交互数据的编码 */
    protected $charset = 'utf-8';

    /** @var string 版本号 */
    protected $version = 2;

    /**
     * 业务授权URL
     * @var string
     */
    protected $getAuthUrl = 'https://openweb.jushuitan.com/auth';

    public function __construct($config = [])
    {
        if(isset($config['shop_id'])){
            $config['shop_id'] = (int)$config['shop_id'];
        }
        parent::__construct($config);
    }

    public function getAttributes(): array
    {
        $attributes = [];
        $class = new \ReflectionClass($this);
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes[$property->getName()] = $property->getValue($this);
        }
        return $attributes;
    }

    public function setEnv($value){
        $this->env = $value;
    }

    public function getUrl(): string
    {
        if($this->env == self::PROD){
            return $this->product_url;
        }else{
            return $this->dev_url;
        }
    }
}