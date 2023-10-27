<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/1/18
 * Time: 14:12
 */
?>
<style>
    .el-tabs__header {
        font-size: 16px;
    }


    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
        width: 350px;
        margin-left: 20px;
    }

    .input-item .el-input-group__prepend {
        background-color: #fff;
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

    .content {
        height: 20px;
        padding: 0 5px;
        line-height: 20px;
        color: #E6A23C;
        background-color: #FCF6EB;
        width: auto;
        display: inline-block;
    }

    .select {
        float: left;
        width: 100px;
        margin-right: 10px;
    }

    .el-message-box__message {
        text-align: center;
        font-size: 16px;
        margin: 10px 0 20px;
    }

    .el-dialog {
        min-width: 400px!important;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('plugins/bonus', '分红提现');?></span>
            <el-form size="small" :inline="true" :model="search" style="float: right;margin-top: -5px;">
                <el-form-item>
                    <app-export-dialog action_url='index.php?r=plugin/bonus/mall/cash/index' :field_list='exportList' :params="search" @selected="confirmSubmit">
                    </app-export-dialog>
                </el-form-item>
            </el-form>
        </div>
        <div class="table-body">
            <div flex="dir:left cross:center" style="margin-bottom: 20px;">
                <div flex="dir:left cross:center">
                    <div style="margin-right: 10px;"><?= \Yii::t('plugins/bonus', '申请时间');?></div>
                    <el-date-picker
                            size="small"
                            @change="changeTime"
                            v-model="search.time"
                            type="datetimerange"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            range-separator="<?= \Yii::t('plugins/bonus', '至');?>"
                            start-placeholder="<?= \Yii::t('plugins/bonus', '开始时间');?>"
                            end-placeholder="<?= \Yii::t('plugins/bonus', '结束时间');?>">
                    </el-date-picker>
                </div>
                <div class="input-item">
                    <el-input @keyup.enter.native="toSearch" size="small" v-model="search.keyword" clearable
                              placeholder="<?= \Yii::t('plugins/bonus', '请输入搜索内容');?>" @clear="toSearch">
                        <el-select size="small" v-model="search.type" slot="prepend" class="select">
                            <el-option key="1" label="<?= \Yii::t('plugins/bonus', '用户ID');?>" value="4"></el-option>
                            <el-option key="2" label="<?= \Yii::t('plugins/bonus', '昵称');?>" value="1"></el-option>
                            <el-option key="3" label="<?= \Yii::t('plugins/bonus', '姓名');?>" value="2"></el-option>
                            <el-option key="4" label="<?= \Yii::t('plugins/bonus', '手机号');?>" value="3"></el-option>
                        </el-select>
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
            </div>
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '全部');?>" name="-1"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '待审核');?>" name="0"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '待打款');?>" name="1"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '已打款');?>" name="2"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '驳回');?>" name="3"></el-tab-pane>
                <el-table :data="list" border v-loading="loading" size="small" style="margin-bottom: 15px;">
                    <el-table-column label="ID" prop="id" width="60"></el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '基本信息');?>" width="250">
                        <template slot-scope="scope">
                            <app-image style="float: left;margin-right: 5px;margin: 20px" mode="aspectFill" :src="scope.row.user.avatar"></app-image>
                            <div style="margin-top: 25px;">{{scope.row.user.nickname}}</div>
                            <div>
                                <img v-if="scope.row.user.platform" src="statics/img/mall/wx.png" alt="">
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '姓名');?>" width="250" prop="name">
                        <el-table-column label="<?= \Yii::t('plugins/bonus', '手机号');?>" prop="mobile">
                            <template slot-scope="scope">
                                <div>{{scope.row.user.name}}</div>
                                <div>{{scope.row.user.mobile}}</div>
                            </template>
                        </el-table-column>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '提现方式');?>" prop="name">
                        <template slot-scope="scope">
                            <div v-if="scope.row.pay_type == 'auto'"><?= \Yii::t('plugins/bonus', '自动提现');?></div>
                            <div v-if="scope.row.pay_type == 'wechat'"><?= \Yii::t('plugins/bonus', '微信线下转账');?></div>
                            <div v-if="scope.row.pay_type == 'alipay'"><?= \Yii::t('plugins/bonus', '支付宝线下转账');?></div>
                            <div v-if="scope.row.pay_type == 'bank'"><?= \Yii::t('plugins/bonus', '银行卡线下转账');?></div>
                            <div v-if="scope.row.pay_type == 'balance'"><?= \Yii::t('plugins/bonus', '余额提现');?></div>
                            <div v-if="scope.row.pay_type == 'bank'"><?= \Yii::t('plugins/bonus', '开户人');?>：{{scope.row.extra.name}}</div>
                            <div v-if="scope.row.pay_type == 'bank'"><?= \Yii::t('plugins/bonus', '开户行');?>：{{scope.row.extra.bank_name}}</div>
                            <div v-if="scope.row.pay_type == 'bank' || scope.row.pay_type == 'alipay'"><?= \Yii::t('plugins/bonus', '帐号');?>：{{scope.row.extra.mobile}}</div>
                            <div v-if="scope.row.pay_type == 'wechat' || scope.row.pay_type == 'alipay'"><?= \Yii::t('plugins/bonus', '姓名');?>：{{scope.row.extra.name}}</div>
                            <div v-if="scope.row.pay_type == 'wechat'"><?= \Yii::t('plugins/bonus', '微信号');?>：{{scope.row.extra.mobile}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column width='180' prop='all_children' label="<?= \Yii::t('plugins/bonus', '提现信息');?>">
                        <template slot-scope="scope">
                            <div><?= \Yii::t('plugins/bonus', '申请提现金额');?>：￥{{scope.row.cash.price}}</div>
                            <div><?= \Yii::t('plugins/bonus', '手续费');?>：￥{{scope.row.cash.service_charge}}</div>
                            <div><?= \Yii::t('plugins/bonus', '实际打款');?>：￥{{scope.row.cash.actual_price}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="状态" width="80" prop="status_text">
                        <template slot-scope="scope">
                            <el-tag size="small" type="info" v-if="scope.row.status == 0"><?= \Yii::t('plugins/bonus', '待审核');?></el-tag>
                            <el-tag size="small" type="primary" v-if="scope.row.status == 1"><?= \Yii::t('plugins/bonus', '待打款');?></el-tag>
                            <el-tag size="small" type="success" v-if="scope.row.status == 2"><?= \Yii::t('plugins/bonus', '已打款');?></el-tag>
                            <el-tag size="small" type="danger" v-if="scope.row.status == 3"><?= \Yii::t('plugins/bonus', '驳回');?></el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '时间');?>" width="220">
                        <template slot-scope="scope">
                            <div v-if="scope.row.status >= 0 && scope.row.time.created_at"><?= \Yii::t('plugins/bonus', '申请时间');?>：{{scope.row.time.created_at}}</div>
                            <div v-if="scope.row.status == 3 && scope.row.time.reject_at"><?= \Yii::t('plugins/bonus', '审核时间');?>：{{scope.row.time.reject_at}}</div>
                            <div v-if="scope.row.status > 0 && scope.row.time.apply_at"><?= \Yii::t('plugins/bonus', '审核时间');?>：{{scope.row.time.apply_at}}</div>
                            <div v-if="scope.row.status > 1 && scope.row.time.remittance_at"><?= \Yii::t('plugins/bonus', '打款时间');?>：{{scope.row.time.remittance_at}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '备注');?>" width="220">
                        <template slot-scope="scope">
                            <div v-if="scope.row.remark">{{scope.row.remark}}</div>
                            <div>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="toRemark(scope.row)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '备注');?>" placement="top">
                                        <img src="statics/img/plugins/remark.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '操作');?>" width="200">
                        <template slot-scope="scope">
                            <el-button v-if="scope.row.status == 1" type="text" size="mini" circle style="margin-top: 10px" @click.native="toTransfer(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '打款');?>" placement="top">
                                    <img src="statics/img/mall/transfer.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 0" type="text" size="mini" circle style="margin-top: 10px" @click.native="agree(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '审核');?>" placement="top">
                                    <img src="statics/img/plugins/audit.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 0 || scope.row.status == 1" type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="apply(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '驳回');?>" placement="top">
                                    <img src="statics/img/mall/nopass.png" alt="">
                                </el-tooltip>
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tabs>
            <div flex="box:last cross:center">
                <div></div>
                <div>
                    <el-pagination
                            v-if="list.length > 0"
                            style="display: inline-block;float: right;"
                            background :page-size="pagination.pageSize"
                            @current-change="pageChange"
                            layout="prev, pager, next, jumper" :current-page="pagination.current_page"
                            :total="pagination.totalCount">
                    </el-pagination>
                </div>
            </div>
        </div>
    </el-card>
    <el-dialog :title="title" :visible.sync="dialogContent" width="40%">
        <el-input type="textarea" :rows="8" v-model="content" :placeholder="placeholder" autocomplete="off"></el-input>
        <div slot="footer" class="dialog-footer">
            <el-button size="small" @click="dialogContent = false"><?= \Yii::t('plugins/bonus', '取消');?></el-button>
            <el-button size="small" type="primary" @click="contentConfirm(3,content)" v-if="title == <?= \Yii::t('plugins/bonus', '拒绝理由');?>" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
            <el-button size="small" type="primary" @click="remark" v-if="placeholder == <?= \Yii::t('plugins/bonus', '请填写备注内容');?>" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-dialog title="提示" :visible.sync="dialogAudit" width="30%">
        <div flex="dir:top main-center" style="text-align: center;font-size: 16px;">
            <div style="font-size: 18px;margin-bottom: 10px;"><?= \Yii::t('plugins/bonus', '是否确认通过提现申请');?></div>
            <div><?= \Yii::t('plugins/bonus', '申请提现金额');?>：￥{{detail.cash.price}}</div>
            <div><?= \Yii::t('plugins/bonus', '手续费');?>：￥{{detail.cash.service_charge}}</div>
            <div><?= \Yii::t('plugins/bonus', '实际打款');?>：￥{{detail.cash.actual_price}}</div>
        </div>
        <div slot="footer" class="dialog-footer">
            <el-button size="small" @click="dialogAudit = false"><?= \Yii::t('plugins/bonus', '取消');?></el-button>
            <el-button size="small" type="primary" @click="contentConfirm(1)" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-dialog title="提示" :visible.sync="dialogTransfer" width="100px">
        <div flex="dir:top main-center" style="text-align: center;font-size: 16px;">
            <div><?= \Yii::t('plugins/bonus', '是否确认打款');?></div>
            <div><?= \Yii::t('plugins/bonus', '实际打款');?>：<span style="color: #FF9C54">￥{{detail.cash.actual_price}}</span></div>
        </div>
        <div slot="footer" class="dialog-footer">
            <el-button size="small" @click="dialogTransfer = false"><?= \Yii::t('plugins/bonus', '取消');?></el-button>
            <el-button size="small" type="primary" @click="contentConfirm(2)" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
        </div>
    </el-dialog>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            search: {
                date_start: '',
                date_end: '',
                keyword: '',
                type: '4',
                page: 1,
                status: '-1',
                time: []
            },
            title:'',
            placeholder: '',
            loading: false,
            activeName: '-1',
            list: [],
            pagination: null,
            dialogLoading: false,
            dialogContent: false,
            dialogAudit: false,
            dialogTransfer: false,
            content: '',
            detail: {
                cash:{}
            },
            contentBtnLoading: false,
            exportList: [],
        },
        mounted() {
            this.loadData();
        },
        methods: {
            agree(e) {
                this.dialogAudit = !this.dialogAudit;
                this.detail = e;
            },

            toTransfer(e) {
                this.dialogTransfer = !this.dialogTransfer;
                this.detail = e;
            },

            changeTime() {
                if(this.search.time) {
                    this.search.date_start = this.search.time[0];
                    this.search.date_end = this.search.time[1];
                }else {
                    this.search.date_start = null;
                    this.search.date_end = null;
                }
                this.toSearch();
            },

            toSearch() {
                this.search.page = 1;
                this.loadData();
            },

            confirmSubmit() {
                this.search.status = this.activeName
            },
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/cash/index',
                        date_start: this.search.date_start,
                        date_end: this.search.date_end,
                        status: this.activeName,
                        keyword: this.search.keyword,
                        search_type: this.search.type,
                        page: this.search.page
                    },
                    method: 'get',
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination
                        this.exportList = e.data.data.export_list
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
            pageChange(page) {
                this.list = [];
                this.search.page = page;
                this.loadData();
            },
            handleClick(tab, event) {
                this.search.status = this.activeName;
                this.toSearch()
            },
            apply(res) {
                this.dialogContent = true;
                this.title = "<?= \Yii::t('plugins/bonus', '拒绝理由');?>";
                this.placeholder = "<?= \Yii::t('plugins/bonus', '请填写拒绝理由');?>";
                this.content = '';
                this.detail = res
            },

            toRemark(res) {
                this.dialogContent = true;
                this.title = "<?= \Yii::t('plugins/bonus', '添加备注');?>";
                this.placeholder = "<?= \Yii::t('plugins/bonus', '请填写备注内容');?>";
                this.detail = res;
                this.content = res.remark;
                if(res.remark) {
                    this.title = "<?= \Yii::t('plugins/bonus', '修改备注');?>";
                }
            },
            remark() {
                this.contentBtnLoading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/cash/remark',
                    },
                    data: {
                        remark: this.content,
                        id: this.detail.id,
                    },
                    method: 'post'
                }).then(e => {
                    this.contentBtnLoading = false;
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                        this.dialogContent = false;
                        this.content = '';
                        this.loadData();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.contentBtnLoading = false;
                    this.$message.error(e.data.msg);
                });
            },
            contentConfirm(status,content) {
                this.contentBtnLoading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/cash/cash-apply',
                    },
                    data: {
                        content: content,
                        id: this.detail.id,
                        status: status,
                    },
                    method: 'post'
                }).then(e => {
                    this.contentBtnLoading = false;
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                        this.dialogAudit = false;
                        this.dialogContent = false;
                        this.dialogTransfer = false;
                        this.content = '';
                        this.loadData();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.contentBtnLoading = false;
                    this.$message.error(e.data.msg);
                });
            }
        }
    });
</script>