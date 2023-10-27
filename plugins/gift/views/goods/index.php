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
            :tabs="tabs"
            goods_url="plugin/gift/mall/goods/index"
            edit_goods_url='plugin/gift/mall/goods/edit'
            edit_goods_status_url='plugin/gift/mall/goods/switch-status'>
    </app-goods-list>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                tabs: [
                    {
                        name: '<?= \Yii::t('plugins/gift', '全部');?>',
                        value: '-1'
                    },
                    {
                        name: '<?= \Yii::t('plugins/gift', '销售中');?>',
                        value: '1'
                    },
                    {
                        name: '<?= \Yii::t('plugins/gift', '下架中');?>',
                        value: '0'
                    },
                    {
                        name: '<?= \Yii::t('plugins/gift', '售罄');?>',
                        value: '2'
                    },
                ]
            }
        },
        methods: {}
    });
</script>
