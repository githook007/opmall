<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\passport;

use app\bootstrap\response\ApiCode;
use app\forms\common\RoleSettingForm;
use app\jobs\UserActionJob;
use app\models\AdminRegister;
use app\models\Mall;
use app\models\Model;
use app\models\User;

class PassportForm extends Model
{
    public $username;
    public $password;
    public $user_type;
    public $mall_id;
    public $pic_captcha;
    public $checked;

    const DES_KEY = "des_song_123456"; // 加密key @czs

    public function rules()
    {
        return [
            [['username', 'password', 'user_type', 'pic_captcha', 'checked'], 'required'],
            [['mall_id'], 'string'],
            [['pic_captcha'], 'captcha', 'captchaAction' => 'site/pic-captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'user_type' => '用户类型',
            'mall_id' => '商城ID',
            'pic_captcha' => '验证码',
        ];
    }

    public function login()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $mallId = base64_decode($this->mall_id);
        try {
            $key = md5(self::DES_KEY);
            $this->password = @openssl_decrypt(
                base64_decode($this->password),
                'des-ede3-cbc',
                $key,
                OPENSSL_RAW_DATA,
                substr($key, 0, 8)
            ); // 解密密码 @czs

            $query = User::find()->alias('u')->joinWith(['identity' => function ($query) {
                if ((int)$this->user_type === 1) {
                    $query->andWhere([
                        'or',
                        ['is_super_admin' => 1],
                        ['is_admin' => 1]
                    ]);
                } else {
                    $query->andWhere(['is_operator' => 1]);
                }
            }])->andWhere(['u.username' => $this->username, 'u.is_delete' => 0]);

            if ((int)$this->user_type === 2) {
                $query->andWhere(['mall_id' => $mallId]);
            }

            /** @var User $user */
            $user = $query->one();
            if (!$user) {
                $registerExist = AdminRegister::find()->where([
                    'username' => $this->username,
                    'status' => 0,
                    'is_delete' => 0,
                ])->exists();
                if ($registerExist) {
                    return [
                        'code' => ApiCode::CODE_ERROR,
                        'msg' => '用户审核中',
                        'data' => [
                            'register' => true,
                        ],
                    ];
                }
                throw new \Exception('用户不存在');
            }

            // 员工账号登录需判断 商城是否过期
            if ($this->user_type == 2) {
                $mall = Mall::findOne($user->mall_id);
                if (!$mall) {
                    throw new \Exception('商城不存在，ID:' . $user->mall_id);
                }
                if ($mall->expired_at != '0000-00-00 00:00:00' && strtotime($mall->expired_at) < time()) {
                    throw new \Exception('商城已过期，无法登录，请联系管理员');
                }
            }

            if (!\Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
                throw new \Exception('密码错误');
            }

            $adminInfo = $user->adminInfo;
            // 加判断是为了排除员工账号
            if (($user->identity->is_admin === 1 || $user->identity->is_super_admin === 1) && !$adminInfo) {
                throw new \Exception('账户异常：账户信息不存在');
            }

            if ($user->identity->is_admin === 1 &&
                $adminInfo->expired_at !== '0000-00-00 00:00:00' &&
                time() > strtotime($adminInfo->expired_at)) {
                throw new \Exception('账户已过期！请联系管理员');
            }

            // 一天只更新一次token
            if(date("Y-m-d 00:00:00", strtotime($user->updated_at)) < date("Y-m-d 00:00:00", time())){
                $user->access_token = \Yii::$app->security->generateRandomString();
                $user->save();
            }

            $duration = $this->checked == 'true' ? 86400 : 0;
            \Yii::$app->user->login($user, $duration);
            setcookie('__login_route', '/admin/passport/login');
            if ($this->user_type == 1) {
                // 管理员
                $route = 'admin/index/index';
                $user->setLoginData(User::LOGIN_ADMIN);
            } else {
                // 员工
                $route = 'mall/index/index';
                $user->setLoginData(User::LOGIN_STAFF);
            }

            $dataArr = [
                'newBeforeUpdate' => [],
                'newAfterUpdate' => [],
                'modelName' => 'app\models\User',
                'modelId' => $user->id,
                'remark' => $this->user_type == 1 ? '管理员登录' : '员工登录',
                'user_id' => $user->id,
                'mall_id' => $mallId
            ];
            $class = new UserActionJob($dataArr);
            \Yii::$app->queue->delay(0)->push($class);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '登录成功',
                'data' => [
                    'url' => $route
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function getRoleSetting()
    {
        $mallId = base64_decode(\Yii::$app->request->get('mall_id'));
        $form = new RoleSettingForm();
        $form->mall_id = $mallId;
        $setting = $form->getSetting();

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'setting' => $setting
            ]
        ];
    }
}
