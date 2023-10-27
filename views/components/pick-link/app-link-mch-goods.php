<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/18
 * Time: 10:52 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-mch-goods">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据商品ID、商品名称搜索');?>"
                            list-url="plugin/mch/mall/goods/index" :other="other"
                            :select="select"
                            @confirm="confirm" v-model="isSelect" :id="id">
            <template #table="slotProps">
                <el-table
                        ref="multipleTable"
                        :data="slotProps.table.list"
                        border
                        max-height="400"
                        style="width: 100%;margin-top: 20px"
                        v-loading="slotProps.table.listLoading">
                    <el-table-column label="ID" prop="id">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.id}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="store.name" label="<?= \Yii::t('components/other', '店铺信息');?>" width="200">
                        <template slot-scope="scope">
                            <div flex="box:first">
                                <app-image style="margin-right: 5px" width="25" height="25" mode="aspectFill"
                                           :src="scope.row.store.cover_url"></app-image>
                                <div style="display: -webkit-box;height:25px;line-height: 25px;-webkit-box-orient: vertical;-webkit-line-clamp: 1;">{{scope.row.store.name}}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '商品名称');?>" prop="name" width="350">
                        <template slot-scope="scope">
                            <div flex="box:first">
                                <div style="padding-right: 10px;">
                                    <app-image mode="aspectFill" :src="scope.row.goodsWarehouse.cover_pic"></app-image>
                                </div>
                                <div flex="cross:top">
                                    <div flex="dir:left" style="overflow:hidden;">
                                        <div style="position: absolute;width: 250px;height: 46px;overflow: hidden" flex="dir:left">
                                            <el-tooltip class="item"
                                                        effect="dark"
                                                        placement="top">
                                                <template slot="content">
                                                    <div style="width: 320px;" flex="cross:center">{{scope.row.goodsWarehouse.name}}</div>
                                                </template>
                                                <div v-line-clamp="2" v-if="scope.row.goodsWarehouse.name && scope.row.goodsWarehouse.name.length < 30" flex="cross:center">{{scope.row.goodsWarehouse.name}}</div>
                                                <div v-line-clamp="2" v-else>{{scope.row.goodsWarehouse.name}}</div>
                                            </el-tooltip>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '售价');?>" prop="price"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '库存');?>" prop="goods_stock">
                        <template slot-scope="scope">
                            <div v-if="scope.row.goods_stock > 0">{{scope.row.goods_stock}}</div>
                            <div v-else style="color: red;"><?= \Yii::t('components/other', '售罄');?></div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-mch-goods', {
        template: '#app-link-mch-goods',
        props: {
            value: {
                type: Object | null,
                default: null
            }
        },
        data() {
            return {
                id: -1,
                select: '',
                other: {
                    search: {
                        status: 1
                    }
                }
            };
        },
        created() {
            this.select = this.value;
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.name,
                    id: select.id,
                    mch_id: select.mch_id
                };
                this.$emit('confirm', this.select);
            },
        },
        computed: {
            isSelect() {
                return !this.select || this.select.length === 0
            }
        }
    });
</script>
