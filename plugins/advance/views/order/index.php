<?php
Yii::$app->loadViewComponent('app-order');
?>

<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <app-order order-url="plugin/advance/mall/order/index"
                   :select-list="selectList"
                   :tabs="tabs"
                   order-detail-url="plugin/advance/mall/order/detail"
                   recycle-url="plugin/advance/mall/order/destroy-all">
            <slot name="orderTitle"><?= \Yii::t('plugins/advance', '尾款订单');?></slot>
        </app-order>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                selectList: [
                    {value: '1', name: "<?= \Yii::t('plugins/advance', '订单号');?>"},
                    {value: 'advance_no', name: "<?= \Yii::t('plugins/advance', '定金订单号');?>"},
                    {value: '9', name: "<?= \Yii::t('plugins/advance', '商户单号');?>"},
                    {value: '2', name: "<?= \Yii::t('plugins/advance', '用户名');?>"},
                    {value: '4', name: "<?= \Yii::t('plugins/advance', '用户ID');?>"},
                    {value: '5', name: "<?= \Yii::t('plugins/advance', '商品名称');?>"},
                    {value: '3', name: "<?= \Yii::t('plugins/advance', '收件人');?>"},
                    {value: '6', name: "<?= \Yii::t('plugins/advance', '收件人电话');?>"},
                    {value: '7', name: "<?= \Yii::t('plugins/advance', '门店名称');?>"}
                ],
                tabs: [
                    {value: '-1', name: "<?= \Yii::t('plugins/advance', '全部');?>"},
                    {value: '0', name: "<?= \Yii::t('plugins/advance', '未付款');?>"},
                    {value: '1', name: "<?= \Yii::t('plugins/advance', '待发货');?>"},
                    {value: '2', name: "<?= \Yii::t('plugins/advance', '待收货');?>"},
                    {value: '3', name: "<?= \Yii::t('plugins/advance', '已完成');?>"},
                    {value: '4', name: "<?= \Yii::t('plugins/advance', '待处理');?>"},
                    {value: '5', name: "<?= \Yii::t('plugins/advance', '已取消');?>"},
                    {value: '7', name: "<?= \Yii::t('plugins/advance', '回收站');?>"},
                ],
            };
        },
        methods: {},
    });
</script>
