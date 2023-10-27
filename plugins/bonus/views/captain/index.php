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

    .platform-img {
        width: 24px;
        height: 24px;
        margin-top: 8px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('plugins/bonus', '队长管理');?></span>
            <el-form size="small" :inline="true" :model="search" style="float: right;margin-top: -5px;">
                <el-form-item>
                    <app-new-export-dialog-2
                        :params="search"
                        @selected="confirmSubmit"
                        :field_list='exportList'
                        action_url="plugin/bonus/mall/captain/index">
                    </app-new-export-dialog-2>
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
                            start-placeholder="<?= \Yii::t('plugins/bonus', '开始日期');?>"
                            end-placeholder="<?= \Yii::t('plugins/bonus', '结束日期');?>">
                    </el-date-picker>
                </div>
                <div class="input-item">
                    <el-input @keyup.enter.native="toSearch" size="small" v-model="search.keyword" clearable
                              placeholder="<?= \Yii::t('plugins/bonus', '请输入搜索内容');?>" @clear="toSearch">
                        <el-select size="small" v-model="search.type" slot="prepend" class="select">
                            <el-option key="4" label="<?= \Yii::t('plugins/bonus', '用户ID');?>" value="4"></el-option>
                            <el-option key="1" label="<?= \Yii::t('plugins/bonus', '昵称');?>" value="1"></el-option>
                            <el-option key="2" label="<?= \Yii::t('plugins/bonus', '姓名');?>" value="2"></el-option>
                            <el-option key="3" label="<?= \Yii::t('plugins/bonus', '手机号');?>" value="3"></el-option>
                        </el-select>
                        <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                    </el-input>
                </div>
            </div>
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '全部');?>" name="-1"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '未审核');?>" name="0"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('plugins/bonus', '已审核');?>" name="1"></el-tab-pane>
                <el-table :data="list" border v-loading="loading" size="small" style="margin-bottom: 15px;">
                    <el-table-column label="ID" prop="user_id" width="60"></el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '基本信息');?>" width="400">
                        <template slot-scope="scope">
                            <app-image style="float: left;margin-right: 5px;margin: 20px" mode="aspectFill" :src="scope.row.avatar"></app-image>
                            <div style="margin-top: 15px;">
                                <div>{{scope.row.user.nickname}}</div>
                                <img class="platform-img" :src="scope.row.platform_icon" alt="">
                            </div>
                            <div v-if="scope.row.remark" class="content">
                                {{scope.row.remark}}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '队长等级');?>" width="150" prop="level">
                        <template slot-scope="scope">
                            <div>
                                <span v-if="scope.row.level" style="font-size: 14px">{{scope.row.level.name}}</span>
                                <el-button type="text" circle @click="changeLevel(scope.row)">
                                    <img src="statics/img/mall/order/edit.png" alt="">
                                </el-button>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '姓名');?>" prop="name">
                        <el-table-column label="<?= \Yii::t('plugins/bonus', '手机号');?>" prop="mobile">
                            <template slot-scope="scope">
                                <div>{{scope.row.name}}</div>
                                <div>{{scope.row.mobile}}</div>
                            </template>
                        </el-table-column>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '累计分红');?>" prop="name">
                        <el-table-column label="<?= \Yii::t('plugins/bonus', '可提现分红');?>" prop="mobile">
                            <template slot-scope="scope">
                                <div>{{scope.row.all_bonus}}</div>
                                <div>{{scope.row.total_bonus}}</div>
                            </template>
                        </el-table-column>
                    </el-table-column>
                    <el-table-column width='200' prop='all_member' label="<?= \Yii::t('plugins/bonus', '队员数量');?>">
                        <template slot-scope="scope">
                            <el-button type="text" @click="dialogChildShow(scope.row)">
                                {{scope.row.all_member}}
                            </el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '时间');?>" width="250">
                        <template slot-scope="scope">
                            <div v-if="scope.row.status >= 0"><?= \Yii::t('plugins/bonus', '时间');?>：{{scope.row.created_at}}</div>
                            <div v-if="scope.row.status == 1"><?= \Yii::t('plugins/bonus', '审核时间');?>：{{scope.row.apply_at}}</div>
                            <div v-if="scope.row.status == 2"><?= \Yii::t('plugins/bonus', '审核时间');?>：{{scope.row.apply_at}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '状态');?>" width="80" prop="status">
                        <template slot-scope="scope">
                            <el-tag size="small" type="info" v-if="scope.row.status == 0"><?= \Yii::t('plugins/bonus', '待审核');?></el-tag>
                            <el-tag size="small" v-if="scope.row.status == 1"><?= \Yii::t('plugins/bonus', '已通过');?></el-tag>
                            <el-tag size="small" type="danger" v-if="scope.row.status == 2"><?= \Yii::t('plugins/bonus', '拒绝');?></el-tag>
                            <el-tag size="small" type="warning" v-if="scope.row.status == 3"><?= \Yii::t('plugins/bonus', '处理中');?></el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('plugins/bonus', '操作');?>" width="250px" fixed="right">
                        <template slot-scope="scope">
                            <el-button v-if="scope.row.status == 0" type="text" size="mini" circle style="margin-top: 10px" @click.native="agree(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '通过申请');?>" placement="top">
                                    <img src="statics/img/mall/pass.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 0" type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="apply(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '拒绝申请');?>" placement="top">
                                    <img src="statics/img/mall/nopass.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 1" type="text" size="mini" circle style="margin-top: 10px" @click.native="toRelease(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '解除队长');?>" placement="top">
                                    <img src="statics/img/plugins/release.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 1" type="text" size="mini" circle style="margin-top: 10px" @click.native="order(scope.row.name)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '队员订单');?>" placement="top">
                                    <img src="statics/img/mall/share/order.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button v-if="scope.row.status == 2" type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="deleteShare(scope.row.user_id)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '删除');?>" placement="top">
                                    <img src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </el-button>
                            <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="openContent(scope.row)">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/bonus', '备注');?>" placement="top">
                                    <img src="statics/img/mall/order/add_remark.png" alt="">
                                </el-tooltip>
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tabs>
            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                    hide-on-single-page
                    background :page-size="pagination.pageSize"
                    @current-change="pageChange"
                    layout="prev, pager, next, jumper" :current-page="pagination.current_page"
                    :total="pagination.totalCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <el-dialog :title="title" :visible.sync="dialogContent" width="30%">
        <el-form>
            <el-form-item>
                <el-input type="textarea" :rows="5" v-model="content" :placeholder="placeholder" autocomplete="off"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button size="small" @click="dialogContent = false"><?= \Yii::t('plugins/bonus', '取消');?></el-button>
            <el-button size="small" type="primary" v-if="title == '<?= \Yii::t('plugins/bonus', '拒绝理由');?>'"
                       @click="beApply" :loading="contentBtnLoading">
                <?= \Yii::t('plugins/bonus', '确定');?>
            </el-button>
            <el-button size="small" type="primary" v-else-if="title == '<?= \Yii::t('plugins/bonus', '解除理由');?>'"
                       @click="beRelease" :loading="contentBtnLoading">
                <?= \Yii::t('plugins/bonus', '确定');?>
            </el-button>
            <el-button size="small" type="primary" v-else @click="beRemark" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-dialog title="<?= \Yii::t('plugins/bonus', '修改队长等级');?>" :visible.sync="toChange" width="30%">
        <el-form>
            <el-form-item label="<?= \Yii::t('plugins/bonus', '队长等级');?>">
                <el-select size="small" style="width: 70%;" v-model="value" placeholder="<?= \Yii::t('plugins/bonus', '请选择');?>">
                    <el-option label="无" value="0"></el-option>
                    <el-option
                        v-for="item in member"
                        :key="item.id"
                        :label="item.name"
                        :value="item.id">
                    </el-option>
                </el-select>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button size="small" @click="toChange = false"><?= \Yii::t('plugins/bonus', '取消');?></el-button>
            <el-button size="small" type="primary" @click="changeSubmit" :loading="contentBtnLoading"><?= \Yii::t('plugins/bonus', '确定');?></el-button>
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
                time: []
            },
            title:'',
            placeholder: '',
            loading: false,
            activeName: '-1',
            list: [],
            value: null,
            toChange: false,
            pagination: {},
            dialogLoading: false,
            dialogContent: false,
            content: "",
            detail: {},
            contentBtnLoading: false,
            exportList: [],
            member: [],
            status: null
        },
        mounted() {
            this.loadData();
            this.getMember();
        },
        methods: {
            // 获取数据
            getMember() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/members/all-member'
                    },
                    method: 'get',
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.member = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
            changeLevel(e) {
                console.log(e)
                this.toChange = true;
                this.detail = e;
                if(e.level) {
                    this.value = +e.level.id;
                }else {
                    this.value = ''
                }
            },
            changeSubmit() {
                this.contentBtnLoading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/level'
                    },
                    data: {
                        user_id: this.detail.user_id,
                        level: this.value,
                    },
                    method: 'post',
                }).then(e => {
                    this.contentBtnLoading = false;
                    if (e.data.code == 0) {
                        this.toChange = false;
                        this.loadData();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
            // 通过审核
            agree(e) {
                this.$confirm("<?= \Yii::t('plugins/bonus', '是否确认通过审核');?>", "<?= \Yii::t('plugins/bonus', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/bonus', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/bonus', '取消');?>"
                }).then(res => {
                    this.detail = e;
                    this.status = 1;
                    this.content = "<?= \Yii::t('plugins/bonus', '后台管理员审核通过');?>";
                    this.detail.status = 3;
                    this.beApply();
                }).catch(res => {
                    this.$message({
                        type: 'info',
                        message: "<?= \Yii::t('plugins/bonus', '取消了操作');?>"
                    });
                });
            },
            // 发送审核消息
            beApply() {
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/apply',
                    },
                    data: {
                        user_id: this.detail.user_id,
                        status: this.status,
                        reason: this.content,
                    },
                    method: 'post',
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        if(this.status == 1) {
                            this.detail = {};
                            this.status = null;
                            this.content = '';
                            let queue_id = e.data.data.queue_id;
                            this.passStatus(queue_id)
                        }else {
                            this.$message.success(e.data.data);
                            this.loadData();
                            this.detail = {};
                            this.status = null;
                            this.content = '';
                            this.dialogContent = false;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },

            passStatus(queue_id) {
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/apply-status',
                        queue_id: queue_id
                    },
                    method: 'get',
                }).then(res => {
                    loading = false;
                    if (res.data.code == 0) {
                        if(res.data.data.retry && res.data.data.retry == 1) {
                            this.passStatus(queue_id);
                        }else {
                            this.$message.success("<?= \Yii::t('plugins/bonus', '操作成功');?>");
                            this.loadData();
                            this.contentBtnLoading = false;
                            this.dialogContent = false;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                        this.contentBtnLoading = false;
                    }
                })
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
            // 搜索
            toSearch() {
                this.search.page = 1;
                this.loadData();
            },
            // 获取状态
            confirmSubmit() {
                this.search.status = this.activeName;
                this.search.search_type = this.search.type;
            },
            // 获取数据
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/index',
                        status: this.activeName,
                        date_start: this.search.date_start,
                        date_end: this.search.date_end,
                        keyword: this.search.keyword,
                        search_type: this.search.type,
                        page: this.search.page,
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
            // 分页
            pageChange(page) {
                this.search.page = page;
                this.loadData();
            },
            // 获取数据状态
            handleClick(tab, event) {
                this.search.status = this.activeName;
                this.toSearch();
            },
            // 审核拒绝发起
            apply(e) {
                this.dialogContent = true;
                this.title = "<?= \Yii::t('plugins/bonus', '拒绝理由');?>";
                this.placeholder = "<?= \Yii::t('plugins/bonus', '请填写拒绝理由');?>";
                this.content = '';
                this.detail = e;
                this.status = 2;
            },
            // 前往订单
            order(name) {
                navigateTo({
                    r: 'plugin/bonus/mall/order/index',
                    name: name
                })
            },
            // 备注
            beRemark() {
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/remark',
                    },
                    data: {
                        user_id: this.detail.user_id,
                        remark: this.content,
                    },
                    method: 'post',
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                        this.loadData();
                        this.detail = {};
                        this.content = '';
                        this.dialogContent = false;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
            toRelease(e) {
                this.dialogContent = true;
                this.title = "<?= \Yii::t('plugins/bonus', '解除理由');?>";
                this.placeholder = "<?= \Yii::t('plugins/bonus', '请填写解除理由');?>";
                this.content = '';
                this.detail = e;
            },
            // 解除队长
            beRelease() {
                this.contentBtnLoading = true;
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/remove',
                    },
                    data:{
                        user_id: this.detail.user_id,
                        reason: this.content,
                    },
                    method: 'post'
                }).then(e => {
                    this.contentBtnLoading = false;
                    if (e.data.code == 0) {
                        let queue_id = e.data.data.queue_id;
                        this.remove(queue_id)
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.$message.error(e.data.msg);
                });
            },
            remove(queue_id) {
                request({
                    params: {
                        r: 'plugin/bonus/mall/captain/remove-status',
                        queue_id: queue_id
                    },
                    method: 'get',
                }).then(res => {
                    loading = false;
                    if (res.data.code == 0) {
                        if(res.data.data.retry && res.data.data.retry == 1) {
                            this.remove(queue_id);
                        }else {
                            this.$message.success("<?= \Yii::t('plugins/bonus', '操作成功');?>");
                            this.loadData();
                            this.contentBtnLoading = false;
                            this.dialogContent = false;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                        this.contentBtnLoading = false;
                    }
                })
            },
            // 删除记录
            deleteShare(id) {
                this.$confirm("<?= \Yii::t('plugins/bonus', '是否删除该条记录');?>", "<?= \Yii::t('plugins/bonus', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/bonus', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/bonus', '取消');?>",
                    type: 'warning',
                    center: true,
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = "<?= \Yii::t('plugins/bonus', '执行中');?>...";
                            request({
                                params: {
                                    r: 'plugin/bonus/mall/captain/delete',
                                },
                                data:{
                                    user_id: id
                                },
                                method: 'post'
                            }).then(e => {
                                done();
                                instance.confirmButtonLoading = false;
                                if (e.data.code == 0) {
                                    this.loadData();
                                } else {
                                    this.$message.error(e.data.msg);
                                }
                            }).catch(e => {
                                done();
                                instance.confirmButtonLoading = false;
                                this.$message.error(e.data.msg);
                            });
                        } else {
                            done();
                        }
                    }
                }).then(() => {
                }).catch(e => {
                    this.$message({
                        type: 'info',
                        message: "<?= \Yii::t('plugins/bonus', '取消了操作');?>"
                    });
                });
            },
            // 申请添加备注
            openContent(res) {
                this.dialogContent = true;
                this.title = "<?= \Yii::t('plugins/bonus', '添加备注');?>";
                this.placeholder = "<?= \Yii::t('plugins/bonus', '请填写备注内容');?>";
                this.detail = res;
                this.content = '';
                if(res.remark) {
                    this.title = "<?= \Yii::t('plugins/bonus', '修改备注');?>";
                    this.content = res.remark
                }
            },
            // 访问成员
            dialogChildShow(e) {
                this.$navigate({
                    r: 'plugin/bonus/mall/captain/detail',
                    captain_id: e.user_id,
                });
            }
        }
    });
</script>
