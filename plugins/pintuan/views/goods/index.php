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
            @get-all-checked="getAllChecked"
            ref="goodsList"
            goods_url="plugin/pintuan/mall/goods/index"
            edit_goods_url='plugin/pintuan/mall/goods/edit'
            edit_goods_status_url="plugin/pintuan/mall/goods/switch-status"
            batch_update_status_url="plugin/pintuan/mall/goods/batch-update-status"
            :status_change_text="statusChangeText"
            :action-witch="200"
            :batch-list="batchList">

        <template slot="batch" slot-scope="item">
            <div v-if="item.item === 'hotSell'">
                <el-form-item label="<?= \Yii::t('plugins/pintuan', '是否加入热销');?>">
                    <el-switch @change="batchHostSell"
                               v-model="batchList[0].params.status"
                               :active-value="1"
                               :inactive-value="0"
                    ></el-switch>
                </el-form-item>
            </div>
        </template>

        <template slot="column-col">
            <el-table-column label="<?= \Yii::t('plugins/pintuan', '是否热销');?>">
                <template slot-scope="scope">
                    <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            @change="switchSellWell(scope.row)"
                            v-model="scope.row.is_sell_well">
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column width="80" label="单买">
                <template slot-scope="scope">{{scope.row.pintuanGoods.is_alone_buy ? '<?= \Yii::t('plugins/pintuan', '是');?>' : '<?= \Yii::t('plugins/pintuan', '否');?>'}}</template>
            </el-table-column>
            <el-table-column width="80" label="<?= \Yii::t('plugins/pintuan', '拼团组');?>">
                <template slot-scope="scope">
                    <el-tag style="margin-right: 3px" type="danger" size="mini" v-for="item in scope.row.groups"
                            :key="item.id">
                        {{item.people_num}}
                    </el-tag>
                </template>
            </el-table-column>
        </template>

        <template slot="action" slot-scope="item">
            <el-button @click="pintuan(item.item)" type="text" circle size="mini">
                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/pintuan', '拼团设置');?>" placement="top">
                    <img src="statics/img/plugins/setting.png" alt="">
                </el-tooltip>
            </el-button>
        </template>
    </app-goods-list>

</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                statusChangeText: '<?= \Yii::t('plugins/pintuan', '拼团商品至少需要添加一个拼团组');?>',
                batchList: [
                    {
                        name: '<?= \Yii::t('plugins/pintuan', '热销');?>',
                        key: 'hotSell',
                        url: 'plugin/pintuan/mall/goods/batch-update-hot-sell',
                        content: '<?= \Yii::t('plugins/pintuan', '批量移除热销');?>',
                        params: {
                            status: 0
                        }
                    },
                ],
                isAllChecked: false,
            };
        },
        methods: {
            // 商品热销
            switchSellWell(row) {
                let self = this;
                request({
                    params: {
                        r: 'plugin/pintuan/mall/goods/switch-sell-well',
                    },
                    method: 'post',
                    data: {
                        is_sell_well: row.is_sell_well,
                        id: row.id
                    }
                }).then(e => {
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            pintuan(row) {
                navigateTo({
                    r: 'plugin/pintuan/mall/goods/pintuan',
                    id: row.id,
                });
            },
            batchHostSell() {
                let isAllChecked= this.isAllChecked;
                this.batchList[0].content = isAllChecked ? '<?= \Yii::t('plugins/pintuan', '批量设置所有商品');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/pintuan', '加入');?>' : '<?= \Yii::t('plugins/pintuan', '移除');?>') + '<?= \Yii::t('plugins/pintuan', '是否继续');?>' : '<?= \Yii::t('plugins/pintuan', '批量');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/pintuan', '加入');?>' : '<?= \Yii::t('plugins/pintuan', '移除');?>') + '<?= \Yii::t('plugins/pintuan', '是否继续');?>'
            },
            getAllChecked(isAllChecked) {
                this.batchList[0].content = isAllChecked ? '<?= \Yii::t('plugins/pintuan', '批量设置所有商品');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/pintuan', '加入');?>' : '<?= \Yii::t('plugins/pintuan', '移除');?>') + '<?= \Yii::t('plugins/pintuan', '是否继续');?>' : '<?= \Yii::t('plugins/pintuan', '批量');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/pintuan', '加入');?>' : '<?= \Yii::t('plugins/pintuan', '移除');?>') + '<?= \Yii::t('plugins/pintuan', '是否继续');?>';
                this.isAllChecked = isAllChecked;
            }
        },
        mounted() {
        }
    });
</script>
