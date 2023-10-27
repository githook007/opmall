<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

use app\forms\common\CommonOption;
use app\models\Option;

$indSetting = CommonOption::get(Option::NAME_IND_SETTING);
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
        padding-top: 40px;
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
        height: 42px;
        line-height: 42px;
        color: #00d900;
        font-size: 24px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
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
        margin-top: 30px;
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
                <img v-if="user_type == 2" class="msg-logo" :src="roleSetting.logo" alt="">
                <img v-else class="msg-logo" :src="login_logo" alt="">
            </div>
            <el-card class="box-card" shadow="always">
                <div class="right-box">
                    <el-form :model="ruleForm" class="login-form" :rules="rules2" ref="ruleForm" label-width="0"
                             size="small" autocomplete="off">
                        <div class="form-title" :style="{'color': mainColor}">{{user_type == 1 ? "<?= \Yii::t('admin/passport', '管理员');?>" : "<?= \Yii::t('admin/passport', '员工');?>"}}<?= \Yii::t('admin/passport', '登录');?></div>
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
                    </el-form>
                    <!--                    <img class="logo" :src="roleSetting.logo" alt="">-->
                    <div v-if="user_type == 1" class="register_box">
                        <span class="register" :style="{'color': mainColor}" @click="forget"><?= \Yii::t('admin/passport', '忘记密码');?></span>
                        <?php if ($indSetting
                            && isset($indSetting['open_register'])
                            && $indSetting['open_register'] == 1) : ?>
                            <span class="register" :style="{'color': mainColor}" style="border-left: 1px solid #a9a9a9;" @click="register"><?= \Yii::t('admin/passport', '注册账号');?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </el-card>

            <!--忘记密码-->
            <div class="foot" :style="{'bottom': footHeight}">
                <!--员工-->
                <template v-if="user_type == 2">
                    <a :href="roleSetting.copyright_url" target="_blank">{{roleSetting.copyright}}</a>
                </template>
                <!--管理员-->
                <template v-else>
                    <?php if ($indSetting && !empty($indSetting['copyright'])) : ?>
                        <a style="text-decoration: none" href="<?= $indSetting['copyright_url'] ?>"
                           target="_blank"><?= $indSetting['copyright'] ?></a><br />
<!--                    --><?php //else : ?>
<!--                        <a href="#" target="_blank">--><?//= \Yii::t('admin/passport', '底部版权');?><!--</a><br />-->
                    <?php endif; ?>
                    <?php if ($indSetting && !empty($indSetting['edition'])) : ?>
                        <a style="text-decoration: none" href=""
                           target="_blank"><?= \Yii::t('admin/passport', '商城版本：');?><?= $indSetting['edition'] ?></a>
<!--                    --><?php //else : ?>
<!--                        <a href="#" target="_blank">--><?//= \Yii::t('admin/passport', '商城版本：');?><!--</a>-->
                    <?php endif; ?>
                </template>
            </div>
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
                username: '',
                password: '',
                footHeight: '5%',
                btnLoading: false,
                user_type: '2',
                dialogFormVisible: false,
                ruleForm: {
                    pic_captcha: '',
                    checked: false
                },
                roleSetting: {},
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
                pic_captcha_src: null,
                desKey: '<?= !empty($key) ? $key : "123456"; ?>', // 加密key @czs
            };
        },
        created() {
            this.loadPicCaptcha();
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
                                r: 'admin/passport/login'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                                user_type: self.user_type,
                                mall_id: getQuery('mall_id'),
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
                                if (e.data.data && e.data.data.register) {
                                    this.$navigate({r: 'admin/passport/register', active: 3});
                                }
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
            register() {
                navigateTo({
                    r: 'admin/passport/register',
                });
            },
            forget() {
                navigateTo({
                    r: 'admin/passport/register',
                    status: 'forget'
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
            getRoleSetting() {
                let self = this;
                request({
                    params: {
                        r: 'admin/passport/role-setting',
                        mall_id: this.mall_id,
                    },
                    method: 'get',
                }).then(e => {
                    self.roleSetting = e.data.data.setting;
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.mall_id = getQuery('mall_id');
            this.user_type = this.mall_id ? '2' : '1';
            let height = document.body.clientHeight;
            this.footHeight = height < 600 ? '1%' : '5%'
            if (this.user_type == 2) {
                this.getRoleSetting();
            }
        }
    });
</script>