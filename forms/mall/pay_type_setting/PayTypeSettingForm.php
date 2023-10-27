<?php
/**
 * @copyright ©2020 hook007
 * @link: https://www.opmall.com
 * Created by PhpStorm.
 * author: opmall
 * Date: 2020/11/4
 * Time: 16:16
 */

namespace app\forms\mall\pay_type_setting;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\models\PayType;

class PayTypeSettingForm extends Model
{
    public $wxapp;
    public $wechat;
    public $mobile;
    public $pc_manage;
    public $app; // @jayi

    public function rules()
    {
        return [
            [['wxapp', 'wechat', 'mobile', 'pc_manage', 'app'], 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $res = CommonOption::set(
            Option::NAME_PAYMENT_PAY_TYPE,
            [
                'wxapp' => $this->wxapp,
                'wechat' => $this->wechat,
                'mobile' => $this->mobile,
                'pc_manage' => $this->pc_manage,
                'app' => $this->app // @jayi
            ],
            \Yii::$app->mall->id,
            Option::GROUP_APP
        );
        if (!$res) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '保存失败'
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function getDetail()
    {
        //权限判断
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        $newPermission = [];
        $platform = array_keys($this->getDefault());
        foreach ($platform as $item) {
            if (in_array($item, $permission)) {
                $newPermission[] = $item;
            }
        }
        $option = CommonOption::get(
            Option::NAME_PAYMENT_PAY_TYPE,
            \Yii::$app->mall->id,
            Option::GROUP_APP
        );
        // @czs
        foreach ($this->getDefault() as $k => $item){
            if(!isset($option[$k])){
                $option[$k] = $item;
            }
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'permission' => $newPermission,
                'option' => $option
            ],
        ];
    }

    public function getDefault()
    {
        return [
            'wxapp' => [
                'wx' => '',
            ],
            'wechat' => [
                'wx' => '',
                'ali' => '',
            ],
            'mobile' => [
                'wx' => '',
                'ali' => ''
            ],
            'pc_manage' => [ // @czs
                'wx' => '',
            ],
            'app' => [ // @jayi
//                'ali' => '', 支付宝暂缓 czs
                'wx' => '',
            ]
        ];
    }
}
