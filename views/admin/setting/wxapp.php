<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/22
 * Time: 16:23
 */
?>
<style>
    .form-body {
        display: flex;
        justify-content: center;
    }

    .form-body .el-form {
        width: 700px;
        margin-top: 10px;
    }

    .url-form.form-body .el-form {
        margin-top: -10px;
    }

    .currency-width {
        width: 400px;
    }

    .currency-width .el-input__inner {
        height: 35px;
        line-height: 35px;
        border-radius: 8px;
    }

    .isAppend .el-input__inner {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .form-body .currency-width .el-input-group__append {
        width: 80px;
        background-color: #2E9FFF;
        color: #fff;
        padding: 0;
        line-height: 35px;
        height: 35px;
        text-align: center;
        border-radius: 8px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border: 0;
    }

    .plugin-list .plugin-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 10px 0;
    }

    .plugin-list .plugin-item:last-child {
        border: none;
    }
    .button-item {
        margin: 20px 0;
        width: 80px;
    }

    .template-list .el-table {
        max-height: 500px;
        overflow: auto;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">
        <div style="margin-bottom: 20px"><?= \Yii::t('admin/setting', '公众号');?></div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="150px" :model="form" ref="form">
                <el-form-item label="APPID">
                    <el-input class="currency-width" v-model="web.mp_app_id"></el-input>
                </el-form-item>
                <el-form-item label="APPSECRET">
                    <el-input class="currency-width" v-model="web.mp_app_secret"></el-input>
                </el-form-item>
            </el-form>
        </div>
        <div style="margin-bottom: 20px"><?= \Yii::t('admin/setting', '基础配置');?></div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="175px" :model="form" ref="form">
                <el-form-item label="<?= \Yii::t('admin/setting', '授权事件接收配置');?>(URL)">
                    <div flex="dir:left cross:center" style="height: 32px;">
                        <div id="auth"><?=Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . "/platform-notify/wechat.php";?></div>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" data-clipboard-target="#auth">
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/wechat', '复制');?>" placement="top">
                                <img src="statics/img/plugins/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/setting', '消息与事件接收配置');?>(URL)">
                    <div flex="dir:left cross:center" style="height: 32px;">
                        <div id="msg"><?=Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/platform-notify/wechat.php/$APPID$';?></div>
                        <el-button class="copy-btn" circle size="mini" type="text" data-clipboard-action="copy" data-clipboard-target="#msg">
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/wechat', '复制');?>" placement="top">
                                <img src="statics/img/plugins/copy.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </div>
                </el-form-item>
                <el-form-item label="APPID">
                    <el-input class="currency-width" v-model="form.appid"></el-input>
                </el-form-item>
                <el-form-item label="APPSECRET">
                    <el-input class="currency-width" v-model="form.appsecret"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/setting', '消息校验Token');?>">
                    <el-input class="currency-width" v-model="form.token"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/setting', '消息解密Key');?>">
                    <el-input class="currency-width" v-model="form.encoding_aes_key"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/setting', '当月可提审次数');?>">
                    <el-button type="primary" @click="showQuota" type="text"><?= \Yii::t('admin/setting', '点击查询');?></el-button>
                </el-form-item>
            </el-form>
        </div>
        <div style="margin-bottom: 20px">
            <?= \Yii::t('admin/setting', '请手动通过开发工具把代码上传至开放平台');?>
            <el-button @click="showLog" type="text"><?= \Yii::t('admin/setting', '发布记录');?></el-button>
        </div>
    </el-card>
    <el-button class='button-item' :loading="submitLoading" type="primary" @click="submit" size="small"><?= \Yii::t('admin/setting', '保存');?></el-button>
    <el-dialog class="template-list" title="<?= \Yii::t('admin/setting', '发布记录');?>" :visible.sync="dialogTableVisible">
        <el-table :data="list" v-loading="formLoading">
            <el-table-column property="user_version" label="<?= \Yii::t('admin/setting', '版本号');?>"></el-table-column>
            <el-table-column property="create_at" label="<?= \Yii::t('admin/setting', '添加模板时间');?>"></el-table-column>
            <el-table-column label="<?= \Yii::t('admin/setting', '操作');?>" width="150">
                <template slot-scope="scope">
                    <el-button class="set-el-button" size="mini" type="text" circle @click="destroy(scope.row,scope.$index)">
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('admin/setting', '删除');?>" placement="top">
                            <img src="statics/img/mall/del.png" alt="">
                        </el-tooltip>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </el-dialog>
    <el-dialog class="quota-list" title="<?= \Yii::t('admin/setting', '当月可提审次数');?>" :visible.sync="dialogQuotaVisible" width="25%">
        <div class="quota-content" v-loading="quotaLoading">
            <div class="quota-item"><?= \Yii::t('admin/setting', '当月总共可提审次数');?>: {{limit}}<?= \Yii::t('admin/setting', '次');?></div>
            <div class="quota-item"><?= \Yii::t('admin/setting', '当月剩余可提审次数');?>: {{rest}}<?= \Yii::t('admin/setting', '次');?></div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="dialogQuotaVisible = false"><?= \Yii::t('admin/setting', '我知道了');?></el-button>
        </span>
    </el-dialog>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.copy-btn');
    var self = this;
    clipboard.on('success', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('mall/wechat', '复制成功');?>');
        e.clearSelection();
    });
    clipboard.on('error', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('mall/wechat', '复制失败');?>');
    });
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                submitLoading: false,
                formLoading: false,
                dialogTableVisible: false,
                dialogQuotaVisible: false,
                quotaLoading: false,
                form: {
                    appid: '',
                    appsecret: '',
                    token: '',
                    encoding_aes_key: '',
                },
                web: {},
                list: [],
                rest: 0,
                limit: 0,
            };
        },
        created() {
            this.loadData();
        },
        methods: {
            destroy(column, index) {
                this.$confirm("<?= \Yii::t('admin/setting', '确认删除该记录吗?');?>?", "<?= \Yii::t('admin/setting', '提示');?>", {
                    type: 'warning'
                }).then(() => {
                    this.formLoading = true;
                    request({
                        params: {
                            r: 'admin/setting/del-template'
                        },
                        data: {template_id: column.template_id},
                        method: 'post'
                    }).then(e => {
                        this.list.splice(index, 1)
                        this.formLoading = false;
                    }).catch(e => {
                        this.formLoading = false;
                    });

                });
            },
            loadData() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/wxapp',
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.platform) {
                            this.form = e.data.data.platform;
                        }
                        if (e.data.data.web) {
                            this.web = e.data.data.web;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                })
            },
            getLog() {
                this.formLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/template-list',
                    },
                }).then(e => {
                    this.formLoading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list.template_list;
                    }
                }).catch(e => {
                });
            },
            showLog() {
                this.dialogTableVisible = true;
                this.getLog();
            },
            showQuota() {
                let self = this;
                self.$prompt("<?= \Yii::t('admin/setting', '请填写已授权三方的小程序商城id');?>", "<?= \Yii::t('admin/setting', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('admin/setting', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('admin/setting', '取消');?>",
                    inputPattern: /\S+/,
                    inputErrorMessage: "<?= \Yii::t('admin/setting', '请填写已授权三方的小程序商城id');?>",
                }).then(({value}) => {
                    this.dialogQuotaVisible = true;
                    this.getQuota(value);
                }).catch(() => {

                });
            },
            getQuota(mall_id) {
                this.quotaLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/quota',
                        mall_id: mall_id,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.quotaLoading = false;
                        this.rest = e.data.data.rest;
                        this.limit = e.data.data.limit;
                    } else {
                        this.dialogQuotaVisible = false;
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.dialogQuotaVisible = false;
                    this.$message.error(e.data.msg);
                });
            },
            submit() {
                this.submitLoading = true;
                this.$request({
                    params: {
                        r: 'admin/setting/wxapp',
                    },
                    method: 'post',
                    data: {
                        platform: this.form,
                        web: this.web,
                    },
                }).then(e => {
                    this.submitLoading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
        }
    });
</script>
