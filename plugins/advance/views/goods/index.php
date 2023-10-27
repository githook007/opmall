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
            goods_url="plugin/advance/mall/goods/index"
            edit_goods_url='plugin/advance/mall/goods/edit'
            edit_goods_status_url='plugin/advance/mall/goods/switch-status'
            @new-handle-command="newHandleCommand">
        <template slot="select-option">
            <el-option label="<?= \Yii::t('plugins/advance', '预售');?>" value="3"></el-option>
            <el-option label="<?= \Yii::t('plugins/advance', '付尾款中');?>" value="4"></el-option>
            <el-option label="<?= \Yii::t('plugins/advance', '结束');?>" value="5"></el-option>
        </template>
        <template slot="column-col">
            <el-table-column prop="display_deposit" label="<?= \Yii::t('plugins/advance', '定金');?>"></el-table-column>
            <el-table-column prop="goods_status" label="<?= \Yii::t('plugins/advance', '预售状态');?>"></el-table-column>
        </template>
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                tabs: [
                    {
                        name: "<?= \Yii::t('plugins/advance', '全部');?>",
                        value: '-1'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '销售中');?>
",
                        value: '1'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '下架中');?>",
                        value: '0'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '售罄');?>",
                        value: '2'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '预售中');?>",
                        value: '3'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '付尾款中');?>",
                        value: '4'
                    },
                    {
                        name: "<?= \Yii::t('plugins/advance', '结束');?>",
                        value: '5'
                    },
                ]
            }
        },
        methods: {}
    });
</script>