<?php
/**
 * @copyright ©2022 opmall
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/15 9:47
 */
Yii::$app->loadViewComponent('c-price', __DIR__);
Yii::$app->loadViewComponent('recharge', __DIR__);
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .input-item {
        width: 200px;
        margin-right: 20px;
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

    #app .table-body .el-table .el-button {
        border-radius: 16px;
    }

    .create {
        height: 36px;
        line-height: 36px;
        float: right;
        color: #BCBCBC;
        margin-left: 20px;
    }

    .mall-user-info img,.mall-user-info span {
        vertical-align: middle;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0;">
        <div class="table-body">
            <div style="display: flex;margin-bottom: 20px;">
                <div class="input-item">
                    <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('admin/logistics', '请输入商城名称搜索');?>" type="text" clearable
                              @clear="search"
                              v-model="searchForm.keyword">
                        <el-button slot="append" @click="search" icon="el-icon-search"></el-button>
                    </el-input>
                </div>
                <el-button type="primary"
                           size="small"
                           @click="showCreateMallDialog()"><?= \Yii::t('admin/logistics', '选择小程序商城');?>
                </el-button>
                <div class="create">
                    <el-button type="primary"
                               size="small"
                               @click="recharge"><?= \Yii::t('admin/logistics', '账户充值');?>
                    </el-button>
                    <span style="color:#49A9FF;padding: 0 5px;">
                        <b>账户余额：￥{{account.usableAmt}}</b>
                    </span>
                    <span style="color:red;padding: 0 5px;" v-if="account.usableAmt <= 0">
                        <b>请前往充值，避免商城无法使用配送</b>
                    </span>
                </div>
            </div>

            <el-table v-loading="searchLoading" border :data="list" style="margin-bottom: 20px">
                <el-table-column prop="id" label="ID" width="60"></el-table-column>
                <el-table-column prop="name" label="<?= \Yii::t('admin/logistics', '商城名称');?>">
                    <template slot-scope="scope">
                        <el-button type="text" @click="toEnter(scope.row)">{{scope.row.name}}</el-button>
                        <div class="mall-user-info">
                            <span style=" vertical-align: middle;">{{scope.row.user.nickname}}</span>
                            <span style="color: #909399; vertical-align: middle;">{{scope.row.user.username}}</span>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column width="250" label="<?= \Yii::t('admin/logistics', '有效期');?>">
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.expired_type == '<?= \Yii::t('admin/logistics', '未到期');?>'" type="success"><?= \Yii::t('admin/logistics', '未到期');?></el-tag>
                        <el-tag v-if="scope.row.expired_type == '<?= \Yii::t('admin/logistics', '已到期');?>'" type="warning"><?= \Yii::t('admin/logistics', '已到期');?></el-tag>
                        <span>{{scope.row.expired_at == '0000-00-00 00:00:00' ? "<?= \Yii::t('admin/logistics', '永久');?>" : scope.row.expired_at}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('admin/logistics', '操作');?>" width="340">
                    <template slot-scope="scope">
                        <el-button plain size="mini" type="info" @click="openMoney(scope.row)"><?= \Yii::t('admin/logistics', '充值');?></el-button>
<!--                        <el-button plain size="mini" type="info">--><?//= \Yii::t('admin/logistics', '记录');?><!--</el-button>-->
                        <el-button plain size="mini" type="info"
                                   @click="priceSetting(scope.row)"><?= \Yii::t('admin/logistics', '价格设置');?>
                        </el-button>
                        <el-popover placement="top" style="margin: 0 5px;" v-model="scope.row.wl">
                            <div style="margin-bottom: 10px"><?= \Yii::t('admin/logistics', '确定禁用商城使用聚合配送');?>？</div>
                            <div style="text-align: right">
                                <el-button size="mini" type="primary"
                                           @click="scope.row.wl = false"><?= \Yii::t('admin/logistics', '取消');?>
                                </el-button>
                                <el-button size="mini" :loading="loading"
                                           @click="remove(scope.row)"><?= \Yii::t('admin/logistics', '确定');?>
                                </el-button>
                            </div>
                            <el-button plain size="mini" type="info" slot="reference">
                                <?= \Yii::t('admin/logistics', '删除');?>
                            </el-button>
                        </el-popover>
                    </template>
                </el-table-column>
            </el-table>

            <el-pagination
                    style="text-align: right"
                    v-if="pagination"
                    background
                    :page-size="pagination.pageSize"
                    @current-change="pageChange"
                    layout="prev, pager, next"
                    :total="pagination.totalCount">
            </el-pagination>
        </div>
    </el-card>

    <el-dialog title="<?= \Yii::t('admin/logistics', '选择商城');?>" :visible.sync="createMallDialogVisible" width="40%" :close-on-click-modal="false">
        <el-form label-width="100px" size="small" :model="createMallForm" :rules="createMallRules" ref="createMallForm">
            <el-form-item label="商城" prop="name">
                <el-autocomplete v-model="createMallForm.name" value-key="name" @keyup.enter.native="keyUp"
                                 :fetch-suggestions="querySearchAsync" placeholder="请输入用户昵称"
                                 @select="mallClick"></el-autocomplete>
            </el-form-item>
            <el-form-item style="text-align: right">
                <el-button size="small" @click="createMallDialogVisible = false"><?= \Yii::t('admin/logistics', '取消');?></el-button>
                <el-button size="small" :loading="loading" type="primary"
                           @click="createMallSubmit('createMallForm')"><?= \Yii::t('admin/logistics', '确定');?>
                </el-button>
            </el-form-item>
        </el-form>
    </el-dialog>

    <el-dialog title="<?= \Yii::t('admin/logistics', '充值');?>" :visible.sync="moneyDialogVisible" width="40%" :close-on-click-modal="false">
        <el-form label-width="100px" size="small" :model="moneyForm" :rules="moneyRules" ref="moneyForm">
            <el-form-item label="<?= \Yii::t('mall/logistics', '类型');?>" prop="type">
                <el-radio-group v-model.number="moneyForm.type">
                    <el-radio :label="1"><?= \Yii::t('mall/logistics', '充值');?></el-radio>
                    <el-radio :label="2"><?= \Yii::t('mall/logistics', '扣除');?></el-radio>
                </el-radio-group>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('mall/logistics', '金额');?>" prop="money">
                <el-input v-model="moneyForm.money" type="number" placeholder="<?= \Yii::t('mall/logistics', '请输入金额');?>">
                    <template slot="append"><?= \Yii::t('mall/logistics', '元');?></template>
                </el-input>
            </el-form-item>
            <el-form-item style="text-align: right">
                <el-button size="small" @click="moneyDialogVisible = false"><?= \Yii::t('admin/logistics', '取消');?></el-button>
                <el-button size="small" :loading="loading" type="primary"
                           @click="moneySubmit('moneyForm')"><?= \Yii::t('admin/logistics', '确定');?>
                </el-button>
            </el-form-item>
        </el-form>
    </el-dialog>

    <el-dialog append-to-body title="价格设置" :visible.sync="priceDisplay" custom-class="app-dialog-dialog" width="50%">
        <c-price :display="priceDisplay" :id="id" @close="close"></c-price>
    </el-dialog>

    <recharge preview-order-url="admin/logistics/preview-order" query-order-url="admin/logistics/query-order"
              :pay-price="rechargePrice"></recharge>
</div>

<script>
    new Vue({
        el: '#app',
        data() {
            return {
                createMallDialogVisible: false,
                keyword: '',
                createMallForm: {
                    id: null,
                    name: '',
                },
                createMallRules: {
                    name: [
                        {required: true, message: "<?= \Yii::t('admin/logistics', '请选择商城');?>"},
                    ],
                },
                searchForm: {
                    keyword: '',
                },
                searchLoading: false,
                list: [],
                account: {},
                pagination: null,
                loading: false,
                mallListPage: 0,
                priceDisplay: false,
                id: 0,

                moneyDialogVisible: false,
                moneyForm: {
                    type: 1
                },
                moneyRules: {
                    money: [
                        {required: true, message: "<?= \Yii::t('admin/logistics', '请输入金额');?>"},
                    ],
                },

                rechargePrice: null,
            };
        },
        created() {
            this.loadList({});
        },
        methods: {
            openMoney(row){
                this.moneyForm.id = row.id;
                this.moneyDialogVisible = true
            },
            toEnter(row) {
                this.$navigate({
                    r: 'admin/mall/entry',
                    id: row.id,
                    pic_url: null
                });
            },
            close(){
                this.priceDisplay = false;
            },
            priceSetting(row){
                this.priceDisplay = true;
                this.id = row.id;
            },
            showCreateMallDialog() {
                this.createMallDialogVisible = true;
                this.createMallForm.name = '';
                this.createMallForm.id = null;
            },
            createMallSubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.loading = true;
                        this.$request({
                            params: {
                                r: 'admin/logistics/add-mall',
                            },
                            method: 'post',
                            data: this.createMallForm,
                        }).then(e => {
                            this.loading = false;
                            if (e.data.code === 0) {
                                this.createMallDialogVisible = false;
                                this.$message.success(e.data.msg);
                                this.loadList({})
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                    }
                });
            },
            moneySubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.loading = true;
                        this.$request({
                            params: {
                                r: 'admin/logistics/money',
                            },
                            method: 'post',
                            data: this.moneyForm,
                        }).then(e => {
                            this.loading = false;
                            if (e.data.code === 0) {
                                this.moneyDialogVisible = false;
                                this.$message.success(e.data.msg);
                                location.reload();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                    }
                });
            },
            loadList(params) {
                params['r'] = 'admin/logistics/mall';
                params['keyword'] = this.searchForm.keyword;
                params['page'] = this.mallListPage;
                this.searchLoading = true;
                this.$request({
                    params: params,
                }).then(e => {
                    this.searchLoading = false;
                    if (e.data.code === 0) {
                        for (let i in e.data.data.list) {
                            e.data.data.list[i].wl = false;
                        }
                        this.list = e.data.data.list;
                        this.account = e.data.data.account;
                        this.pagination = e.data.data.pagination;
                    }
                }).catch(e => {
                });
            },
            search() {
                this.loadList({});
            },
            pageChange(page) {
                this.mallListPage = page;
                this.loadList({
                    page: page,
                });
            },
            remove(row) {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/logistics/mall-delete',
                    },
                    method: 'post',
                    data: {id: row.id},
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                        row.wl = false;
                        this.loadList({});
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            querySearchAsync(queryString, cb) {
                request({
                    params: {
                        r: 'admin/logistics/mall-search',
                        keyword: queryString
                    }
                }).then(response => {
                    if (response.data.code == 0) {
                        cb(response.data.data.list)
                    } else {
                        this.$message.error(response.data.msg);
                    }
                });
            },
            mallClick(row) {
                this.createMallForm.id = row.id
            },
            recharge() {
                let self = this;
                self.$prompt("<?= \Yii::t('admin/user', '请输入充值金额');?>", "<?= \Yii::t('admin/user', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('admin/user', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('admin/user', '取消');?>",
                    inputPattern: /\S+/,
                    inputErrorMessage: "<?= \Yii::t('admin/user', '请输入充值金额');?>",
                    inputType: 'number',
                }).then(({value}) => {
                    if(value <= 0){
                        this.$message.warning('<?= \Yii::t('mall/wlhulian', '金额必须大于0');?>');
                        return false;
                    }
                    self.rechargePrice = value;
                }).catch(() => {

                });
            },
        }
    });
</script>
