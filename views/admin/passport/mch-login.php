<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

use app\forms\common\CommonOption;
use app\helpers\PluginHelper;
use app\models\Option;

$imgUrl = PluginHelper::getPluginBaseAssetsUrl('mch');
$indSetting = CommonOption::get(Option::NAME_IND_SETTING);
$plugins = Yii::$app->plugin->getList();
$isWxapp = 0;
$isAlipay = 0;
foreach ($plugins as $plugin) {
    if ($plugin->name == 'wxapp') {
        $isWxapp = 1;
    }
    if ($plugin->name == 'aliapp') {
        $isAlipay = 1;
    }
}
?>
<script>const passportBg = '<?=($indSetting && !empty($indSetting['passport_bg'])) ? $indSetting['passport_bg'] : ''?>';</script>
<style>
    .login {
        width: 100%;
        min-height: 880px;
        height: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .login .box-card {
        position: relative;
        border-radius: 15px;
        z-index: 99;
        border: 0;
        width: 470px;
        height: 510px;
        margin: 0 auto;
    }

    .el-card__body {
        padding: 0;
        display: flex;
        width: 1080px;
    }


    .login .box-card .right-box{
        padding-top: 60px;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 470px;
    }

    .msg-logo {
        height: 60px;
    }

    .login-form {
        position: relative;
        line-height: 20px;
        color: #101010;
        font-size: 14px;
    }

    .form-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 42px;
        line-height: 42px;
        color: #00d900;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
    }
    .form-title div{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .form-title img{
        width: 30px;
    }
    .form-box{
        padding-top: 20px;
        width: 330px;
    }

    .el-input {
        height: 55px;
        padding-left: 50px;
        background-color: #f8f8f8;
        border-color: #f8f8f8;
        background-size: 18px 18px;
        background-position: 17px;
        background-repeat: no-repeat;
    }

    .ws-input1 .el-input{
        padding-left: 50px;
        background-image: url("statics/img/4.png");
    }
    .ws-input2 .el-input{
        padding-left: 50px;
        background-image: url("statics/img/3.png");
    }
    .ws-input3 .el-input{
        padding-left: 50px;
        background-image: url("statics/img/2.png");
    }

    .el-input .el-input__inner {
        height: 55px;
        background-color: #f8f8f8;
        border: none;
        -webkit-box-shadow: 0 0 1000px #f8f8f8 inset;
    }

    .login-btn {
        width: 100%;
        border-radius: 20px;
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        color: white;
        text-align: center;
        cursor: pointer;
    }

    .username, password {
        margin-bottom: 20px;
    }

    .radio-box {
        height: 35px;
        line-height: 35px;
    }

    .register_box {
        text-align: center;
        width: 330px;
        margin-top: 40px;
    }

    .register {
        display: inline-block;
        width: 48%;
        height: 15px;
        line-height: 15px;
        text-align: center;
        cursor: pointer;
        color: #00d900;
    }

    .el-dialog {
        width: 35%;
    }



    .opacity {
        background-color: rgba(0, 0, 0, 0.01);
        height: 100%;
        width: 100%;
        position: absolute;
        left: 0;
        top: 0;
        z-index: 1;
    }



    .foot {
        position: absolute;
        left: 0;
        right: 0;
        width: auto;
        color: #fff;
        text-align: center;
        font-size: 16px;
    }

    .foot a,
    .foot a:visited {
        color: #ffffff;
    }

    .footer-text {
        margin-bottom: 10px;
    }

    .login-type-img {
        width: 84px;
        height: 84px;
        margin-bottom: 14px;
    }

    .login-type-label {
        color: #999999;
        font-size: 16px;
    }

    .login-type-box {
        margin: 45px 25px 0;
        cursor: pointer;
    }

    .login-type-img-mini {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }

    .pic-captcha {
        width: 100px;
        height: 36px;
        vertical-align: middle;
        cursor: pointer;
        margin-left: 20px;
    }
</style>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/crypto-js.min.js"></script>
<div id="app" v-cloak>
    <div class="login" :style="{'background-image':'url('+login_bg+')'}">
        <div class="opacity">
            <div flex="main:center" style="margin: 18vh  0 20px 0;">
                <img class="msg-logo" :src="mchSettingInfo.logo || login_logo" alt="">
            </div>
            <el-card class="box-card" shadow="always">
                <div class="right-box">
                    <el-form :model="ruleForm" class="login-form" :rules="rules2" ref="ruleForm" label-width="0"
                             size="small" autocomplete="off">
                        <div class="form-title" :style="{'color': mainColor}">
                            <div><?= \Yii::t('admin/passport', '商家登录');?></div>
                            <div>
                                <img @click="loginTypeChange" v-if="loginType == 1" class="qr_code"
                                     src="<?= $imgUrl . '/img/qr_code.png' ?>">
                                <img @click="loginTypeChange" v-if="loginType == 2" class="qr_code"
                                     src="<?= $imgUrl . '/img/account.png' ?>">
                            </div>
                        </div>
                        <template v-if="loginType == 1">
                            <div class="form-box">
                                <el-form-item prop="username">
                                    <div class="ws-input1">
                                        <el-input @keyup.enter.native="login('ruleForm')" placeholder="<?= \Yii::t('admin/passport', '请输入用户名');?>"
                                                  v-model="ruleForm.username"></el-input>
                                    </div>
                                </el-form-item>
                                <el-form-item prop="password">
                                    <div class="ws-input2">
                                        <el-input  class="ws-input2" @keyup.enter.native="login('ruleForm')" type="password" placeholder="<?= \Yii::t('admin/passport', '请输入密码');?>"
                                                   v-model="ruleForm.password"></el-input>
                                    </div>
                                </el-form-item>
                                <el-form-item prop="pic_captcha">
                                    <div class="ws-input3">
                                        <el-input class="ws-input3" @keyup.enter.native="login('ruleForm')" placeholder="<?= \Yii::t('admin/passport', '验证码');?>"
                                                  style="width: 205px"
                                                  v-model="ruleForm.pic_captcha"></el-input>
                                        <img :src="pic_captcha_src" class="pic-captcha" @click="loadPicCaptcha">
                                    </div>
                                </el-form-item>
                                <el-form-item>
                                    <el-checkbox v-model="ruleForm.checked"><?= \Yii::t('admin/passport', '记住我');?></el-checkbox>
                                </el-form-item>
                                <el-form-item>
                                    <div class="login-btn" :style="{'background-color': mainColor, 'box-shadow': '0 4px 5px '+mainColor+'55'}" @click="login('ruleForm')"><?= \Yii::t('admin/passport', '登录');?></div>
                                </el-form-item>
                            </div>
                        </template>
                        <template v-if="loginType == 2">
                            <template v-if="!qrCodeImg">
                                <div flex="main:center" style="width: 330px">
                                    <div v-if="isWxapp" @click="qrCodeLogin(1)" class="login-type-box"
                                         flex="dir:top cross:center">
                                        <img class="login-type-img" src="<?= $imgUrl . '/img/wechat.png' ?>">
                                        <span class="login-type-label"><?= \Yii::t('admin/passport', '微信登录');?></span>
                                    </div>
                                    <div v-if="isAlipay" @click="qrCodeLogin(2)" class="login-type-box"
                                         flex="dir:top cross:center">
                                        <img class="login-type-img" src="<?= $imgUrl . '/img/alipay.png' ?>">
                                        <span class="login-type-label"><?= \Yii::t('admin/passport', '支付宝登录');?></span>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <div flex="dir:left main:center" style="width: 330px">
                                    <div v-if="isWxapp" @click="qrCodeLogin(1)"
                                         :class="['login-type-box-mini', currentQrCodeType== 1 ? 'type-box-active' : '']"
                                         flex="dir:left main:center cross:center">
                                        <img class="login-type-img-mini" src="<?= $imgUrl . '/img/wechat.png' ?>">
                                        <span class="login-type-label"><?= \Yii::t('admin/passport', '微信');?></span>
                                    </div>
                                    <div v-if="isAlipay" @click="qrCodeLogin(2)"
                                         :class="['login-type-box-mini', currentQrCodeType== 2 ? 'type-box-active' : '']"
                                         flex="dir:left main:center cross:center">
                                        <img class="login-type-img-mini" src="<?= $imgUrl . '/img/alipay.png' ?>">
                                        <span class="login-type-label"><?= \Yii::t('admin/passport', '支付宝');?></span>
                                    </div>
                                </div>
                                <div flex="main:center cross:center dir:top">
                                    <img class="qr-code-img" :src="qrCodeImg">
                                    <span style="color: #999999;">{{qrCodeLoginError}}</span>
                                </div>
                            </template>
                        </template>
                    </el-form>
                </div>
            </el-card>
        </div>
    </div>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                login_bg: passportBg ? passportBg : (_baseUrl + '/statics/img/admin/BG.png'),
                login_logo: _siteLogo,
                mainColor: '#0062d9',
                footHeight: '5%',
                roleSetting: {},
                username: '',
                password: '',
                btnLoading: false,
                dialogFormVisible: false,
                loginType: 1,//1.账号登录 2.扫码登录
                ruleForm: {
                    pic_captcha: '',
                    mall_id: getQuery('mall_id'),
                    checked: false,
                },
                rules2: {
                    username: [
                        {required: true, message: "<?= \Yii::t('admin/passport', '请输入用户名');?>", trigger: 'blur'},
                    ],
                    password: [
                        {required: true, message: "<?= \Yii::t('admin/passport', '请输入密码');?>", trigger: 'blur'},
                    ],
                    pic_captcha: [
                        {required: true, message: "<?= \Yii::t('admin/passport', '请输入右侧图片上的文字');?>", trigger: 'blur'},
                    ],
                },
                isWxapp: <?= $isWxapp ?>,
                isAlipay: <?= $isAlipay ?>,
                loading: false,
                qrCodeImg: '',
                currentQrCodeType: 1,
                pic_captcha_src: null,
                qrCodeLoginError: '',
                mchSettingInfo: {},
                desKey: '<?= !empty($key) ? $key : "123456"; ?>', // 加密key @czs
            };
        },
        methods: {
            login(formName) {
                let self = this;
                self.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        let orgPwd = self.ruleForm.password;
                        self.ruleForm.password = self.encrypt(self.ruleForm.password, self.desKey, self.desKey); // 密码加密 @czs
                        request({
                            params: {
                                r: 'admin/passport/mch-login'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.ruleForm.password = orgPwd;
                            self.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                self.$navigate({
                                    r: e.data.data.url,
                                });
                            } else {
                                this.loadPicCaptcha();
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            console.log(e);
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            encrypt(str, key, iv) { // 密码加密传输 @czs
                let encode_str = '';
                key = CryptoJS.MD5(key).toString();
                iv = CryptoJS.MD5(iv).toString();
                let crypto_key = CryptoJS.enc.Utf8.parse(key);
                let crypto_iv = CryptoJS.enc.Utf8.parse(iv.substr(0, 8));
                if (typeof (str) == 'string') {
                    encode_str = CryptoJS.TripleDES.encrypt(str, crypto_key, {
                        iv: crypto_iv,
                        mode: CryptoJS.mode.CBC,
                        padding: CryptoJS.pad.Pkcs7
                    });
                } else {
                    encode_str = CryptoJS.TripleDES.encrypt(JSON.stringify(str), crypto_key, {
                        iv: crypto_iv,
                        mode: CryptoJS.mode.CBC,
                        padding: CryptoJS.pad.Pkcs7
                    });
                }
                return encode_str.toString();
            },
            loginTypeChange() {
                this.loginType = this.loginType === 1 ? 2 : 1;
            },
            qrCodeLogin(type) {
                let self = this;
                self.loading = true;
                self.currentQrCodeType = type;
                request({
                    params: {
                        r: 'admin/passport/login-qr-code',
                    },
                    method: 'post',
                    data: {
                        type: type,
                        mall_id: getQuery('mall_id'),
                    },
                    headers: {'x-app-platform': type == 1 ? 'wx' : 'ali'},
                }).then(e => {
                    self.loading = false;
                    if (e.data.code === 0) {
                        self.qrCodeImg = e.data.data.data.file_path;
                        let interval = setInterval(function () {
                            self.checkQrCode(e.data.data.token)
                        }, 2000)
//                        clearInterval(interval)
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            checkQrCode(token) {
                let self = this;
                request({
                    params: {
                        r: 'admin/passport/check-mch-login',
                    },
                    method: 'post',
                    data: {
                        token: token,
                        mall_id: getQuery('mall_id'),
                    },
                }).then(e => {
                    self.loading = false;
                    if (e.data.code === 0) {
                        self.$navigate({
                            r: e.data.data.url,
                        });
                    } else {
                        self.qrCodeLoginError = e.data.msg;
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            loadPicCaptcha() {
                this.$request({
                    noHandleError: true,
                    params: {
                        r: 'site/pic-captcha',
                        refresh: true,
                    },
                }).then(response => {
                }).catch(response => {
                    if (response.data.url) {
                        this.pic_captcha_src = response.data.url;
                    }
                });
            },
            mchSetting() {
                let self = this;
                request({
                    params: {
                        r: 'admin/passport/mch-setting',
                        mall_id: getQuery('mall_id')
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        console.log(e.data.data.setting)
                        self.mchSettingInfo = e.data.data.setting;
                    } else {
                        console.log(e)
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.loadPicCaptcha();
            this.mchSetting();
        }
    });
</script>