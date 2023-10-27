<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>

<style>
   
</style>
<template id="app-send-second">
    <div class="app-send-second">
        <el-alert type="success" style="margin-bottom: 10px;">订单商品重量需要大于0才可以使用聚合配送</el-alert>
        <div style="text-align: center; margin-bottom: 10px;">当前余额：￥{{balance}}</div>
        <template v-if="send_count">
            部分发货的订单不支持此配送
        </template>
        <template v-else>
            <!-- 发货 -->
            <el-form label-width="130px"
                     @submit.native.prevent="prev"
                     class="sendForm"
                     :model="sendForm"
                     v-loading="sendLoading"
                     :rules="rules"
                     ref="sendForm">
                <el-form-item label="<?= \Yii::t('components/order', '运力列表');?>" prop="delivery">
                    <el-select style="width: 400px;" v-model="sendForm.delivery" multiple placeholder="<?= \Yii::t('admin/logistics', '请选择');?>">
                        <el-option
                                v-for="item in delivery_list"
                                :key="item.deliveryCode"
                                :label="item.deliveryChannelName"
                                :value="item.deliveryCode">
                        </el-option>
                    </el-select>
                    <div>优先按选中的最高金额扣款，实际配送后退差价</div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/order', '商家备注');?>">
                    <el-input type="textarea" size="small" v-model="sendForm.merchant_remark"
                              autocomplete="off">
                    </el-input>
                </el-form-item>
                <el-form-item style="text-align: right">
                    <el-button size="small" @click="close"><?= \Yii::t('components/order', '取消');?></el-button>
                    <el-button size="small" type="primary" :loading="sendLoading"
                               @click="sendSubmit('sendForm')">
                        <?= \Yii::t('components/order', '确定');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </template>
    </div>
</template>

<script>
    Vue.component('app-send-second', {
        template: '#app-send-second',
        props: {
            order: {
                type: Object,
                default: function () {
                    return {}
                }
            },
            active: String,
        },
        watch: {
            active: {
                handler(v) {
                    if (v === 'second') {
                        this.getSetting();
                    }
                },
                deep: true
            },
        },
        data() {
            return {
                sendForm: {
                    estimate_price: 0,
                },
                delivery_list: [],
                send_count: 0,
                balance: 0,
                sendLoading: false,
                rules: {
                    merchant_remark: [
                        {required: true, message: "<?= \Yii::t('components/order', '商家留言不能为空');?>", trigger: 'change'},
                    ],
                },
            }
        },
        methods: {
            close(){
                this.$emit('close')
            },
            getSetting() {
                let self = this;
                self.delivery_list = [];
                self.sendLoading = true;
                request({
                    params: {
                        r: 'mall/wlhulian/send-data',
                        id: this.order.id
                    },
                }).then(e => {
                    self.sendLoading = false;
                    if (e.data.code == 0) {
                        self.delivery_list = e.data.data.setting;
                        self.send_count = e.data.data.sendCount;
                        self.balance = e.data.data.balance;
                        self.sendForm = {
                            estimate_price: 0,
                        };
                    } else {
                        self.balance = e.data.data.balance || 0;
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            sendSubmit(formName) {
                this.$refs[formName].validate((valid,mes) => {
                    if (valid) {
                        this.sendLoading = true;
                        request({
                            params: {
                                r: 'mall/wlhulian/send-order',
                            },
                            method: 'post',
                            data: Object.assign(this.sendForm, {order_id: this.order.id}),
                        }).then(e => {
                            this.sendLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                location.reload();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                        //test
                        this.$message.error(Object.values(mes).shift().shift().message);
                    }
                });
            },
        },
        created() {},
    })
</script>