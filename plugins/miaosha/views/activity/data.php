<?php
/**
 * Created by PhpStorm.
 * User: fjt
 * Date: 2019/12/30
 * Time: 11:46
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('goods/app-search');
Yii::$app->loadViewComponent('goods/app-batch');
?>
<style>
    .el-card__header {
        background-color: #ffffff;
    }
    .shelves {
        background-color: #f5f7fa;
        height: 50px;
        line-height: 50px;
        padding-left: 20px;
        margin-top: 20px;
    }
    .table-body {
        padding: 20px;
        background-color: #fff;
    }
    .el-form-item--small.el-form-item {
        margin-bottom: 0;
    }
    .app-search .search-box {
        margin-bottom: 10px;
    }

    .app-search .div-box {
        margin-right: 10px;
    }

    .app-search .input-item {
        display: inline-block;
        width: 250px;
    }

    .app-search .input-item .el-input__inner {
        border-right: 0;
    }

    .app-search .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-search .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .app-search .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .app-search .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .app-search .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .app-search .clear-where {
        color: #419EFB;
        cursor: pointer;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" v-loading="detailLoading" style="border:0;background-color: #f3f3f3;" body-style="background-color: #f3f3f3;padding: 0 0 0 0;">
        <div slot="header">
            <el-breadcrumb>
                <el-breadcrumb-item><?= \Yii::t('plugins/miaosha', '活动数据');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-card style="margin-top: 10px">
            <div class="app-search">
                <el-tabs v-if="tabs.length > 0" v-model="newActiveName" @tab-click="handleClick">
                    <el-tab-pane v-for="(item, index) in tabs" :key="index" :label="item.name" :name="item.value"></el-tab-pane>
                </el-tabs>
                <div class="search-box" flex="dir:left cross-center">
                    <div class="div-box" flex="dir:left">
                        <div flex="cross:center" style="height: 32px;"><?= \Yii::t('plugins/miaosha', '场次时间');?></div>
                        <el-date-picker
                                size="small"
                                @change="changeTime"
                                v-model="datetime"
                                type="daterange"
                                value-format="yyyy-MM-dd"
                                range-separator="<?= \Yii::t('plugins/miaosha', '至');?>"
                                start-placeholder="<?= \Yii::t('plugins/miaosha', '开始日期');?>"
                                end-placeholder="<?= \Yii::t('plugins/miaosha', '结束日期');?>">
                        </el-date-picker>
                    </div>
                    <div class="input-item div-box" flex="cross-center">
                        <div>
                            <el-input @keyup.enter.native="toSearch" size="small" placeholder="<?= \Yii::t('plugins/miaosha', '请输入商品名称搜索');?>"
                                      v-model="search.keyword" clearable
                                      @clear="toSearch">
                                <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                            </el-input>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shelves">
                <el-button size="mini" @click="operatingActivity(0)"><?= \Yii::t('plugins/miaosha', '上架');?></el-button>
                <el-button size="mini" @click="operatingActivity(1)"><?= \Yii::t('plugins/miaosha', '下架');?></el-button>
                <el-button size="mini" @click="operatingActivity(2)"><?= \Yii::t('plugins/miaosha', '删除');?></el-button>
            </div>
            <el-table
                    ref="multipleTable"
                    :data="list"
                    tooltip-effect="dark"
                    style="width: 100%"
                    border
                    v-loading="listLoading"
                    @selection-change="selectionChange">

                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '商品名称');?>"
                        width="500">
                    <template slot-scope="scope">
                        <div flex="box:first">
                            <div style="padding-right: 10px;">
                                <app-image mode="aspectFill" :src="scope.row.goods_cover_pic"></app-image>
                            </div>
                            <div>
                                <app-ellipsis :line="1" v-if="scope.row.goods_name">{{scope.row.goods_name}}</app-ellipsis>
                                <div style="color: #ffaf34;"><?= \Yii::t('plugins/miaosha', '秒杀价');?>{{scope.row.goods_miaosha_price}}</div>
                            </div>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '场次');?>"
                        prop="date_time"
                        show-overflow-tooltip>
                </el-table-column>

                <el-table-column
                        prop="payment_num"
                        label="<?= \Yii::t('plugins/miaosha', '秒杀件数');?>"
                        width="150"
                        show-overflow-tooltip
                >
                </el-table-column>

                <el-table-column
                        prop="payment_amount"
                        label="<?= \Yii::t('plugins/miaosha', '实付金额');?>"
                        show-overflow-tooltip>
                </el-table-column>

                <el-table-column
                        prop="activity_status"
                        label="<?= \Yii::t('plugins/miaosha', '活动状态');?>"
                        width="100"
                        show-overflow-tooltip>
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '未开始');?>'" type="info"><?= \Yii::t('plugins/miaosha', '未开始');?></el-tag>
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '进行中');?>'"><?= \Yii::t('plugins/miaosha', '进行中');?></el-tag>
                        <el-tag  v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '下架中');?>'" type="warning"><?= \Yii::t('plugins/miaosha', '下架中');?></el-tag>
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '已结束');?>'" type="danger"><?= \Yii::t('plugins/miaosha', '已结束');?></el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '操作');?>"
                        fixed="right"
                >
                    <template slot-scope="scope" v-if="scope.row.is_show_edit === 1">
                        <el-button  type="text" circle size="mini" @click="edit(scope.row)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/miaosha', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                        hide-on-single-page
                        @current-change="pagination"
                        background
                        layout="prev, pager, next, jumper"
                        :page-count="page_count">
                </el-pagination>
        </el-card>
</div>
</el-card>
</div>


<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                search: {
                    date_start: '',
                    date_end: '',
                    keyword: '',
                    status: 1
                },
                datetime: [],
                selection_list: [],
                newActiveName: '1',
                tabs: [
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '全部');?>',
                        value: '1'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '未开始');?>',
                        value: '2'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '进行中');?>',
                        value: '3'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '已结束');?>',
                        value: '4'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '下架中');?>',
                        value: '5'
                    },
                ],
                page_count: 0,

                form: {},
                activity_id: -1,
                detail: {},
                current_page: 0,
                page: 1,

                detailLoading: false,
                listLoading: false,
                goods_warehouse_id: 0,
            };
        },
        created() {
            this.activity_id = getQuery('id');
            this.getList();
        },
        methods: {
            async getList() {
                this.listLoading = true;
                const res = await request({
                    params: {
                        r: '/plugin/miaosha/mall/activity/activity-goods',
                        activity_id: this.activity_id,
                        search: {
                            keyword: this.search.keyword,
                            date_start: this.search.date_start,
                            date_end: this.search.date_end,
                            status: this.search.status,
                        },
                        page: this.page,
                    },
                    method: 'get'
                });
                if (res.data.code === 0) {
                    let { list, pagination } = res.data.data;
                    this.page_count = pagination.page_count;
                    this.list = list;
                }
                this.listLoading = false;
            },

            toSearch(searchData) {
                this.page = 1;
                this.getList();
            },

            async operatingActivity(status) {
                if (this.selection_list.length === 0) return;

                if (status === 0) {
                    this.$confirm('<?= \Yii::t('plugins/miaosha', '此操作将上架活动');?>', '<?= \Yii::t('plugins/miaosha', '提示');?>', {
                        confirmButtonText: '<?= \Yii::t('plugins/miaosha', '确定');?>',
                        cancelButtonText: '<?= \Yii::t('plugins/miaosha', '取消');?>',
                        type: 'warning'
                    }).then(() => {
                        request({
                            params: {
                                r: `plugin/miaosha/mall/goods/batch-update-status`,
                                page: this.page,
                                search: this.search
                            },
                            method: 'post',
                            data: {
                                is_all:  0,
                                batch_ids: this.selection_list,
                                status: 1,
                                plugin_sign: 'miaosha',
                            }
                        }).then((res) => {
                            if (res.data.code === 0) {
                                this.getList();
                            }
                        });
                    });
                } else if (status === 1) {
                    this.$confirm('<?= \Yii::t('plugins/miaosha', '此操作将下架活动');?>', '<?= \Yii::t('plugins/miaosha', '提示');?>', {
                        confirmButtonText: '<?= \Yii::t('plugins/miaosha', '确定');?>',
                        cancelButtonText: '<?= \Yii::t('plugins/miaosha', '取消');?>',
                        type: 'warning'
                    }).then(() => {
                        request({
                            params: {
                                r: 'plugin/miaosha/mall/goods/batch-update-status',
                                page: this.page,
                                search: this.search
                            },
                            method: 'post',
                            data: {
                                is_all:  0,
                                batch_ids: this.selection_list,
                                status: 0,
                                plugin_sign: 'miaosha',
                            }
                        }).then(res => {
                            if (res.data.code === 0) {
                                this.getList();
                            }
                        })
                    });

                } else if (status === 2) {
                    this.$confirm('<?= \Yii::t('plugins/miaosha', '此操作将删除活动');?>', '<?= \Yii::t('plugins/miaosha', '提示');?>', {
                        confirmButtonText: '<?= \Yii::t('plugins/miaosha', '确定');?>',
                        cancelButtonText: '<?= \Yii::t('plugins/miaosha', '取消');?>',
                        type: 'warning'
                    }).then(() => {
                        request({
                            params: {
                                r: `plugin/miaosha/mall/goods/batch-miaosha-destroy`,
                                page: this.page,
                                search: this.search
                            },
                            method: 'post',
                            data: {
                                is_all:  0,
                                batch_ids: this.selection_list,
                                goods_warehouse_id: this.goods_warehouse_id,
                                plugin_sign: 'miaosha'
                            }
                        }).then(res => {
                            if (res.data.code === 0) {
                                this.getList();
                            }
                        });
                    });
                }
            },

            selectionChange(list) {
                this.selection_list = [];
                list.map((item) => {
                    this.selection_list.push(item.id);
                });
            },

            pagination(currentPage) {
                this.page = currentPage;
                this.getList();
            },

            edit(row) {
                navigateTo({
                    r: `plugin/miaosha/mall/activity/edit`,
                    id: row.miaosha_goods_id,
                    sessions_id: row.id,
                    page: this.page
                });
            },

            changeTime() {
                if (this.datetime) {
                    this.search.date_start = this.datetime[0];
                    this.search.date_end = this.datetime[1];
                } else {
                    this.search.date_start = null;
                    this.search.date_end = null;
                }
                this.toSearch();
            },

            handleClick() {
                this.search.status = this.newActiveName;
                this.toSearch();
            },
        }
    });
</script>
