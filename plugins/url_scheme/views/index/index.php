
<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: wxf
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-info .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        display: inline-block;
        width: 285px;
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
    .el-form-item__content .el-input-group {
        vertical-align: middle;
    }
    .rules {
        padding: 20px;
        background-color: #F4F4F5;
        margin-bottom: 20px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('plugins/url_scheme', '链接生成工具');?>(<?= \Yii::t('plugins/url_scheme', '微信小程序');?>)</span>
                <el-button style="float: right; margin: -5px 0" type="primary" @click="toggleDialog" size="small">
                    <?= \Yii::t('plugins/url_scheme', '立即生成');?>
                </el-button>
            </div>
        </div>
        <div class="table-body">
            <div class="rules" style="background-color: #ECF5FE">
                <div><?= \Yii::t('plugins/url_scheme', '微信内等拉起小程序的业务场景');?></div>
            </div>
            <div class="input-item">
                <el-input @keyup.enter.native="search" size="small" placeholder="<?= \Yii::t('plugins/url_scheme', '请输入链接名称搜索');?>" v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-table class="table-info" :data="list" border style="width: 100%" v-loading="listLoading">
                <el-table-column label="<?= \Yii::t('plugins/url_scheme', '链接名称');?>" prop="name" width="300"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/url_scheme', '创建时间');?>" prop="created_at" width="200"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/url_scheme', '失效时间');?>" prop="expire" width="200"></el-table-column>
                <el-table-column label="iOS<?= \Yii::t('plugins/url_scheme', '专用链接');?>" prop="url_scheme" width="320">
                    <template slot-scope="scope">
                        <span :id="'ios'+scope.$index">{{scope.row.url_scheme}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/url_scheme', '通用链接');?>" prop="url" width="390">
                    <template slot-scope="scope">
                        <span :id="'anzhuo'+scope.$index">{{scope.row.url}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/url_scheme', '操作');?>">
                    <template slot-scope="scope">
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" :data-clipboard-target="'#ios'+scope.$index">
                            <el-tooltip effect="dark" content="iOS<?= \Yii::t('plugins/url_scheme', '专用链接');?>" placement="top">
                                <img src="statics/img/mall/copy-other.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" :data-clipboard-target="'#anzhuo'+scope.$index">
                            <el-tooltip effect="dark" content="<?= \Yii::t('plugins/url_scheme', '通用链接');?>" placement="top">
                                <img src="statics/img/mall/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        :page-size="pagination.pageSize" hide-on-single-page background @current-change="pageChange" layout="prev, pager, next, jumper" :total="pagination.total_count">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <el-dialog title="<?= \Yii::t('plugins/url_scheme', '生成推广链接');?>" :visible.sync="dialogAdd" width="30%">
        <el-form @submit.native.prevent :model="addForm" label-width="100px" :rules="addFormRules" ref="addForm">
            <el-form-item label="<?= \Yii::t('plugins/url_scheme', '链接名称');?>" prop="name">
                <el-input size="small" placeholder="<?= \Yii::t('plugins/url_scheme', '限制14个字以内');?>" maxlength="14" v-model="addForm.name"></el-input>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('plugins/url_scheme', '小程序路径');?>" prop="link">
                <el-input :disabled="true" size="small" v-model="addForm.link.new_link_url" autocomplete="off">
                    <app-pick-link slot="append" @selected="selectAdvertUrl">
                        <el-button size="mini"><?= \Yii::t('plugins/url_scheme', '选择链接');?></el-button>
                    </app-pick-link>
                </el-input>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('plugins/url_scheme', '分销商选择');?>" prop="nickname">
                <el-autocomplete size="small" style="width: 70%;" v-model="addForm.nickname" value-key="nickname" :fetch-suggestions="querySearchAsync" placeholder="<?= \Yii::t('plugins/url_scheme', '请选择分销商');?>" @select="shareClick"></el-autocomplete>
                <div style="font-size: 12px;color: #909399;height: 28px;"><?= \Yii::t('plugins/url_scheme', '请选择绑定指定分销商');?></div>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('plugins/url_scheme', '失效时间');?>" prop="is_expire">
                <el-radio v-model="addForm.is_expire" :label="0"><?= \Yii::t('plugins/url_scheme', '永久有效');?></el-radio>
                <el-radio v-model="addForm.is_expire" :label="1">
                    <span style="position: relative;">
                        <el-input size="small" type="number" style="width: 160px;margin-right: 10px;" v-model="addForm.expire_time">
                            <template slot="append"><?= \Yii::t('plugins/url_scheme', '天');?></template>
                        </el-input>
                        <div v-if="addForm.is_expire == 1" style="font-size: 12px;color: #909399;position: absolute;bottom: -25px;left: 0;">0< <?= \Yii::t('plugins/url_scheme', '失效时间');?> ≤30</div>
                    </span>
                    <span><?= \Yii::t('plugins/url_scheme', '后失效');?></span>
                </el-radio>
            </el-form-item>
            <el-form-item>
                <el-button size="small" style="float: right;padding: 0;width: 70px;height: 32px;margin-left: 20px" type="primary" @click="addSubmit" :loading="btnLoading"><?= \Yii::t('plugins/url_scheme', '确定');?></el-button>
                <el-button size="small" style="float: right;padding: 0;width: 70px;height: 32px;" @click="toggleDialog"><?= \Yii::t('plugins/url_scheme', '取消');?></el-button>
            </el-form-item>
        </el-form>
    </el-dialog>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.copy-btn');

    var self = this;
    clipboard.on('success', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('plugins/url_scheme', '复制成功');?>');
        e.clearSelection();
    });
    clipboard.on('error', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('plugins/url_scheme', '复制失败');?>');
    });
    const app = new Vue({
        el: '#app',
        data() {
            return {
                addFormRules: {
                    name: [
                        { required: true, message: '<?= \Yii::t('plugins/url_scheme', '链接名称不得为空');?>', trigger: 'blur' }
                    ],
                    link: [
                        { required: true, message: '<?= \Yii::t('plugins/url_scheme', '小程序链接不得为空');?>', trigger: 'blur' }
                    ],
                    is_expire: [
                        { required: true, message: '<?= \Yii::t('plugins/url_scheme', '请选择失效时间');?>', trigger: 'blur' }
                    ],
                },
                addForm: {
                    name: '',
                    link: {
                        new_link_url: ''
                    },
                    is_expire: 0,
                    expire_time: ''
                },
                keyword: '',
                userkeyword: '',
                dialogAdd: false,
                btnLoading: false,
                listLoading: false,
                page: 1,
                pagination: {},
                list: [],
            };
        },
        methods: {
            selectAdvertUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    self.addForm.link = item;
                })
            },
            //搜索
            querySearchAsync(queryString, cb) {
                this.userkeyword = queryString;
                this.shareUser(cb);
            },

            shareClick(row) {
                this.addForm.user_id = row.id;
                console.log(this.addList)
            },
            search() {
                this.page = 1;
                this.getList();
            },
            shareUser(cb) {
                request({
                    params: {
                        r: 'mall/share/index-data',
                        keyword: this.userkeyword,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        cb(e.data.data.list);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {});
            },
            addSubmit() {
                this.$refs.addForm.validate((valid) => {
                    if (valid) {
                        if(this.addForm.is_expire == 1 && !(this.addForm.expire_time > 0 && this.addForm.expire_time < 30)) {
                            this.$message.error('<?= \Yii::t('plugins/url_scheme', '请填写正确的失效时间');?>');
                            return false;
                        }
                        this.btnLoading = true;
                        request({
                            params: {
                                r: '/plugin/url_scheme/mall/index/index',
                            },
                            data: this.addForm,
                            method: 'post',
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.toggleDialog();
                                this.getList();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        });
                    }
                });
            },
            toggleDialog() {
                this.dialogAdd = !this.dialogAdd;
                this.addForm.expire_time = '';
                if(this.$refs.addForm) {
                    this.$refs.addForm.resetFields();
                }
            },
            pageChange(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: '/plugin/url_scheme/mall/index/index',
                        keyword: this.keyword,
                        page: this.page
                    },
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
        },
        created: function () {
            this.getList();
        }
    });
</script>