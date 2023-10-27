<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */
Yii::$app->loadViewComponent('app-goods-list');
$sourceType = \Yii::$app->session['sourceType'];
?>
<style>

</style>
<div id="app" v-cloak>
    <app-goods-list
        ref="goodsList"
        :is_action="false"
        :is_add_goods="false"
        goods_url="plugin/supply_goods/mall/mch-goods/mch-goods-list"
        edit_goods_url='plugin/mch/mall/goods/edit'
        :is-show-batch-button="false"
        :is-show-cat="false"
        :is-show-delete="false"
        :is-show-up-down="false"
        batch_update_status_url="plugin/mch/mall/goods/batch-switch-status"
        edit_goods_status_url="plugin/mch/mall/goods/switch-status">

        <template slot="column-col-first">
            <el-table-column prop="store.name" label="店铺信息" width="200">
                <template slot-scope="scope">
                    <div flex="box:first">
                        <app-image style="margin-right: 5px" width="25" height="25" mode="aspectFill"
                                   :src="scope.row.store.cover_url"></app-image>
                        <div style="display: -webkit-box;height:25px;line-height: 25px;-webkit-box-orient: vertical;-webkit-line-clamp: 1;">{{scope.row.store.name}}</div>
                    </div>
                </template>
            </el-table-column>
        </template>

        <template slot="column-col">
            <el-table-column width="150" prop="status" label="上架申请">
                <template slot-scope="scope">
                    <div v-if="scope.row.mchGoods.status == 1">
                        <el-button @click="auditStatus(scope.row, 1)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="同意" placement="top">
                                <img src="statics/img/mall/pass.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button @click="auditStatus(scope.row, 0)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="拒绝" placement="top">
                                <img src="statics/img/mall/nopass.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                    <div v-if="scope.row.mchGoods.status == 2">已通过</div>
                    <div v-if="scope.row.mchGoods.status == 3">已拒绝</div>
                    <div v-if="scope.row.mchGoods.status == 0">未申请</div>
                </template>
            </el-table-column>
        </template>
        <?php if($sourceType == 1){?>
            <template slot="action" slot-scope="item">
                <el-button @click="edit(item.item)" type="text" circle size="mini">添加</el-button>
            </template>
        <?php }?>
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
            };
        },
        methods: {
            edit(row) {
                if (row) {
                    navigateTo({
                        r: 'plugin/supply_goods/mall/mch-goods/goods-detail',
                        id: row.id,
                        mch_id: row.mch_id,
                    }, true);
                } else {
                    navigateTo({
                        r: 'plugin/supply_goods/mall/mch-goods/goods-detail',
                    });
                }
            },
        }
    });
</script>
