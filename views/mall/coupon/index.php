<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .set-el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }
    .sort-input {
        width: 100%;
        background-color: #F3F5F6;
        height: 32px;
    }

    .sort-input span {
        height: 32px;
        width: 100%;
        line-height: 32px;
        display: inline-block;
        padding: 0 10px;
        font-size: 13px;
    }

    .sort-input .el-input__inner {
        height: 32px;
        line-height: 32px;
        background-color: #F3F5F6;
        float: left;
        padding: 0 10px;
        border: 0;
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
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('mall/coupon', '优惠券管理');?></span>
            <el-button style="float: right; margin: -5px 0" type="primary" size="small" @click="$navigate({r:'mall/coupon/edit'})"><?= \Yii::t('mall/coupon', '新增');?>
            </el-button>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('mall/coupon', '请输入优惠券名称搜索');?>" v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table v-loading="loading" border :data="list" style="width: 100%;margin-bottom: 15px">
                <el-table-column width='100' prop="id" label="ID"></el-table-column>
                <el-table-column prop="name" label="<?= \Yii::t('mall/coupon', '优惠券名称');?>"></el-table-column>
                <el-table-column width='100' prop="min_price" label="<?= \Yii::t('mall/coupon', '最低消费金额');?>">
                </el-table-column>
                <el-table-column width='150' prop="sub_price" label="<?= \Yii::t('mall/coupon', '优惠方式');?>">
                    <template slot-scope="scope">
                        <div v-if="scope.row.type == 2"><?= \Yii::t('mall/coupon', '优惠');?>:{{scope.row.sub_price}}<?= \Yii::t('mall/coupon', '元');?></div>
                        <div v-if="scope.row.type == 1">{{scope.row.discount}}折</div>
                        <div v-if="scope.row.discount_limit && scope.row.type == 1"><?= \Yii::t('mall/coupon', '优惠上限');?>:{{scope.row.discount_limit}}</div>
                    </template>
                </el-table-column>
                <el-table-column width='120' prop="sub_price" label="<?= \Yii::t('mall/coupon', '使用范围');?>">
                    <template slot-scope="scope">
                        <span v-if="scope.row.appoint_type == 1"><?= \Yii::t('mall/coupon', '指定商品类目');?></span>
                        <span v-if="scope.row.appoint_type == 2"><?= \Yii::t('mall/coupon', '指定商品');?></span>
                        <span v-if="scope.row.appoint_type == 3"><?= \Yii::t('mall/coupon', '全场通用');?></span>
                        <span v-if="scope.row.appoint_type == 4"><?= \Yii::t('mall/coupon', '当面付');?></span>
                        <span v-if="scope.row.appoint_type == 5"><?= \Yii::t('mall/coupon', '礼品卡');?></span>
                    </template>
                </el-table-column>
                <el-table-column width='180' prop="expire_type" label="<?= \Yii::t('mall/coupon', '有效时间');?>">
                    <template slot-scope="scope">
                        <span v-if="scope.row.expire_type == 1">
                        <?= \Yii::t('mall/coupon', '领取');?>{{scope.row.expire_day}}<?= \Yii::t('mall/coupon', '天后过期');?>
                    </span>
                        <span v-else-if="scope.row.expire_type == 2">
                        {{scope.row.begin_time}} - {{scope.row.end_time}}
                    </span>
                    </template>
                </el-table-column>
                <el-table-column width='150' prop="total_count" label="<?= \Yii::t('mall/coupon', '数量');?>">
                    <template slot-scope="scope">
                        <div v-if="scope.row.total_count == -1">
                            <div><?= \Yii::t('mall/coupon', '总数量无限制');?></div>
                            <div><?= \Yii::t('mall/coupon', '剩余发放数无限制');?></div>
                        </div>
                        <div v-else>
                            <div><?= \Yii::t('mall/coupon', '总数量');?>{{scope.row.count}}</div>
                            <div><?= \Yii::t('mall/coupon', '剩余发放数');?>{{scope.row.total_count}}</div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width='80' prop="is_join" label="<?= \Yii::t('mall/coupon', '加入领券中心');?> ">
                    <template slot-scope="scope">
                        <el-switch v-model="scope.row.is_join" active-value="1"
                                   @change="handleCenter(scope.$index, scope.row)" inactive-value="0"></el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/coupon', '操作');?>" width="180">
                    <template slot-scope="scope">
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="handleSend(scope.$index, scope.row)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/coupon', '发放优惠券');?>" placement="top">
                                <img src="statics/img/mall/send.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="handleEdit(scope.$index, scope.row)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/coupon', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="handleDel(scope.$index, scope.row)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/coupon', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="box:last cross:center">
                <div style="visibility: hidden">
                    <el-button plain type="primary" size="small"><?= \Yii::t('mall/coupon', '批量操作');?>1</el-button>
                </div>
                <div>
                    <el-pagination v-if="pagination" :page-size="pagination.pageSize"
                                   style="display: inline-block;float: right;" background @current-change="pageChange"
                                   layout="prev, pager, next, jumper" :total="pagination.total_count">
                    </el-pagination>
                </div>
            </div>
        </div>

        <el-dialog append-to-body title="提示"
                   :visible.sync="visible" :close-on-click-modal="false"
                   width="30%">
            <el-form @submit.native.prevent label-width="90px" ref="form" :model="form">
                <el-form-item style="width: 70%">
                    <el-alert
                            title="<?= \Yii::t('mall/coupon', '确认删除该记录吗');?>?"
                            type="warning"
                            :closable="false"
                            show-icon>
                    </el-alert>
                </el-form-item>
                <el-form-item>
                    <el-radio v-model="form.type" :label="1"><?= \Yii::t('mall/coupon', '已领取的可以使用');?></el-radio>
                    <el-radio v-model="form.type" :label="2"><?= \Yii::t('mall/coupon', '一并删除');?></el-radio>
                </el-form-item>
                <el-form-item style="text-align: right">
                    <el-button type="primary" @click="visible = false"
                               size="medium"><?= \Yii::t('admin/coupon', '取消');?>
                    </el-button>
                    <el-button type="primary" @click="formSubmit('form')" :loading="formLoading"
                               size="medium"><?= \Yii::t('admin/coupon', '确认');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                list: [],
                keyword: '',
                pagination: null,
                page: 1,

                visible: false,
                formLoading: false,
                form: {
                    type: 1
                },
            };
        },

        methods: {
            formSubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.loading = true;
                        request({
                            params: {
                                r: 'mall/coupon/destroy'
                            },
                            data: this.form,
                            method: 'post'
                        }).then(e => {
                            this.loading = false;
                            if (e.data.code === 0) {
                                this.$message({
                                    message: '<?= \Yii::t('mall/coupon', '删除成功');?>',
                                    type: 'success'
                                });
                                setTimeout(() => {
                                    this.loadData()
                                }, 300);
                                this.visible = false;
                            } else {
                                this.$alert(e.data.msg, '<?= \Yii::t('mall/coupon', '提示');?>', {
                                    confirmButtonText: '<?= \Yii::t('mall/coupon', '确定');?>'
                                })
                            }
                        }).catch(e => {
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/coupon', '提示');?>', {
                                confirmButtonText: '<?= \Yii::t('mall/coupon', '确定');?>'
                            })
                        });
                    }
                })
            },
            //搜索
            search() {
                this.page = 1;
                this.loadData();
            },
            handleCenter: function(row, column) {
                let para = Object.assign({ id: column.id }, { is_join: column.is_join });
                request({
                    params: {
                        r: 'mall/coupon/edit-center'
                    },
                    data: para,
                    method: 'post'
                }).then(e => {
                    if (e.data.code === 0) {
                        this.$message({
                            message: e.data.msg,
                            type: 'success'
                        });

                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('mall/coupon', '提示');?>', {
                            confirmButtonText: '<?= \Yii::t('mall/coupon', '确定');?>'
                        })
                    }
                }).catch(e => {});

            },
            //带着ID前往编辑页面
            handleEdit: function(row, column) {
                navigateTo({ r: 'mall/coupon/edit', id: column.id });
            },

            // 优惠券发放页面
            handleSend: function(row, column) {
                navigateTo({ r: 'mall/coupon/send', id: column.id });
            },

            //分页
            pageChange(page) {
                this.page = page;
                this.loadData();
            },

            //删除
            handleDel: function(row, column) {
                this.form.id = column.id
                this.visible = true;
            },
            // 根据参数获取请求信息
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'mall/coupon/index',
                        keyword: this.keyword,
                        page: this.page,
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    } else {
                        this.listLoading = false;
                        this.$message({
                            message: e.data.msg,
                            type: 'error'
                        });
                    }
                }).catch(e => {
                    this.listLoading = false;
                });
            }
        },
        created() {
            this.loadData();
        }
    })
</script>