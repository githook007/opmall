<?php
/**
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/9 14:52
 */
?>
<style>
    .form-body {
        display: flex;
        justify-content: center;
        position: relative;
    }

    .form-body .el-form {
        width: 650px;
        margin-top: 10px;
    }

    .submit-btn {
        height: 32px;
        width: 65px;
        line-height: 32px;
        text-align: center;
        border-radius: 16px;
        padding: 0;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }

    .wechat-image {
        height: 232px;
        width: 200px;
        cursor: pointer;
        position: relative;
    }

    .wechat-end-box {
        height: 32px;
        line-height: 32px;
        width: 200px;
        padding: 0 12px;
        color: #606266;
        border-left: 1px solid #e2e2e2;
        border-right: 1px solid #e2e2e2;
        border-bottom: 1px solid #e2e2e2;
        word-break: break-all;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">

        <div class="form-body">
            <el-form :model="ruleForm" ref="ruleForm" :rules="rules" label-width="150px" position-label="right">
                <template v-if="activeName == 'first'">


                    <template>
                        <div style="margin-bottom: 20px;height: 20px;">
                            <span style="position: absolute;left: 10px"><?= \Yii::t('admin/setting', '通联支付配置');?></span>
                        </div>
                        <el-form-item label="<?= \Yii::t('admin/setting', '通联支付商户号');?>" prop="orgid">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.orgid"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('admin/setting', '通联支付appid');?>" prop="tl_appid">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.tl_appid"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('admin/setting', '通联支付私钥');?>" prop="tl_rsaPrivateKey">
                            <el-input class="out-max" size="small" v-model.trim="ruleForm.tl_rsaPrivateKey">
                            </el-input>
                        </el-form-item>
                    </template>

                </template>



                <el-form-item>
                    <el-button class="submit-btn" type="primary" @click="submit('ruleForm')" :loading="submitLoading"><?= \Yii::t('admin/setting', '保存');?></el-button>
                </el-form-item>
            </el-form>
        </div>

    </el-card>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                activeName: 'first',
                ruleForm: {
                    orgid: '',
                    tl_appid: '',
                    tl_rsaPrivateKey: '',
                },
                rules: {
                    orgid: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请填写通联支付商户号');?>',},
                    ],
                    tl_appid: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请填写通联支付appid');?>',},
                    ],
                    tl_rsaPrivateKey: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请填写通联支付私钥');?>',},
                    ],
                },
                hidden: {
                    wechat_key: true,
                    wechat_cert_pem: true,
                    wechat_key_pem: true,
                    alipay_public_key: true,
                    alipay_private_key: true,
                    alipay_appcert: true,
                    alipay_rootcert: true,
                },
                submitLoading: false,

                wechatVisible: false,
                wechatForm: {
                    name: '',
                    mobile: '',
                    start_time: '',
                    end_time: '',
                    is_all_day: false,
                    wechat_name: '',
                    qrcode_url: '',
                },

            };
        },
        computed: {
        },
        created() {
            this.getSetting();
        },
        methods: {
            handleClick(tab, event) {
                console.log(tab, event);
            },
            submit(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.submitLoading = true;
                        request({
                            params: {
                                r: 'admin/setting/communication'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.submitLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.submitLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getSetting() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/communication',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data.setting;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            isShow(name) {
                let sign = false;
                this.ruleForm.pay_list.forEach(function(item) {
                    if (item == name) {
                        sign = true;
                    }
                })

                return sign;
            },
            editWechat(item, index) {
                this.index = index;
                this.wechatForm = Object.assign({}, item);
                this.wechatVisible = true;
            },
            picClose(index) {
                this.ruleForm.customer_service_list.splice(index, 1);
            },
            wechatSelect(e) {
                if (e.length) {
                    this.wechatForm.qrcode_url = e[0].url;
                }
            },

            wechatClose() {
                this.wechatForm.qrcode_url = '';
            },
            wechatSubmit() {
                if (!this.ruleForm.customer_service_list) {
                    this.ruleForm.customer_service_list = [];
                }
                this.$refs.wechatForm.validate((valid) => {
                    if (valid) {
                        if (this.index === -1) {
                            this.ruleForm.customer_service_list.push(Object.assign({}, this.wechatForm));
                        } else {
                            this.ruleForm.customer_service_list.splice(this.index, 1, this.wechatForm);
                        }
                        this.wechatVisible = false;
                    }
                });
            },
            addWechat() {
                this.index = -1;
                this.wechatForm = {
                    name: '',
                    mobile: '',
                    start_time: '',
                    end_time: '',
                    is_all_day: false,
                    wechat_name: '',
                    qrcode_url: '',
                };
                this.wechatVisible = true
            },
        },
    });
</script>