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
        display: inline-block;
        width: 250px;
        margin: 0 0 20px 20px;
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
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/user', '会员购买记录');?></span>
                <div style="float: right;margin: -5px 0">
                    <app-new-export-dialog-2
                            :field_list='export_list'
                            :params="searchData"
                            action_url="mall/user/level-log"
                            @selected="exportConfirm">
                    </app-new-export-dialog-2>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-date-picker size="small" v-model="date" type="datetimerange"
                            style="float: left"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            range-separator="<?= \Yii::t('mall/user', '至');?>" start-placeholder="<?= \Yii::t('mall/user', '开始日期');?>"
                            @change="selectDateTime"
                            end-placeholder="<?= \Yii::t('mall/user', '结束日期');?>">
            </el-date-picker>
            <div class="input-item">
                <el-input @keyup.enter.native='search' size="small" placeholder="<?= \Yii::t('mall/user', '请输入搜索内容');?>" v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table :data="form" border style="width: 100%" v-loading="listLoading">
                <el-table-column prop="id" label="ID" width="80"></el-table-column>
                <el-table-column prop="order_no" label="<?= \Yii::t('mall/user', '订单号');?>"></el-table-column>
                <el-table-column prop="user.nickname" label="<?= \Yii::t('mall/user', '昵称');?>"></el-table-column>
                <el-table-column prop="pay_price" label="<?= \Yii::t('mall/user', '支付金额');?>"></el-table-column>
                <el-table-column prop="pay_info" label="<?= \Yii::t('mall/user', '购买情况');?>"></el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('mall/user', '支付时间');?>"></el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination @current-change="pagination" background layout="prev, pager, next, jumper"
                               :page-count="pageCount"></el-pagination>
            </div>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                searchData: {
                    keyword: '',
                    start_date: '',
                    end_date: '',
                },
                date: '',
                keyword: '',
                form: [],
                pageCount: 0,
                listLoading: false,
                export_list: [],
            };
        },
        methods: {
            exportConfirm() {
                this.searchData.keyword = this.keyword;
                this.searchData.date = this.date;
            },
            pagination(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            search() {
                this.page = 1;
                this.getList();
            },

            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/user/level-log',
                        page: this.page,
                        start_date: this.searchData.start_date,
                        end_date: this.searchData.end_date,
                        user_id: getQuery('user_id'),
                        keyword: this.keyword,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.form = e.data.data.list;
                        this.export_list = e.data.data.export_list;
                        this.pageCount = e.data.data.pagination.page_count;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            selectDateTime(e) {
                if(e != null) {
                    this.searchData.start_date = e[0];
                    this.searchData.end_date = e[1];
                }else {
                    this.searchData.start_date = '';
                    this.searchData.end_date = '';
                }
                this.search();
            }
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
