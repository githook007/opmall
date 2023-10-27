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
                <span><?= \Yii::t('mall/card', '卡券列表');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit" size="small"><?= \Yii::t('mall/card', '添加卡券');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('mall/card', '请输入卡券名称搜索');?>" v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table v-loading="listLoading" :data="list" border style="width: 100%">
                <el-table-column prop="id" label="ID" width="60"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/card', '卡券名称');?>" >
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/card', '核销总次数');?>" width="100" prop="number"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/card', '卡券图标');?>" width="100">
                    <template slot-scope="scope">
                        <app-image mode="aspectFill" :src="scope.row.pic_url"></app-image>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/card', '有效期');?>" width="320">
                    <template slot-scope="scope">
                        <div v-if="scope.row.expire_type == 1"><?= \Yii::t('mall/card', '发放之日起');?><span
                                    class="text-color">{{scope.row.expire_day}}</span><?= \Yii::t('mall/card', '天内');?>
                        </div>
                        <div v-else>
                            <span class="text-color">{{scope.row.begin_time}}</span>
                            - <span class="text-color">{{scope.row.end_time}}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="is_allow_send" label="<?= \Yii::t('mall/card', '是否允许转赠');?>" width="130">
                    <template slot-scope="scope">
                        <el-switch
                                :active-value="1"
                                :inactive-value="0"
                                @change="switchSend(scope.row)"
                                v-model="scope.row.is_allow_send">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('mall/card', '添加日期');?>" width="180">
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/card', '操作');?>" width="180">
                    <template slot-scope="scope">
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="handleSend(scope.$index, scope.row,list.id)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/card', '发放卡券');?>" placement="top">
                                <img src="statics/img/mall/send.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" type="text" size="mini" circle @click="edit(scope.row.id)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/card', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" type="text" size="mini" circle @click="destroy(scope.row, scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/card', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination @current-change="pagination" background layout="prev, pager, next, jumper"
                               :page-count="pageCount">
                </el-pagination>
            </div>
        </div>
        <el-dialog append-to-body title="提示" :visible.sync="visible" width="30%">
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
                switchLoading: false,
                switchList: [],

                visible: false,
                formLoading: false,
                form: {
                    type: 1
                },
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
                        r: 'mall/card/index',
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
                        r: 'mall/card/edit',
                        id: id,
                    });
                } else {
                    navigateTo({
                        r: 'mall/card/edit',
                    });
                }
            },
            destroy(row, index) {
                this.form.id = row.id
                this.visible = true;
            },
            formSubmit(formName) {
                let self = this;
                self.$refs[formName].validate(valid => {
                    if (valid) {
                        self.listLoading = true;
                        request({
                            params: {
                                r: 'mall/card/destroy',
                            },
                            method: 'post',
                            data: self.form
                        }).then(e => {
                            self.listLoading = false;
                            if (e.data.code === 0) {
                                self.$message.success(e.data.msg);
                                self.getList();
                                self.visible = false;
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            console.log(e);
                        });
                    }
                })
            },
            // 优惠券发放页面
            handleSend: function(row, column) {
                navigateTo({ r: 'mall/card/send', id: column.id });
            },
            // 是否允许转赠
            switchSend(row) {
                let self = this;
                this.$request({
                    params: {
                        r: 'mall/card/switch-send',
                    },
                    method: 'post',
                    data: {
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
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
