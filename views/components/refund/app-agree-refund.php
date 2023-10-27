<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('order/app-send');
?>

<style>
    .app-agree-refund .refund-price {
        width: 100px;
        margin: 0 5px;
        font-size: 16px;
    }

    .app-agree-refund .span-label {
        display: inline-block;
        height: 32px;
        line-height: 32px;
    }

    .app-agree-refund .get-print {
        margin: 0;
    }
</style>

<template id="app-agree-refund">
    <div class="app-agree-refund">
        <!-- 选择地址 -->
        <el-dialog :visible.sync="dialogVisible" width="70%" @close="closeDialog">
            <el-table v-loading="submitLoading" :data="address" v-loading="listLoading">
                <el-table-column align="center" property="name" label="<?= \Yii::t('components/refund', '收件人');?>" width="150"></el-table-column>
                <el-table-column align="center" property="mobile" label="<?= \Yii::t('components/refund', '电话');?>" width="200"></el-table-column>
                <el-table-column align="center" property="address" label="<?= \Yii::t('components/refund', '地址');?>"></el-table-column>
                <el-table-column align="center" property="remark" label="<?= \Yii::t('components/refund', '备注');?>"></el-table-column>
                <el-table-column align="center" label="<?= \Yii::t('components/refund', '操作');?>" width="100">
                    <template slot-scope="scope">
                        <el-button size="mini" type="primary" @click="chooseAddress(scope.row)"><?= \Yii::t('components/refund', '选择');?></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-dialog>

        <!-- 售后退货退款 确认收货 -->
        <el-dialog :title="refundOrder.type == 3 ? '<?= \Yii::t('components/refund', '确认退款');?>' : '<?= \Yii::t('components/refund', '确认已收到货');?>'" :visible.sync="refundConfirmVisible" width="20%" @close="closeDialog">
            <div style="text-align: center;font-size: 16px;" flex="main:center cross:center">
                <i style="color: #E6A23C;margin-right: 10px;font-size: 24px" class="el-icon-warning"></i>
                <span class="span-label">{{refundOrder.type == 3 ? "<?= \Yii::t('components/refund', '确认退款后');?>" : "<?= \Yii::t('components/refund', '确认收货后');?>"}},<?= \Yii::t('components/refund', '退款金额');?></span>
                <el-input size="small" class="refund-price" type="number" v-model="para.refund_price"></el-input>
                <span class="span-label"><?= \Yii::t('components/refund', '元将直接返还给用户');?>！</span>
            </div>
            <span slot="footer" class="dialog-footer">
                    <el-button size="mini" @click="refundConfirmVisible = false"><?= \Yii::t('components/refund', '取消');?></el-button>
                    <el-button size="mini" type="primary" :loading="submitLoading" @click="refundOver"><?= \Yii::t('components/refund', '确定');?></el-button>
                </span>
        </el-dialog>

        <!--售后换货 确认收货并发货 -->
        <app-send
                @close="dialogClose"
                @submit="dialogSubmit"
                :is-show="sendVisible"
                :send-type="sendType"
                :is-refund="true"
                :is-show-print="false"
                :order="refundOrder">
        </app-send>
    </div>
</template>

<script>
    Vue.component('app-agree-refund', {
        template: '#app-agree-refund',
        props: {
            isShow: {
                type: Boolean,
                default: false,
            },
            isShowConfirm: {
                type: Boolean,
                default: false,
            },
            refundOrder: {
                type: Object,
                default: function () {
                    return {}
                }
            },
            url: {
                type: String,
                default: 'mall/order/refund-handle'
            },
            // 此数据可在组件内部ajax获取
            address: {
                type: Array,
                default: function () {
                    return [];
                }
            }
        },
        watch: {
            isShow: function (newVal) {
                if (newVal) {
                    this.openDialog()
                }
            },
            isShowConfirm: function (newVal) {
                if (newVal) {
                    this.check()
                } else {
                    this.sendVisible = false;
                }
            },
        },
        data() {
            return {
                dialogVisible: false,
                refundConfirmVisible: false,
                merchant_remark: '',
                title: '',
                content: "<?= \Yii::t('components/refund', '填写拒绝理由');?>",
                submitLoading: false,
                listLoading: false,
                para: {},
                sendVisible: false,
                sendType: '',
            }
        },
        methods: {
            // 打开备注
            openDialog(e) {
                if (this.refundOrder.type == 3) {
                    this.agreeRefund(this.refundOrder);
                } else if (this.refundOrder.order.send_type == 1 || this.refundOrder.order.send_type == 2) {
                    this.agreeCityRefund(this.refundOrder);
                }else {
                    if (this.address.length > 0) {
                        this.dialogVisible = true;
                    } else {
                        this.$confirm("<?= \Yii::t('components/refund', '暂无退货地址');?>", "<?= \Yii::t('components/refund', '提示');?>", {
                            confirmButtonText: "<?= \Yii::t('components/refund', '确定');?>",
                            cancelButtonText: "<?= \Yii::t('components/refund', '取消');?>",
                            type: 'warning'
                        }).then(() => {
                            this.$navigate({
                                r: 'mall/refund-address/edit',
                            });
                        }).catch(() => {
                            this.closeDialog();
                        });
                    }
                }
            },
            closeDialog() {
                this.$emit('close')
            },
            // 选择地址
            chooseAddress(row) {
                let para = {
                    order_refund_id: this.refundOrder.id,
                    type: this.refundOrder.type,
                    is_agree: 1,
                    refund: 1,
                    refund_price: this.refundOrder.refund_price,
                    address_id: row.id
                };
                this.submitLoading = true;
                request({
                    params: {
                        r: this.url,
                    },
                    data: para,
                    method: 'post'
                }).then(e => {
                    this.dialogVisible = false;
                    this.submitLoading = false;
                    if (e.data.code === 0) {
                        this.$emit('submit');
                        this.closeDialog();
                        this.$message({
                            message: e.data.msg,
                            type: 'success'
                        });
                    } else {
                        this.$message({
                            message: e.data.msg,
                            type: 'warning'
                        });
                    }
                }).catch(e => {
                });
            },
            // 确认收货
            check() {
                if (this.refundOrder.type == 1 || this.refundOrder.type == 3) {
                    this.para = {
                        order_refund_id: this.refundOrder.id,
                        type: this.refundOrder.type,
                        is_agree: '1',
                        refund: '2',
                        refund_price: this.refundOrder.refund_price
                    };
                    this.refundConfirmVisible = true;
                } else if (this.refundOrder.type == 2) {
                    this.sendVisible = true;
                } else {
                    this.$message.error("<?= \Yii::t('components/refund', '未知操作');?>")
                    return;
                }
            },
            refundOver() {
                if (this.para.refund_price >= 0) {
                    this.submitLoading = true;
                    let para = this.para;
                    request({
                        params: {
                            r: 'mall/order/refund-handle',
                        },
                        data: para,
                        method: 'post'
                    }).then(e => {
                        this.submitLoading = false;
                        if (e.data.code === 0) {
                            this.refundConfirmVisible = false;
                            this.$emit('submit');
                            this.closeDialog();
                            this.$message({
                                message: e.data.msg,
                                type: 'success'
                            });
                        } else {
                            this.$message({
                                message: e.data.msg,
                                type: 'warning'
                            });
                        }
                    }).catch(e => {
                    });
                } else {
                    this.$message({
                        message: "<?= \Yii::t('components/refund', '退款金额不能小于0');?>",
                        type: 'warning'
                    });
                }
            },
            dialogClose() {
                this.sendVisible = false;
                this.closeDialog();
            },
            dialogSubmit() {
                this.sendVisible = false;
                this.$emit('submit');
            },
            // 售后 仅退款
            agreeRefund(orderRefund) {
                // 退款的确认收货
                this.$confirm("<?= \Yii::t('components/refund', '是否同意用户仅退款');?>", "<?= \Yii::t('components/refund', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('components/refund', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('components/refund', '取消');?>",
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/order/refund-handle',
                        },
                        data: {
                            order_refund_id: orderRefund.id,
                            type: orderRefund.type,
                            is_agree: 1,
                            refund: 1,
                            refund_price: orderRefund.refund_price,
                        },
                        method: 'post'
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.$emit('submit');
                            this.closeDialog();
                            this.$message({
                                message: e.data.msg,
                                type: 'success'
                            });
                        } else {
                            this.closeDialog();
                            this.$message({
                                message: e.data.msg,
                                type: 'warning'
                            });
                        }
                    }).catch(e => {
                    });
                }).catch(() => {
                    this.closeDialog();
                });
            },
            // 同城配送退货退款
            agreeCityRefund(orderRefund) {
                let text = orderRefund.order.send_type == 1 ? "<?= \Yii::t('components/refund', '该订单为到店自提订单');?>" : "<?= \Yii::t('components/refund', '该订单为同城配送订单');?>"
                this.$confirm(text, "<?= \Yii::t('components/refund', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('components/refund', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('components/refund', '取消');?>",
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/order/refund-handle',
                        },
                        data: {
                            order_refund_id: orderRefund.id,
                            type: orderRefund.type,
                            is_agree: 1,
                            refund: 1,
                            refund_price: orderRefund.refund_price,
                        },
                        method: 'post'
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.$emit('submit');
                            this.closeDialog();
                            this.$message({
                                message: e.data.msg,
                                type: 'success'
                            });
                        } else {
                            this.$message({
                                message: e.data.msg,
                                type: 'warning'
                            });
                        }
                    }).catch(e => {
                    });
                }).catch(() => {
                    this.closeDialog();
                });
            }
        }
    })
</script>