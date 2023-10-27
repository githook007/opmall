<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-comment');
?>
<div id="app" v-cloak>
    <app-comment sign="booking" reply_url='plugin/booking/mall/comment/reply' edit_url="plugin/booking/mall/comment/edit"></app-comment>
</div>
<script>
const app = new Vue({
    el: '#app',
    mounted() {
        if (getQuery('id')) {}
    }
});
</script>