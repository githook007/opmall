<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .form-body {
        padding: 10px 20px;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-button {
        margin: 0;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <?= \Yii::t('mall/role_setting', '基础设置');?>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="130px" size="small">
                <el-tabs v-model="activeName">
                    <el-tab-pane label="<?= \Yii::t('mall/role_setting', '基础设置');?>" name="first">
                        <el-form-item label="<?= \Yii::t('mall/role_setting', '修改密码状态');?>">
                            <el-switch
                                    v-model="ruleForm.update_password_status"
                                    :active-value="1"
                                    :inactive-value="0">
                            </el-switch>
                        </el-form-item>
                    </el-tab-pane>
                    <el-tab-pane label="<?= \Yii::t('mall/role_setting', '员工登录页设置');?>" name="second">
                        <el-row>
                            <el-col :span="12">
                                <el-form-item label="<?= \Yii::t('mall/role_setting', '员工页面LOGO');?>">
                                    <el-input class="currency-width isAppend" v-model="ruleForm.logo">
                                        <template slot="append">
                                            <app-attachment v-model="ruleForm.logo" :multiple="false" :max="1">
                                                <el-tooltip class="item"
                                                            effect="dark"
                                                            content="<?= \Yii::t('mall/role_setting', '建议尺寸');?>:325 * 325"
                                                            placement="top">
                                                    <el-button size="mini"><?= \Yii::t('mall/role_setting', '上传图片');?></el-button>
                                                </el-tooltip>
                                            </app-attachment>
                                        </template>
                                    </el-input>
                                    <img class="my-img"
                                         style="background-color: #100a46;border-color: #100a46; height: 36px;"
                                         v-if="ruleForm.logo" :src="ruleForm.logo">
                                    <div v-else class="preview"><?= \Yii::t('mall/role_setting', '建议尺寸');?>98*36</div>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('mall/role_setting', '员工页面版权信息');?>">
                                    <el-input class="currency-width" v-model="ruleForm.copyright"></el-input>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('mall/role_setting', '员工页面版权链接');?>">
                                    <el-input class="currency-width" v-model="ruleForm.copyright_url"
                                              placeholder="<?= \Yii::t('mall/role_setting', '例如');?>:https://www.baidu.com"></el-input>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-tab-pane>
                </el-tabs>
            </el-form>
        </div>
        <el-button :loading="btnLoading" class="button-item" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('mall/role_setting', '保存');?>
        </el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {},
                permissions: [],
                rules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('mall/role_setting', '请输入角色名称');?>', trigger: 'change'},
                    ]
                },
                treeLoading: false,
                btnLoading: false,
                defaultProps: {
                    label: 'name'
                },
                defaultCheckedKeys: [],
                activeName: 'first',
            };
        },
        methods: {
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/role-setting/index'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
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
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getSetting() {
                let self = this;
                request({
                    params: {
                        r: 'mall/role-setting/index',
                    },
                    method: 'get',
                }).then(e => {
                    self.ruleForm = e.data.data.setting;
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.getSetting();
        }
    });
</script>
