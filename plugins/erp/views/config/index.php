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
</style>
<div id="app" v-cloak>
    <el-card v-loading="cardLoading" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('plugins/erp', '聚水潭erp设置');?></span>
            </div>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="172px" size="small">
            <el-row>
                <el-col :span="24">
                    <div class="title">
                        <span><?= \Yii::t('plugins/erp', '基本设置');?></span>
                    </div>
                    <div class="form-body">
                        <el-form-item label="erp<?= \Yii::t('plugins/erp', '状态');?>">
                            <el-switch
                                    v-model="ruleForm.status"
                                    active-value="1"
                                    inactive-value="0"
                                    active-color="#409EFF">
                            </el-switch>
                        </el-form-item>
                        <template v-if="ruleForm.status == 1">
                            <el-form-item label="<?= \Yii::t('plugins/erp', '对接环境');?>" prop="env">
                                <el-radio v-model="ruleForm.env" label="prod"><?= \Yii::t('plugins/erp', '正式环境');?>
                                </el-radio>
                                <el-radio v-model="ruleForm.env" label="dev"><?= \Yii::t('plugins/erp', '测试环境');?>
                                </el-radio>
                                <div style="color: #CCCCCC;cursor: pointer;">
                                    <span @click="$navigate('https://openweb.jushuitan.com/doc?docId=110', 1)">
                                        <?= \Yii::t('plugins/erp', '点击查看测试环境说明');?>
                                    </span>
                                </div>
                            </el-form-item>
                            <el-form-item label="APP Key" prop="app_key">
                                <el-input v-model="ruleForm.app_key" type="text" placeholder="<?= \Yii::t('plugins/erp', '请输入');?>APP Key"></el-input>
                            </el-form-item>
                            <el-form-item label="APP Secret" prop="app_secret">
                                <el-input v-model="ruleForm.app_secret" type="text" placeholder="<?= \Yii::t('plugins/erp', '请输入');?>APP Secret"></el-input>
                            </el-form-item>
                            <el-form-item label="token" prop="access_token">
                                <div style="display: flex;justify-content: space-between">
                                    <el-input v-model="ruleForm.access_token" type="text" placeholder="<?= \Yii::t('plugins/erp', '请输入');?>access_token"></el-input>
                                    <el-button size="small" @click="auth" style="margin-left: 10px"><?= \Yii::t('plugins/erp', '获取');?>token</el-button>
                                </div>
                            </el-form-item>
                        </template>
                    </div>
                </el-col>
                <el-col :span="24" v-if="ruleForm.status == 1">
                    <div class="title">
                        <span><?= \Yii::t('plugins/erp', '店铺设置');?></span>
                    </div>
                    <div class="form-body">
                        <div style="color: rgb(255, 69, 68); padding: 0px 0px 20px 50px" v-if="shopErr"><?= \Yii::t('plugins/erp', '店铺错误信息');?></div>
                        <template v-else>
                            <el-form-item label="<?= \Yii::t('plugins/erp', '设置店铺');?>" prop="shop_id">
                                <el-tag
                                        style="margin-right: 5px;margin-bottom: 5px;"
                                        v-if="shop && shop.shop_name">
                                    {{shop.shop_name}}
                                </el-tag>
                                <el-autocomplete v-model="ruleForm.shop_name" value-key="shop_name"
                                                 :fetch-suggestions="querySearchAsync" placeholder="<?= \Yii::t('plugins/erp', '请选择');?>"
                                                 @select="shareClick" style="display: block;"></el-autocomplete>
                                <div style="color:#909399"><?= \Yii::t('plugins/erp', '店铺选择说明');?></div>
                            </el-form-item>
                        </template>
                    </div>
                </el-col>
            </el-row>
        </el-form>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('plugins/erp', '保存');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {},
                shop: [],
                shopList: [],
                shopErr: '',
                rules: {
                    app_key: [
                        {required: true, message: '<?= \Yii::t('plugins/erp', '请输入');?>APP Key', trigger: 'blur'},
                    ],
                    app_secret: [
                        {required: true, message: '<?= \Yii::t('plugins/erp', '请输入');?>APP Secret', trigger: 'blur'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            querySearchAsync(queryString, cb) {
                request({
                    params: {
                        r: 'plugin/erp/mall/config/shop',
                        keyword: queryString
                    }
                }).then(response => {
                    if (response.data.code == 0) {
                        cb(response.data.data)
                    } else {
                        this.$message.error(response.data.msg);
                    }
                });
            },
            shareClick(row) {
                this.ruleForm.shop_id = row.shop_id
            },
            // 授权弹窗
            auth() {
                request({
                    params: {
                        r: 'plugin/erp/mall/config/auth'
                    },
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code == 0) {
                        window.open(e.data.data.authUrl);
                        this.$confirm('<?= \Yii::t('plugins/erp', '新窗口授权');?>', '<?= \Yii::t('plugins/erp', '提示');?>', {
                            confirmButtonText: '<?= \Yii::t('plugins/erp', '成功授权');?>',
                            cancelButtonText: '<?= \Yii::t('plugins/erp', '重试授权');?>',
                            type: 'warning'
                        }).then(() => {
                            this.$message({
                                type: 'success',
                                message: '<?= \Yii::t('plugins/erp', '已完成授权');?>'
                            });
                            this.getDetail();
                        }).catch(() => {
                        });
                    }else{
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            handleClick() {
                this.$refs.input.value = null;
                this.$refs.input.click();
            },
            getDetail() {
                this.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/erp/mall/config/index'
                    },
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code == 0) {
                        this.ruleForm = e.data.data.list;
                        this.shop = e.data.data.shop;
                        this.shopErr = e.data.data.shopErr;
                    }
                }).catch(e => {
                });
            },
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/erp/mall/config/index'
                            },
                            method: 'post',
                            data: self.ruleForm
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'plugin/erp/mall/config/index',
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
