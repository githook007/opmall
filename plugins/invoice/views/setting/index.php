<?php
defined('YII_ENV') or exit('Access Denied');
?>
<style>
    .form-body {
        padding: 20px 50% 20px 20px;
        background-color: #fff;
        margin-bottom: 20px;
        min-width: 1000px
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

    .mobile-box {
        pointer-events: none !important;
        width: 400px;
        height: calc(800px - 55px);
        padding: 35px 11px;
        background-color: #fff;
        border-radius: 30px;
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        margin-right: 1rem;
    }

    .mobile-box .show-box {
        height: calc(667px - 55px);
        width: 375px;
        overflow: auto;
        font-size: 12px;
    }

    .menus-box .menu-item {
        cursor: move;
        background-color: #fff;
        margin: 5px 0;
    }

    .head-bar {
        width: 378px;
        height: 64px;
        position: relative;
        background: url('statics/img/mall/home_block/head.png') center no-repeat;
    }

    .head-bar div {
        position: absolute;
        text-align: center;
        width: 378px;
        font-size: 16px;
        font-weight: 600;
        height: 64px;
        line-height: 88px;
    }

    .head-bar img {
        width: 378px;
        height: 64px;
    }

    .el-tabs__header  class="header" {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 10px;
    }

    .bg-rule {
        color: #FFFFFF;
        font-size: 12px;
        text-align: center;
        background: rgba(0, 0, 0, 0.4);
        line-height: 24px;
        width: 46px;
        border-radius: 12px 0 0 12px;
        position: absolute;
        right: 0;
        top: 20px;
    }

    .bg-rule:nth-child(2) {
        top: 56px;
    }

    .preview-icon {
        position: absolute;
        height: 90%;
        top: 10%;
        left: 9%;
        right: 9%;
    }

    .pond-left {
        height: 26.5%;
        padding: 4% 4% 7%;
        width: 33%;
    }
    .header {
        font-size: 15px;
        font-weight: bold;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">

        <el-form :model="form" label-width="150px" ref="form" size="small" v-loading="loading"
                 :rules="FormRules">
            <div class="form-body">
                <div slot="header" class="header"><?= \Yii::t('plugins/invoice', '基本配置');?></div>
                <el-form-item class="switch" label="<?= \Yii::t('plugins/invoice', '开票服务');?>" prop="bonusSwitch">
                    <el-switch v-model="form.switch" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '税收商品编码');?>" prop="tax_code">
                    <el-input size="small" type="number" v-model="form.tax_code" autocomplete="off"></el-input><span><?= \Yii::t('plugins/invoice', '商品所属分类商品编码');?>[<a
                                href="https://gomall-cdn.opmall.com/%E7%A8%8E%E6%94%B6%E5%88%86%E7%B1%BB%E7%BC%96%E7%A0%81V39.0.xlsx"><?= \Yii::t('plugins/invoice', '税收商品编码税率表');?></a>]</span>
                </el-form-item>
                <el-form-item label="默认费率（%）" prop="tax_rate">
                    <el-input size="small" type="number" v-model="form.tax_rate" autocomplete="off"></el-input><span><?= \Yii::t('plugins/invoice', '请参照');?></span>
                </el-form-item>
                <div slot="header" class="header"><?= \Yii::t('plugins/invoice', '密钥配置');?></div>
                <el-form-item label="appkey" prop="appkey">
                    <el-input size="small" v-model="form.appkey" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="secretKey" prop="secretKey">
                    <el-input size="small" v-model="form.secretKey" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方纳税人识别号');?>" prop="seller_taxpayer_num">
                    <el-input size="small" v-model="form.seller_taxpayer_num" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '税盘号');?>" prop="terminal_code">
                    <el-input size="small" v-model="form.terminal_code" autocomplete="off"></el-input>
                </el-form-item>
                <div slot="header" class="header"><?= \Yii::t('plugins/invoice', '开票信息配置');?><span style="color: red;"><?= \Yii::t('plugins/invoice', '则默认读取商户平台销方配置');?></span></div>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方名称');?>" prop="seller_name">
                    <el-input size="small" v-model="form.seller_name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方地址');?>" prop="seller_address">
                    <el-input size="small" v-model="form.seller_address" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方电话');?>" prop="seller_tel">
                    <el-input size="small" v-model="form.seller_tel" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方银行名称');?>" prop="seller_bank_name">
                    <el-input size="small" v-model="form.seller_bank_name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '销方银行账号');?>" prop="seller_bank_account">
                    <el-input size="small" v-model="form.seller_bank_account" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/invoice', '开票人姓名');?>" prop="drawer">
                    <el-input size="small" v-model="form.drawer" autocomplete="off"></el-input>
                </el-form-item>
            </div>
            <el-button type="primary" class="button-item" size="small" :loading=btnLoading @click="onSubmit"><?= \Yii::t('plugins/invoice', '保存');?>
            </el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                prizeImg: [],
                loading: true,
                time: null,
                btnLoading: false,
                form: {
                    switch: 0,
                    tax_code: '',
                    tax_rate: 6,
                    appkey: '',
                    secretKey: '',
                    seller_taxpayer_num: '',
                    terminal_code: '',
                    seller_name: '',
                    seller_address: '',
                    seller_tel: '',
                    seller_bank_name: '',
                    seller_bank_account: '',
                    drawer: '',
                },
                FormRules: {

                },
            };
        },

        methods: {
            selectBgPic(e) {
                if(e.length){
                    this.form.bg_pic = e.shift()['url'];
                }
            },
            resetImg(type) {
                if (type === 'bg_pic') {
                    this.form.bg_pic = "<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl('pond') . '/img/pond-head.png' ?>";
                }
            },
            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;

                        let para = Object.assign(this.form);
                        request({
                            params: {
                                r: 'plugin/invoice/mall/setting/',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message({
                                    message: e.data.msg,
                                    type: 'success'
                                });
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                            this.$message.error(e.data.msg);
                        });
                    }
                });
            },

            setDefault() {
                this.resetImg('bg_pic');
                this.form.bg_color = '#f12416';
                this.form.bg_color_type = 'pure';
                this.form.bg_gradient_color = '#f12416';
            },

            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/setting',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        if (!e.data.data) {
                            return ;
                        }
                        let time = [];
                        time.unshift(e.data.data.start_at);
                        time.push(e.data.data.end_at);
                        e.data.data.time = time;
                        this.form = e.data.data;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
        },
        mounted: function () {
            this.getList();
        }
    })
</script>