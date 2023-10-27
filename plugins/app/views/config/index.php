<?php
/**
 * Created by PhpStorm
 * User: chenzs
 * Date: 2020/9/29
 * Time: 4:08 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('app-rich-text')
?>

<style>
    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 0;
    }

    .title {
        margin-top: 10px;
        padding: 18px 20px;
        border-top: 1px solid #F3F3F3;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .form-body {
        background-color: #fff;
        padding: 20px 20% 20px 0;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }

    .tip {
        margin: 0px 20px 20px;
        background-color: rgb(244, 244, 245);
        padding: 10px 15px;
        color: rgb(144, 147, 153);
        display: inline-block;
        font-size: 15px;
    }

</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <el-form @submit.native.prevent :model="form" :rules="rules" label-width="150px" ref="form">
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="<?= \Yii::t('plugins/app', '地图设置');?>" name="map">
                    <el-row>
                        <el-col :span="24">
                            <div class="title">
                                <span>android</span>
                            </div>
                            <div class="form-body">
                                <el-form-item class="switch" label="<?= \Yii::t('plugins/app', '百度地图');?>AK" prop="baidu_android_ak">
                                    <el-input size="small" placeholder="<?= \Yii::t('plugins/app', '开发者申请的');?>AK" v-model="form.baidu_android_ak" autocomplete="off"></el-input>
                                </el-form-item>
                            </div>
                            <div class="title">
                                <span>ios</span>
                            </div>
                            <div class="form-body">
                                <el-form-item class="switch" label="<?= \Yii::t('plugins/app', '百度地图');?>AK" prop="baidu_ios_ak">
                                    <el-input size="small" placeholder="<?= \Yii::t('plugins/app', '开发者申请的');?>AK" v-model="form.baidu_ios_ak" autocomplete="off"></el-input>
                                </el-form-item>
                            </div>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/app', '微信设置');?>" name="wx">
                    <el-row>
                        <el-col :span="24">
                            <div class="title">
                                <span><?= \Yii::t('plugins/app', '登录设置');?></span>
                            </div>
                            <div class="form-body">
                                <div class="tip"><?= \Yii::t('plugins/app', '微信登录提示');?></div>
                                <el-form-item class="switch" label="AppID" prop="app_id">
                                    <el-input size="small" placeholder="<?= \Yii::t('plugins/app', '移动应用');?>AppID" v-model="form.app_id"></el-input>
                                </el-form-item>
                                <el-form-item class="switch" label="AppSecret" prop="app_secret">
                                    <el-input size="small" placeholder="<?= \Yii::t('plugins/app', '移动应用');?>AppSecret" v-model="form.app_secret"></el-input>
                                </el-form-item>
                            </div>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/app', '协议配置');?>" name="agreement">
                    <el-row>
                        <el-col :span="24">
                            <div class="title">
                                <span><?= \Yii::t('plugins/app', '协议配置');?></span>
                            </div>
                            <div class="form-body">
                                <el-form-item class="switch" label="<?= \Yii::t('plugins/app', '用户协议');?>">
                                    <div>
                                        <el-radio v-model="form.agreement_type" :label="1"><?= \Yii::t('plugins/app', '自定义内容');?></el-radio>
                                        <el-radio v-model="form.agreement_type" :label="2"><?= \Yii::t('plugins/app', '网页链接');?></el-radio>
                                    </div>
                                    <div v-if="form.agreement_type == 1" style="margin-top: 10px">
                                        <app-rich-text v-model="form.agreement_content"></app-rich-text>
                                    </div>
                                    <div v-if="form.agreement_type == 2" style="margin-top: 10px">
                                        <el-input size="small" v-model="form.agreement_link"></el-input>
                                    </div>
                                </el-form-item>
                            </div>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <el-button class='button-item' :loading="btnLoading" type="primary" @click="store('form')" size="small"><?= \Yii::t('plugins/app', '保存');?></el-button>
            </el-tabs>
        </el-form>
    </el-card>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                activeName: 'map',
            	cardLoading: false,
            	btnLoading: false,
                index: -1,
                form: {
                    baidu_android_ak: '',
                    baidu_ios_ak: '',
                    app_id: '',
                    app_secret: '',
                },
                rules: {
                    // baidu_android_ak: [{required: true, message: '请填写AK'}],
                    // baidu_ios_ak: [{required: true, message: '请填写AK'}],
                    // app_id: [{required: true, message: '请填写移动应用AppID'}],
                    // app_secret: [{required: true, message: '请填写移动应用AppSecret'}],
                },
            };
        },
        created() {
            this.getDetail();
        },
        methods: {
            handleClick(tab, event) {
                console.log(tab, event);
            },
            store(formName) {
                let self = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/app/mall/config/index'
                            },
                            method: 'post',
                            data: self.form
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/app/mall/config/index',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.form = e.data.data
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
        },
    });
</script>