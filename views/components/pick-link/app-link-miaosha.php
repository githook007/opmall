<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/26
 * Time: 5:45 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-miaosha">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据商品ID、商品名称搜索');?>"
                            list-url="plugin/miaosha/mall/activity/link" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '名称');?>" prop="name"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '时间');?>" prop="open_date"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '场次');?>" prop="open_time"></el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-miaosha', {
        template: '#app-link-miaosha',
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
                    is_activity: 1
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

