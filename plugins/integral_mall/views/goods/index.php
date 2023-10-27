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
            goods_url="plugin/integral_mall/mall/goods/index"
            edit_goods_url='plugin/integral_mall/mall/goods/edit'
            :is-show-svip="false"
            :is-show-goods-member="false"
            :batch-list="batchList">

        <template slot="column-col">
            <el-table-column prop="integralMallGoods.integral_num" label="<?= \Yii::t('plugins/integral_mall', '兑换价格');?>">
                <template slot-scope="scope">
                    <div>{{scope.row.price}}/{{scope.row.integralMallGoods.integral_num}}</div>
                </template>
            </el-table-column>
            <el-table-column prop="confine_count" width="110" label="<?= \Yii::t('plugins/integral_mall', '每人可兑换数');?>">
                <template slot-scope="scope">
                    <div>{{scope.row.confine_count == -1 ? '<?= \Yii::t('plugins/integral_mall', '不限');?>':scope.row.confine_count}}</div>
                </template>
            </el-table-column>
        </template>
        <template slot="switch-col">
            <el-table-column prop="is_sell_well" width="80" label="<?= \Yii::t('plugins/integral_mall', '放置首页');?>">
                <template slot-scope="scope">
                    <el-switch
                            :active-value="1"
                            :inactive-value="0"
                            @change="switchQuickShop(scope.row)"
                            v-model="scope.row.integralMallGoods.is_home">
                    </el-switch>
                </template>
            </el-table-column>
        </template>

        <template slot="batch" slot-scope="item">
            <div v-if="item.item === 'index'">
                <el-form-item label="<?= \Yii::t('plugins/integral_mall', '是否放置首页');?>">
                    <el-switch
                            @change="batch"
                            v-model="batchList[0].params.status"
                            :active-value="1"
                            :inactive-value="0">
                    </el-switch>
                </el-form-item>
            </div>
            <div v-if="item.item === 'goods_integral'">
                <el-form-item label="<?= \Yii::t('plugins/integral_mall', '商品积分');?>">
                    <div flex="dir:left">
                        <div>
                            <el-tooltip effect="dark" placement="top">
                                <div slot="content"><?= \Yii::t('plugins/integral_mall', '批量修改规格商品所需积分');?></div>
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </div>
                        <div style="margin-left: 5px;width: 100%;">
                            <el-input type="number"
                                      size="small"
                                      oninput="this.value = this.value.replace(/[^0-9]/, '');"
                                      min="0"
                                      placeholder="<?= \Yii::t('plugins/integral_mall', '请输入积分数量');?>"
                                      v-model="batchList[1].params.goods_integral">
                            </el-input>
                        </div>
                    </div>
                </el-form-item>
            </div>
        </template>
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                batchList: [
                    {
                        name: '<?= \Yii::t('plugins/integral_mall', '放置首页');?>',
                        key: 'index',
                        url: 'plugin/integral_mall/mall/goods/batch-update-index',
                        content: '<?= \Yii::t('plugins/integral_mall', '批量移除放置首页');?>',
                        params: {
                            status: 0
                        }
                    },
                    {
                        name: '<?= \Yii::t('plugins/integral_mall', '所需积分');?>',
                        key: 'goods_integral',
                        url: 'plugin/integral_mall/mall/goods/batch-update-integral',
                        content: '<?= \Yii::t('plugins/integral_mall', '量修改商品所需积分');?>',
                        params: {
                            goods_integral: 0
                        }
                    }
                ],
                isAllChecked: false,
            };
        },
        methods: {
            // 放置首页
            switchQuickShop(row) {
                let self = this;
                request({
                    params: {
                        r: 'plugin/integral_mall/mall/goods/switch-sell-well',
                        id: row.id
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                        self.$refs.goodsList.getList();
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            batch() {
                let isAllChecked = this.isAllChecked;
                this.batchList[0].content = isAllChecked ? '<?= \Yii::t('plugins/integral_mall', '批量设置所有商品');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/integral_mall', '加入');?>' : '<?= \Yii::t('plugins/integral_mall', '移除');?>') + '<?= \Yii::t('plugins/integral_mall', '放置首页是否继续');?>' : '<?= \Yii::t('plugins/integral_mall', '批量');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/integral_mall', '加入');?>' : '<?= \Yii::t('plugins/integral_mall', '移除');?>') + '<?= \Yii::t('plugins/integral_mall', '放置首页是否继续');?>'
            },
            getAllChecked(isAllChecked) {
                this.batchList[0].content = isAllChecked ? '<?= \Yii::t('plugins/integral_mall', '批量设置所有商品');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/integral_mall', '加入');?>' : '<?= \Yii::t('plugins/integral_mall', '移除');?>') + '<?= \Yii::t('plugins/integral_mall', '放置首页是否继续');?>' : '<?= \Yii::t('plugins/integral_mall', '批量');?>' + (this.batchList[0].params.status ? '<?= \Yii::t('plugins/integral_mall', '加入');?>' : '<?= \Yii::t('plugins/integral_mall', '移除');?>') + '<?= \Yii::t('plugins/integral_mall', '放置首页是否继续');?>';
                this.isAllChecked = isAllChecked;
            }
        }
    });
</script>
