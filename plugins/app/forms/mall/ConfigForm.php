<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/9/29
 * Time: 4:15 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\app\forms\mall;

use app\bootstrap\response\ApiCode;
use app\plugins\app\forms\common\CommonSetting;
use app\models\Model;

/**
 * @property CommonSetting $setting
 */
class ConfigForm extends Model
{
    public $baidu_android_ak;
    public $baidu_ios_ak;
    public $app_id;
    public $app_secret;
    public $agreement_type;
    public $agreement_link;
    public $agreement_content;

    protected $setting;

    public function init()
    {
        parent::init();
        $this->setting = CommonSetting::getCommon();
    }

    public function rules()
    {
        return [
            [['baidu_android_ak', 'baidu_ios_ak', 'app_id', 'app_secret', 'agreement_link'], 'trim'],
            [['baidu_android_ak', 'baidu_ios_ak', 'app_id', 'app_secret', 'agreement_content'], 'string'],
            [['agreement_type'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return $this->setting->getName();
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $this->setting->setSetting($this->attributes);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function get(){
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '成功',
            'data' => $this->setting->getSetting()
        ];
    }
}
