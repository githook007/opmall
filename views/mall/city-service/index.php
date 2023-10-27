<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .text-color {
        color: red;
    }
    .set-el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-info .el-button {
        padding: 0 !important;
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
</style>
<div id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/city_service', '即时配送商家');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/city_service', '新增配送');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-table v-loading="listLoading" :data="list" border style="width: 100%">
                <el-table-column prop="id" label="ID" width="60"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '配送名称');?>" width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="2">({{scope.row.is_debug ? '<?= \Yii::t('mall/city_service', '测试');?>' : '<?= \Yii::t('mall/city_service', '正式');?>'}}){{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '配送公司');?>" width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.corporation_name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '使用第三方平台接口');?>" width="150" prop="new_service_type"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '商户ID');?>" prop="shop_id" width="80"></el-table-column>
                <el-table-column label="appkey" prop="appkey"></el-table-column>
                <el-table-column label="appsecret" prop="appsecret"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '商家门店编号');?>" prop="shop_no"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/city_service', '操作');?>" width="180">
                    <template slot-scope="scope">
                        <el-button class="set-el-button" type="text" size="mini" circle @click="edit(scope.row.id)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/city_service', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" type="text" size="mini" circle @click="destroy(scope.row, scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/city_service', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination @current-change="pagination" background layout="prev, pager, next"
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
                keyword: '',
                list: [],
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
                        r: 'mall/city-service/index',
                        page: self.page,
                        keyword: self.keyword,
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
                        r: 'mall/city-service/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/city-service/edit',
                    });
                }
            },
            destroy(row, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('mall/city_service', '删除该条数据');?>?', '<?= \Yii::t('mall/city_service', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/city_service', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/city_service', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'mall/city-service/delete',
                        },
                        method: 'post',
                        data: {
                            id: row.id,
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
                    self.$message.info('<?= \Yii::t('mall/city_service', '已取消删除');?>')
                });
            },
        },
        mounted: function () {
            this.getList();
        }
    });
</script>