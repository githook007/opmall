<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-user-batch');
?>
<style>
    .table-body {
        padding: 20px 20px 0 20px;
        background-color: #fff;
    }

    .table-info .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
        width: 290px;
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

    .select {
        float: left;
        width: 100px;
        margin-right: 10px;
    }

    .remark_name {
        color: #888888;
        font-size: 12px;
        margin-top: -5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 200px;
        display: inline-block;
        height: 15px;
        line-height: 15px;
    }
    .remark {
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
        overflow: hidden;
        line-height: 25px;
        max-height: 100px;
    }

    .platform-img {
        width: 24px;
        height: 24px;
        margin-right: 4px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/user', '用户管理');?></span>
                <app-new-export-dialog-2
                        style="float: right;margin-top: -5px"
                        :field_list='exportList'
                        :params="searchData"
                        action_url="mall/user/index"
                        @selected="exportConfirm">
                </app-new-export-dialog-2>
            </div>
        </div>
        <div class="table-body">
            <el-select size="small" v-model="searchData.member_level" @change='search' class="select">
                <el-option key="0" label="<?= \Yii::t('mall/user', '全部会员');?>" value="0"></el-option>
                <el-option :key="item.level" :label="item.name" :value="item.level" v-for="item in member"></el-option>
            </el-select>
            <el-select size="small" v-model="searchData.platform" @change='search' class="select">
                <el-option key="0" label="<?= \Yii::t('mall/user', '全部平台');?>" value="0"></el-option>
                <el-option v-for="(item, index) in platformList" :key="item.key" :label="item.name" :value="item.key"></el-option>
            </el-select>
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('mall/user', '请输入ID昵称');?>" v-model="searchData.keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <app-user-batch :choose-list="multipleSelection" :mall-members="mall_members" @to-search="getList">
                <template slot="batch" slot-scope="item">
                    <slot name="batch" :item="item.item"></slot>
                </template>
            </app-user-batch>
            <el-table
                    ref="multipleTable"
                    class="table-info"
                    :data="form"
                    border
                    style="width: 100%"
                    v-loading="listLoading"
                    @sort-change="changeSort"
                    @selection-change="handleSelectionChange">
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column prop="user_id" label="ID" width="75"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/user', '头像');?>" width="300">
                    <template slot-scope="scope">
                        <div>
                            <div flex="dir:left cross:center">
                                <app-image mode="aspectFill" style="margin-right: 8px;flex-shrink: 0" :src="scope.row.avatar"></app-image>
                                <div style="width: 100%;">
                                    <!--                             <el-button class="edit-sort" type="text" @click="editRemark(scope.row)">
                                                                    <img src="statics/img/mall/order/edit.png" alt="">
                                                                </el-button> -->
                                    <div>{{scope.row.nickname}}</div>
                                    <el-tooltip v-if="scope.row.remark_name" effect="dark" placement="bottom-start" :content="`<?= \Yii::t('mall/user', '备注名');?>${scope.row.remark_name}`">
                                        <div class="remark_name"><?= \Yii::t('mall/user', '备注名');?>{{scope.row.remark_name}}</div>
                                    </el-tooltip>
                                    <div flex="main:justify" style="width: 100%;">
                                        <img class="platform-img" :src="scope.row.platform_icon" alt="">
                                        <el-button v-if="scope.row.platform_user_id" @click="openId(scope.$index)" type="success" style="padding:5px !important;"><?= \Yii::t('mall/user', '显示OpenId');?></el-button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="scope.row.is_open_id">
                                <block v-for="(item, index) in scope.row.icon" v-key="index">
                                    <div flex="dir:left cross:center" style="margin-top: 5px">
                                        <img class="platform-img" :src="item" alt="">
                                        <span>{{scope.row.openid[index]}}</span>
                                    </div>
                                </block>
                            </div>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column prop="remark" label="<?= \Yii::t('mall/user', '备注');?>" width="180">
                    <template slot-scope="scope">
                        <el-tooltip class="item" v-if="scope.row.showRemark" effect="dark" :content="scope.row.remark" placement="top">
                            <div class="remark">{{scope.row.remark}}</div>
                        </el-tooltip>
                        <div v-else class="remark">{{scope.row.remark}}</div>
                    </template>
                </el-table-column>
                <el-table-column prop="mobile" label="<?= \Yii::t('mall/user', '手机号');?>" width="120">
                </el-table-column>
                <el-table-column prop="member_level" label="<?= \Yii::t('mall/user', '会员类型');?>" width="120">
                    <template slot-scope="scope">
                        <div v-if="scope.row.member_level == item.level" v-for="item in mall_members">{{item.name}}</div>
                        <div v-if="scope.row.member_level == 0"><?= \Yii::t('mall/user', '普通用户');?></div>
                    </template>
                </el-table-column>
                <el-table-column prop="order_count" label="<?= \Yii::t('mall/user', '订单数');?>" sortable="custom">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/order/index', user_id:scope.row.user_id})"
                                   v-text="scope.row.order_count"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="order_sum" label="<?= \Yii::t('mall/user', '订单金额');?>" sortable="custom">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/order/index', user_id:scope.row.user_id})"
                                   v-text="scope.row.order_sum"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="coupon_count" label="<?= \Yii::t('mall/user', '优惠券数量');?>" sortable="custom">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/user/coupon', user_id:scope.row.user_id})"
                                   v-text="scope.row.coupon_count"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="card_count" label="<?= \Yii::t('mall/user', '卡券数量');?>" sortable="custom">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/user/card', user_id:scope.row.user_id})"
                                   v-text="scope.row.card_count"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="balance" label="<?= \Yii::t('mall/user', '余额');?>">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/user/balance-log', user_id:scope.row.user_id})"
                                   v-text="scope.row.balance"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="integral" label="<?= \Yii::t('mall/user', '积分');?>">
                    <template slot-scope="scope">
                        <el-button type="text" @click="$navigate({r: 'mall/user/integral-log', user_id:scope.row.user_id})"
                                   v-text="scope.row.integral"></el-button>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('mall/user', '加入时间');?>" width="180"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/user', '操作');?>" width="220"  fixed="right">
                    <template slot-scope="scope">
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user', '编辑');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="$navigate({r: 'mall/user/edit', id:scope.row.user_id, page: page})">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user', '编辑积分');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="handleIntegral(scope.row)">
                                <img src="statics/img/mall/integral.png" alt="">
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user', '编辑余额');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="handleBalance(scope.row)">
                                <img src="statics/img/mall/balance.png" alt="">
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user', '重置余额支付密码');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="resetPassword(scope.row)">
                                <img src="statics/img/mall/change.png" alt="">
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination @current-change="pagination" hide-on-single-page background layout="total, prev, pager, next, jumper"
                               :total="pageCount" :page-size="pageSize" :current-page="currentPage"></el-pagination>
            </div>
        </div>
        <!-- 编辑积分 -->
        <el-dialog title="<?= \Yii::t('mall/user', '编辑积分');?>" :visible.sync="dialogIntegral" width="30%">
            <el-form :model="integralForm" label-width="80px" :rules="integralFormRules" ref="integralForm">
                <el-form-item label="<?= \Yii::t('mall/user', '操作');?>" prop="type">
                    <el-radio v-model="integralForm.type" label="1"><?= \Yii::t('mall/user', '充值');?></el-radio>
                    <el-radio v-model="integralForm.type" label="2"><?= \Yii::t('mall/user', '扣除');?></el-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '积分数');?>" prop="num" size="small">
                    <el-input oninput="this.value = this.value.replace(/[^0-9]/g, '');" v-model="integralForm.num" :max="999999999"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '充值图片');?>" prop="pic_url">
                    <app-attachment :multiple="false" :max="1" @selected="integralPicUrl">
                        <el-button size="mini"><?= \Yii::t('mall/user', '选择文件');?></el-button>
                    </app-attachment>
                    <app-image width="80px" height="80px" mode="aspectFill" :src="integralForm.pic_url"></app-image>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '备注');?>" prop="remark" size="small">
                    <el-input v-model="integralForm.remark"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogIntegral = false"><?= \Yii::t('mall/user', '取消');?></el-button>
                <el-button :loading="btnLoading" type="primary" @click="integralSubmit"><?= \Yii::t('mall/user', '确认');?></el-button>
            </div>
        </el-dialog>
        <!-- 编辑余额 -->
        <el-dialog title="<?= \Yii::t('mall/user', '编辑余额');?>" :visible.sync="dialogBalance" width="30%">
            <el-form :model="balanceForm" label-width="80px" :rules="balanceFormRules" ref="integralForm">
                <el-form-item label="<?= \Yii::t('mall/user', '操作');?>" prop="type">
                    <el-radio v-model="balanceForm.type" label="1"><?= \Yii::t('mall/user', '充值');?></el-radio>
                    <el-radio v-model="balanceForm.type" label="2"><?= \Yii::t('mall/user', '扣除');?></el-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '金额');?>" prop="price" size="small">
                    <el-input type="number" v-model="balanceForm.price" :max="99999999"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '充值图片');?>" prop="pic_url">
                    <app-attachment :multiple="false" :max="1" @selected="balancePicUrl">
                        <el-button size="mini"><?= \Yii::t('mall/user', '选择文件');?></el-button>
                    </app-attachment>
                    <app-image width="80px" height="80px" mode="aspectFill" :src="balanceForm.pic_url"></app-image>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/user', '备注');?>" prop="remark" size="small">
                    <el-input v-model="balanceForm.remark"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogBalance = false"><?= \Yii::t('mall/user', '取消');?></el-button>
                <el-button :loading="btnLoading" type="primary" @click="balanceSubmit"><?= \Yii::t('mall/user', '确认');?></el-button>
            </div>
        </el-dialog>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                searchData: {
                    keyword: '',
                    platform: '0',
                    member_level: '0',
                },
                platform: '0',
                member_level: '0',
                mall_members: [],
                keyword: '',
                form: [],
                member: [],
                pageCount: 0,
                pageSize: 10,
                page: 1,
                member_page: 1,
                currentPage: null,
                listLoading: false,
                btnLoading: false,

                // 导出
                exportList: [],

                //积分
                dialogIntegral: false,
                integralForm: {
                    type: '1',
                    num: '',
                    pic_url: '',
                    remark: '',
                },
                integralFormRules: {
                    type: [
                        {required: true, message: '<?= \Yii::t('mall/user', '操作不能为空');?>', trigger: 'blur'},
                    ],
                    num: [
                        {required: true, message: '<?= \Yii::t('mall/user', '积分数不能为空');?>', trigger: 'blur'},
                    ],
                },

                //余额
                dialogBalance: false,
                balanceForm: {
                    type: '1',
                    price: '',
                    pic_url: '',
                    remark: '',
                },
                balanceFormRules: {
                    type: [
                        {required: true, message: '<?= \Yii::t('mall/user', '操作不能为空');?>', trigger: 'blur'},
                    ],
                    num: [
                        {required: true, message: '<?= \Yii::t('mall/user', '金额不能为空');?>', trigger: 'blur'},
                    ],
                },

                multipleSelection: [],
                platformList: []
            };
        },
        methods: {
            strlen(str){
                var len = 0;
                for (var i=0; i<str.length; i++) {
                    var c = str.charCodeAt(i);
                    //单字节加1
                    if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
                        len++;
                    }
                    else {
                        len+=2;
                    }
                }
                return len;
            },
            resetPassword(item) {
                this.$confirm('<?= \Yii::t('mall/user', '重置该用户支付密码');?>', '<?= \Yii::t('mall/user', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/user', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/user', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/user/reset-pay-password',
                        },
                        method: 'post',
                        data: {
                            user_id: item.user_id
                        },
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.$message.success('<?= \Yii::t('mall/user', '重置成功');?>');
                        } else {
                            this.$message.error(e.data.msg);
                        }
                        this.btnLoading = false;
                    }).catch(e => {
                        this.btnLoading = false;
                    });
                })
            },
            openId(index) {
                let item = this.form;
                item[index].is_open_id = !item[index].is_open_id;
                this.form = JSON.parse(JSON.stringify(this.form));
            },
            exportConfirm() {
                this.searchData.keyword = this.keyword;
            },
            //积分
            integralPicUrl(e) {
                if (e.length) {
                    this.integralForm.pic_url = e[0].url;
                }
            },
            handleIntegral(row) {
                this.integralForm = Object.assign(this.integralForm, {user_id: row.user_id});
                this.dialogIntegral = true;
            },
            integralSubmit() {
                this.$refs.integralForm.validate((valid) => {
                    if (valid) {
                        let para = Object.assign({}, this.integralForm);
                        this.btnLoading = true;
                        this.dialogIntegral = false;
                        request({
                            params: {
                                r: 'mall/user/integral',
                            },
                            method: 'post',
                            data: para,
                        }).then(e => {
                            if (e.data.code === 0) {
                                location.reload();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                            this.btnLoading = false;
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },

            //余额
            balancePicUrl(e) {
                if (e.length) {
                    this.balanceForm.pic_url = e[0].url;
                }
            },
            handleBalance(row) {
                this.balanceForm = Object.assign(this.balanceForm, {user_id: row.user_id});
                this.dialogBalance = true;
            },
            balanceSubmit() {
                this.$refs.integralForm.validate((valid) => {
                    if (valid) {
                        let para = Object.assign({}, this.balanceForm);
                        this.btnLoading = true;
                        this.dialogBalance = false;
                        request({
                            params: {
                                r: 'mall/user/balance',
                            },
                            method: 'post',
                            data: para,
                        }).then(e => {
                            if (e.data.code === 0) {
                                location.reload();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                            this.btnLoading = false;
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
            //
            search() {
                this.page = 1;
                this.getList();
            },

            pagination(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/user/index',
                        page: this.page,
                        member_level: this.searchData.member_level,
                        platform: this.searchData.platform,
                        keyword: this.searchData.keyword,
                        sort: this.searchData.order,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.form = e.data.data.list;
                        for(item of this.form) {
                            item.showRemark = false;
                            if(this.strlen(item.remark) > 42) {
                                item.showRemark = true;
                            }
                        }
                        this.exportList = e.data.data.exportList;
                        this.pageCount = e.data.data.pagination.total_count;
                        this.pageSize = e.data.data.pagination.pageSize;
                        this.currentPage = e.data.data.pagination.current_page;
                        this.mall_members = e.data.data.mall_members;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            getMember() {
                let self = this;
                request({
                    params: {
                        r: 'mall/mall-member/index',
                        page: self.member_page
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.data.list.length > 0) {
                        if(self.member_page == 1) {
                            self.member = e.data.data.list;
                        }else {
                            self.member = self.member.concat(e.data.data.list);
                        }
                        self.member_page++;
                        self.getMember();
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            toggleSelection(rows) {
                if (rows) {
                    rows.forEach(row => {
                        this.$refs.multipleTable.toggleRowSelection(row);
                    });
                } else {
                    this.$refs.multipleTable.clearSelection();
                }
            },
            changeSort(column) {
                this.loading = true;
                if(column.order == "descending") {
                    this.searchData.order = column.prop + '_desc'
                }else if (column.order == "ascending") {
                    this.searchData.order = column.prop + '_asc'
                }else {
                    this.searchData.order = null
                }
                this.getList();
            },
            handleSelectionChange(val) {
                let self = this;
                self.multipleSelection = [];
                val.forEach(function(item) {
                    self.multipleSelection.push(item.user_id);
                })
            },
            getPlatform() {
                request({
                    params: {
                        r: 'mall/index/platform',
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.code === 0) {
                        this.platformList = e.data.data
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.page = getQuery('page') ? getQuery('page') : 1;
            this.getList();
            this.getMember();
            this.getPlatform();
        }
    });
</script>
