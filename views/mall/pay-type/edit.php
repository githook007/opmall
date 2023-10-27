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

    .out-max {
        width: 500px;
    }

    .out-max > .el-card__header {
        padding: 0 15px;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item>
                <span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'mall/pay-type/index'})">
                    <?= \Yii::t('mall/pay_type', 'pay_type');?>
                </span>
            </el-breadcrumb-item>
            <el-breadcrumb-item v-if="id"><?= \Yii::t('mall/pay_type', 'edit_pay_type');?></el-breadcrumb-item>
            <el-breadcrumb-item v-else><?= \Yii::t('mall/pay_type', 'add_pay_type');?></el-breadcrumb-item>
        </el-breadcrumb>
    </div>
    <el-card v-loading="listLoading" shadow="never" style="background: #FFFFFF" body-style="background-color: #ffffff;">
        <el-form :model="editForm" ref="editForm" :rules="editFormRules" label-width="150px" position-label="right">
            <el-form-item prop="name" label="<?= \Yii::t('mall/pay_type', 'pay_name');?>">
                <el-input class="out-max" size="small" v-model="editForm.name"></el-input>
            </el-form-item>
            <el-form-item prop="type" label="<?= \Yii::t('mall/pay_type', 'pay_choose');?>">
                <el-radio-group v-model="editForm.type" @change="changeType">
                    <el-radio :label="1"><?= \Yii::t('mall/pay_type', 'weixin');?></el-radio>
<!--                    <el-radio :label="2">--><?//= \Yii::t('mall/pay_type', 'alipay');?><!--</el-radio>-->
                </el-radio-group>
            </el-form-item>
            <template v-if="editForm.type == 1">
                <el-form-item prop="channel" label="<?= \Yii::t('mall/pay_type', 'pay_channel');?>"> <!-- @czs -->
                    <el-radio-group v-model="editForm.channel" @change="changeType">
                        <el-radio :label="1"><?= \Yii::t('mall/pay_type', 'official');?></el-radio>
                        <el-radio :label="5"><?= \Yii::t('mall/pay_type', 'allinpay');?></el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item prop="is_service" label="<?= \Yii::t('mall/pay_type', 'pay_type_select');?>">
                    <el-radio-group v-model="editForm.is_service" @change="changeType">
                        <el-radio :label="0"><?= \Yii::t('mall/pay_type', 'general_merchant');?></el-radio>
                        <el-radio :label="1" v-if="editForm.channel == 1"><?= \Yii::t('mall/pay_type', 'service_provider_merchant');?></el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'wx_appId');?>" prop="appid">
                    <el-input class="out-max" size="small" v-model.trim="editForm.appid"></el-input>
                </el-form-item>
                <template v-if="editForm.channel == 5">
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'tl_mchId');?>" prop="tl_merchantId">
                        <el-input class="out-max" size="small" v-model.trim="editForm.tl_merchantId"></el-input>
                    </el-form-item>
                </template>
                <template v-if="editForm.is_service == 0">
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'wx_mchId');?>" prop="mchid">
                        <el-input class="out-max" size="small" v-model.trim="editForm.mchid"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'wx_key');?>" prop="key">
                        <el-input @focus="hidden.key = false"
                                  class="out-max" size="small"
                                  v-if="hidden.key"
                                  readonly
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" v-model.trim="editForm.key"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'wx_pay');?>apiclient_cert.pem" prop="cert_pem">
                        <el-input @focus="hidden.cert_pem = false"
                                  class="out-max" size="small"
                                  v-if="hidden.cert_pem"
                                  readonly
                                  type="textarea"
                                  :rows="5"
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                  v-model="editForm.cert_pem"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'wx_pay');?>apiclient_key.pem" prop="key_pem">
                        <el-input @focus="hidden.key_pem = false"
                                  class="out-max" size="small"
                                  v-if="hidden.key_pem"
                                  readonly
                                  type="textarea"
                                  :rows="5"
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                  v-model="editForm.key_pem"></el-input>
                    </el-form-item>
                </template>
                <template v-if="editForm.is_service == 1">
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2951');?>" prop="mchid">
                        <el-input class="out-max" size="small" v-model.trim="editForm.mchid"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2952');?>" prop="service_appid">
                        <el-input class="out-max" size="small" v-model.trim="editForm.service_appid"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2953');?>" prop="service_mchid">
                        <el-input class="out-max" size="small" v-model.trim="editForm.service_mchid"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2954');?>" prop="service_key">
                        <el-input @focus="hidden.service_key = false"
                                  class="out-max" size="small"
                                  v-if="hidden.service_key"
                                  readonly
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" v-model.trim="editForm.service_key"></el-input>
                    </el-form-item>

                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2955');?>apiclient_cert.pem">
                        <app-upload @complete="updateSuccess" accept="" :params="service_cert_pem"
                                    :simple="true" style="display: inline-block">
                            <el-button size="small"><?= \Yii::t('mall/pay_type', 'a2956');?></el-button>
                        </app-upload>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2955');?>apiclient_key.pem">
                        <app-upload @complete="updateSuccess" accept="" :params="service_key_pem"
                                    :simple="true" style="display: inline-block">
                            <el-button size="small"><?= \Yii::t('mall/pay_type', 'a2956');?></el-button>
                        </app-upload>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2957');?>" prop="key">
                        <el-input @focus="hidden.key = false"
                                  class="out-max" size="small"
                                  v-if="hidden.key"
                                  readonly
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" v-model.trim="editForm.key"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2958');?>apiclient_cert.pem" prop="cert_pem">
                        <el-input @focus="hidden.cert_pem = false"
                                  class="out-max" size="small"
                                  v-if="hidden.cert_pem"
                                  readonly
                                  type="textarea"
                                  :rows="5"
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                  v-model="editForm.cert_pem"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2958');?>apiclient_key.pem" prop="key_pem">
                        <el-input @focus="hidden.key_pem = false"
                                  class="out-max" size="small"
                                  v-if="hidden.key_pem"
                                  readonly
                                  type="textarea"
                                  :rows="5"
                                  placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                        </el-input>
                        <el-input v-else class="out-max" size="small" type="textarea" :rows="5"
                                  v-model="editForm.key_pem"></el-input>
                    </el-form-item>
                </template>
                <el-form-item prop="is_v3" label="<?= \Yii::t('mall/pay_type', 'is_v3');?>">
                    <el-radio-group v-model="editForm.is_v3">
                        <el-radio :label="1"><?= \Yii::t('mall/pay_type', 'no');?></el-radio>
                        <el-radio :label="2"><?= \Yii::t('mall/pay_type', 'yes');?></el-radio>
                    </el-radio-group>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'payment_V3_key');?>" prop="v3key" v-if="editForm.is_v3 == 2">
                    <el-input @focus="hidden.v3key = false"
                              class="out-max" size="small"
                              v-if="hidden.v3key"
                              readonly
                              placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                    </el-input>
                    <el-input v-else class="out-max" size="small" v-model.trim="editForm.v3key"></el-input>
                </el-form-item>
            </template>
            <template v-if="editForm.type == 2">
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2959');?>" prop="alipay_appid">
                    <el-input class="out-max" size="small" v-model="editForm.alipay_appid"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2960');?>" prop="alipay_public_key">
                    <el-input @focus="hidden.alipay_public_key = false"
                              class="out-max" size="small"
                              v-if="hidden.alipay_public_key"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                    </el-input>
                    <el-input v-else v-model="editForm.alipay_public_key" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2961');?>" prop="app_private_key">
                    <el-input @focus="hidden.app_private_key = false"
                              class="out-max" size="small"
                              v-if="hidden.app_private_key"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                    </el-input>
                    <el-input v-else v-model="editForm.app_private_key" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2962');?>" prop="appcert">
                    <el-input @focus="hidden.appcert = false"
                              class="out-max" size="small"
                              v-if="hidden.appcert"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                    </el-input>
                    <el-input v-else v-model="editForm.appcert" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/pay_type', 'a2963');?>" prop="alipay_rootcert">
                    <el-input @focus="hidden.alipay_rootcert = false"
                              class="out-max" size="small"
                              v-if="hidden.alipay_rootcert"
                              readonly
                              type="textarea"
                              :rows="5"
                              placeholder="<?= \Yii::t('mall/pay_type', 'hidden_content');?>">
                    </el-input>
                    <el-input v-else v-model="editForm.alipay_rootcert" type="textarea" rows="5"
                              class="key-textarea out-max" size="small"></el-input>
                </el-form-item>
            </template>
        </el-form>
    </el-card>
    <el-button size="small" style="margin-top: 20px" :loading="btnLoading" type="primary" @click="submit"><?= \Yii::t('mall/pay_type', 'a2964');?></el-button>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                service_key_pem: {
                    r: 'mall/pay-type/upload-pem',
                    type: 'key',
                    id: getQuery('id'),
                },
                service_cert_pem: {
                    r: 'mall/pay-type/upload-pem',
                    type: 'cert',
                    id: getQuery('id'),
                },
                id: getQuery('id'),
                btnLoading: false,
                listLoading: false,
                hidden: {
                    alipay_public_key: true,
                    app_private_key: true,
                    appcert: true,
                    alipay_rootcert: true,
                    service_key_pem: true,
                    service_cert_pem: true,
                    service_key: true,
                    key: true,
                    cert_pem: true,
                    key_pem: true,
                    v3key: true,
                    alipay_private_key: true,
                },
                editForm: {
                    alipay_appid: '',//支付宝APPID
                    alipay_public_key: '', //支付宝公钥
                    app_private_key: '',//应用私钥
                    appcert: '', //应用公钥证书
                    alipay_rootcert: '', //支付宝根证书
                    service_key_pem: '', //微信支付服务商apiclient_key
                    service_cert_pem: '', //微信支付服务商apiclient_cert
                    service_key: '', //微信支付服务商Api密钥
                    service_mchid: '', //服务商商户号
                    service_appid: '', //服务商AppId
                    appid: '', //微信APPID
                    mchid: '', //微信支付商户号 ////特约商户商户号
                    key: '', //微信支付Api密钥
                    cert_pem: '', //微信支付apiclient_cert
                    key_pem: '', //微信支付apiclient_key
                    v3key: '', //微信支付v3密钥
                    is_service: 0, //支付类型选择
                    type: 1, //支付方式选择
                    name: '', //支付名称
                    is_v3: 1, //v3提现
                    channel: 1, //支付渠道选择
                    currency: 1, //结算货币
                },
                editFormRules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2965');?>', trigger: 'change'},
                    ],
                    type: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2966');?>', trigger: 'change'},
                    ],
                    is_service: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2967');?>', trigger: 'change'},
                    ],
                    key: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2968');?>', trigger: 'change'},
                    ],
                    appid: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2969');?>', trigger: 'change'},
                    ],
                    mchid: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'mchId_input');?>', trigger: 'change'},
                    ],
                    tl_merchantId: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'tl_mchId_input');?>', trigger: 'change'},
                    ],
                    service_appid: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2971');?>', trigger: 'change'},
                    ],
                    service_mchid: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2972');?>', trigger: 'change'},
                    ],
                    app_private_key: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2973');?>', trigger: 'change'},
                    ],
                    alipay_public_key: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2974');?>', trigger: 'change'},
                    ],
                    alipay_appid: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2975');?>', trigger: 'change'},
                    ],
                    appcert: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2976');?>', trigger: 'change'},
                    ],
                    alipay_rootcert: [
                        {required: true, message: '<?= \Yii::t('mall/pay_type', 'a2977');?>', trigger: 'change'},
                    ],
                },
            }
        },

        methods: {
            updateSuccess(e) {
                if (e[0].response.data.code == 0) {
                    this.$message.success('<?= \Yii::t('mall/pay_type', 'a2978');?>')
                }
            },
            changeType() {
                //clear validate
                this.$refs.editForm.clearValidate();
                if(this.editForm.channel !== 1) {
                    this.editForm.is_service = 0;
                }
            },
            submit() {
                this.$refs.editForm.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign({}, this.editForm);
                        request({
                            params: {
                                r: 'mall/pay-type/edit',
                            },
                            data: para,
                            method: 'POST'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                setTimeout(function () {
                                    navigateTo({
                                        r: 'mall/pay-type/index',
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
            getForm() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/pay-type/edit',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.editForm = e.data.data.detail;
                        this.editForm.currency = this.editForm.currency || 1;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(() => {
                    this.listLoading = false;
                });
            },
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getForm();
            }
        }
    });
</script>
