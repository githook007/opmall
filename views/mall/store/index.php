<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .set-el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
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

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/store', '门店列表');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/store', '添加门店');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('mall/store', '请输入门店名称搜索');?>" v-model="keyword" clearable @clear="search">
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
                        label="<?= \Yii::t('mall/store', '门店名称');?>">
                    <template slot-scope="scope">
                        <app-ellipsis :line="2">{{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/store', '封面图');?>"
                        width="80">
                    <template slot-scope="scope">
                        <app-image mode="aspectFill" :src="scope.row.cover_url"></app-image>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/store', '联系方式');?>"
                        width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.mobile}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/store', '门店地址');?>"
                        width="220">
                    <template slot-scope="scope">
                        <app-ellipsis :line="2">{{scope.row.address}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/store', '经纬度');?>"
                        width="220">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.latitude_longitude}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/store', '默认门店');?>" width="120">
                    <template slot-scope="scope">
                        <el-switch
                                @change="isDefault(scope)"
                                v-model="scope.row.is_default"
                                active-value="1"
                                inactive-value="0">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="<?= \Yii::t('mall/store', '是否启用');?>" width="120">
                    <template slot-scope="scope">
                        <el-switch active-value="1" inactive-value="0" @change="switchStatus(scope.row)"
                                   v-model="scope.row.status">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/store', '操作');?>"
                        fixed="right"
                        width="220" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="text" class="set-el-button" size="mini" circle @click="edit(scope.row.id)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/store', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button type="text" class="set-el-button" size="mini" circle @click="destroy(scope.row, scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/store', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                        background
                        hide-on-single-page
                        :page-size="pagination.pageSize"
                        @current-change="pageChange"
                        layout="prev, pager, next, jumper"
                        :total="pagination.total_count">
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
                pagination: 0,
                keyword: '',
            };
        },
        methods: {
            switchStatus(row) {
                let self = this;
                request({
                    params: {
                        r: 'mall/store/switch-status',
                    },
                    method: 'post',
                    data: {
                        status: row.status,
                        id: row.id
                    }
                }).then(e => {
                    if (e.data.code === 0) {
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            search() {
                this.page = 1;
                this.getList();
            },
            pageChange(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/store/index',
                        page: self.page,
                        keyword: self.keyword,
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pagination = e.data.data.pagination;
                }).catch(e => {
                    console.log(e);
                });
            },
            edit(id) {
                if (id) {
                    navigateTo({
                        r: 'mall/store/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/store/edit',
                    });
                }
            },
            destroy(row, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('mall/store', '删除该条数据');?>?', '<?= \Yii::t('mall/store', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/store', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/store', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/store/destroy',
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
                    self.$message.info('<?= \Yii::t('mall/store', '已取消删除');?>')
                });
            },
            // 默认门店
            isDefault(scope) {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/store/switch-default',
                    },
                    method: 'post',
                    data: {
                        id: scope.row.id,
                        is_default: scope.row.is_default,
                    }
                }).then(e => {
                    self.$message.success(e.data.msg);
                    self.getList();
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
