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

    .form-body .el-form {
        margin-top: 10px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading">
        <div class="form-body">
            <el-alert :closable="false" type="success">门店只能添加，暂时不能更新，请确保数据正确再提交</el-alert>
            <el-form @submit.native.prevent :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px">
                <el-form-item label="<?= \Yii::t('admin/logistics', '门店');?>" prop="storeId">
                    <el-input size="small"
                              type="text"
                              disabled
                              v-model="ruleForm.storeId">
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '联系人姓名');?>" prop="contactName">
                    <el-input size="small"
                              type="text"
                              :disabled="storeId"
                              v-model="ruleForm.contactName">
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '联系人电话');?>" prop="contactPhone">
                    <el-input size="small"
                              type="text"
                              :disabled="storeId"
                              v-model="ruleForm.contactPhone">
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '门店名称');?>" prop="shopName">
                    <el-input size="small"
                              type="text"
                              :disabled="storeId"
                              v-model="ruleForm.shopName">
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '行业类型');?>" prop="industryType">
                    <el-select size="small" :disabled="storeId" clearable v-model="ruleForm.industryType" placeholder="<?= \Yii::t('admin/logistics', '请选择');?>">
                        <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '运力集合');?>" prop="deliverySupplierList">
                    <el-checkbox-group v-model="ruleForm.deliverySupplierList" :disabled="storeId">
                        <el-checkbox v-for="item in delivery_supplier" :label="item.deliveryCode">{{item.deliveryChannelName}}</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>

                <el-form-item label="位置信息">
                    <el-form-item label="门店地址" label-width="80px">
                        <el-input v-model="ruleForm.shopAddress" :disabled="storeId"></el-input>
                    </el-form-item>
                    <el-form-item label-width="80px" label="经度">
                        <el-input v-model="ruleForm.longitude" :disabled="storeId"></el-input>
                    </el-form-item>
                    <el-form-item label-width="80px" label="纬度">
                        <el-input v-model="ruleForm.latitude" :disabled="storeId"></el-input>
                    </el-form-item>
                    <el-form-item label="地图"  label-width="80px">
                        <div flex="dir:left">
                            <app-map @map-submit="mapEvent"
                                     :address="ruleForm.shopAddress"
                                     :lat="ruleForm.latitude"
                                     :long="ruleForm.longitude">
                                <el-button size="small" v-if="!storeId">展开地图</el-button>
                            </app-map>
                        </div>
                    </el-form-item>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '门牌号');?>" prop="shopAddressDetail">
                    <el-input size="small"
                              :disabled="storeId"
                              type="text"
                              v-model="ruleForm.shopAddressDetail">
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
    <el-button class="button-item" type="primary" :loading="loading" @click="submit"><?= \Yii::t('admin/logistics', '保存');?></el-button>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                ruleForm: {},
                rules: {},
                options: [],
                delivery_supplier: [],
                storeId: false,
            };
        },
        created() {
            this.getSetting();
        },
        methods: {
            //地图确定事件
            mapEvent(e) {
                let self = this;
                self.ruleForm.longitude = e.long;
                self.ruleForm.latitude = e.lat;
                self.ruleForm.shopAddress = e.address;
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            submit() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/logistics/store',
                    },
                    method: 'post',
                    data: {
                        form: this.ruleForm,
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                        this.getSetting();
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
                        r: 'admin/logistics/store',
                    },
                    method: 'get',
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data.store;
                        this.options = e.data.data.options;
                        this.delivery_supplier = e.data.data.delivery_supplier;
                        if(this.ruleForm.storeId){
                            this.storeId = true;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            }
        },
    });
</script>
