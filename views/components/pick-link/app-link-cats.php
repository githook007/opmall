<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/24
 * Time: 5:58 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<style>
    .dialog-cat-box {
        border: 1px solid #E3E3E3;
        height: 250px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        width: 100%;
        margin-right: 10px;
        overflow-y: auto;
        margin-top: 20px;
    }

    .app-link-cats .dialog-cat-box .el-table td {
        padding: 0;
    }
</style>
<template id="app-link-cats">
    <div class="app-link-cats" style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '分类选择');?>" placeholder="<?= \Yii::t('components/other', '根据分类名称搜索');?>"
                            search-type="cat"
                            list-url="mall/cat/all-list" :other="other" :select="select"
                            @confirm="confirm" v-model="isSelect" :id="id">
            <template #table="slotProps">
                <div flex="dir:left">
                    <div class="dialog-cat-box">
                        <el-table
                                :show-header="false"
                                :data="slotProps.table.list"
                                :row-class-name="tableRowClassName"
                                style="width: 100%"
                                v-loading="slotProps.table.listLoading">
                            <el-table-column>
                                <template slot-scope="scope">
                                    <div flex="box:last" style="padding: 12px 0" @click="tableRowClick(scope.$index)">
                                        <div>{{scope.row.label}}</div>
                                        <div v-if="scope.row.children">
                                            <i class="el-icon-arrow-right"></i>
                                        </div>
                                    </div>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                    <template v-if="slotProps.table.list.length > 0 && index > -1 && slotProps.table.list[index].children && slotProps.table.list[index].children.length > 0">
                        <div class="dialog-cat-box">
                            <el-table
                                    :show-header="false"
                                    :data="slotProps.table.list[index].children"
                                    highlight-current-row
                                    :row-class-name="tableRowClassName2"
                                    style="width: 100%"
                                    v-loading="slotProps.table.listLoading">
                                <el-table-column>
                                    <template slot-scope="scope">
                                        <div flex="box:last" style="padding: 12px 0" @click="tableRowClick2(scope.$index)">
                                            <div>{{scope.row.label}}</div>
                                            <div v-if="scope.row.children">
                                                <i class="el-icon-arrow-right"></i>
                                            </div>
                                        </div>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                        <template v-if="index2 > -1 && slotProps.table.list[index].children[index2].children && slotProps.table.list[index].children[index2].children.length > 0">
                            <div class="dialog-cat-box" v-if="slotProps.table.list[index].children[index2].children.length > 0">
                                <el-table
                                        :show-header="false"
                                        :data="slotProps.table.list[index].children[index2].children"
                                        highlight-current-row
                                        :row-class-name="tableRowClassName3"
                                        style="width: 100%"
                                        v-loading="slotProps.table.listLoading">
                                    <el-table-column prop="label">
                                        <template slot-scope="scope">
                                            <div flex="box:last" style="padding: 12px 0" @click="tableRowClick3(scope.$index)">
                                                <div>{{scope.row.label}}</div>
                                            </div>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </template>
                    </template>
                </div>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-link-cats', {
        template: '#app-link-cats',
        props: {
            value: {
                type: Object | null,
                default: null
            },
            type: String
        },
        data() {
            return {
                id: -1,
                select: '',
                other: {
                    type: 'link'
                },
                index: -1,
                index2: -1,
                index3: -1,
            };
        },
        created() {
            this.select = this.value;
        },
        provide(){
            return {
                linkCats: this
            }
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.label,
                    cat_id: select.value,
                    mch_id: select.mch_id,
                };
                if (this.index2 > -1) {
                    this.select.second_name = select.children[this.index2].label;
                    this.select.cat_id = select.children[this.index2].value;
                    if (this.index3 > -1) {
                        this.select.third_name = select.children[this.index2].children[this.index3].label;
                        this.select.cat_id = select.children[this.index2].children[this.index3].value;
                    }
                }
                this.$emit('confirm', this.select);
            },
            tableRowClassName({row, rowIndex}) {
                if (this.index === rowIndex) {
                    return 'current-row';
                }
                return '';
            },
            tableRowClassName2({row, rowIndex}) {
                if (this.index2 === rowIndex) {
                    return 'current-row';
                }
                return '';
            },
            tableRowClassName3({row, rowIndex}) {
                if (this.index3 === rowIndex) {
                    return 'current-row';
                }
                return '';
            },
            // 选择分类 表格行点击事件
            tableRowClick(index) {
                this.index = index;
                this.id = index;
                this.index2 = -1;
                this.index3 = -1;
            },
            tableRowClick2(index) {
                this.index2 = index;
                this.index3 = -1;
            },
            tableRowClick3(index) {
                this.index3 = index;
            },
        },
        computed: {
            isSelect() {
                return !this.select || this.select.length === 0
            }
        },
        watch: {
            type: {
                handler() {
                    this.id = -1;
                    this.select = null;
                    this.index2 = -1;
                    this.index3 = -1;
                    this.index1 = -1;
                },
                immediate: true
            }
        }
    });
</script>

