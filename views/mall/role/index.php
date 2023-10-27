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

    .el-table .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        width: 250px;
        margin-right: 40px;
        margin-bottom: 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus {
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

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/role', '角色列表');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/role', '添加角色');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native='search' size="small" placeholder="<?= \Yii::t('mall/role', '请输入角色名称');?>" v-model="keyword" clearable
                          @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        fixed
                        prop="id"
                        label="ID"
                        width="80">
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="<?= \Yii::t('mall/role', '角色名称');?>"
                        width="120">
                </el-table-column>
                <el-table-column
                        prop="remark"
                        label="<?= \Yii::t('mall/role', '备注');?>">
                </el-table-column>
                <el-table-column
                        prop="user.nickname"
                        label="<?= \Yii::t('mall/role', '创建者');?>"
                        width="150">
                </el-table-column>
                <el-table-column
                        prop="created_at"
                        label="<?= \Yii::t('mall/role', '添加日期');?>"
                        width="220">
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/role', '操作');?>"
                        fixed="right"
                        width="200">
                    <template slot-scope="scope">
                        <el-button type="text" @click="edit(scope.row.id)" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/role', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button type="text" @click="destroy(scope.row.id, scope.$index)" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/role', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                        @current-change="pagination"
                        background
                        hide-on-single-page
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
                pageCount: 0,
                keyword: '',
            };
        },
        methods: {
            search() {
                this.page = 1;
                this.getList();
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
                        r: 'mall/role/index',
                        page: self.page,
                        keyword: self.keyword
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
                        r: 'mall/role/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/role/edit',
                    });
                }
            },
            destroy(id, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('mall/role', '删除该条数据');?>?', '<?= \Yii::t('mall/role', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/role', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/role', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/role/destroy',
                        },
                        method: 'post',
                        data: {
                            id: id,
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
                    self.$message.info('<?= \Yii::t('mall/role', '已取消删除');?>')
                });
            }
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
