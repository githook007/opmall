<?php
Yii::$app->loadViewComponent('app-refuse-remark', __DIR__ . "/../");
defined('YII_ENV') or exit('Access Denied');
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

    .table-body .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }
    .item {
        color: #000;
    }
    .fixed-paginations{
        padding-bottom: 26px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"style="height: 800px;">
        <div slot="header">
            <span><?= \Yii::t('plugins/invoice', '已开发票');?></span>
        </div>
        <div class="table-body">
            <app-refuse-remark
                    :url="editRemarkUrl"
                    @close="dialogClose"
                    @submit="dialogSubmit"
                    :is-show="refuseRemark"
                    :order="datas">
            </app-refuse-remark>

            <el-form size="small" :inline="true" :model="search">
                <el-form-item>
                    <span><?= \Yii::t('plugins/invoice', 'invoice_z35');?></span>
                    <el-select style="width: 200px" @change="typeSearch(search.invoice_type_code)" v-model="search.invoice_type_code" clearable>
                        <el-option label="<?= \Yii::t('plugins/invoice', '增值税专用发票');?>" value="004"></el-option>
                        <el-option label="<?= \Yii::t('plugins/invoice', '增值税普通发票');?>" value="007"></el-option>
                        <el-option label="<?= \Yii::t('plugins/invoice', '增值税卷式发票');?>" value="025"></el-option>
                        <el-option label="<?= \Yii::t('plugins/invoice', '增值税电子普通发票');?>" value="026"></el-option>
                        <el-option label="<?= \Yii::t('plugins/invoice', '增值税电子专用发票');?>" value="028"></el-option>
                        <el-option label="<?= \Yii::t('plugins/invoice', '区块链电子发票');?>" value="032"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <div class="input-item">
                        <el-input @keyup.enter.native="pondSearch(search.keyword)" size="small" placeholder="<?= \Yii::t('plugins/invoice', '申请用户昵称或手机');?>" v-model="search.keyword" clearable @clear='pondSearch(search.keyword)'>
                            <el-button slot="append" icon="el-icon-search" @click="pondSearch(search.keyword)"></el-button>
                        </el-input>
                    </div>
                </el-form-item>
                <div flex="dir:left cross:center" style="margin-bottom: 20px;display: inline-block;">
                    <div flex="dir:left cross:center">
                        <div style="margin-right: 10px;"><?= \Yii::t('plugins/invoice', '申请时间');?></div>
                        <el-date-picker
                                size="small"
                                @change="changeTime"
                                v-model="search.time"
                                type="datetimerange"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                range-separator="<?= \Yii::t('plugins/invoice', '至');?>"
                                start-placeholder="<?= \Yii::t('plugins/invoice', '开始日期');?>"
                                end-placeholder="<?= \Yii::t('plugins/invoice', '结束日期');?>">
                        </el-date-picker>
                    </div>
                </div>
                <el-form-item>
                    <div class="input-item">
                        <el-input @keyup.enter.native="pondSearchs(search.orderSn)" size="small" placeholder="<?= \Yii::t('plugins/invoice', '订单号');?>" v-model="search.orderSn" clearable @clear='pondSearchs(search.orderSn)'>
                            <el-button slot="append" icon="el-icon-search" @click="pondSearchs(search.orderSn)"></el-button>
                        </el-input>
                    </div>
                </el-form-item>
            </el-form>

            <el-table v-loading="loading" border :data="list" style="width: 100%;margin-bottom: 15px;">
                <el-table-column prop="add_time" label="<?= \Yii::t('plugins/invoice', '申请时间');?>" width="150"></el-table-column>
                <el-table-column prop="nickname" label="<?= \Yii::t('plugins/invoice', '申请用户');?>" width="150"></el-table-column>
                <el-table-column prop="order.order_no" label="<?= \Yii::t('plugins/invoice', '订单号');?>" width="230"></el-table-column>
                <el-table-column prop="buyer_title" label="<?= \Yii::t('plugins/invoice', '购方名称');?>" width="230"></el-table-column>
                <el-table-column prop="buyer_taxpayer_num" label="<?= \Yii::t('plugins/invoice', '购方纳税人识别号');?>"  width="210"></el-table-column>
                <el-table-column prop="buyer_address" label="<?= \Yii::t('plugins/invoice', '购方地址');?>" width="210"></el-table-column>
                <el-table-column prop="buyer_phone" label="<?= \Yii::t('plugins/invoice', '购方电话');?>"  width="150"></el-table-column>
                <el-table-column prop="buyer_bank_name" label="<?= \Yii::t('plugins/invoice', '购方银行名称');?>" width="230"></el-table-column>
                <el-table-column prop="buyer_bank_account" label="<?= \Yii::t('plugins/invoice', '购方银行账号');?>" width="230"></el-table-column>
                <el-table-column prop="payee" label="<?= \Yii::t('plugins/invoice', '收件人姓名');?>" width="120"></el-table-column>
                <el-table-column prop="buyer_email" label="<?= \Yii::t('plugins/invoice', '收票人邮箱');?>" width="200"></el-table-column>
                <el-table-column prop="invoice_type_code" label="<?= \Yii::t('plugins/invoice', '开具发票类型');?>" width="250"></el-table-column>
                <el-table-column prop="medium" label="<?= \Yii::t('plugins/invoice', '发票介质');?>" width="100"></el-table-column>
                <el-table-column prop="total_pay_price" label="<?= \Yii::t('plugins/invoice', '发票金额');?>" width="100"></el-table-column>


            </el-table>
            <!--<div class="fixed-pagination">-->
            <div class="fixed-paginations">
                <el-pagination
                        :page-size="10"
                        hide-on-single-page
                        style="display: inline-block;float: right;"
                        background
                        @current-change="pageChange"
                        layout="total, prev, pager, next, jumper"
                        :total="total_count"></el-pagination>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                search: {
                    keyword: '',
                    status: '2',
                    date_start: '',
                    date_end: '',
                },
                activeName: '0',
                loading: false,
                list: [],
                pagination: null,
                total_count: 0,
                page:1,
                refuseRemark: false,  // 拒绝窗口默认关闭,
                // 拒绝窗口请求URL
                editRemarkUrl: 'plugin/invoice/mall/applyOrder/refuse',
                datas: {},
                detailInfo: false,  // 申请详情窗口,
            };
        },

        methods: {
            //分页
            pageChange(page) {
                this.page = page;
                this.getList();
            },
            // 时间筛选
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
            dialogClose() {
                this.refuseRemark = false;  // 关闭拒绝窗口
                this.detailInfo = false;  // 关闭详情
            },
            // 搜索
            toSearch() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/applyOrder',
                        date_start: this.search.date_start,
                        date_end: this.search.date_end,
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                    }
                }).catch(e => {

                });
            },

            // 选择器筛选
            typeSearch(val) {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/applyOrder',
                        invoice_type_code: val
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                    }
                }).catch(e => {

                });
            },
            // 关键词搜索
            pondSearch(val) {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/applyOrder',
                        nickname: val
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                    }
                }).catch(e => {

                });
            },
            // 订单号搜索
            pondSearchs(val) {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/applyOrder',
                        order_sn: val
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                    }
                }).catch(e => {

                });
            },
            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/invoice/mall/applyOrder',
                        status:2,
                        page: this.page,
                        pageSize: 10
                    },
                }).then(e => {
                    if (e.data.code == 0) {
                        this.loading = false;
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                        this.total_count = e.data.data.pagination.total_count;
                    }
                }).catch(e => {
                });
            },
            dialogSubmit() {
                this.getList();
            },
        },
        created() {
            this.getList();
        }
    })
</script>