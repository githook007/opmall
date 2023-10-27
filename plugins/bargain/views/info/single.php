<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/12/17
 * Time: 16:19
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('app-info', __DIR__)
?>
<div id="app" v-cloak="">
    <app-info type="single" type="all"></app-info>
</div>
<script>
    const app = new Vue({
        el: '#app'
    });
</script>