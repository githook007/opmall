<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/18
 * Time: 10:22 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-link-mch">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '店铺选择');?>" placeholder="<?= \Yii::t('components/other', '根据店铺ID、店铺信息搜索');?>"
                            list-url="plugin/mch/mall/mch/index" :other="other"
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
                    <el-table-column label="ID" prop="id" width="100">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.id}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            :show-overflow-tooltip="true"
                            label="<?= \Yii::t('components/other', '店铺信息');?>">
                        <template slot-scope="scope">
                            <div flex="cross:center">
                                <div style="flex-shrink: 0; flex-grow: 0;">
                                    <app-image width="25" height="25" :src="scope.row.store.cover_url"></app-image>
                                </div>
                                <div style="margin-left: 10px;flex-grow:1; overflow:hidden;text-overflow: ellipsis;">{{scope.row.store.name}}</div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            label="<?= \Yii::t('components/other', '联系人');?>" width="200">
                        <template slot-scope="scope">
                            <div>
                                <app-ellipsis style="margin-left: 10px;" :line="1">{{scope.row.realname}}
                                </app-ellipsis>
                                <app-ellipsis style="margin-left: 10px;" :line="1"><?= \Yii::t('components/other', '电话');?>:{{scope.row.mobile}}
                                </app-ellipsis>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-mch', {
        template: '#app-link-mch',
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
                    show_name: select.store.name,
                    id: select.id,
                    mch_id: select.id,
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
