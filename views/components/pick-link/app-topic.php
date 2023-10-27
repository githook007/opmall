<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/17
 * Time: 11:30 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-topic">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '选择标签');?>" placeholder="<?= \Yii::t('components/other', '根据id、标签名搜索');?>"
                            list-url="mall/topic/index" :other="other"
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
                    <el-table-column label="<?= \Yii::t('components/other', '标签名');?>" prop="tag_name"></el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '专题内容');?>" prop="title" width="400">
                        <template slot-scope="scope">
                            <div flex="dir:left" style="height: 50px">
                                <img :src="scope.row.pic_url" style="width: 50px;height: 50px;margin-right: 10px;flex-grow: 0;flex-shrink: 0">
                                <div flex="dir:top">
                                    <app-ellipsis :line="1">{{scope.row.title}}</app-ellipsis>
                                    <span>{{scope.row.created_at}}</span>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-topic', {
        template: '#app-topic',
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
                    is_show: 1
                }
            };
        },
        created() {
            this.select = this.value;
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.tag_name,
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


