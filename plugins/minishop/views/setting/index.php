<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */
?>
<style>
    .button-item {
        padding: 9px 25px;
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

    .user-list{
        width: 150%;
    }

    .user-item .el-checkbox-button__inner{
        border: 1px solid #e2e2e2;
        /*height: 125px;*/
        width: 120px;
        padding-top: 15px;
        text-align: center;
        margin: 0 20px 20px 0;
        cursor: pointer;
        border-radius: 0!important;
    }

    .user-item.active{
        background-color: #50A0E4;
        color: #fff;
    }

    .user-list .avatar{
        height: 60px;
        width: 60px;
        border-radius: 30px;
    }

    .username{
        margin-top: 10px;
        font-size: 13px;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .platform-img {
        width: 24px;
        height: 24px;
        margin-top: 8px;
    }
</style>
<div id="app" v-cloak>
    <el-card v-loading="cardLoading" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('plugins/minishop', '基础设置');?></span>
            </div>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="172px" size="small">
            <el-row>
                <el-col :span="24">
                    <div class="title">
                        <span><?= \Yii::t('plugins/minishop', '组件开通接入');?></span>
                    </div>
                    <div class="form-body">
                        <el-form-item prop="status" label="<?= \Yii::t('plugins/minishop', '测试开启');?>">
                            <el-switch v-model="ruleForm.status" active-value="1"
                                       inactive-value="0"></el-switch>
                        </el-form-item>
                        <el-form-item prop="user_id_list">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/minishop', '选择用户');?></span>
                            </template>
                            <el-alert
                                    style="margin-bottom:10px;"
                                    type="info"
                                    title="<?= \Yii::t('plugins/minishop', '选择的用户可以直接下单到微信自定义交易组件里进行接入');?>"
                                    :closable="false">
                            </el-alert>
                            <el-input size="small" v-model="keyword" placeholder="<?= \Yii::t('plugins/minishop', '昵称');?>" style="width: 50%"></el-input>
                            <el-button size="small" :loading=foundLoading @click="search"><?= \Yii::t('plugins/minishop', '查找用户');?></el-button>
                            <el-checkbox-group  class="user-list" v-model="ruleForm.user_id" size="medium">
                                <el-checkbox-button class="user-item" v-for="item in userList" :label="item.id" :key="item.id">
                                    <img class="avatar" :src="item.avatar" alt="">
                                    <div class="username">{{ item.nickname }}</div>
                                    <img class="platform-img" :src="item.platform_icon" alt="">
                                </el-checkbox-button>
                            </el-checkbox-group>
                        </el-form-item>
                    </div>
                </el-col>
            </el-row>
        </el-form>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('plugins/minishop', '保存');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    user_id: []
                },
                rules: {},
                userList: [],
                cardLoading: false,
                btnLoading: false,
                foundLoading:false,
                keyword:null,
            };
        },
        methods: {
            search(){
                this.foundLoading = true;
                request({
                    params: {
                        r: 'plugin/minishop/mall/setting/search-user',
                        keyword: this.keyword,
                    },
                }).then(e => {
                    this.foundLoading = false;
                    if (e.data.code == 0) {
                        this.ruleForm.user_id = [];
                        this.userList = e.data.data.list;
                    }
                }).catch(e => {
                    this.foundLoading = false;
                });
            },
            getDetail() {
                this.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/minishop/mall/setting'
                    },
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code == 0) {
                        this.ruleForm = e.data.data.setting || {};
                        this.userList = e.data.data.user_list || [];
                    }
                }).catch(e => {
                });
            },
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    console.log(self.id)
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/minishop/mall/setting'
                            },
                            method: 'post',
                            data: self.ruleForm
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'plugin/minishop/mall/setting',
                                });
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
