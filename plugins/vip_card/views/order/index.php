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
                :is-show-print="false"
                :is-show-order-type="false"
                :is-show-order-status="false"
                order-url="plugin/vip_card/mall/order"
                recycle-url="plugin/vip_card/mall/order/destroy-all">
            <template slot="orderTag" slot-scope="order">
                <el-tag size="small" type="success" v-if="order.order.is_sale == 1"><?= \Yii::t('plugins/vip_card', '已完成');?></el-tag>
            </template>
            <template slot="attr" slot-scope="order">
                <?= \Yii::t('plugins/vip_card', '小标题');?>
                <el-tag size="mini" style="margin-right: 5px;">
                    {{order.item.extra.card_detail_name}}
                </el-tag>
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
                    {value: '3', name: '<?= \Yii::t('plugins/vip_card', '已完成');?>'},
                    {value: '7', name: '<?= \Yii::t('plugins/vip_card', '回收站');?>'},
                ],
                selectList: [
                    {value: '1', name: '<?= \Yii::t('plugins/vip_card', '订单号');?>'},
                    {value: '2', name: '<?= \Yii::t('plugins/vip_card', '用户名');?>'},
                    {value: '3', name: '<?= \Yii::t('plugins/vip_card', '用户ID');?>'},
                    {value: '4', name: '<?= \Yii::t('plugins/vip_card', '小标题');?>'},
                ]
            };
        },
        created() {
        },
        methods: {}
    });
</script>
