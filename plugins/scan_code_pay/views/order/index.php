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
                :tabs="tabs"
                :select-list="selectList"
                active-name="3"
                :is-show-order-type="false"
                :is-show-order-status="false"
                order-url="plugin/scan_code_pay/mall/order"
                order-detail-url="plugin/scan_code_pay/mall/order/detail"
                recycle-url="plugin/scan_code_pay/mall/order/destroy-all">
            <template slot="orderTag" slot-scope="order">
                <el-tag size="small" type="success" v-if="order.order.is_sale == 1"><?= \Yii::t('plugins/scan_code_pay', '已完成');?></el-tag>
            </template>
        </app-order>
    </el-card>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                tabs: [
                    {value: '3', name: '<?= \Yii::t('plugins/scan_code_pay', '已完成');?>'},
                    {value: '7', name: '<?= \Yii::t('plugins/scan_code_pay', '回收站');?>'},
                ],
                selectList: [
                    {value: '1', name: '<?= \Yii::t('plugins/scan_code_pay', '订单号');?>'},
                    {value: '2', name: '<?= \Yii::t('plugins/scan_code_pay', '用户名');?>'},
                    {value: '4', name: '<?= \Yii::t('plugins/scan_code_pay', '用户ID');?>'},
                ]
            };
        },
        created() {

        },
        methods: {

        }
    });
</script>
