<?php

/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 17:05
 */
?>
<style>
    #app .el-checkbox {
        margin-bottom: 0;
    }

    .button-item {
        margin-top: 20px;
        padding: 9px 25px;
    }
</style>
<template id="c-price">
    <div>
        <el-card shadow="never" v-loading="cardLoading">
            <el-row>
                <el-col :span="20">
                    <el-form @submit.native.prevent :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px">
                        <el-form-item label="<?= \Yii::t('mall/logistics', '价格类型');?>" prop="price_type">
                            <div>
                                <el-radio-group v-model.number="ruleForm.price_type">
                                    <el-radio :label="1"><?= \Yii::t('mall/logistics', '固定金额');?></el-radio>
                                    <el-radio :label="2"><?= \Yii::t('mall/logistics', '百分比例');?></el-radio>
                                </el-radio-group>
                            </div>
                            <div v-if="ruleForm.price_type == 1">
                                <el-input v-model="ruleForm.price_value" type="number" placeholder="<?= \Yii::t('mall/logistics', '请输入金额');?>">
                                    <template slot="prepend"> <?= \Yii::t('mall/logistics', '派单固定增加');?></template>
                                    <template slot="append"><?= \Yii::t('mall/logistics', '元');?></template>
                                </el-input>
                            </div>
                            <div v-if="ruleForm.price_type == 2">
                                <el-input v-model="ruleForm.price_value" type="number" placeholder="<?= \Yii::t('mall/logistics', '请输入百分比');?>">
                                    <template slot="prepend"> <?= \Yii::t('mall/logistics', '每单增加派单金额的');?></template>
                                    <template slot="append"><?= \Yii::t('mall/logistics', '%');?></template>
                                </el-input>
                            </div>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
        </el-card>
        <el-button class="button-item" type="primary" :loading="loading" @click="submit"><?= \Yii::t('admin/logistics', '保存');?></el-button>
    </div>
</template>

<script>
    Vue.component('c-price', {
        template: '#c-price',
        props: {
            display: Boolean,
            id: String
        },
        data() {
            return {
                loading: false,
                ruleForm: {},
                rules: {
                    //appId: [
                    //    {required: true, message: "<?//= \Yii::t('admin/logistics', '请输入应用id');?>//",},
                    //],
                },
                cardLoading: false,
            };
        },
        watch: {
            display: {
                handler(v) {
                    if (this.display) {
                        this.getSetting();
                    }
                },
                immediate: true,
            },
        },
        methods: {
            submit() {
                this.loading = true;
                this.ruleForm.id = this.id;
                this.$request({
                    params: {
                        r: 'admin/logistics/price',
                    },
                    method: 'post',
                    data: this.ruleForm,
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                        this.$emit('close');
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            getSetting() {
                this.cardLoading = true;
                this.$request({
                    params: {
                        r: 'admin/logistics/price',
                        id: this.id
                    },
                    method: 'get',
                }).then(e => {
                    this.cardLoading = false;
                    this.ruleForm = e.data.data.setting;
                }).catch(e => {
                });
            },
        }
    });
</script>
