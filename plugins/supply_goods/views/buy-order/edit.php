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
        margin: 0;
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

    .table-body .el-table .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }

    .el-form-item {
        margin-bottom: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="listLoading" class="box-card">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                          @click="$navigate({r:'plugin/shopping/mall/buy-order/index'})"><?= \Yii::t('plugins/shopping', 'shopping_z23');?></span>
                </el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('plugins/shopping', 'shopping_z24');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="table-body">
            <div style="background-color: #fce9e6;width: 100%;border-color: #edd7d4;color: #e55640;border-radius: 2px;padding: 15px;margin-bottom: 20px;">
                <p><?= \Yii::t('plugins/shopping', 'shopping_z25');?></p>
                <p><?= \Yii::t('plugins/shopping', 'shopping_z26');?>
                    <?= \Yii::t('plugins/shopping', 'shopping_z27');?></p>
            </div>
            <el-form size="small" :inline="true" @submit.native.prevent :model="search">
                <el-form-item>
                    <div class="input-item">
                        <el-input size="small" @keyup.enter.native="commonSearch" placeholder="<?= \Yii::t('plugins/shopping', 'shopping_z28');?>" v-model="search.keyword" clearable @clear='commonSearch'>
                            <el-button slot="append" icon="el-icon-search" @click="commonSearch"></el-button>
                        </el-input>
                    </div>
                </el-form-item>
                <el-form-item v-if="multipleSelection.length > 0">
                    <el-button type="primary" @click="batchAdd" size="small"><?= \Yii::t('plugins/shopping', 'shopping_z29');?></el-button>
                </el-form-item>
            </el-form>
            <el-table :data="list" border style="width: 100%;margin-bottom: 15px"
                      @selection-change="handleSelectionChange">
                <el-table-column type="selection" width="50"></el-table-column>
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="order_no" label="<?= \Yii::t('plugins/shopping', 'shopping_z30');?>" width="200"></el-table-column>
                <el-table-column prop="total_price" label="<?= \Yii::t('plugins/shopping', 'shopping_z31');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/shopping', 'shopping_z32');?>">
                    <template slot-scope="scope">
                        <div v-for="item in scope.row.detail" flex="box:first">
                            <div style="padding-right: 10px">
                                <app-image width="25px" height="25px" mode="aspectFill"
                                           :src="item.goods_info.goods_attr.cover_pic"></app-image>
                            </div>
                            <div>
                                <app-ellipsis :line="1">{{item.goods ? item.goods.goodsWarehouse.name : ''}}
                                </app-ellipsis>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('plugins/shopping', 'shopping_z33');?>">
                    <template slot-scope="scope">
                        <el-button @click="shopping(scope.row.id)" type="text" circle size="mini">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/shopping', 'shopping_z34');?>" placement="top">
                                <img src="statics/img/mall/plus.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="box:last cross:center" style="margin-top: 20px;">
                <div></div>
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
        <el-dialog title="<?= \Yii::t('plugins/shopping', 'shopping_z34');?>" :visible.sync="progressVisible">
            <div style="margin: 10px 0;">
                <?= \Yii::t('plugins/shopping', 'shopping_z35');?>{{multipleSelection.length}}<?= \Yii::t('plugins/shopping', 'shopping_z36');?>{{progressErrorCount}}<?= \Yii::t('plugins/shopping', 'shopping_z37');?>
            </div>
            <el-progress :text-inside="true" :stroke-width="18" :percentage="progressCount"></el-progress>
            <div style="text-align: right;margin-top: 20px;">
                <el-button type="success" :loading="btnLoading" @click="sendConfirm" size="small"><?= \Yii::t('plugins/shopping', 'shopping_z38');?></el-button>
            </div>
            <div style="margin-top: 20px;" v-for="(item,index) in progressErrors" :key="index">
                <?= \Yii::t('plugins/shopping', 'shopping_z39');?>: {{item.id}},{{item.errmsg}}
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
                        r: 'plugin/shopping/mall/buy-order/edit',
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
            shopping(id) {
                let self = this;
                self.$confirm('<?= \Yii::t('plugins/shopping', 'shopping_z40');?>', '<?= \Yii::t('plugins/shopping', 'shopping_z41');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z38');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z42');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'plugin/shopping/mall/buy-order/edit',
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
                    self.$message.info('<?= \Yii::t('plugins/shopping', 'shopping_z43');?>')
                });
            },
            // 搜索
            commonSearch() {
                this.getList();
            },
            // 发送完成确认
            sendConfirm() {
                this.btnLoading = true;
                navigateTo({
                    r: 'plugin/shopping/mall/buy-order/edit'
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
                    self.$confirm('<?= \Yii::t('plugins/shopping', 'shopping_z40');?>', '<?= \Yii::t('plugins/shopping', 'shopping_z41');?>', {
                        confirmButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z38');?>',
                        cancelButtonText: '<?= \Yii::t('plugins/shopping', 'shopping_z42');?>',
                        type: 'warning'
                    }).then(() => {
                        let count = 100;
                        let orderCount = self.multipleSelection.length;
                        let progressItem = (count / orderCount).toFixed(0);
                        self.progressVisible = true;
                        self.multipleSelection.forEach(function (item) {
                            request({
                                params: {
                                    r: 'plugin/shopping/mall/buy-order/edit',
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
                        self.$message.info('<?= \Yii::t('plugins/shopping', 'shopping_z43');?>')
                    });
                } else {
                    self.$message.error('<?= \Yii::t('plugins/shopping', 'shopping_z44');?>')
                }
            }
        }
    });
</script>
