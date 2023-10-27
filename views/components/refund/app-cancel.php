<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>

<style>

</style>

<template id="app-cancel">
    <div class="app-cancel">
        <!-- 备注 -->
        <el-dialog :title="title" :visible.sync="dialogVisible" width="30%" @close="closeDialog">
            <el-form>
                <el-form-item :label="content">
                    <el-input type="textarea" v-model="merchant_remark" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item style="text-align: right">
                    <el-button size="small" @click="dialogVisible = false"><?= \Yii::t('components/refund', '取消');?></el-button>
                    <el-button size="small" type="primary" :loading="submitLoading" @click="toSumbit"><?= \Yii::t('components/refund', '确定');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    Vue.component('app-cancel', {
        template: '#app-cancel',
        props: {
            isShow: {
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
            }
        },
        watch: {
            isShow: function (newVal) {
                if (newVal) {
                    this.openDialog()
                }
            }
        },
        data() {
            return {
                dialogVisible: false,
                merchant_remark: '',
                title: '',
                content: "<?= \Yii::t('components/refund', '填写拒绝理由');?>",
                submitLoading: false,
            }
        },
        methods: {
            // 打开备注
            openDialog(e) {
                this.title = "<?= \Yii::t('components/refund', '拒绝售后');?>";
                this.dialogVisible = true;
            },
            closeDialog() {
                this.$emit('close')
            },
            toSumbit() {
                this.submitLoading = true;
                request({
                    params: {
                        r: this.url
                    },
                    data: {
                        order_refund_id: this.refundOrder.id,
                        type: this.refundOrder.type,
                        is_agree: 2,
                        refund_price: this.refundOrder.refund_price,
                        merchant_remark: this.merchant_remark
                    },
                    method: 'post'
                }).then(e => {
                    this.submitLoading = false;
                    if (e.data.code === 0) {
                        this.dialogVisible = false;
                        this.$message.success(e.data.msg);
                        this.$emit('submit')
                    } else {
                        this.$message.error(e.data.msg);
                    }

                }).catch(e => {
                });
            }
        }
    })
</script>