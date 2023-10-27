<?php
/**
 * author: chenzs
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/29 15:59
 */
Yii::$app->loadViewComponent('app-invoice-detail', __DIR__."/../");
?>
<div id="app" v-cloak>
    <app-invoice-detail get-order-list-url="plugin/invoice/mall/applyOrder/index"></app-invoice-detail>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {};
        },
        created() {
        },
        methods: {}
    })
</script>