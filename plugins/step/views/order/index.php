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
        <app-order order-url="plugin/step/mall/order"
                   order-detail-url="plugin/step/mall/order/detail"
                   recycle-url="plugin/step/mall/order/destroy-all">
            <template slot="other" slot-scope="item">
                <span> + {{item.item.extra.currency}}<?= \Yii::t('plugins/step', 'step_z61');?></span>
            </template>
        </app-order>
    </el-card>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {

            };
        },
        created() {

        },
        methods: {

        }
    });
</script>
