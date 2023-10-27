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

    .goods-image {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }

    .el-button--mini.is-circle {
        padding: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div flex="dir:left box:last">
                <div><?= \Yii::t('mall/live', '直播商品');?></div>
                <div>
                    <el-button size="small" @click="edit" type="primary"><?= \Yii::t('mall/live', '添加直播商品');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane v-for="(tab, index) in tabs" :key="index" :label="tab.label" :name="tab.value"></el-tab-pane>
            </el-tabs>
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        width="80"
                        prop="goodsId"
                        label="<?= \Yii::t('mall/live', '商品ID');?>"
                        width="120">
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/live', '商品名称');?>"
                        width="220">
                    <template slot-scope="scope">
                        <div flex="dir:left">
                            <img :src="scope.row.coverImgUrl" class="goods-image">
                            <div>
                                <app-ellipsis :line="2">{{scope.row.name}}</app-ellipsis>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        width="180"
                        label="<?= \Yii::t('mall/live', '价格形式');?>">
                    <template slot-scope="scope">
                        <el-tag type="primary"><span v-html="scope.row.price_text"></span></el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                        prop="url"
                        label="<?= \Yii::t('mall/live', '小程序路径');?>">
                </el-table-column>
                <el-table-column
                        width="100"
                        label="<?= \Yii::t('mall/live', '添加方式');?>">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.thirdPartyTag == 2"><?= \Yii::t('mall/live', '后台添加');?></el-tag>
                        <el-tag v-else type="success"><?= \Yii::t('mall/live', '微信添加');?></el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                        width="100"
                        label="<?= \Yii::t('mall/live', '审核状态');?>">
                    <template slot-scope="scope">
                        <el-tag v-if="status == 0" type="info"><?= \Yii::t('mall/live', '未审核');?></el-tag>
                        <el-tag v-if="status == 1" type="primary"><?= \Yii::t('mall/live', '审核中');?></el-tag>
                        <el-tag v-if="status == 2" type="success"><?= \Yii::t('mall/live', '审核通过');?></el-tag>
                        <el-tag v-if="status == 3" type="danger"><?= \Yii::t('mall/live', '审核驳回');?></el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('mall/live', '操作');?>">
                    <template slot-scope="scope">
                        <template>
                            <el-button v-if="status != 1" @click="edit(scope.row)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/live', '编辑');?>" placement="top">
                                    <img src="statics/img/mall/edit.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="status == 0 || status == 3" @click="submitAudit(scope.row)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/live', '审核');?>" placement="top">
                                    <img src="statics/img/mall/live/btn_examine_n.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="status == 1 && scope.row.audit_id" @click="cancelAudit(scope.row)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/live', '撤回');?>" placement="top">
                                    <img src="statics/img/mall/live/btn_withdraw_n.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="status != 1" @click="deleteGoods(scope.row)" type="text" circle size="mini">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/live', '删除');?>" placement="top">
                                    <img src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </el-button>
                        </template>
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
                pageCount: 0,
                status: 0,

                tabs: [
                    {
                        label: '<?= \Yii::t('mall/live', '未审核');?>',
                        value: '0'
                    },
                    {
                        label: '<?= \Yii::t('mall/live', '审核中');?>',
                        value: '1'
                    },
                    {
                        label: '<?= \Yii::t('mall/live', '审核通过');?>',
                        value: '2'
                    },
                    {
                        label: '<?= \Yii::t('mall/live', '审核驳回');?>',
                        value: '3'
                    },
                ],
                activeName: '0',
            };
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
                        r: 'mall/live/goods',
                        page: self.page,
                        status: self.status,
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code == 0) {
                        self.list = e.data.data.list;
                        self.pageCount = e.data.data.pageCount;
                    } else {
                        self.$message.error(e.data.msg)
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            deleteGoods(row) {
                let self = this;
                this.$confirm('<?= \Yii::t('mall/live', '此操作将删除该商品');?>?', '<?= \Yii::t('mall/live', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/live', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/live', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/live/delete-goods',
                        },
                        data: {
                            goods_id: row.goodsId,
                        },
                        method: 'post',
                    }).then(e => {
                        if (e.data.code == 0) {
                            self.$message.success(e.data.msg);
                            self.getList();
                        } else {
                            self.$message.error(e.data.msg)
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {

                });
            },
            cancelAudit(row) {
                let self = this;
                this.$confirm('<?= \Yii::t('mall/live', '撤销审核');?>?', '<?= \Yii::t('mall/live', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/live', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/live', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/live/cancel-audit',
                        },
                        data: {
                            goods_id: row.goodsId,
                        },
                        method: 'post',
                    }).then(e => {
                        if (e.data.code == 0) {
                            self.$message.success(e.data.msg);
                            self.getList();
                        } else {
                            self.$message.error(e.data.msg)
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {

                });
            },
            submitAudit(row) {
                let self = this;
                this.$confirm('<?= \Yii::t('mall/live', '提交审核');?>?', '<?= \Yii::t('mall/live', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/live', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/live', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/live/submit-audit',
                        },
                        data: {
                            goods_id: row.goodsId,
                        },
                        method: 'post',
                    }).then(e => {
                        if (e.data.code == 0) {
                            self.$message.success(e.data.msg);
                            self.getList();
                        } else {
                            self.$message.error(e.data.msg)
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {

                });
            },
            handleClick(tab, event) {
                this.status = tab.index;
                this.getList();
            },
            edit(row) {
                if (row) {
                    this.$navigate({
                        r: 'mall/live/goods-edit',
                        goods_id: row.goodsId
                    });
                } else {
                    this.$navigate({
                        r: 'mall/live/goods-edit',
                    });
                }
            }
        },
        mounted: function () {
            if (getQuery('status')) {
                this.activeName = getQuery('status');
                this.status = getQuery('status');
            }
            this.getList();
        }
    });
</script>
