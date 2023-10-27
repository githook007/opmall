<?php

?>
<style>
    .header-box {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 10px;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .fast-input {
        width: 40vw;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/" style="display: inline-block">
            <el-breadcrumb-item>
                <span style="color: #409EFF;cursor: pointer"
                      @click="$navigate({r:'plugin/wxapp/wx-app-config/setting'})">
                    <?= \Yii::t('plugins/wxapp', '小程序配置');?>
                </span>
            </el-breadcrumb-item>
            <el-breadcrumb-item><?= \Yii::t('plugins/wxapp', '快速注册小程序');?></el-breadcrumb-item>
        </el-breadcrumb>
        <el-button style="float: right;margin-top:-5px" size="small"
                   type="primary"
                   @click="$navigate({r:'plugin/wxapp/third-platform/fast-create-list'})"
        ><?= \Yii::t('plugins/wxapp', '提交记录');?>
        </el-button>
    </div>
    <el-card shadow="never" style="background: #FFFFFF" body-style="background-color: #ffffff;">
        <div style="margin: 24px 0;background: #ECF5FE;padding: 20px">
            <div style="color: #999999;font-size: 13px;margin-bottom: 4px"><?= \Yii::t('plugins/wxapp', '使用说明');?></div>
            <div style="margin: 2px 0"><?= \Yii::t('plugins/wxapp', '通过该接口创建的小程序创建成功后即为');?></div>
            <div style="margin: 2px 0"><?= \Yii::t('plugins/wxapp', '填写以下信息');?></div>
            <div style="margin: 2px 0"><?= \Yii::t('plugins/wxapp', '法人于24h内进行法人身份信息和人脸信息认证');?></div>
            <div style="margin: 2px 0"><?= \Yii::t('plugins/wxapp', '身份认证通过后');?></div>
        </div>
        <el-form :model="editForm" ref="editForm" :rules="editFormRules" label-width="150px" position-label="right">
            <el-form-item prop="name" label="<?= \Yii::t('plugins/wxapp', '企业名称');?>">
                <el-input v-model="editForm.name" placeholder="<?= \Yii::t('plugins/wxapp', '请输入公司名称');?>" size="small" class="fast-input"></el-input>
            </el-form-item>
            <el-form-item prop="code_type" label="<?= \Yii::t('plugins/wxapp', '代码类型');?>">
                <el-select v-model="editForm.code_type" placeholder="<?= \Yii::t('plugins/wxapp', '统一社会信用代码');?>">
                    <el-option label="<?= \Yii::t('plugins/wxapp', '企业代码');?>" value="1"></el-option>
                    <el-option label="<?= \Yii::t('plugins/wxapp', '组织机构代码');?>" value="2"></el-option>
                    <el-option label="<?= \Yii::t('plugins/wxapp', '营业执照注册号');?>" value="3"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item prop="code" label="<?= \Yii::t('plugins/wxapp', '企业代码');?>">
                <el-input v-model="editForm.code"
                          placeholder="<?= \Yii::t('plugins/wxapp', '请输入相应的企业代码');?>"
                          size="small"
                          class="fast-input"
                ></el-input>
            </el-form-item>
            <el-form-item prop="legal_persona_wechat" label="<?= \Yii::t('plugins/wxapp', '法人微信号');?>">
                <el-input v-model="editForm.legal_persona_wechat"
                          placeholder="<?= \Yii::t('plugins/wxapp', '请输入法人的微信号');?>"
                          size="small"
                          class="fast-input"></el-input>
            </el-form-item>
            <el-form-item prop="legal_persona_name" label="<?= \Yii::t('plugins/wxapp', '法人姓名');?>">
                <el-input v-model="editForm.legal_persona_name"
                          placeholder="<?= \Yii::t('plugins/wxapp', '请输入法人姓名');?>"
                          size="small"
                          class="fast-input"
                ></el-input>
            </el-form-item>
            <el-form-item prop="component_phone" label="<?= \Yii::t('plugins/wxapp', '联系电话');?>">
                <el-input v-model="editForm.component_phone"
                          placeholder="<?= \Yii::t('plugins/wxapp', '请输入联系方式');?>"
                          size="small"
                          class="fast-input"
                ></el-input>
            </el-form-item>
        </el-form>
    </el-card>
    <el-button size="small" style="margin-top: 20px" :loading="btnLoading" type="primary" @click="submit"><?= \Yii::t('plugins/wxapp', '提交');?></el-button>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                btnLoading: false,
                editForm: {
                    name: '',
                    code_type: '1',
                    code: '',
                    legal_persona_wechat: '',
                    legal_persona_name: '',
                    component_phone: '',
                },
                editFormRules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '企业名称不能为空');?>', trigger: 'blur'},
                    ],
                    code_type: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '企业类型不能为空');?>', trigger: 'blur'},
                    ],
                    code: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '企业代码不能为空');?>', trigger: 'blur'},
                    ],
                    legal_persona_wechat: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '法人微信号不能为空');?>', trigger: 'blur'},
                    ],
                    legal_persona_name: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '法人姓名不能为空');?>', trigger: 'blur'},
                    ],
                    component_phone: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '联系电话不能为空');?>', trigger: 'blur'},
                    ],
                },
            }
        },

        methods: {
            submit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign({}, this.editForm);
                        request({
                            params: {
                                r: 'plugin/wxapp/third-platform/fast-create',
                            },
                            data: para,
                            method: 'POST'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                setTimeout(function () {
                                    navigateTo({
                                        r: 'plugin/wxapp/third-platform/fast-create-list',
                                    })
                                }, 1000);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
        }
    });
</script>
