<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/16
 * Time: 5:23 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-live">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '选择直播间');?>" placeholder="<?= \Yii::t('components/other', '根据房间id或者房间名搜索');?>"
                            list-url="mall/live/index"
                            :select="select"
                            :is-show-search="false"
                            @confirm="confirm" v-model="isSelect" :id="id">
            <template #table="slotProps">
                <el-table
                    ref="multipleTable"
                    :data="slotProps.table.list"
                    border
                    max-height="400"
                    style="width: 100%;margin-top: 20px"
                    v-loading="slotProps.table.listLoading">
                    <el-table-column label="<?= \Yii::t('components/other', '房间');?>ID" prop="roomid">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.roomid}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '房间名');?>" prop="name">
                        <template slot-scope="scope">
                            <app-ellipsis :line="1">{{scope.row.name}}</app-ellipsis>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '主播信息');?>">
                        <template slot-scope="scope">
                            <div flex="cross:center">
                                <img style="width: 45px;height: 45px " :src="scope.row.anchor_img">
                                <span style="margin-left: 10px;">{{scope.row.anchor_name}}</span>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column
                            width="180"
                            label="<?= \Yii::t('components/other', '计划直播时间');?>">
                        <template slot-scope="scope">
                            <div>{{scope.row.start_time}}</div>
                            <div>{{scope.row.end_time}}</div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-live', {
        template: '#app-live',
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
            };
        },
        created() {
            this.select = this.value;
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.name,
                    room_id: select.roomid
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


