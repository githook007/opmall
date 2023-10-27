<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/8 18:12
 */
?>
<style>
    .header-box {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 10px;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
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
        padding: 20px 50% 20px 0;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
        margin-bottom: 50px;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/" style="display: inline-block">
            <el-breadcrumb-item>
        <span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'plugin/wxapp/wx-app-config/setting'})">
          <?= \Yii::t('plugins/wxapp', '基础设置');?>
        </span>
            </el-breadcrumb-item>
            <el-breadcrumb-item><?= \Yii::t('plugins/wxapp', '隐私保护设置');?></el-breadcrumb-item>
        </el-breadcrumb>
    </div>
    <el-form :model="ruleForm"
             :rules="rules"
             ref="ruleForm"
             label-width="194px"
             size="small">
        <el-card v-loading="loading" style="border:0" shadow="never">
            <div slot="header" class="flex-row">
                <span><?= \Yii::t('plugins/wxapp', '收集方信息配置');?></span>
            </div>
            <div>
                <el-form-item label="<?= \Yii::t('plugins/wxapp', '邮箱地址');?>">
                    <el-input v-model="ruleForm.owner_setting.contact_email"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/wxapp', '手机号');?>" prop="contact_phone">
                    <el-input v-model="ruleForm.owner_setting.contact_phone"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/wxapp', 'QQ号');?>">
                    <el-input v-model="ruleForm.owner_setting.contact_qq"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/wxapp', '微信号');?>">
                    <el-input v-model="ruleForm.owner_setting.contact_weixin"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/wxapp', '通知方式');?>" prop="notice_method">
                    <el-input v-model="ruleForm.owner_setting.notice_method"></el-input>
                    <div style="color: #CCCCCC;"><?= \Yii::t('plugins/wxapp', '当收集信息有变动时');?></div>
                </el-form-item>
            </div>
        </el-card>
        <el-card v-loading="loading" style="border:0;margin-top: 10px;" shadow="never">
            <div slot="header" class="flex-row">
                <span><?= \Yii::t('plugins/wxapp', '收集用户信息配置');?></span>
            </div>
            <el-card shadow="never">
                <div slot="header">
                    <span style="color: red;">要收集的用户信息配置（必填）</span>
                </div>
                <div v-for="(item,index) in ruleForm.setting_list" :key="index">
                    <el-form-item :label="nameList[item.privacy_key]">
                        <el-input v-model="item.privacy_text"></el-input>
                    </el-form-item>
                </div>
            </el-card>
            <el-card shadow="never" v-if="other" style="margin-top: 10px;">
                <div slot="header">
                    <span>可选填的信息配置项目</span>
                </div>
                <div v-for="(item,index) in other" :key="index">
                    <el-form-item :label="nameList[item.privacy_key]">
                        <el-input v-model="item.privacy_text">
                            <el-button slot="append" type="text" @click="defaultText(index)" style="padding: 15px;" v-if="item.default_text">默认词</el-button>
                        </el-input>
                    </el-form-item>
                </div>
            </el-card>
        </el-card>
        <el-button :loading="submitLoading" class="button-item" size="small" type="primary"
                   @click="submit('ruleForm')"><?= \Yii::t('plugins/wxapp', '保存');?>
        </el-button>
    </el-form>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                wechatVisible: false,
                ruleForm: {
                    owner_setting: {
                        contact_email: '',
                        contact_phone: '',
                        contact_qq: '',
                        contact_weixin: '',
                        notice_method: '',
                    },
                    setting_list: [],
                },
                nameList: {},
                other: {},
                rules: {
                    contact_phone: [
                        {required: true, validator: (rule, value, callback) => {
                                if (!this.ruleForm.owner_setting.contact_phone) {
                                    callback(`<?= \Yii::t('plugins/wxapp', '输入手机号');?>`);
                                }
                                callback();
                            }},
                    ],
                    notice_method: [
                        {required: true, validator: (rule, value, callback) => {
                                if (!this.ruleForm.owner_setting.notice_method) {
                                    callback(`<?= \Yii::t('plugins/wxapp', '输入通知方式');?>`);
                                }
                                callback();
                            }},
                    ]
                },
                submitLoading: false,
            };
        },
        created() {
            this.loadData();
        },
        methods: {
            defaultText(index){
                this.other[index].privacy_text = this.other[index].default_text;
            },
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/wxapp/wx-app-config/privacy-setting',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data.list;
                        this.nameList = e.data.data.name;
                        this.other = e.data.data.other;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            submit(formName) {
                this.$refs[formName].validate((valid,mes) => {
                    if (valid) {
                        this.submitLoading = true;
                        request({
                            params: {
                                r: 'plugin/wxapp/wx-app-config/privacy-setting',
                            },
                            method: 'post',
                            data: {
                                ruleForm: JSON.stringify(this.ruleForm),
                                other: JSON.stringify(this.other),
                            },
                        }).then(e => {
                            this.submitLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                history.go(-1)
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                        //test
                        this.$message.error(Object.values(mes).shift().shift().message);
                    }
                });
            },
        },
        computed: {},
    });
</script>