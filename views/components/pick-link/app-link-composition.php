<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/18
 * Time: 3:49 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-composition">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据商品ID、套餐名称搜索');?>"
                            list-url="plugin/composition/mall/index/list" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '套餐名称');?>" width="300" prop="name"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '套餐类型');?>" prop="type">
                        <template slot-scope="scope">
                            <div>{{scope.row.type == 1 ? '<?= \Yii::t('components/other', '固定套餐');?>' : '<?= \Yii::t('components/other', '搭配套餐');?>'}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '优惠金额');?>" prop="price">
                        <template slot-scope="scope">
                            <div v-if="scope.row.type == 1">￥{{scope.row.price}}</div>
                            <div v-else>￥{{scope.row.min_price}}<span v-if="scope.row.min_price != scope.row.max_price">~￥{{scope.row.max_price}}</span></div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '库存');?>" prop="stock" sortable="custom">
                        <template slot-scope="scope">
                            <div>
                                <span :class="scope.row.stock < 50 ? 'zero-stock':''">{{scope.row.stock}}</span>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-composition', {
        template: '#app-link-composition',
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
                    show_name: select.name,
                    id: select.id,
                    composition_id: select.id,
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

