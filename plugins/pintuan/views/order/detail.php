<?php
/**
 * @copyright ©2022 opmall
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/29 15:59
 */
Yii::$app->loadViewComponent('app-order-detail');
?>
<div id="app" v-cloak>
    <app-order-detail get-order-list-url="plugin/pintuan/mall/order/index"></app-order-detail>
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
    })
</script>