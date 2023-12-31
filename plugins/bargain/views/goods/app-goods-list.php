<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/12/13
 * Time: 15:32
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
$mchId = Yii::$app->user->identity->mch_id;
Yii::$app->loadViewComponent('goods/app-search');
Yii::$app->loadViewComponent('goods/app-batch');
?>

<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .app-goods-list .table-body .edit-sort-img {
        width: 14px;
        height: 14px;
        margin-left: 5px;
        cursor: pointer;
    }

    .app-goods-list .goods-cat .el-tag--mini {
        max-width: 60px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

</style>
<template id="app-goods-list">
    <div class="app-goods-list">
        <el-card v-loading="listLoading" class="box-card" shadow="never" style="border:0"
                 body-style="background-color: #f3f3f3;padding: 10px 0 0;">
            <div slot="header">
                <span>{{header_title}}</span>
                <el-button v-if="is_add_goods" style="float: right; margin: -5px 0" type="primary" size="small"
                           @click="edit">{{add_goods_title}}
                </el-button>
            </div>
            <div class="table-body">
                <app-search :tabs="tabs" :is-show-cat="isShowCat" :date-label="date_label" :new-search="search" @to-search="toSearch" :new-active-name="newActiveName"></app-search>
                <app-batch :choose-list="choose_list"
                           @to-search="getList"
                           :batch-update-status-url="batch_update_status_url"
                           :status-change-text="status_change_text"
                           :is-show-svip="isShowSvip"
                           :is-show-express="isShowExpress"
                           :is-show-integral="isShowIntegral"
                           :is-show-batch-button="isShowBatchButton"
                           :batch-list="batchList">
                    <template slot="batch" slot-scope="item">
                        <slot name="batch" :item="item.item"></slot>
                    </template>
                </app-batch>
                <el-table
                    ref="multipleTable"
                    :data="list"
                    border
                    style="width: 100%;margin-bottom: 15px"
                    @selection-change="handleSelectionChange"
                    @sort-change="sortChange">
                    <el-table-column type="selection" align="center" width="60"></el-table-column>
                    <el-table-column prop="id" label="<?= \Yii::t('plugins/bargain', '商品ID');?>" sortable="false" width="120"></el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bargain', '商品名称');?>" width="350">
                        <template slot-scope="scope">
                            <div flex="box:first">
                                <div style="padding-right: 10px;">
                                    <app-image mode="aspectFill" :src="scope.row.goodsWarehouse.cover_pic"></app-image>
                                </div>
                                <div flex="dir:top main:justify">
                                    <div v-if="goodsId != scope.row.id" flex="dir:left">
                                        <el-tooltip class="item"
                                                    effect="dark"
                                                    placement="top">
                                            <template slot="content">
                                                <div style="width: 320px;">{{scope.row.goodsWarehouse.name}}</div>
                                            </template>
                                            <div style="width: 250px;height:25px;overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">{{scope.row.goodsWarehouse.name}}</div>
                                        </el-tooltip>
                                    </div>
                                    <div style="height:25px;overflow: hidden; text-overflow:ellipsis; white-space: nowrap;">
                                        <?= \Yii::t('plugins/bargain', '规格');?>
                                        <el-tag size="small" style="margin-right: 5px"
                                                v-for="(item, index) in scope.row.attr_groups" :key="index">
                                            {{item.attr_group_name}}:{{item.attr_list[0].attr_name}}
                                        </el-tag>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="min_price" width="200" label="<?= \Yii::t('plugins/bargain', '最低价');?>"></el-table-column>
                    <el-table-column prop="goods_stock" label="<?= \Yii::t('plugins/bargain', '库存');?>" sortable="false" width="120">
                        <template slot-scope="scope">
                            <div v-if="scope.row.goods_stock > 0">{{scope.row.goods_stock}}</div>
                            <div v-else style="color: red;"><?= \Yii::t('plugins/bargain', '售罄');?></div>
                        </template>
                    </el-table-column>
                    <el-table-column prop="created_at" width="350" label="<?= \Yii::t('plugins/bargain', '活动时间');?>">
                        <template slot-scope="scope">
                            <div>{{scope.row.begin_time}}<?= \Yii::t('plugins/bargain', '至');?></div>
                            <div>{{scope.row.end_time}}</div>
                        </template>
                    </el-table-column>

                    <el-table-column label="<?= \Yii::t('plugins/bargain', '活动状态');?>" width="120" prop="status_text">
                        <template slot-scope="scope">
                            <el-tag v-if="scope.row.status_text == 1" type="info"><?= \Yii::t('plugins/bargain', '未开始');?></el-tag>
                            <el-tag v-else-if="scope.row.status_text == 2"><?= \Yii::t('plugins/bargain', '进行中');?></el-tag>
                            <el-tag v-else-if="scope.row.status_text == 3" type="danger"><?= \Yii::t('plugins/bargain', '已结束');?></el-tag>
                            <el-tag v-else-if="scope.row.status_text == 4" type="warning"><?= \Yii::t('plugins/bargain', '已下架');?></el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column
                        label="<?= \Yii::t('plugins/bargain', '操作');?>"
                        width="250"
                        fixed="right">
                        <template slot-scope="scope">
                            <slot name="action" :item="scope.row"></slot>
                            <template v-if="is_action">
                                <el-button @click="edit(scope.row)" type="text" circle size="mini" v-if="scope.row.status_text != 3">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bargain', '编辑');?>" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button @click="goto(scope.row)" type="text" circle size="mini" v-if="scope.row.status_text == 2 || scope.row.status_text == 4 || scope.row.status_text == 3">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bargain', '活动数据');?>" placement="top">
                                        <img src="statics/img/mall/share/order.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                        </template>
                    </el-table-column>
                </el-table>
                <div flex="dir:right">

                        <el-pagination
                            hide-on-single-page
                            @current-change="pagination"
                            background
                            :current-page="current_page"
                            layout="prev, pager, next, jumper"
                            :page-count="pageCount">
                        </el-pagination>

                </div>
            </div>
        </el-card>
    </div>
</template>
<script>
    Vue.component('app-goods-list', {
        template: '#app-goods-list',
        props: {
            goods_url: {
                type: String,
                default: 'mall/goods/index'
            },
            edit_goods_url: {
                type: String,
                default: 'mall/goods/edit'
            },
            info_goods_url: {
                type: String,
                default: ''
            },
            destroy_goods_url: {
                type: String,
                default: 'mall/goods/destroy'
            },
            edit_goods_sort_url: {
                type: String,
                default: 'mall/goods/edit-sort'
            },
            edit_goods_status_url: {
                type: String,
                default: 'mall/goods/switch-status'
            },
            batch_update_status_url: {
                type: String,
                default: 'mall/goods/batch-update-status'
            },
            is_edit_goods_name: {
                type: Boolean,
                default: false
            },
            is_action: {
                type: Boolean,
                default: true
            },
            actionWitch: {
                type: Number,
                default: 150
            },
            is_add_goods: {
                type: Boolean,
                default: true
            },
            status_change_text: {
                type: String,
                default: '',
            },
            add_goods_title: {
                type: String,
                default: "<?= \Yii::t('plugins/bargain', '添加商品');?>",
            },
            header_title: {
                type: String,
                default: "<?= \Yii::t('plugins/bargain', '商品列表');?>",
            },
            date_label: {
                type: String,
                default: '',
            },
            tabs: {
                type: Array,
                default: function () {
                    return [
                        {
                            name: "<?= \Yii::t('plugins/bargain', '全部');?>",
                            value: '-1'
                        },
                        {
                            name: "<?= \Yii::t('plugins/bargain', '未开始');?>",
                            value: '1'
                        },
                        {
                            name: "<?= \Yii::t('plugins/bargain', '进行中');?>",
                            value: '2'
                        },
                        {
                            name: "<?= \Yii::t('plugins/bargain', '已结束');?>",
                            value: '3'
                        },
                        {
                            name: "<?= \Yii::t('plugins/bargain', '已下架');?>",
                            value: '0'
                        },
                    ];
                }
            },
            /**
             * 批量设置参数
             * 具体参数看 app-batch 组件
             */
            batchList: Array,
            isShowSvip: true,// 批量设置超级会员卡是否显示
            isShowExpress: true, // 批量设置运费是否显示
            isShowIntegral:true,// 批量设置积分是否显示
            isShowBatchButton: true,//批量设置按钮是否显示
            isShowCat: true
        },
        data() {
            return {
                search: {
                    keyword: '',
                    status: '-1',
                    sort_prop: '',
                    sort_type: '',
                    cats: [],
                    date_start: null,
                    date_end: null,
                },
                list: [],
                listLoading: false,
                page: 1,
                pageCount: 0,
                current_page: 1,
                is_mch: <?= $mchId > 0 ? 1 : 0 ?>,
                mchMallSetting: {},
                choose_list: [],
                btnLoading: false,
                sort: 0,
                id: 0,// 应该无用了
                sort_goods_id: 0,

                goodsId: 0,
                goodsName: '',

                // 分类筛选
                dialogVisible: false,
                dialogLoading: false,
                options: [],
                cats: [],
                children: [],
                third: [],
                mch_id: '<?= $mchId ?>',
                newActiveName: '-1',
            };
        },
        created() {
            let self = this;
            if (this.is_mch) {
                this.getMchMallSetting();
            }
            if (getQuery('page') > 1) {
                this.page = getQuery('page');
            }

            // 搜索条件从缓存中获取
            let search = this.getCookie('search');
            if (search) {
                let newSearch = JSON.parse(search);
                this.search.keyword = newSearch.keyword;
                this.search.cats = newSearch.cats;
                this.search.date_start = newSearch.date_start;
                this.search.date_end = newSearch.date_end;
                self.tabs.forEach(function (item) {
                    if (item.value == newSearch.status) {
                        self.search.status = newSearch.status;
                        self.newActiveName = newSearch.status;
                    }
                })
            }
            this.getList();
        },
        methods: {
            editGoodsName(row) {
                this.goodsId = row.id;
                this.goodsName = row.goodsWarehouse.name;
            },
            changeGoodsName(row) {
                let self = this;
                request({
                    params: {
                        r: 'mall/goods/update-goods-name'
                    },
                    data: {
                        goods_id: self.goodsId,
                        goods_name: self.goodsName
                    },
                    method: 'post'
                }).then(e => {
                    if (e.data.code == 0) {
                        self.goodsId = null;
                        self.$message.success(e.data.msg);
                        self.getList();
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.$message.error(e.data.msg);
                });
            },
            // 全选单前页
            handleSelectionChange(val) {
                let self = this;
                self.choose_list = [];
                val.forEach(function (item) {
                    self.choose_list.push(item.id);
                })
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                self.saveSearch();
                request({
                    params: {
                        r: self.goods_url,
                        page: self.page,
                        search: self.search,
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pageCount = e.data.data.pagination.page_count;
                    self.current_page = e.data.data.pagination.current_page;
                }).catch(e => {
                    console.log(e);
                });
            },
            edit(row) {
                if (row.id) {
                    navigateTo({
                        r: this.edit_goods_url,
                        id: row.id,
                        mch_id: row.mch_id,
                        page: this.page,
                    });
                    this.saveSearch();
                } else {
                    navigateTo({
                        r: this.edit_goods_url,
                        page: this.page
                    });
                }
            },
            getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            },
            saveSearch() {
                document.cookie = "search=" + JSON.stringify(this.search);
            },
            destroy(row, index) {
                let self = this;
                self.$confirm("<?= \Yii::t('plugins/bargain', '删除该条数据, 是否继续');?>", "<?= \Yii::t('plugins/bargain', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/bargain', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/bargain', '取消');?>",
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: this.destroy_goods_url,
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.list.splice(index, 1);
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info("<?= \Yii::t('plugins/bargain', '已取消删除');?>")
                });
            },
            applyStatus(id) {
                let self = this;
                self.$confirm("<?= \Yii::t('plugins/bargain', '申请上架, 是否继续');?>", "<?= \Yii::t('plugins/bargain', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/bargain', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/bargain', '取消');?>",
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/goods/apply-status',
                        },
                        method: 'post',
                        data: {
                            id: id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.getList();
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info("<?= \Yii::t('plugins/bargain', '已取消申请');?>")
                });
            },
            // 商品上下架
            switchStatus(row) {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: this.edit_goods_status_url,
                    },
                    method: 'post',
                    data: {
                        status: row.status,
                        id: row.id,
                        mch_id: row.mch_id
                    }
                }).then(e => {
                    self.btnLoading = false;
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                    self.getList();
                }).catch(e => {
                    console.log(e);
                });
            },
            getMchMallSetting() {
                let self = this;
                request({
                    params: {
                        r: 'mall/mch/mch-setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.mchMallSetting = e.data.data.setting;
                }).catch(e => {
                    console.log(e);
                });
            },
            toSearch(searchData) {
                this.page = 1;
                this.search = searchData;
                this.getList();
            },
            // 排序排列
            sortChange(row) {
                if (row.prop) {
                    this.search.sort_prop = row.prop;
                    this.search.sort_type = row.order == "descending" ? 0 : 1;
                } else {
                    this.search.sort_prop = '';
                    this.search.sort_type = '';
                }
                this.getList();
            },
            quit() {
                this.sort_goods_id = null;
                this.goodsId = null;
            },
            editSort(row) {
                this.sort_goods_id = row.id;
                this.sort = row.sort;
                if (this.is_mch) {
                    this.sort = row.mchGoods.sort;
                }
            },
            changeSortSubmit(row) {
                let self = this;
                row.sort = self.sort;
                let route = self.edit_goods_sort_url;
                if (!row.sort || row.sort < 0) {
                    self.$message.warning("<?= \Yii::t('plugins/bargain', '排序值不能小于0');?>")
                    return;
                }
                if (this.is_mch) {
                    route = 'plugin/mch/mall/goods/edit-sort';
                    row.mchGoods.sort = self.sort;
                }
                request({
                    params: {
                        r: route
                    },
                    method: 'post',
                    data: {
                        id: row.id,
                        sort: row.sort,
                    }
                }).then(e => {
                    self.btnLoading = false;
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                        this.sort_goods_id = null;
                        self.getList();
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.$message.error(e.data.msg);
                    self.btnLoading = false;
                });
            },
            goto(row) {
                navigateTo({
                    r: this.info_goods_url,
                    id: row.id,
                    mch_id: row.mch_id,
                    page: this.page,
                });
                this.saveSearch();
            },
        },
    });
</script>


