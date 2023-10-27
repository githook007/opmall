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

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px;
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

    .table-body .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/mall_member', '会员等级');?></span>
                <div style="float: right; margin: -5px 0">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/mall_member', '添加会员等级');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small"
                          placeholder="<?= \Yii::t('mall/mall_member', '请输入会员名称搜索');?>"
                          v-model="keyword"
                          clearable
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
                        prop="id"
                        label="ID"
                        width="80">
                </el-table-column>
                <el-table-column
                        prop="level"
                        label="<?= \Yii::t('mall/mall_member', '会员等级');?>"
                        width="80">
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '会员等级');?>"
                        width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '折扣');?>">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.discount}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '购买价格(元)');?>">
                    <template slot-scope="scope">
                        <app-ellipsis v-if="scope.row.is_purchase == 1" :line="1">{{scope.row.price}}</app-ellipsis>
                        <app-ellipsis v-else :line="1"><?= \Yii::t('mall/mall_member', '未启用');?></app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '累计金额升级条件(元)');?>">
                    <template slot-scope="scope">
                        <app-ellipsis v-if="scope.row.auto_update == 1" :line="1">{{scope.row.money}}</app-ellipsis>
                        <app-ellipsis v-else :line="1"><?= \Yii::t('mall/mall_member', '未启用');?></app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '启用状态');?>"
                        width="80">
                    <template slot-scope="scope">
                        <el-switch
                                active-value="1"
                                inactive-value="0"
                                @change="switchStatus(scope.row)"
                                v-model="scope.row.status">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/mall_member', '操作');?>"
                        fixed="right"
                        width="180">
                    <template slot-scope="scope">
                        <el-button circle size="mini" type="text" @click="edit(scope.row.id)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/mall_member', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button circle size="mini" type="text" @click="destroy(scope.row, scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/mall_member', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
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
                keyword: '',
                listLoading: false,
                page: 1,
                pageCount: 0,
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
                        r: 'mall/mall-member/index',
                        page: self.page,
                        keyword: this.keyword
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
                        r: 'mall/mall-member/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/mall-member/edit',
                    });
                }
            },
            switchStatus(row) {
                let self = this;
                console.log(row.id);
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/mall-member/switch-status',
                    },
                    method: 'post',
                    data: {
                        id: row.id,
                    }
                }).then(e => {
                    self.listLoading = false;
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
            destroy(row, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('mall/mall_member', '删除该会员, 是否继续');?>?', '<?= \Yii::t('mall/mall_member', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/mall_member', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/mall_member', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/mall-member/destroy',
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                            self.list.splice(index, 1);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('mall/mall_member', '已取消删除');?>')
                });
            },
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
