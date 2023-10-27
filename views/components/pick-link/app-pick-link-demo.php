<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/15
 * Time: 3:51 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('layout/app-pagination');
?>
<style>
    .set-el-button {
        padding: 0;
        margin-left: 5px;
    }
</style>
<template id="app-pick-link-demo">
    <div>
        <el-dialog class="app-pick-link-demo" :title="title"
                   :visible.sync="dialogFormVisible"
                   @opened="dialogOpened"
                   :close-on-click-modal="false"
                   :close-on-press-escape="false"
                   append-to-body>
            <el-select
                    v-if="searchType === 'cat'"
                    v-model="search.keyword"
                    filterable
                    clearable
                    :loading="loading"
                    size="small"
                    @change="searchChange"
                    @clear="filterMethod"
            >
                <el-option
                        v-for="(item,index) in allOption"
                        :key="index"
                        :label="item.label"
                        :value="item.value">
                </el-option>
            </el-select>
            <el-input  v-else-if="isShowSearch" v-model="search.keyword" size="mini" :placeholder="placeholder"
                       @keyup.enter.native="dialogOpened(1)" clearable @clear="clear">
                <el-button slot="append" @click="dialogOpened(1)"><?= \Yii::t('components/other', '搜索');?></el-button>
            </el-input>
            <slot name="table" v-bind:table="tableData"><?= \Yii::t('components/other', '等待数据加载');?>...</slot>
            <div class="dialog-footer" flex="dir:left" style="justify-content: space-between">
                <div>
                    <app-pagination :pagination="pagination" @change="pageChange"></app-pagination>
                </div>
                <div style="margin-top: 25px">
                    <el-button size="mini" type="primary" @click="confirm"><?= \Yii::t('components/other', '确定');?></el-button>
                </div>
            </div>
        </el-dialog>
        <div @click="dialogFormVisible = !dialogFormVisible" style="display: inline-block;"
             v-if="value">
            <el-button size="mini"><?= \Yii::t('components/other', '选择');?></el-button>
        </div>
        <div flex="dir:left cross:center" v-else>
            <el-tooltip effect="dark" placement="top">
                <div slot="content">{{tooltip}}
                </div>
                <div flex="dir:left cross:center"
                     style="padding: 6px 16px; border: 1px solid #DCDFE6; border-radius: 3px">
                    <div style="max-width: 70px;height: 16px;line-height: 16px">
                        <app-ellipsis :line="1">{{select.show_name}}</app-ellipsis>
                    </div>
                    <template v-if="select.second_name">
                        <img width="16px" height="16px" src="statics/img/mall/customer-right.png" alt="">
                        <div style="width: 39px;height: 16px;line-height: 16px">
                            <app-ellipsis :line="1">{{select.second_name}}</app-ellipsis>
                        </div>
                    </template>
                    <template v-if="select.third_name">
                        <img width="16px" height="16px" src="statics/img/mall/customer-right.png" alt="">
                        <div style="width: 39px;height: 16px;line-height: 16px">
                            <app-ellipsis :line="1">{{select.third_name}}</app-ellipsis>
                        </div>
                    </template>
                </div>
            </el-tooltip>
            <el-button class="set-el-button" size="mini" type="text" @click="dialogFormVisible = !dialogFormVisible">
                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('components/other', '编辑');?>" placement="top">
                    <img src="statics/img/mall/customer-edit.png" alt="">
                </el-tooltip>
            </el-button>
        </div>
    </div>
</template>
<script>
    Vue.component('app-pick-link-demo', {
        template: '#app-pick-link-demo',
        props: {
            value: {
                type: Object | null,
                default: null
            },
            title: String,
            placeholder: String,
            listUrl: String,
            id: Number,
            isShowSearch: {
                type: Boolean,
                default: true
            },
            searchType: String,
            other: {
                type: Object,
                default: null
            },
            select: Object
        },
        inject: ['linkCats'],
        data() {
            return {
                dialogFormVisible: false,
                list: [],
                listLoading: false,
                search: {
                    keyword: ''
                },
                allList: [],
                allOption: [],
                searchOption: [],
                loading: false,
                pagination: {},
            };
        },
        created() {
        },
        methods: {
            searchChange(arr) {
                if (arr.length == 1) {
                    let item = JSON.parse(JSON.stringify(this.allList[arr[0]]));
                    item.children = []
                    this.linkCats.index = 0;
                    this.linkCats.index2 = -1;
                    this.linkCats.index3 = -1;
                    this.list = [item];
                }
                if (arr.length == 2) {
                    let item = JSON.parse(JSON.stringify(this.allList[arr[0]]));
                    item.children = [item.children[arr[1]]]
                    if(item.children[0] && item.children[0].children){
                        item.children[0].children = [];
                    }

                    this.list = [item]
                    this.linkCats.index = 0;
                    this.linkCats.index2 = 0;
                    this.linkCats.index3 = -1;
                }
                if (arr.length == 3) {
                    let item = JSON.parse(JSON.stringify(this.allList[arr[0]]));
                    item.children = [item.children[arr[1]]];
                    item.children[0].children = [item.children[0].children[arr[2]]]
                    if(item.children[0].children[0].children){
                        item.children[0].children[0].children = [];
                    }
                    this.linkCats.index = 0;
                    this.linkCats.index2 = 0;
                    this.linkCats.index3 = 0;
                    this.list = [item]
                }
            },
            filterMethod(keyword = '') {
                const self = this;

                if(keyword == ''){
                    self.list = self.allList;
                }
                let allList = [];
                for (let index1 in self.allList) {
                    let item1 = self.allList[index1];
                    if (item1.children && item1.children.length) {
                        for (let index2 in item1.children) {
                            let item2 = item1.children[index2];
                            if (item2.children && item2.children.length) {
                                for (let index3 in item2.children) {
                                    let item3 = item2.children[index3];
                                    let keys = [
                                        item1.label,
                                        item2.label,
                                        item3.label
                                    ]
                                    if (keyword && keys.join(" ").indexOf(keyword) === -1) {
                                        continue;
                                    }
                                    allList.push({
                                        label: keys.join(" > "),
                                        value: [index1, index2, index3]
                                    })
                                }
                                let keys = [
                                    item1.label,
                                    item2.label,
                                ]
                                allList.push({
                                    label: keys.join(" > "),
                                    value: [index1, index2]
                                })
                            } else {
                                let keys = [
                                    item1.label,
                                    item2.label,
                                ]
                                if (keyword && keys.join(" ").indexOf(keyword) === -1) {
                                    continue;
                                }
                                allList.push({
                                    label: keys.join(" > "),
                                    value: [index1, index2]
                                });
                            }
                        }

                        let keys = [
                            item1.label,
                        ]
                        allList.push({
                            label: keys.join(" > "),
                            value: [index1]
                        })
                    } else {
                        let keys = [
                            item1.label,
                        ]
                        if (keyword && keys.join(" ").indexOf(keyword) === -1) {
                            continue;
                        }
                        allList.push({
                            label: keys.join(" > "),
                            value: [index1],
                        });
                    }
                }
                self.allOption = allList;
            },
            dialogOpened(page = 1) {
                this.listLoading = true;
                if(this.searchType === 'cat'){
                    this.search.keyword = '';
                    this.allOption = [];
                }
                let params = {
                    r: this.listUrl,
                    search: this.search,
                    page: page,
                    keyword: this.search.keyword
                };
                if (this.other) {
                    let other = this.other;
                    if (other.search) {
                        other.search = Object.assign({}, other.search, params.search);
                    }
                    params = Object.assign({}, params, other);
                }
                this.$request({
                    params: params
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.allList = e.data.data.list;
                        this.list = e.data.data.list
                        if(this.searchType === 'cat'){
                            this.filterMethod();
                        }
                        this.pagination = e.data.data.pagination
                    }
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            confirm() {
                let select = '';
                if(this.searchType === 'cat'){
                    select = this.list[this.linkCats.index];
                } else {
                    select = this.list[this.id];
                }
                if (select) {
                    console.log(select);
                    this.$emit('confirm', select);
                }
                this.dialogFormVisible = false;
            },
            pageChange(currentPage) {
                this.dialogOpened(currentPage);
            },
            clear() {
                this.search.keyword = '';
                this.dialogOpened(1);
            }
        },
        computed: {
            tableData() {
                return {
                    list: this.list,
                    listLoading: this.listLoading
                }
            },
            tooltip() {
                let tooltip = this.select.show_name;
                if (this.select.second_name) {
                    tooltip += '/' + this.select.second_name
                }
                if (this.select.third_name) {
                    tooltip += '/' + this.select.third_name
                }
                return tooltip;
            }
        }
    });
</script>


