<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/18
 * Time: 11:06 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-lottery">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据抽奖ID、商品名称搜索');?>"
                            list-url="plugin/lottery/mall/lottery/link" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '抽奖');?>ID" prop="lotteryGoods.id">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.lotteryGoods.id}}
                                </el-radio>
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
                                                <div v-line-clamp="2" v-if="scope.row.goodsWarehouse.name.length < 30" flex="cross:center">{{scope.row.goodsWarehouse.name}}</div>
                                                <div v-line-clamp="2" v-else>{{scope.row.goodsWarehouse.name}}</div>
                                            </el-tooltip>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="attr_str" label="<?= \Yii::t('components/other', '规格');?>" width="220"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '活动时间');?>" width="170">
                        <template slot-scope="scope">
                            {{scope.row.lotteryGoods.start_at}}-{{scope.row.lotteryGoods.end_at}}
                        </template>
                    </el-table-column>
                    <el-table-column prop="lotteryGoods.stock" label="<?= \Yii::t('components/other', '中奖数量');?>" width="120"></el-table-column>
                    <el-table-column prop="lotteryGoods.deplete_integral_num" label="<?= \Yii::t('components/other', '抽奖条件');?>">
                        <template slot-scope="scope">
                            {{scope.row.lotteryGoods.deplete_integral_num | msgFormat}}
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-lottery', {
        template: '#app-link-lottery',
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
                    status: 1
                }
            };
        },
        created() {
            this.select = this.value;
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.goods_name,
                    lottery_id: select.lotteryGoods.id,
                };
                this.$emit('confirm', this.select);
            },
        },
        computed: {
            isSelect() {
                return !this.select || this.select.length === 0
            }
        },
        filters: {
            msgFormat(num) {
                return num > 0 ? `<?= \Yii::t('components/other', '消耗');?>${num}<?= \Yii::t('components/other', '积分');?>` : `<?= \Yii::t('components/other', '无条件');?>`
            }
        }
    });
</script>

