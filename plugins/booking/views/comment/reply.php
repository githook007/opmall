<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-comment-reply');
?>
<div id="app" v-cloak>
    <app-comment-reply navigate_url='plugin/booking/mall/comment'></app-comment-reply>
</div>
<script>
const app = new Vue({
    el: '#app',
    mounted() {
        if (getQuery('id')) {}
    }
});
</script>