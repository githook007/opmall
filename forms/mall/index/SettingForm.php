<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/16 11:15
 */

namespace app\forms\mall\index;

use app\bootstrap\response\ApiCode;
use app\helpers\ArrayHelper;
use app\models\MallSetting;
use app\models\Model;
use yii\helpers\Html;

class SettingForm extends Model
{
    public $name;
    public $contact_tel;
    public $over_time;
    public $delivery_time;
    public $after_sale_time;
    public $payment_type;
    public $send_type;
    public $kdniao_mch_id;
    public $kdniao_api_key;
    public $member_integral;
    public $member_integral_rule;
    public $profit_sharing; // 分账功能 @czs
    public $is_small_app;
    public $small_app_id;
    public $small_app_url;
    public $small_app_pic;
    public $is_customer_services;
    public $customer_services_pic;
    public $is_dial;
    public $dial_pic;
    public $is_web_service;
    public $web_service_url;
    public $web_service_type; // @czs
    public $enterprise_wechat_id;
    public $web_service_pic;
    public $is_quick_navigation;
    public $quick_navigation_style;
    public $quick_navigation_opened_pic;
    public $quick_navigation_closed_pic;
    public $is_show_stock;
    public $is_use_stock;
    public $sell_out_pic;
    public $sell_out_other_pic;

    public $is_common_user_member_price;
    public $is_member_user_member_price;
    public $is_share_price;
    public $is_purchase_frame;
    public $purchase_num;
    public $is_comment;
    public $is_sales;
    public $is_mobile_auth;
    public $is_official_account;
    public $is_manual_mobile_auth;
    public $is_icon_members_grade;
    public $is_goods_video;

    public $is_quick_map;
    public $quick_map_pic;
    public $quick_map_address;
    public $longitude;
    public $latitude;
    public $is_quick_home;
    public $quick_home_pic;

    public $logo;

    public $share_title;
    public $share_pic;

    public $is_add_app;
    public $add_app_bg_color;
    public $add_app_bg_transparency;
    public $add_app_bg_radius;
    public $add_app_text;
    public $add_app_text_color;
    public $add_app_icon_color_type;

    public $is_icon_super_vip;
    public $is_show_normal_vip;
    public $is_show_super_vip;
    public $is_required_position;
    public $is_share_tip;
    public $is_must_login;

    //购物车
    public $is_show_cart;
    //已售量（商品列表）
    public $is_show_sales_num;
    //商品名称
    public $is_show_goods_name;
    //划线价
    public $is_underline_price;
    //列表划线价
    public $is_list_underline_price;
    //快递
    public $is_express;
    //非分销商分销中心显示
    public $is_not_share_show;
    //购物车悬浮按钮
    public $is_show_cart_fly;
    //回到顶部悬浮按钮
    public $is_show_score_top;

    public $express_select_type;
    public $express_aliapy_code;

    public $is_quick_customize;
    public $quick_customize_pic;
    public $quick_customize_open_type;
    public $quick_customize_new_params;
    public $quick_customize_link_url;
    public $mall_logo_pic;
    public $send_type_desc;

    public $is_show_hot_goods;

    public $kd100_key;
    public $kd100_customer;
    public $kd100_secret;
    public $kd100_siid;
    public $print_type;
    public $kd100_yum;

    public $is_open;
    public $open_type;
    public $week_list;
    public $time_list;
    public $is_auto_open;
    public $auto_open_time;

//    public $is_video_number;
    /** 旧的视频号公众号配置（废弃），留着是为了兼容很老的系统 */
    public $video_number_app_id;
    public $video_number_app_secret;
//    public $is_video_number_member;
//    public $video_number_member_list;
//    public $video_number_template_list;
//    public $video_number_share_title;
//    public $video_number_user_1;
//    public $video_number_user_2;

    public $kdniao_select_type;
    public $has_order_evaluate;
    public $order_evaluate_day;

    public $customer_service_list;

    public $member_grade;

    public function rules()
    {
        return [
            [['name'], 'trim',],
            [['contact_tel', 'kdniao_mch_id', 'kdniao_api_key', 'member_integral_rule',
                'small_app_id', 'small_app_url', 'small_app_pic', 'customer_services_pic',
                'dial_pic', 'web_service_url', 'enterprise_wechat_id', 'web_service_pic', 'quick_navigation_closed_pic',
                'quick_navigation_opened_pic', 'quick_map_pic', 'quick_map_address', 'longitude', 'latitude',
                'quick_home_pic', 'logo', 'share_title', 'share_pic', 'add_app_bg_color', 'add_app_text',
                'add_app_text_color', 'sell_out_pic', 'sell_out_other_pic', 'express_select_type', 'express_aliapy_code',
                'quick_customize_pic', 'quick_customize_open_type', 'quick_customize_link_url', 'mall_logo_pic',
                'kd100_key', 'kd100_customer', 'kd100_secret', 'kd100_siid', 'print_type', 'auto_open_time', 'auto_open_time',
                'video_number_app_id', 'video_number_app_secret', 'kdniao_select_type', 'kd100_yum'], 'string'],
            [['over_time', 'delivery_time', 'after_sale_time', 'member_integral',
                'profit_sharing', 'is_customer_services', 'is_dial', 'quick_navigation_style',
                'is_common_user_member_price', 'is_member_user_member_price', 'is_share_price', 'is_purchase_frame',
                'is_comment', 'is_sales', 'is_mobile_auth', 'is_official_account', 'is_icon_members_grade',
                'is_quick_map', 'is_small_app', 'is_web_service', 'is_quick_navigation',
                'is_manual_mobile_auth', 'is_quick_home', 'is_add_app', 'add_app_bg_transparency', 'add_app_bg_radius',
                'add_app_icon_color_type', 'purchase_num', 'is_icon_super_vip', 'is_show_normal_vip',
                'is_show_super_vip', 'is_show_cart', 'is_show_sales_num', 'is_show_goods_name', 'is_underline_price',
                'is_list_underline_price', 'is_express', 'is_not_share_show', 'is_show_cart_fly', 'is_show_score_top',
                'is_goods_video', 'is_show_stock', 'is_use_stock', 'is_required_position', 'is_share_tip', 'is_quick_customize', 'is_must_login',
                'is_show_hot_goods', 'is_open','open_type','is_auto_open', 'web_service_type',
                'has_order_evaluate', 'order_evaluate_day', 'member_grade'], 'integer'],
            [['name'], 'required',],
            [['share_title', 'share_pic', 'sell_out_pic', 'sell_out_other_pic', 'express_select_type', 'express_aliapy_code', 'mall_logo_pic'], 'default', 'value' => ''],
            [['payment_type', 'send_type', 'quick_customize_new_params', 'send_type_desc', 'time_list',
                'week_list', 'customer_service_list'], 'safe'],
            [['delivery_time', 'after_sale_time'], 'integer', 'min' => 0, 'max' => 30],
            [['over_time'], 'integer', 'min' => 0, 'max' => 100],
            [['delivery_time', 'over_time', 'after_sale_time'], 'default', 'value' => 0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'member_integral' => '积分',
            'delivery_time' => '自动确认收货时间',
            'over_time' => '未支付订单超时时间',
            'after_sale_time' => '售后时间',
        ];
    }

    public function save()
    {
        $this->name = Html::encode($this->name);
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->checkData();

            $default = \Yii::$app->mall->getDefault();
            $list = MallSetting::find()->andWhere(['mall_id' => \Yii::$app->mall->id, 'is_delete' => 0])
                ->orderBy(['id' => SORT_ASC])
                ->all();

            /** @var MallSetting[] $newList */
            $newList = ArrayHelper::index($list, "key");

            foreach ($this->attributes as $key => $value) {
                if (isset($default[$key]) && is_array($default[$key])) {
                    if($key == 'send_type_desc'){
                        $value = $default[$key];
                    }
                    $value = json_encode($value);
                }
                if (isset($default[$key]) && !isset($newList[$key])) {
                    $mallSetting = new MallSetting();
                    $mallSetting->key = $key;
                    $mallSetting->value = (string)$value;
                    $mallSetting->mall_id = \Yii::$app->mall->id;
                    $res = $mallSetting->save();
                    if (!$res) {
                        throw new \Exception($this->getErrorMsg($mallSetting));
                    }
                }
                if (isset($newList[$key])  && $newList[$key]->value != $value) {
                    $mallSetting = $newList[$key];
                    $mallSetting->value = (string)$value;
                    $res = $mallSetting->save();
                    if (!$res) {
                        throw new \Exception($this->getErrorMsg($mallSetting));
                    }
                }
            }
            foreach (array_diff(array_keys($newList), array_keys($default)) as $key){
                $mallSetting = $newList[$key];
                $mallSetting->is_delete = 1;
                $res = $mallSetting->save();
                if (!$res) {
                    throw new \Exception($this->getErrorMsg($mallSetting));
                }
            }

            \Yii::$app->mall->attributes = $this->attributes;
            if (!\Yii::$app->mall->save()) {
                throw new \Exception('保存失败,商城数据异常');
            }

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功。',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine(),
                ]
            ];
        }
    }

    private function checkData()
    {
        if ($this->is_open == 1 && $this->open_type == 2 && empty($this->week_list)) {
            throw new \Exception('请选择营业日期星期');
        }

        if (count($this->customer_service_list) > 10) {
            throw new \Exception('客服微信最多添加10个');
        }

        if ($this->time_list && is_array($this->time_list)) {
            foreach ($this->time_list as $item) {
                if (count($item['value']) != 2) {
                    throw new \Exception('营业时间段设置异常');
                }
                if ($item['value'][0] == $item['value'][1]) {
                    throw new \Exception('营业时间段不能相同');
                }
            }
        }

        if ($this->is_add_app && mb_strlen($this->add_app_text) > 20) {
            throw new \Exception('小程序提示->提示文本内容长度不能大于20个字符');
        }
    }
}
