<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/17
 * Time: 3:10 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-pintuan">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据商品ID、商品名称搜索');?>"
                            list-url="plugin/pintuan/mall/activity/index" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '商品ID');?>" prop="id">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.id}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="<?= \Yii::t('components/other', '商品名称');?>"
                            width="320">
                        <template slot-scope="scope">
                            <div flex="box:first">
                                <div style="padding-right: 10px;">
                                    <app-image mode="aspectFill" :src="scope.row.goods_cover_pic"></app-image>
                                </div>
                                <div style="display: -webkit-box;height:50px;line-height: 25px;-webkit-box-orient: vertical;-webkit-line-clamp: 2;">
                                    {{scope.row.goods_name}}
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="groups"
                            label="<?= \Yii::t('components/other', '拼团组/拼团价');?>"
                            width="200"
                    >
                        <template slot-scope="scope"><div flex="dir:top"  >
                                <div v-for="(it, i) in scope.row.groups.slice(0,2)">
                                    <el-tag  style="margin-bottom: 10px">
                                        {{it.people_num}}<?= \Yii::t('components/other', '人');?>|￥{{it.price}}
                                    </el-tag>
                                </div>
                            </div>
                            <el-popover
                                    placement="top-start"
                                    width="200"
                                    trigger="hover">
                                <div v-for="(it, i) in scope.row.groups">
                                    <el-tag  style="margin-bottom: 10px">
                                        {{it.people_num}}<?= \Yii::t('components/other', '人');?>|￥{{it.price}}
                                    </el-tag>
                                </div>
                                <span v-if="scope.row.groups.length > 2" class="groups" slot="reference"><?= \Yii::t('components/other', '查看全部');?></span>
                            </el-popover>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="goods_stock"
                            label="<?= \Yii::t('components/other', '库存');?>">
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-pintuan', {
        template: '#app-pintuan',
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
                    type: 'link',
                    search: {
                        status: 3
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
                    show_name: select.goods_name,
                    goods_id: select.id
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

