<?php

/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 17:05
 */
?>
<style>
    .online-pay .el-radio__label {
        vertical-align: middle;
    }

    .online-pay .label {
        width: 75px;
        margin-left: 75px;
    }

    .online-pay .item-box {
        margin-bottom: 15px;
    }

    .online-pay .qrcode-bg {
        width: 140px;
        height: 140px;
        background-image:url('statics/img/admin/app_manage/qrcode_bg.png');
        background-size: 100% 100%;
        padding: 3px;
    }

    .online-pay .header {
        font-size: 16px;
        border-bottom: 1px solid #EBEEF5;
        padding: 5px 0 20px;
    }
</style>
<template id="recharge">
    <div>
        <el-dialog :show-close="false" class="online-pay" :visible.sync="previewOrderDialog.visible" width="725px">
            <template slot="title">
                <div flex="box:last"  class="header">
                    <div style="width: 200px;">余额充值</div>
                    <div><?= \Yii::t('mall/wlhulian', '账号');?>：{{title}}</div>
                </div>
            </template>
            <template>
                <div flex="box:first" class="item-box">
                    <div class="label"><?= \Yii::t('mall/wlhulian', '订单编号');?></div>
                    <div>{{previewOrderDialog.order_no}}</div>
                </div>
                <div flex="box:first" class="item-box">
                    <div class="label"><?= \Yii::t('mall/wlhulian', '支付金额');?></div>
                    <div style="color: #ff4544;font-size: 18px;">{{previewOrderDialog.money}}元</div>
                </div>

                <div flex="box:first" class="item-box">
                    <div class="label"></div>
                    <div flex="dir:left cross:center">
                        <div v-loading="previewOrderDialog.loading" class="qrcode-bg">
                            <img style="width: 100%;height: 100%;" :src="previewOrderDialog.code_url">
                        </div>
                        <div style="margin-left: 15px;font-size: 15px;" flex="dir:top">
                            <span style="margin-top: 5px;"><?= \Yii::t('mall/wlhulian', '扫描二维码支付');?></span>
                        </div>
                    </div>
                </div>

                <div style="text-align: right;">
                    <el-button
                            @click="hintDialogVisible = true"
                            type="primary"
                            size="small">
                        <?= \Yii::t('mall/wlhulian', '关闭');?>
                    </el-button>
                </div>
            </template>
        </el-dialog>

        <el-dialog class="hint-box" :visible.sync="hintDialogVisible" :show-close="false" width="301px">
            <template slot="title">
                <div flex="dir:top cross:center">
                    <img class="icon" src="statics/img/admin/app_manage/hint_icon.png">
                </div>
            </template>
            <div flex="dir:top cross:center">
                <div class="title"><?= \Yii::t('mall/wlhulian', '确定要关闭支付');?></div>
                <div class="content"><?= \Yii::t('mall/wlhulian', '你是否要关闭支付');?></div>
                <div class="content"><?= \Yii::t('mall/wlhulian', '别手误哦');?>~</div>
                <div style="width: 252px;margin-top: 20px;" flex="dir:left box:mean">
                    <div style="text-align: center;">
                        <el-button @click="cancelPayment"><?= \Yii::t('mall/wlhulian', '确认取消');?></el-button>
                    </div>
                    <div style="text-align: center;">
                        <el-button @click="hintDialogVisible = false" type="primary"><?= \Yii::t('mall/wlhulian', '继续支付');?></el-button>
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    Vue.component('recharge', {
        template: '#recharge',
        props: {
            previewOrderUrl: String,
            queryOrderUrl: String,
            payPrice: String,
            title: {
                type: String,
                default: '聚合配送'
            }
        },
        data() {
            return {
                hintDialogVisible: false,
                buyLoading: false,
                previewOrderDialog: {
                    visible: false,
                    loading: false,
                },
            };
        },
        watch: {
            payPrice: {
                handler(v) {
                    if (this.payPrice) {
                        this.previewOrderDialog.visible = true;
                        this.previewOrderDialog.money = this.payPrice;
                        this.previewOrderSubmit();
                    }
                },
                immediate: true,
            },
        },
        methods: {
            cancelPayment() {
                this.previewOrderDialog.visible = false;
                this.hintDialogVisible = false;
                clearInterval(this.intervalTime);
            },
            previewOrderSubmit() {
                this.buyLoading = true;
                this.previewOrderDialog.loading = true;
                this.$request({
                    method: 'post',
                    params: {
                        r: this.previewOrderUrl,
                    },
                    data: this.previewOrderDialog
                }).then(e => {
                    this.buyLoading = false;
                    if (e.data.code === 0) {
                        this.previewOrderDialog.visible = true;
                        this.previewOrderDialog.loading = false;
                        this.previewOrderDialog.code_url = e.data.data.code_url;
                        this.previewOrderDialog.order_no = e.data.data.order_no;
                        this.queryOrder(e.data.data.order_no);
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('mall/wlhulian', '提示');?>', {
                            type: 'error'
                        });
                    }
                }).catch(e => {
                });
            },
            queryOrder(orderNo) {
                let self = this;
                self.intervalTime = setInterval(function() {
                    self.$request({
                        params: {
                            r: self.queryOrderUrl,
                            keyword: orderNo,
                        },
                    }).then(e => {
                        if (e.data.code === 0) {
                            if(e.data.data.rechargeStatus == '2') {
                                clearInterval(self.intervalTime);
                                location.reload();
                            }
                        }
                    }).catch(e => {
                    });
                }, 1000);
            },
        }
    });
</script>
