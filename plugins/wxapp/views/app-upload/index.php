<?php
/**
 * Created by IntelliJ IDEA.
 * User: opmall
 * Date: 2019/2/26
 * Time: 10:57
 */
?>
<script>const _branch = '<?=$branch?>';</script>
<style>
    .table-body {
        padding: 40px 20px 20px;
        background-color: #fff;
    }

    .outline {
        display: inline-block;
        vertical-align: middle;
        line-height: 32px;
        height: 32px;
        color: #F56E6E;
        cursor: pointer;
        font-size: 24px;
        margin: 0 5px;
    }

    .plugin-list {
        width: 200px;
        margin-bottom: 20px;
    }

    .plugin-list .plugin-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 10px 0;
    }

    .plugin-list .plugin-item:last-child {
        border: none;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" class="box-card" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span>{{preTitle}}<?= \Yii::t('plugins/wxapp', '小程序发布');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" size="small" @click="getAppQrcode" :loading="app_qrcode_loading">
                        <?= \Yii::t('plugins/wxapp', '获取小程序二维码');?>
                    </el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-steps :active="step" finish-status="success" align-center
                      style="border-bottom: 1px solid #ebeef5;padding-bottom: 20px">
                <el-step title="<?= \Yii::t('plugins/wxapp', '获取配置');?>"></el-step>
                <el-step title="<?= \Yii::t('plugins/wxapp', '预览小程序');?>"></el-step>
                <el-step title="<?= \Yii::t('plugins/wxapp', '上传成功');?>"></el-step>
            </el-steps>
            <div v-if="step==0" style="padding: 20px 10px">
                <div style="text-align: center;color: #ff4544; margin-bottom: 10px">
                    <?= \Yii::t('plugins/wxapp', '必须先去');?>
                    <span style="color: #33cc33"><?= \Yii::t('plugins/wxapp', '小程序代码上传');?></span>
                    <?= \Yii::t('plugins/wxapp', '设置IP白名单');?><span style="color: #33cc33;">{{ip}}</span>
                </div>
                <div style="text-align: center;color: #ff4544; margin-bottom: 10px">
                    <?= \Yii::t('plugins/wxapp', '必须先去');?>
                    <span style="color: #33cc33"><?= \Yii::t('plugins/wxapp', '插件管理');?></span>
                    <?= \Yii::t('plugins/wxapp', '中添加相应的插件');?>
                </div>
                <div style="text-align: center; margin-bottom: 10px;">
                    <?= \Yii::t('plugins/wxapp', '目前小程序直播组件是在公测期间');?>
                </div>
                <div class="plugin-list">
                    <div v-for="(item, index) in all_plugins"
                         :key="index"
                         class="plugin-item"
                         flex="box:last">
                        <div>
                            <div>{{item.name}}</div>
                            <div style="color: #707379">{{item.key}}</div>
                        </div>
                        <div>
                            <el-switch v-model="item.active"></el-switch>
                        </div>
                    </div>
                </div>
                <div style="padding: 20px;text-align: center;">
                    <el-button type="primary" @click="getData" ><?= \Yii::t('plugins/wxapp', '下一步');?></el-button>
                </div>
            </div>
            <div v-else style="text-align: center; padding: 20px 0">
                <el-button type="primary" @click="preview" :loading="upload_loading" v-if="get_preview"><?= \Yii::t('plugins/wxapp', '获取预览二维码');?>
                </el-button>
                <div style="text-align: center;margin-top: 30px;" v-if="get_preview && !preview_qrcode">
                    <div style="color: #909399;">
                        <div><?= \Yii::t('plugins/wxapp', '获取大约会有几秒左右延时');?></div>
                    </div>
                </div>
                <div style="text-align: center" v-if="preview_qrcode">
                    <img :src="preview_qrcode"
                         style="width: 150px;height: 150px; border: 1px solid #e2e2e2;margin-bottom: 12px">
                    <div style="margin-bottom: 12px;"><?= \Yii::t('plugins/wxapp', '扫描二维码可以预览小程序');?></div>
                    <el-button type="primary" @click="upload" :loading="upload_loading" v-if="!upload_success"><?= \Yii::t('plugins/wxapp', '上传小程序');?>
                    </el-button>
                    <div v-else>
                        <div style="margin-bottom: 12px">
                            <span><?= \Yii::t('plugins/wxapp', '上传成功');?></span>
                            <span><?= \Yii::t('plugins/wxapp', '请登录微信小程序平台');?></span>
                            <a href="https://mp.weixin.qq.com/" target="_blank">https://mp.weixin.qq.com/</a>
                            <span><?= \Yii::t('plugins/wxapp', '发布小程序');?></span>
                        </div>
                        <div style="margin-bottom: 12px">
                            <div><?= \Yii::t('plugins/wxapp', '版本号');?>{{version}}</div>
                            <div><?= \Yii::t('plugins/wxapp', '描述');?>{{desc}}</div>
                        </div>
                        <div>
                            <img style="max-width: 100%;height: auto;border: 1px dashed #35b635;"
                                 src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/upload-tip.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </el-card>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                app_qrcode_loading: false,
                app_qrcode: false,
                step: 0,
                upload_loading: false,
                get_preview: false,
                preview_qrcode: false,
                upload_success: false,
                version: '',
                desc: '',
                remark: '',
                token: '',
                getDataLoading: false,
                loadingAppPlugins: false,
                all_plugins: [
                    {
                        key: 'live-player-plugin',
                        name: '<?= \Yii::t('plugins/wxapp', '小程序直播组件');?>',
                        active: false,
                    },
                ],
                ip: "",
            };
        },
        computed: {
            preTitle() {
                if (_branch == 'nomch') {
                    return '<?= \Yii::t('plugins/wxapp', '单商户');?>';
                }
                return '';
            },
        },
        created() {
            this.loadAppPlugins();
        },
        methods: {
            loadAppPlugins() {
                this.loadingAppPlugins = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/app-upload/app-plugins',
                    },
                }).then(e => {
                    this.loadingAppPlugins = false;
                    for (let i in this.all_plugins) {
                        if (e.data.data.indexOf(this.all_plugins[i].key) > -1) {
                            this.all_plugins[i].active = true;
                        }
                    }
                    this.ip = e.data.ip;
                }).catch(e => {
                    this.loadingAppPlugins = false;
                });
            },
            getData() {
                let plugins = [];
                for (let i in this.all_plugins) {
                    if (this.all_plugins[i].active) {
                        plugins.push(this.all_plugins[i].key);
                    }
                }
                this.getDataLoading = true;
                this.$request({
                    method: 'post',
                    params: {
                        r: 'plugin/wxapp/app-upload/get-invoke-code',
                    },
                    data: {
                        plugins: plugins,
                    },
                }).then(e => {
                    this.step = 1;
                    this.get_preview = true;
                    this.token = e.data.data.token;
                }).catch(e => {
                    this.getDataLoading = false;
                });
            },
            getAppQrcode() {
                let html = '';
                if (this.app_qrcode) {
                    html = '<div style="text-align: center;"><img src='
                        + this.app_qrcode
                        + ' style="width: 200px;"></div>';
                    this.$alert(html, '<?= \Yii::t('plugins/wxapp', '小程序码');?>', {
                        dangerouslyUseHTMLString: true
                    });
                    return;
                }
                this.app_qrcode_loading = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/app-upload/app-qrcode',
                    },
                }).then(e => {
                    this.app_qrcode_loading = false;
                    if (e.data.code === 0) {
                        this.app_qrcode = e.data.data.qrcode;
                        html = '<div style="text-align: center;"><img src='
                            + this.app_qrcode
                            + ' style="width: 200px;"></div>';
                        this.$alert(html, '<?= \Yii::t('plugins/wxapp', '小程序码');?>', {
                            dangerouslyUseHTMLString: true
                        });
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('plugins/wxapp', '提示');?>');
                    }
                }).catch(e => {
                    this.app_qrcode_loading = false;
                });
            },
            preview() {
                this.upload_loading = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/app-upload/index',
                        action: 'preview',
                        token: this.token,
                    },
                }).then(e => {
                    this.upload_loading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.qrcode) {
                            this.preview_qrcode = e.data.data.qrcode;
                            this.step = 2;
                            this.get_preview = false;
                        }
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('plugins/wxapp', '提示');?>', {
                            callback() {
                                location.reload();
                            },
                        });
                    }
                }).catch(e => {
                    this.upload_loading = false;
                });
            },
            upload() {
                this.upload_loading = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/app-upload/index',
                        action: 'upload',
                        token: this.token,
                    },
                }).then(e => {
                    this.upload_loading = false;
                    if (e.data.code === 0) {
                        this.step = 3;
                        this.upload_success = true;
                        this.version = e.data.data.version;
                        this.desc = e.data.data.desc;
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('plugins/wxapp', '提示');?>');
                    }
                }).catch(e => {
                    this.upload_loading = false;
                });
            },
        },
    });
</script>