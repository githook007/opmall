<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('app-info', __DIR__)
?>
<div id="app" v-cloak="">
    <app-info type="all"></app-info>
</div>
<script>
    const app = new Vue({
        el: '#app'
    });
</script>
