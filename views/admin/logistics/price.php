<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 17:05
 */
Yii::$app->loadViewComponent('c-price', __DIR__);
?>
<div id="app" v-cloak>
    <c-price :display="priceDisplay" :id="id"></c-price>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                priceDisplay: true,
                id: '0'
            };
        },
        created() {},
        methods: {},
    });
</script>
