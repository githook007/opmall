<?php
/**
 * @copyright ©2022 opmall
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/17 14:48
 */
?>
<style>

    .plugin-icon-bg {
        display: inline-block;
        margin-right: 20px;
        border-radius: 10px;
        font-size: 0;
    }

    .plugin-icon {
        display: block;
        width: 100px;
        height: 100px;
    }

    .local-tag {
        background: #E6A23C;
        color: #fff;
        padding: 0 4px;
        height: 19px;
        line-height: 19px;
        font-size: 12px;
    }

    .header-box {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 10px;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }
    .el-card, .el-message {
        border-radius: 0;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                      @click="$navigate({r:'mall/plugin/index', cat_name: catName})">
                    {{catDisplayName?catDisplayName:'<?= \Yii::t('mall/plugin', '插件中心');?>'}}
                </span></el-breadcrumb-item>
            <el-breadcrumb-item><?= \Yii::t('mall/plugin', '插件详情');?></el-breadcrumb-item>
        </el-breadcrumb>
    </div>
    <el-card shadow="never" v-loading="loading" style="border:0">
        <template v-if="plugin">
            <div slot="header" class="form-body" flex="dir:left box:first cross:top">
                <div>
                    <div class="plugin-icon-bg" :style="{background: catColor}">
                        <img :src="plugin.pic_url" class="plugin-icon">
                    </div>
                </div>
                <div>
                    <div style="margin-bottom: 0px">{{plugin.display_name}}</div>
                    <div style="margin-bottom: 4px">
                        <span style="color: #909399;">{{plugin.name}}</span>
                        <span style="color: #909399;"
                              v-if="plugin.installed_plugin">[{{plugin.installed_plugin.version?plugin.installed_plugin.version:'<?= \Yii::t('mall/plugin', '未知版本');?>'}}]</span>
                        <span style="color: #909399;" v-else>[{{plugin.version?plugin.version:'<?= \Yii::t('mall/plugin', '未知版本');?>'}}]</span>
                        <span class="local-tag" v-if="plugin.type==='local'"><?= \Yii::t('mall/plugin', '本地');?></span>
                    </div>
                    <div style="color: #666666; margin-bottom: 4px;">{{plugin.desc}}</div>
                    <div>
                        <template v-if="plugin.installed_plugin">
                            <el-button :disabled="true" size="small"><?= \Yii::t('mall/plugin', '已安装');?></el-button>
                            <el-button v-if="plugin.new_version" type="warning" size="small"
                                       @click="showNewVersion = true"><?= \Yii::t('mall/plugin', '有更新');?>
                            </el-button>
                            <el-button @click="uninstall" size="small" :loading="uninstallLoading"><?= \Yii::t('mall/plugin', '卸载');?></el-button>
                        </template>
                        <template v-else>
                            <template v-if="plugin.type === 'local'">
                                <el-button @click="install" type="primary" size="small" :loading="installLoading"><?= \Yii::t('mall/plugin', '安装');?>
                                </el-button>
                            </template>
                            <template v-else>
                                <template v-if="plugin.order">
                                    <template v-if="plugin.order.is_pay === 1">
                                        <el-button @click="downloadConfirm" type="primary" size="small"
                                                   :loading="installLoading"><?= \Yii::t('mall/plugin', '安装');?>
                                        </el-button>
                                    </template>
                                    <template v-else>
                                        <el-button @click="payDialogVisible = true" type="primary" size="small"
                                                   :loading="payLoading"><?= \Yii::t('mall/plugin', '付款');?>
                                        </el-button>
                                    </template>
                                </template>
                                <template v-else>
                                    <el-button @click="buy" type="primary" size="small" :loading="buyLoading"><?= \Yii::t('mall/plugin', '购买');?>
                                    </el-button>
                                </template>
                            </template>
                        </template>
                    </div>
                </div>
            </div>

            <div v-html="plugin.content"></div>

        </template>
    </el-card>

    <el-dialog :visible.sync="payDialogVisible" width="480px">
        <template v-if="plugin && plugin.order">
            <div style="margin-bottom: 20px"><?= \Yii::t('mall/plugin', '请联系管理员完成付款操作');?></div>
            <div flex="box:first" style="margin-bottom: 12px">
                <div style="width: 80px"><?= \Yii::t('mall/plugin', '订单号');?></div>
                <div>{{plugin.order.order_no}}</div>
            </div>
            <div flex="box:first" style="margin-bottom: 12px">
                <div style="width: 80px"><?= \Yii::t('mall/plugin', '金额');?></div>
                <div>{{plugin.order.pay_price}}<?= \Yii::t('mall/plugin', '元');?></div>
            </div>
            <div flex="box:first">
                <div style="width: 80px"><?= \Yii::t('mall/plugin', '状态');?></div>
                <div v-if="plugin.order.is_pay==0" style="color: #E6A23C"><?= \Yii::t('mall/plugin', '待付款');?></div>
                <div v-if="plugin.order.is_pay==1" style="color: #67C23A"><?= \Yii::t('mall/plugin', '已付款');?></div>
            </div>
        </template>
    </el-dialog>

    <el-dialog :visible.sync="showNewVersion" title="<?= \Yii::t('mall/plugin', '版本更新');?>" :close-on-click-modal="false">
        <template v-if="plugin && plugin.new_version">
            <el-form label-width="75px">
                <el-form-item label="<?= \Yii::t('mall/plugin', '版本号');?>">{{plugin.new_version.version}}</el-form-item>
                <el-form-item label="<?= \Yii::t('mall/plugin', '更新内容');?>">{{plugin.new_version.content}}</el-form-item>
            </el-form>
            <div slot="footer">
                <el-button type="primary" size="small" @click="update" :loading="updateBtnLoading"><?= \Yii::t('mall/plugin', '立即更新');?></el-button>
            </div>
        </template>
    </el-dialog>

</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: true,
                plugin: null,
                buyLoading: false,
                payLoading: false,
                installLoading: false,
                payDialogVisible: false,
                uninstallLoading: false,
                showNewVersion: false,
                updateBtnLoading: false,
                catColor: '#409eff',
                catName: getQuery('cat_name'),
                catDisplayName: getQuery('cat_display_name'),
            };
        },
        created() {
            if (getQuery('cat_color')) {
                this.catColor = getQuery('cat_color');
            }
            this.loadData();
        },
        methods: {
            loadData() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'mall/plugin/detail',
                        name: getQuery('name'),
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.plugin = e.data.data;
                    }
                }).catch(e => {
                });
            },
            buy() {
                this.$confirm('<?= \Yii::t('mall/plugin', '确认购买该插件');?>', '<?= \Yii::t('mall/plugin', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/plugin', '确认');?>',
                    cancelButtonText: '<?= \Yii::t('mall/plugin', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    this.buyLoading = true;
                    this.$request({
                        params: {
                            r: 'mall/plugin/buy',
                            id: this.plugin.id,
                        },
                    }).then(e => {
                        this.buyLoading = false;
                        if (e.data.code === 0) {
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/plugin', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/plugin', '提示');?>', {
                                type: 'error',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        }
                    }).catch(e => {
                    });
                }).catch(() => {
                });
            },
            downloadConfirm() {
                this.$confirm('<?= \Yii::t('mall/plugin', '安装过程请勿关闭或刷新浏览器');?>', '<?= \Yii::t('mall/plugin', '注意');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/plugin', '确认');?>',
                    cancelButtonText: '<?= \Yii::t('mall/plugin', '取消');?>',
                    type: 'warning',
                    closeOnClickModal: false,
                }).then(() => {
                    this.download();
                }).catch(() => {
                });
            },
            download() {
                this.installLoading = true;
                this.$request({
                    params: {
                        r: 'mall/plugin/download',
                        id: this.plugin.id,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.install();
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('mall/plugin', '提示');?>', {
                            type: 'warning',
                        }).then(() => {
                            location.reload();
                        });
                    }
                }).catch(e => {
                    this.installLoading = false;
                });
            },
            install() {
                this.$confirm('<?= \Yii::t('mall/plugin', '将开始安装插件');?>', '<?= \Yii::t('mall/plugin', '提示');?>', {
                    closeOnClickModal: false,
                }).then(() => {
                    this.installLoading = true;
                    this.$request({
                        params: {
                            r: 'mall/plugin/install',
                            name: this.plugin.name,
                        },
                    }).then(e => {
                        this.installLoading = false;
                        if (e.data.code === 0) {
                            this.$alert('<?= \Yii::t('mall/plugin', '安装成功');?>', '<?= \Yii::t('mall/plugin', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/plugin', '安装失败');?>', {
                                type: 'error',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        }
                    }).catch(e => {
                    });
                }).catch(() => {
                    this.installLoading = false;
                });
            },
            uninstall() {
                this.$prompt('<?= \Yii::t('mall/plugin', '如果要卸载该插件');?>', '<?= \Yii::t('mall/plugin', '警告');?>', {}).then(({value}) => {
                    if (!value || typeof value !== 'string') {
                        return;
                    }
                    value = value.replace(/(^\s*)|(\s*$)/g, "").toLowerCase();
                    if (value !== 'yes') {
                        this.$message.warning('<?= \Yii::t('mall/plugin', '输入内容不正确');?>');
                        return;
                    }
                    this.uninstallLoading = true;
                    this.$request({
                        params: {
                            r: 'mall/plugin/uninstall',
                            name: this.plugin.name,
                        },
                    }).then(e => {
                        this.uninstallLoading = false;
                        if (e.data.code === 0) {
                            this.$alert('<?= \Yii::t('mall/plugin', '卸载成功');?>', '<?= \Yii::t('mall/plugin', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/plugin', '卸载失败');?>', {
                                type: 'error',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        }
                    }).catch(e => {
                        this.uninstallLoading = false;
                    });
                }).catch(() => {
                });
            },
            update() {
                this.$confirm('<?= \Yii::t('mall/plugin', '确认更新版本');?>', '<?= \Yii::t('mall/plugin', '提示');?>').then(e => {
                    this.updateBtnLoading = true;
                    this.download();
                }).catch(e => {
                });
            },
        }
    });
</script>
