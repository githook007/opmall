<?php

namespace app\plugins\wxapp\models;

use app\forms\OAuth;
use app\helpers\CurlHelper;

/**
 * This is the model class for table "{{%wxapp_trial}}".
 *
 * @property int $id
 * @property int $type 1：企业认证；2：个体认证
 * @property string $enterprise_name 企业名称
 * @property string $code 企业代码
 * @property int $code_type 企业代码类型（1：统一社会信用代码， 2：组织机构代码，3：营业执照注册号）
 * @property string $legal_persona_wechat 法人微信
 * @property string $legal_persona_name 法人姓名
 * @property string $legal_persona_idcard 法人身份证
 * @property string $component_phone 第三方联系电话
 * @property int $status 1审核中；2认证成功；3认证失败
 * @property int $source  来源
 * @property string $appid 创建小程序appid
 * @property string $notify_url  通知地址
 * @property string $status_msg  状态消息
 * @property string $wxapp_name      小程序名称
 * @property string $id_card_pic     身份证正面
 * @property string $license_pic     营业执照
 * @property string $wxapp_desc      小程序简介
 * @property string $wxapp_avatar    小程序头像
 * @property string $wxapp_category  小程序类目
 * @property string $updated_at
 * @property string $created_at
 * @property string $deleted_at
 * @property int $is_delete
 */
class WxappTrial extends \app\models\ModelActiveRecord
{
    const PLATFORM_SOURCE = 1; // 平台
    const WX_SERVER_SOURCE = 2; // 微信服务市场

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wxapp_trial}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['appid', 'updated_at', 'created_at', 'deleted_at'], 'required'],
            [['type', 'code_type', 'status', 'is_delete', 'source'], 'integer'],
            [['updated_at', 'created_at', 'deleted_at'], 'safe'],
            [['enterprise_name', 'legal_persona_wechat', 'legal_persona_name', 'component_phone', 'appid',
                'legal_persona_idcard', 'code', 'status_msg', 'notify_url'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'enterprise_name' => '企业名称',
            'code' => '企业代码',
            'code_type' => 'Code Type',
            'legal_persona_wechat' => '法人微信',
            'legal_persona_name' => '法人姓名',
            'component_phone' => '第三方联系电话',
            'status' => 'Status',
            'appid' => 'Appid',
            'source' => '来源',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
            'deleted_at' => 'Deleted At',
            'is_delete' => 'Is Delete',
        ];
    }

    public function notify(){
        if(!$this->notify_url){
            return;
        }
        $params = [
            'legal_persona_name' => $this->legal_persona_name,
            "enterprise_name" => $this->enterprise_name,
            "code" => $this->code,
            "code_type" => $this->code_type,
            "legal_persona_wechat" => $this->legal_persona_wechat,
            "legal_persona_idcard" => $this->legal_persona_idcard,
            "component_phone" => $this->component_phone,
            'status' => $this->status,
            'appId' => $this->appid,
            "msg" => $this->status_msg
        ];
        if(empty($this->component_phone)){
            unset($params['component_phone']);
        }
        switch ($this->source){
            case self::PLATFORM_SOURCE:
                $params['sign'] = OAuth::getSign($params);
                $res = CurlHelper::getInstance()->httpPost($this->notify_url, [], $params);
                if ($res['code'] != 0) {
                    \Yii::error("notify_url 请求接口报错：".$res['msg']);
                }
                break;
            case self::WX_SERVER_SOURCE:
                break;
        }
        return;
    }
}
