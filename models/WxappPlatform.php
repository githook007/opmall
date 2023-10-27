<?php

namespace app\models;

use app\forms\open3rd\Open3rd;
use app\forms\open3rd\Open3rdException;
use Yii;

/**
 * This is the model class for table "{{%wxapp_platform}}".
 *
 * @property int $id
 * @property string $appid 第三方平台应用appid
 * @property string $appsecret 第三方平台应用appsecret
 * @property string $token 第三方平台应用token（消息校验Token）
 * @property string $encoding_aes_key 第三方平台应用Key（消息加解密Key）
 * @property string $component_access_token
 * @property int $token_expires token过期时间
 * @property int $type 授权类型
 1：公众号
 2：小程序
 3：公众号/小程序同时展现
 * @property string $domain 域名
 * @property string $created_at
 * @property string $updated_at
 */
class WxappPlatform extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wxapp_platform}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appid', 'appsecret', 'token', 'encoding_aes_key', 'domain', 'created_at', 'updated_at'], 'required'],
            [['token_expires', 'type'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['appid'], 'string', 'max' => 128],
            [['appsecret', 'token', 'component_access_token'], 'string', 'max' => 255],
            [['encoding_aes_key'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appid' => 'Appid',
            'appsecret' => 'Appsecret',
            'token' => 'Token',
            'encoding_aes_key' => 'Encoding Aes Key',
            'component_access_token' => 'Component Access Token',
            'token_expires' => 'Token Expires',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    static private $platform;
    /**
     * @param int $type
     * @return WxappPlatform|array|\yii\db\ActiveRecord|null
     * @throws \app\forms\open3rd\Open3rdException
     * @throws \luweiss\Wechat\WechatException
     */
    public static function getPlatform($type = 2)
    {
        if(!isset(self::$platform[$type])) {
            $platform = self::find()->where([
                'type' => $type,
            ])->one();
            self::$platform[$type] = $platform;
        }else{
            $platform = self::$platform[$type];
        }
        if ($platform && $platform->token_expires < time()) {
            try {
                $open3rd = new Open3rd([
                   'appId' => $platform->appid,
                   'appSecret' => $platform->appsecret
                ]);
                if ($platform->token_expires < time()) {
                    self::$log = false;
                    $token = $open3rd->getComponentAccessToken();
                    $platform->component_access_token = $token;
                    $platform->token_expires = time() + 5800;
                    if (!$platform->save()) {
                        Yii::error('更新open3rdtoken失败,保存失败!');
                    }
                    self::$log = true;
                }
            } catch (Open3rdException $exception) {
                Yii::error('更新open3rdtoken失败' . $exception->getMessage());
            }
        }
        return $platform;
    }
}
