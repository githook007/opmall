<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-comment-edit');
?>
<div id="app" v-cloak>
    <app-comment-edit sign="booking" navigate_url='plugin/booking/mall/comment'></app-comment-edit>
</div>
<script>
const app = new Vue({
    el: '#app',
    mounted() {
        if (getQuery('id')) {

        }
    }
});
</script>