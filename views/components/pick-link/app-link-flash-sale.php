<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/18
 * Time: 5:47 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-flash-sale">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '商品选择');?>" placeholder="<?= \Yii::t('components/other', '根据商品名称搜索');?>"
                            list-url="/plugin/flash_sale/mall/activity/link" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '活动名称');?>" prop="title">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.activity_name}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            prop="name"
                            label="<?= \Yii::t('components/other', '商品名称');?>">
                        <template slot-scope="scope">
                            <div>
                                <div v-line-clamp="2" v-if="scope.row.name.length < 30" flex="cross:center">{{scope.row.name}}</div>
                                <div v-line-clamp="2" v-else>{{scope.row.name}}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="<?= \Yii::t('components/other', '活动时间');?>"
                            show-overflow-tooltip>
                        <template slot-scope="scope">
                            <template>
                                <p>{{scope.row.start_at}} <?= \Yii::t('components/other', '至');?></p>
                                <p>{{scope.row.end_at}}</p>
                            </template>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-flash-sale', {
        template: '#app-link-flash-sale',
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
                    keyword_label: 'title'
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
                    id: select.id
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

