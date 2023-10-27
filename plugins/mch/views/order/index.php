<?php
/**
 * @copyright ©2022 opmall
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/29 15:59
 */
Yii::$app->loadViewComponent('app-order');
?>
<style>
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <app-order
                :select-list="selectList"
                :is-show-recycle="false"
                :is-show-confirm="false"
                :is-show-finish="false"
                :is-show-send="false"
                :is-show-clerk="false"
                :is-show-print="false"
                :is-show-remark="false"
                :is-show-cancel="false"
                :is-show-edit-address="false"
                :is-show-edit-express-price="false"
                :is-show-edit-single-price="false"
                order-url="plugin/mch/mall/order/index"
                order-detail-url="plugin/mch/mall/order/detail">
        </app-order>
    </el-card>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                selectList: [
                    {value: '1', name: '<?= \Yii::t('plugins/mch', '订单号');?>'},
                    {value: '2', name: '<?= \Yii::t('plugins/mch', '用户名');?>'},
                    {value: '4', name: '<?= \Yii::t('plugins/mch', '用户');?>ID'},
                    {value: '5', name: '<?= \Yii::t('plugins/mch', '商品名称');?>'},
                    {value: '3', name: '<?= \Yii::t('plugins/mch', '收件人');?>'},
                    {value: '6', name: '<?= \Yii::t('plugins/mch', '收件人电话');?>'},
                    {value: 'mch_name', name: '<?= \Yii::t('plugins/mch', '商户名称');?>'}
                ]
            };
        },
        created() {
        },
        methods: {}
    });
</script>
