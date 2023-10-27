<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .el-button.change-quit,.el-button.change-success {
        padding: 10px 5px;
        display: block;
        float: right;
    }

    .input-item {
        width: 250px;
        margin: 0 0 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover{
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus{
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .el-input-group__append .el-button {
        margin: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/home_nav', '导航图标列表');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/home_nav', '添加导航图标');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input size="small" placeholder="<?= \Yii::t('mall/home_nav', '请输入搜索内容');?>" v-model="keyword" clearable
                          @clear="search" @keyup.enter.native="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        prop="id"
                        label="ID"
                        width="100">
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '名称');?>"
                        width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '导航图标');?>"
                        width="80">
                    <template slot-scope="scope">
                        <app-image width="35" height="35" mode="aspectFill" :src="scope.row.icon_url"></app-image>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '导航链接');?>">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.url}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '排序');?>"
                        width="180">
                    <template slot-scope="scope">
                        <div v-if="id != scope.row.id">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/home_nav', '排序');?>" placement="top">
                                <span>{{scope.row.sort}}</span>
                            </el-tooltip>
                            <el-button class="edit-sort" type="text" @click="editSort(scope.row)">
                                <img src="statics/img/mall/order/edit.png" alt="">
                            </el-button>
                        </div>
                        <div style="display: flex;align-items: center" v-else>
                            <el-input style="min-width: 70px" type="number" size="mini" class="change" v-model="sort"
                                      autocomplete="off"></el-input>
                            <el-button class="change-quit" type="text" style="color: #F56C6C;padding: 0 5px" icon="el-icon-error"
                                       circle @click="quit()"></el-button>
                            <el-button class="change-success" type="text" style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                       icon="el-icon-success" circle @click="change(scope.row)">
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '是否显示');?>"
                        width="120">
                    <template slot-scope="scope">
                        <el-switch
                                @change="switchStatus(scope)"
                                v-model="scope.row.status"
                                active-value="1"
                                inactive-value="0">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/home_nav', '操作');?>"
                        width="220">
                    <template slot-scope="scope">
                        <el-button @click="edit(scope.row.id)" size="small" type="text" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/home_nav', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button @click="destroy(scope.row, scope.$index)" size="small" type="text" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/home_nav', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        @current-change="pagination"
                        background
                        layout="prev, pager, next, jumper"
                        :page-count="pageCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                listLoading: false,
                page: 1,
                id: null,
                keyword: '',
                pageCount: 0,
                sort: 0,
            };
        },
        methods: {
            search() {
                this.page = 1;
                this.getList();
            },

            editSort(row) {
                this.id = row.id;
                this.sort = row.sort;
            },

            quit() {
                this.id = null;
            },

            change(row) {
                let self = this;
                row.sort = self.sort;
                request({
                    params: {
                        r: 'mall/home-nav/edit'
                    },
                    method: 'post',
                    data: {
                        form: row,
                    }
                }).then(e => {
                    self.btnLoading = false;
                    if (e.data.code == 0) {
                        self.$message.success(e.data.msg);
                        this.id = null;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.$message.error(e.data.msg);
                    self.btnLoading = false;
                });
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/home-nav/index',
                        keyword: this.keyword,
                        page: self.page
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pageCount = e.data.data.pagination.page_count;
                }).catch(e => {
                    console.log(e);
                });
            },
            edit(id) {
                if (id) {
                    navigateTo({
                        r: 'mall/home-nav/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/home-nav/edit',
                    });
                }
            },
            destroy(row, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('mall/home_nav', '删除该条数据');?>?', '<?= \Yii::t('mall/home_nav', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/home_nav', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/home_nav', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/home-nav/destroy',
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
                    self.$message.info('<?= \Yii::t('mall/home_nav', '已取消删除');?>')
                });
            },
            switchStatus(scope) {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/home-nav/status',
                    },
                    method: 'post',
                    data: {
                        status: scope.row.status,
                        id: scope.row.id,
                    }
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code == 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
