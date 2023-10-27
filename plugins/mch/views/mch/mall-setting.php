<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .table-body {
        padding: 20px 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" v-loading="cardLoading" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('plugins/mch', '多商户设置');?></span>
            </div>
        </div>
        <div class="table-body">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="160px" size="small">
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="<?= \Yii::t('plugins/mch', '是否开启分销');?>" prop="is_share">
                            <el-radio-group v-model="ruleForm.is_share" class="ml-24">
                                <el-radio :label="1"><?= \Yii::t('plugins/mch', '开启');?></el-radio>
                                <el-radio :label="0"><?= \Yii::t('plugins/mch', '关闭');?></el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row><!-- @czs -->
                    <el-col :span="12">
                        <el-form-item label="<?= \Yii::t('plugins/mch', '是否开启优惠券');?>" prop="is_coupon">
                            <el-radio-group v-model="ruleForm.is_coupon" class="ml-24">
                                <el-radio :label="1"><?= \Yii::t('plugins/mch', '开启');?></el-radio>
                                <el-radio :label="0"><?= \Yii::t('plugins/mch', '关闭');?></el-radio>
                            </el-radio-group>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('plugins/mch', '保存');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    is_share: 0,
                    is_coupon: 0,
                },
                rules: {},
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            getDetail() {
                this.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/mch/mall/mch/mall-setting',
                        mch_id: getQuery('mch_id')
                    },
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code == 0) {
                        this.ruleForm = e.data.data.setting;
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
                                r: 'plugin/mch/mall/mch/mall-setting'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'plugin/mch/mall/mch/index',
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
