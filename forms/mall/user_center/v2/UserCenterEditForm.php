<?php
/**
 * Created By PhpStorm
 * Date: 2021/7/3
 * Time: 10:26 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\forms\mall\user_center\v2;

use app\forms\admin\mall\MallOverrunForm;
use app\forms\common\config\UserCenterConfig;
use app\forms\common\diy\CheckComponent;
use app\helpers\Json;
use app\models\Model;
use app\models\UserCenter;

class UserCenterEditForm extends Model
{
    public $id;
    public $name;
    public $data;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'data'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'data' => '内容',
        ];
    }

    public function get()
    {
        $data = [
            'allComponents' => $this->allComponents(),
            'overrun' => (new MallOverrunForm())->getSetting(),
        ];

        $newData = [];
        $userCenter = UserCenterConfig::getInstance()->getEdit(\Yii::$app->request->get('id'));
        $idList = ['background'];
        foreach ($data['allComponents'] as $allComponent) {
            foreach ($allComponent['list'] as $item) {
                $idList[] = $item['id'];
            }
        }
        if (!empty($userCenter['data'])) {
            foreach ($userCenter['data'] as $datum) {
                if ($datum['id'] === 'user') {
                    $datum['data']['show_code'] = in_array('teller', \Yii::$app->mall->role->permission) ? 1 : 0;
                }
                if (in_array($datum['id'], $idList)) {
                    $newData[] = $datum;
                }
            }
        }
        $default = [];
        foreach ($this->getDefault() as $item) {
            if (in_array($item['id'], $idList)) {
                $default[$item['id']] = $item['data'];
            }
        }
        $data['data'] = Json::encode($newData, JSON_UNESCAPED_UNICODE);
        $data['name'] = $userCenter['name'];
        $data['id'] = $userCenter['id'];
        $data['defaultComponents'] = $default;
        return $this->success([
            'data' => $data
        ]);
    }

    protected function allComponents()
    {
        $result = [
            [
                'groupName' => '用户中心组件',
                'list' => [
                    [
                        'id' => 'user',
                        'name' => '用户信息',
                        'icon' => 'statics/img/mall/user-center/diy/user-icon.png',
                        'single' => true
                    ],
                    [
                        'id' => 'foot',
                        'name' => '收藏足迹',
                        'icon' => 'statics/img/mall/user-center/diy/foot-icon.png',
                        'single' => true
                    ],
                    [
                        'id' => 'order',
                        'name' => '订单栏',
                        'icon' => 'statics/img/mall/user-center/diy/order-icon.png',
                        'single' => true
                    ],
                    [
                        'id' => 'svip',
                        'key' => 'vip_card',
                        'name' => '超级会员卡',
                        'icon' => 'statics/img/mall/user-center/diy/svip-icon.png',
                        'single' => true
                    ],
                    [
                        'id' => 'account',
                        'name' => '账户栏',
                        'icon' => 'statics/img/mall/user-center/diy/account-icon.png',
                        'single' => true
                    ],
                    [
                        'id' => 'menu',
                        'name' => '菜单栏',
                        'icon' => 'statics/img/mall/user-center/diy/menu-icon.png',
                        'single' => false
                    ],
                ]
            ],
            [
                'groupName' => '基础组件',
                'list' => [
                    [
                        'id' => 'goods',
                        'name' => '商品',
                        'icon' => 'statics/img/mall/user-center/diy/form_goods_icon.png',
                    ],
                    [
                        'id' => 'banner',
                        'name' => '轮播广告',
                        'icon' => 'statics/img/mall/user-center/diy/form_banner_icon.png',
                    ],
                    [
                        'id' => 'notice',
                        'name' => '公告',
                        'icon' => 'statics/img/mall/user-center/diy/form_notice_icon.png',
                    ],
                    [
                        'id' => 'rubik',
                        'name' => '图片广告',
                        'icon' => 'statics/img/mall/user-center/diy/form_rubik_icon.png',
                    ],
                    [
                        'id' => 'link',
                        'name' => '标题',
                        'icon' => 'statics/img/mall/user-center/diy/form_title_icon.png',
                    ],
                ]
            ],
            [
                'groupName' => '其他组件',
                'list' => [
                    [
                        'id' => 'empty',
                        'name' => '空白块',
                        'icon' => 'statics/img/mall/user-center/diy/form_empty_icon.png',
                    ],
                    [
                        'id' => 'customer',
                        'name' => '自定义客服',
                        'icon' => 'statics/img/mall/user-center/diy/form_customer.png',
                    ],
                ]
            ]
        ];
        return CheckComponent::check($result);
    }

    public function save()
    {
        try {
            if (!$this->validate()) {
                throw new \Exception($this->getErrorMsg());
            }
            $model = UserCenter::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id, 'is_delete' => 0]);
            if (!$model) {
                $model = new UserCenter();
                $model->mall_id = \Yii::$app->mall->id;
                $model->is_delete = 0;
                $model->is_recycle = 0;
                $model->config = Json::encode([], JSON_UNESCAPED_UNICODE);
            }
            $model->version = 2;
            $model->name = $this->name;
            $this->data = Json::decode($this->data, true);
            if (empty($this->data)) {
                throw new \Exception('用户中心数据不能为空');
            }
            if (count($this->data) < 2) {
                throw new \Exception('用户中心数据不正确');
            }
            if (!($this->data[0]['id'] == 'user' || ($this->data[0]['id'] == 'background' && $this->data['1']['id'] == 'user'))) {
                throw new \Exception('用户信息组件必须在顶格');
            }
            $model->data = Json::encode($this->data, JSON_UNESCAPED_UNICODE);

            if (!$model->save()) {
                throw new \Exception('保存失败');
            }

            return $this->success([
                'msg' => '保存成功'
            ]);
        } catch (\Exception $e) {
            return $this->failByException($e);
        }
    }

    public function getDefault()
    {
        $iconUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/img/mall/user-center/';
        $svipUrlPrefix = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/img/app/vip_card/';
        return [
            [
                'id' => 'user',
                'data' => [
                    'bg_style' => 2,
                    'mode' => 1,
                    'bg_color' => '#090400',
                    'bg_height' => 360,
                    'top_pic_url' => $iconUrlPrefix . 'img-user-bg.png',
                    'center_pic_url' => $iconUrlPrefix . 'img-user-center-bg.png',
                    'card_pic_url' => $iconUrlPrefix . 'img-user-card-bg.png',
                    'member_pic_url' => $iconUrlPrefix . 'icon-member.png',
                    'member_bg_pic_url' => $iconUrlPrefix . 'card-member-0.png',
                    'general_user_text' => '普通用户',
                    'top_size' => 112,
                    'top_style' => 1,
                    'card_color' => '#FFFFFF',
                    'login_color' => '#9C9C9D',
                    'nickname_color' => '#FFFFFF',
                    'nickname_size' => 40,
                    'img_height' => 360,
                    'top_margin' => 36,
                    'code' => 1,
                    'login_style' => 1,
                    'show_code' => in_array('teller', \Yii::$app->mall->role->permission) ? 1 : 0,
                    'code_pic' => $iconUrlPrefix . 'qr-white.png',
                    'black_code_pic' => $iconUrlPrefix . 'qr.png',
                    'address' => [
                        'status' => 1,
                        'top_margin' => 76,
                        'bg_color' => '#101010',
                        'text_color' => '#FFFFFF',
                        'mode' => 1,
                        'style' => 1,
                        'border' => 1,
                        'border_color' => '#FFFFFF',
                        'pic_url' => $iconUrlPrefix . 'address-white.png'
                    ],
                ]
            ],
            [
                'id' => 'foot',
                'data' => [
                    'bg' => 1,
                    'bg_style' => 2,
                    'bg_color' => '#FFFFFF',
                    'bg_pic' => $iconUrlPrefix . 'img-foot-bg.png',
                    'mode' => 1,
                    'style' => 1,
                    'favorite_icon' => $iconUrlPrefix . 'favorite-white.png',
                    'foot_icon' => $iconUrlPrefix . 'foot-white.png',
                    'gery_favorite_icon' => $iconUrlPrefix . 'favorite.png',
                    'gery_foot_icon' => $iconUrlPrefix . 'foot.png',
                    'card' => 0,
                    'card_color' => '#090400',
                    'card_margin' => 20,
                    'title_color' => '#FFFFFF',
                    'title_size' => 28,
                    'number_color' => '#FFFFFF',
                    'number_size' => 44,
                    'line_color' => '#FFFFFF',
                    'margin' => 0
                ]
            ],
            [
                'id' => 'svip',
                'data' => [
                    'bg_style' => 2,
                    'bg_color' => '#FFFFFF',
                    'card_margin' => 10,
                    'bg_pic' => $iconUrlPrefix . 'img-svip-bg.png',
                    'buy_bg' => $svipUrlPrefix . 'buy_bg.png',
                    'buy_big' => '开通超级会员，立省更多',
                    'buy_big_color' => '#492C01',
                    'buy_small' => '超值全场8.8折！',
                    'buy_small_color' => '#FF0000',
                    'buy_btn_bg_color' => '',
                    'buy_btn_text' => '立即开通',
                    'buy_btn_color' => '#492C01',
                    'renew_bg' => $svipUrlPrefix . 'buy_bg.png',
                    'renew_text' => 'SVIP会员优享9.5折，全场包邮',
                    'renew_text_color' => '#492C01',
                    'renew_btn_bg_color' => '',
                    'margin' => 0
                ]
            ],
            [
                'id' => 'order',
                'data' => [
                    'bg' => 1,
                    'bg_style' => 2,
                    'bg_color' => '#FFFFFF',
                    'bg_pic' => $iconUrlPrefix . 'img-order-bg.png',
                    'mode' => 1,
                    'pay_icon' => $iconUrlPrefix . 'icon-order-0.png',
                    'send_icon' => $iconUrlPrefix . 'icon-order-1.png',
                    'confirm_icon' => $iconUrlPrefix . 'icon-order-2.png',
                    'sale_icon' => $iconUrlPrefix . 'icon-order-3.png',
                    'refund_icon' => $iconUrlPrefix . 'icon-order-4.png',
                    'card_color' => '#FFFFFF',
                    'card_margin' => 20,
                    'title_color' => '#999999',
                    'title_size' => 32,
                    'icon_color' => '#999999',
                    'icon_size' => 24,
                    'margin' => 0
                ]
            ],
            [
                'id' => 'account',
                'data' => [
                    'bg' => 0,
                    'bg_style' => 1,
                    'bg_color' => '#FFFFFF',
                    'bg_pic' => '',
                    'integral' => 1,
                    'balance' => 1,
                    'coupon' => 1,
                    'card' => 1,
                    'mode' => 1,
                    'show_icon' => 1,
                    'integral_icon' => $iconUrlPrefix . 'integral_icon.png',
                    's_integral_icon' => $iconUrlPrefix . 's-integral_icon.png',
                    'integral_color' => '#F6EBDB',
                    'balance_icon' => $iconUrlPrefix . 'balance_icon.png',
                    's_balance_icon' => $iconUrlPrefix . 's-balance_icon.png',
                    'balance_color' => '#F6EBDB',
                    'coupon_icon' => $iconUrlPrefix . 'coupon_icon.png',
                    's_coupon_icon' => $iconUrlPrefix . 's-coupon_icon.png',
                    'coupon_color' => '#F6EBDB',
                    'card_icon' => $iconUrlPrefix . 'card_icon.png',
                    's_card_icon' => $iconUrlPrefix . 's-card_icon.png',
                    'card_color' => '#F6EBDB',
                    'card_bg' => '#FFFFFF',
                    'card_margin' => 20,
                    'title_color' => '#999999',
                    'title_size' => 28,
                    'number_color' => '#333333',
                    'number_size' => 40,
                    'margin' => 0
                ]
            ],
            [
                'id' => 'menu',
                'data' => [
                    'show_title' => 1,
                    'title' => '菜单栏',
                    'title_style' => 1,
                    'title_color' => '#333333',
                    'text_color' => '#666666',
                    'title_size' => 32,
                    'card' => 0,
                    'card_bg' => '#FFFFFF',
                    'titleInCard' => 1,
                    'card_margin' => 20,
                    'margin' => 0,
                    'menus' => []
                ]
            ],
            [
                'id' => 'background',
                'data' => [
                    'showImg' => false,
                    'backgroundColor' => '#FFFFFF',
                    'backgroundPicUrl' => '',
                    'position' => 5,
                    'mode' => 1,
                    'backgroundHeight' => 100,
                    'backgroundWidth' => 100,
                    'positionText' => 'center center',
                    'repeatText' => 'no-repeat',
                ]
            ],
        ];
    }
}