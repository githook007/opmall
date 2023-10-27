<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-goods-list');
?>
<style>
</style>
<div id="app" v-cloak>
    <app-goods-list
            ref="goodsList"
            :is-show-batch-button="false"
            goods_url="plugin/booking/mall/goods/index"
            edit_goods_url='plugin/booking/mall/goods/edit'
            :is-show-express="false">
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
    });
</script>
