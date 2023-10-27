<?php defined('YII_ENV') or exit('Access Denied');
/**
 * @copyright ©2018 hook007
 * @author xay
 * @link https://www.opmall.com/
 */
?>
<div id="app" v-cloak>
    <app-banner url="mall/mall-banner/index" submit_url="mall/mall-banner/edit"></app-banner>
</div>
<script>
const app = new Vue({
    el: '#app'
})
</script>