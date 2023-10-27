<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('app-platform');
?>

<style>
    .app-search .tabs {
        margin-top: 20px;
    }

    .app-search .label {
        margin-right: 10px;
    }

    .app-search .item-box {
        margin-bottom: 10px;
        margin-right: 15px;
    }

    .app-search .clear-where {
        color: #419EFB;
        cursor: pointer;
    }

    .app-search .show-search-icon .el-input__inner {
        border-right: 0;
    }

    .app-search .show-search-icon .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .app-search .show-search-icon .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-search .show-search-icon .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-search .show-search-icon .el-input-group__append .el-button {
        padding: 0;
    }

    .app-search .show-search-icon .el-input-group__append .el-button {
        margin: 0;
    }

    .date-select {
        margin-right: 0!important;
        color: #909399;
    }

    .date-select .el-input__inner {
        background-color: #F5F7FA;
        width: 120px;
        border-right: 0;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        color: #909399;
    }
    .date-picker{
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>

<template id="app-search">
    <div class="app-search">
        <div flex="wrap:wrap cross:center">

            <div class="item-box" flex="dir:left cross:center" v-if="isShowPlatform">
                <div class="label"><?= \Yii::t('components/order', '所属平台');?></div>
                <app-platform v-model="search.platform" @change="toSearch"></app-platform>
            </div>

            <div class="item-box" flex="dir:left" v-if="isGoodsType && is_show_ecard">
                <div flex="cross:center" style="height: 32px;"><?= \Yii::t('components/order', '商品类型');?>：</div>
                <el-select @change="toSearch" v-model="search.type" style="width: 150px;" placeholder="<?= \Yii::t('components/order', '请选择');?>" size="small">
                    <el-option
                            label="<?= \Yii::t('components/order', '全部');?>"
                            value="">
                    </el-option>
                    <el-option
                            label="<?= \Yii::t('components/order', '实体商品');?>"
                            value="goods">
                    </el-option>
                    <el-option
                            label="<?= \Yii::t('components/order', '虚拟商品');?>"
                            value="ecard">
                    </el-option>
                </el-select>
            </div>

            <div v-if="isShowOrderPlugin" class="item-box" flex="dir:left cross:center">
                <div class="label"><?= \Yii::t('components/order', '订单类型');?></div>
                <el-select size="small" style="width: 120px" v-model="search.plugin" @change="toSearch"
                           placeholder="<?= \Yii::t('components/order', '订单类型');?>">
                    <el-option v-for="item in plugins" :key="item.sign" :label="item.name"
                               :value="item.sign">
                    </el-option>
                </el-select>
            </div>
            <div class="item-box" v-if="isShowOrderType" flex="dir:left cross:center">
                <div class="label"><?= \Yii::t('components/order', '配送方式');?></div>
                <el-select size="small" style="width: 120px" v-model="search.send_type" @change="toSearch"
                           placeholder="<?= \Yii::t('components/order', '配送方式');?>">
                    <el-option 
                        v-for="item in sendTypeList"
                        :key="item.value"
                        :label="item.name" 
                        :value="item.value">
                    </el-option>
                </el-select>
            </div>
            <slot name="extra"></slot>
            <div style="display: inherit;">
                <el-select class="item-box date-select" size="small" v-model="search.date_type" placeholder="<?= \Yii::t('components/order', '请选择');?>">
                <el-option :label="dateLabel" value="created_time"></el-option>
                <el-option v-for="item in dateTypeList" :key="item.value" :label="item.label" :value="item.value"></el-option>
                </el-select>
                <el-date-picker
                        class="item-box date-picker"
                        size="small"
                        @change="changeTime"
                        v-model="search.time"
                        type="datetimerange"
                        value-format="yyyy-MM-dd HH:mm:ss"
                        range-separator="<?= \Yii::t('components/order', '至');?>"
                        start-placeholder="<?= \Yii::t('components/order', '开始日期');?>"
                        end-placeholder="<?= \Yii::t('components/order', '结束日期');?>">
                </el-date-picker>
            </div>
            <div class="item-box label" :class="{'show-search-icon':!isSearchMenu}">
                <el-input :style="{ width: isSearchMenu ? '350px' : '250px' }" size="small" v-model="search.keyword" :placeholder="placeholder"  clearable
                          @clear="toSearch"
                          @keyup.enter.native="toSearch">
                    <el-select v-if="isSearchMenu" style="width: 120px" slot="prepend" v-model="search.keyword_1">
                        <el-option v-for="item in selectList" :key="item.value"
                                   :label="item.name"
                                   :value="item.value">
                        </el-option>
                    </el-select>
                    <el-button v-if="!isSearchMenu" slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                </el-input>
            </div>
            <div class="item-box" flex="cross:center">
                <div v-if="isShowClear" @click="clearWhere" class="div-box clear-where"><?= \Yii::t('components/order', '清空筛选条件');?></div>
            </div>
            <div v-if="isShowPrintInvoice && isSendTemplate" class="item-box" flex="dir:left cross:center">
                <el-button type="primary" size="small" @click="printInvoice"><?= \Yii::t('components/order', '打印发货单');?></el-button>
            </div>
        </div>
        <slot></slot>
        <div class="tabs" v-if="tabs.length > 0">
            <el-tabs v-model="newActiveName" @tab-click="handleClick">
                <el-tab-pane v-for="(item, index) in tabsList" :key="index" :label="item.name"
                             :name="item.value"></el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>

<script>
    Vue.component('app-search', {
        template: '#app-search',
        props: {
            selectList: {
                type: Array,
                default: function () {
                    return [
                        {value: '1', name: "<?= \Yii::t('components/order', '订单号');?>"},
                        {value: '9', name: "<?= \Yii::t('components/order', '商户单号');?>"},
                        {value: '2', name: "<?= \Yii::t('components/order', '用户名');?>"},
                        {value: '4', name: "<?= \Yii::t('components/order', '用户ID');?>"},
                        {value: '5', name: "<?= \Yii::t('components/order', '商品名称');?>"},
                        {value: '3', name: "<?= \Yii::t('components/order', '收件人');?>"},
                        {value: '6', name: "<?= \Yii::t('components/order', '收件人电话');?>x"},
                        {value: '7', name: "<?= \Yii::t('components/order', '门店名称');?>"}
                    ]
                }
            },
            num_list: Object,
            sendTypeList: {
                type: Array,
                default: function() {
                    return [
                        {value: -1, name: "<?= \Yii::t('components/order', '全部订单');?>"},
                        {value: 0, name: "<?= \Yii::t('components/order', '快递配送');?>"},
                        {value: 1, name: "<?= \Yii::t('components/order', '到店核销');?>"},
                        {value: 2, name: "<?= \Yii::t('components/order', '同城配送');?>"},
                    ]
                }
            },
            tabs: {
                type: Array,
                default: function () {
                    return [
                        {value: '-1', name: "<?= \Yii::t('components/order', '全部');?>"},
                        {value: '0', name: "<?= \Yii::t('components/order', '未付款');?>"},
                        {value: '1', name: "<?= \Yii::t('components/order', '待发货');?>"},
                        {value: '2', name: '<?= \Yii::t('components/order', '待收货');?>'},
                        {value: '3', name: "<?= \Yii::t('components/order', '已完成');?>"},
                        {value: '4', name: "<?= \Yii::t('components/order', '待处理');?>"},
                        {value: '5', name: "<?= \Yii::t('components/order', '已取消');?>"},
                        {value: '7', name: "<?= \Yii::t('components/order', '回收站');?>"},
                    ]
                }
            },
            activeName: {
                type: String,
                default: '-1',
            },
            plugins: {
                type: Array,
                default: function () {
                    return [
                        {
                            name: "<?= \Yii::t('components/order', '全部订单');?>",
                            sign: 'all',
                        }
                    ];
                }
            },
            dateTypeList: {
                type: Array,
                default: function () {
                    return [];
                }
            },
            isShowOrderType: {
                type: Boolean,
                default: true
            },
            isShowOrderPlugin: {
                type: Boolean,
                default: false
            },
            newSearch: {
                type: Object,
                default: function () {
                    return {
                        time: null,
                        keyword: '',
                        keyword_1: '1',
                        date_start: '',
                        date_end: '',
                        platform: '',
                        status: '',
                        plugin: 'all',
                        send_type: -1,
                        type: '',
                        date_type: 'created_time',
                    }
                }
            },
            dateLabel: {
                type: String,
                default: "<?= \Yii::t('components/order', '下单时间');?>"
            },
            placeholder: {
                type: String,
                default: "<?= \Yii::t('components/order', '请输入搜索内容');?>"
            },
            isShowPlatform: {
                type: Boolean,
                default: true
            },
            isShowPrintInvoice: {
                type: Boolean,
                default: false
            },
            isSendTemplate: {
                type: Boolean,
                default: false
            },
            isGoodsType: {
                type: Boolean,
                default: false
            },
            is_show_ecard: {
                type: Boolean,
                default: false
            },
            isSearchMenu: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {
                search: {},
                newActiveName: null,
                isShowClear: false,
            }
        },
        computed: {
            tabsList() {
                let list = JSON.parse(JSON.stringify(this.tabs));
                if(this.num_list && list.length > 0) {
                    for(let index in this.num_list) {
                        for(let item of list) {
                            if(index == item.value) {
                                item.name += "("+ this.num_list[index] +")"
                            }
                        }
                    }
                }
                return list
            }
        },
        methods: {
            printInvoice() {
                this.$emit('print');
            },
            // 日期搜索
            changeTime() {
                if (this.search.time) {
                    this.search.date_start = this.search.time[0];
                    this.search.date_end = this.search.time[1];
                } else {
                    this.search.date_start = null;
                    this.search.date_end = null;
                }
                this.toSearch();
            },
            toSearch() {
                this.search.page = 1;
                console.log(this.search)
                this.$emit('search', this.search);
                this.checkSearch();
            },
            handleClick(res) {
                this.search.status = this.newActiveName;
                this.toSearch();
            },
            clearWhere() {
                this.search.keyword = '';
                this.search.date_start = null;
                this.search.date_end = null;
                this.search.time = null;
                this.search.platform = '';
                this.search.send_type = -1;
                this.search.plugin = 'all';
                this.toSearch();
            },
            checkSearch() {
                if (this.search.keyword || (this.search.date_start && this.search.date_end)
                    || this.search.plugin != 'all' || this.search.send_type != -1
                    || this.search.platform) {
                    this.isShowClear = true;
                } else {
                    this.isShowClear = false;
                }
            }
        },
        created() {
            this.search = this.newSearch;
            if(this.selectList[0].value != this.newSearch.keyword_1) {
                this.newSearch.keyword_1 = this.selectList[0].value
            }
            this.newActiveName = this.activeName;
            this.search.status = this.newActiveName;
            this.checkSearch();
        }
    })
</script>