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
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="listLoading" class="box-card">
        <div slot="header">
            <span><?= \Yii::t('plugins/shopping', 'shopping_z1');?></span>
            <el-button style="float: right; margin: -5px 0" type="primary" size="small" @click="edit"><?= \Yii::t('plugins/shopping', 'shopping_z2');?></el-button>
        </div>
        <div class="table-body">
            <el-form @submit.native.prevent="commonSearch" size="small" :inline="true" :model="search">
                <el-form-item style="margin-bottom: 0">
                    <div class="input-item">
                        <el-input size="small" placeholder="<?= \Yii::t('plugins/shopping', 'shopping_z3');?>" v-model="search.keyword" clearable @clear='commonSearch'>
                            <el-button slot="append" icon="el-icon-search" @click="commonSearch"></el-button>
                        </el-input>
                    </div>
                </el-form-item>
            </el-form>
            <el-table :data="list" border style="width: 100%;margin-bottom: 15px"
                      @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="50"></el-table-column>
                <el-table-column prop="id" width="100" label="ID"></el-table-column>
                <el-table-column prop="order_no" width="320" label="<?= \Yii::t('plugins/shopping', 'shopping_z5');?>"></el-table-column>
                <el-table-column prop="total_price" label="<?= \Yii::t('plugins/shopping', 'shopping_z4');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/shopping', 'shopping_z6');?>" width='200'>
                    <template slot-scope="scope">
                        <el-tag type="info">{{scope.row.order_status}}</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/shopping', 'shopping_z7');?>" width='500'>
                    <template slot-scope="scope">
                        <div v-for="item in scope.row.detail" flex="box:first">
                            <div style="padding-right: 10px">
                                <app-image width="25px" height="25px" mode="aspectFill"
                                           :src="item.goods_info.goods_attr.pic_url"></app-image>
                            </div>
                            <div>
                                <app-ellipsis :line="1">{{item.goods.goodsWarehouse.name}}</app-ellipsis>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('plugins/shopping', 'shopping_z8');?>"
                        width="200">
                    <template slot-scope="scope">
                        <el-button @click="shopping(scope.row.id)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/shopping', 'shopping_z9');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="box:last cross:center" style="margin-top: 20px;">
                <div style="visibility: hidden">
                    <el-button plain type="danger" @click="batchAdd" size="small"><?= \Yii::t('plugins/shopping', 'shopping_z10');?></el-button>
                </div>
                <div>
                    <el-pagination
                            v-if="pageCount > 0"
                            @current-change="pagination"
                            background
                            layout="prev, pager, next, jumper"
                            :page-count="pageCount">
                    </el-pagination>
                </div>
            </div>
        </div>
        <el-dialog title="<?= \Yii::t('plugins/shopping', 'shopping_z11');?>" :visible.sync="progressVisible">
            <div style="margin: 10px 0;">
                <?= \Yii::t('plugins/shopping', 'shopping_z12');?>{{multipleSelection.length}}<?= \Yii::t('plugins/shopping', 'shopping_z13');?>{{progressErrorCount}}<?= \Yii::t('plugins/shopping', 'shopping_z14');?>
            </div>
            <el-progress :text-inside="true" :stroke-width="18" :percentage="progressCount"></el-progress>
            <div style="text-align: right;margin-top: 20px;">
                <el-button type="success" :loading="btnLoading" @click="sendConfirm" size="small"><?= \Yii::t('plugins/shopping', 'shopping_z15');?></el-button>
            </div>
            <div style="margin-top: 20px;" v-for="(item,index) in progressErrors" :key="index">
                <?= \Yii::t('plugins/shopping', 'shopping_z16');?>: {{item.id}},{{item.errmsg}}
            </div>

        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                search: {
                    keyword: '',
                    status: '',
                },
                list: [],
                listLoading: false,
                page: 1,
                pageCount: 0,

                progressVisible: false,
                progressErrors: [],
                progressCount: 0,
                progressErrorCount: 0, //总失败数
                progressSendCount: 0, //总条数
                btnLoading: false,

                multipleSelection: [],
            };
        },
        created() {
            this.getList();
        },
        methods: {
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
                        r: 'plugin/shopping/mall/buy-order/index',
                        page: self.page,
                        search: self.search,
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
            edit() {
                navigateTo({
                    r: 'plugin/shopping/mall/buy-order/edit',
                });
            },
            shopping(id) {
                let self = this;
                self.$confirm('<?= \Yii::t('plugins/shopping', 'shopping_z17');?>', '<?= \Yii::t('plugins/shopping', 'shopping_z18');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z15');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z19');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'plugin/shopping/mall/buy-order/destroy',
                        },
                        method: 'post',
                        data: {
                            id: id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.$message.success(e.data.msg);
                            self.getList();
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('plugins/shopping', 'shopping_z20');?>')
                });
            },
            // 搜索
            commonSearch() {
                this.page = 1;
                this.getList();
            },
            // 发送完成确认
            sendConfirm() {
                this.btnLoading = true;
                navigateTo({
                    r: 'plugin/shopping/mall/buy-order/index'
                })
            },
            handleSelectionChange(val) {
                let self = this;
                self.multipleSelection = [];
                val.forEach(function (item) {
                    self.multipleSelection.push(item.id);
                })
            },
            batchAdd() {
                let self = this;
                if (self.multipleSelection.length > 0) {
                    self.$confirm('<?= \Yii::t('plugins/shopping', 'shopping_z21');?>', '<?= \Yii::t('plugins/shopping', 'shopping_z18');?>', {
                        confirmButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z15');?>',
                        cancelButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z19');?>',
                        type: 'warning'
                    }).then(() => {
                        let count = 100;
                        let orderCount = self.multipleSelection.length;
                        let progressItem = (count / orderCount).toFixed(0);
                        self.progressVisible = true;
                        self.multipleSelection.forEach(function (item) {
                            request({
                                params: {
                                    r: 'plugin/shopping/mall/buy-order/destroy',
                                },
                                method: 'post',
                                data: {
                                    id: item,
                                }
                            }).then(e => {
                                self.listLoading = false;
                                self.progressSendCount += 1;// 发送总数
                                if (e.data.code === 0) {
                                    self.progressCount = self.progressCount + parseInt(progressItem);
                                } else {
                                    self.progressErrorCount += 1;
                                    self.progressErrors.push({
                                        id: item,
                                        errmsg: e.data.msg
                                    })
                                }
                                if (self.progressSendCount == orderCount) {
                                    self.progressCount = 100;
                                }
                            }).catch(e => {
                                console.log(e);
                            });
                        })
                    }).catch(() => {
                        self.$message.info('<?= \Yii::t('plugins/shopping', 'shopping_z20');?>')
                    });
                } else {
                    self.$message.error('<?= \Yii::t('plugins/shopping', 'shopping_z22');?>')
                }
            }
        }
    });
</script>
