<?php
/**
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/17 14:48
 */

$nickname = \Yii::$app->user->identity->nickname;
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

    .service-box .item {
        margin-bottom: 8px;
    }

    .service-box .image-box {
        border: 1px solid #EBEEF5;
        padding: 10px;
    }

    .online-pay .el-radio__label {
        vertical-align: middle;
    }

    .online-pay .label {
        width: 75px;
        margin-left: 75px;
    }

    .online-pay .item-box {
        margin-bottom: 15px;
    }

    .online-pay .qrcode-bg {
        width: 140px;
        height: 140px;
        background-image:url('statics/img/admin/app_manage/qrcode_bg.png');
        background-size: 100% 100%;
        padding: 3px;
    }

    .online-pay .header {
        font-size: 16px;
        border-bottom: 1px solid #EBEEF5;
        padding: 5px 0 20px;
    }

    .hint-box .title {
        color: #242424;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .hint-box .content {
        font-size: 14px;
        color: #545B60;
    }

    .hint-box .icon {
        width: 73px;
        height: 76px;
        position: absolute;
        top: -40px;
    }
</style>
<div id="app" v-cloak>
    <div slot="header" class="header-box">
        <el-breadcrumb separator="/">
            <el-breadcrumb-item><?= \Yii::t('admin/app_manage', '插件详情');?></el-breadcrumb-item>
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
                              v-if="plugin.installed_plugin">[{{plugin.installed_plugin.version?plugin.installed_plugin.version:'<?= \Yii::t('admin/app_manage', '未知版本');?>'}}]</span>
                        <span style="color: #909399;" v-else>[{{plugin.version?plugin.version:'<?= \Yii::t('admin/app_manage', '本地');?>'}}]</span>
                        <span class="local-tag" v-if="plugin.type==='local'"><?= \Yii::t('admin/app_manage', '本地');?></span>
                    </div>
                    <div>{{plugin.abstract}}</div>
                    <div style="color: #666666; margin-bottom: 4px;">{{plugin.desc}}</div>
                    <div>
                        <!-- 总账号 -->
                        <template v-if="plugin.is_super_admin">
                            <div flex="box:last cross:center">
                                <div style="color: #FF4544;">
                                    <template v-if="!plugin.order">
                                        <span v-if="plugin.price > 0">
                                            ￥<span style="font-size: 20px;">{{plugin.price}}</span>
                                        </span>
                                        <span style="font-size: 20px;" v-else><?= \Yii::t('admin/app_manage', '免费');?></span>
                                    </template>
                                </div>
                                <div>
                                    <template v-if="plugin.installed_plugin">
                                        <el-button :disabled="true" size="small"><?= \Yii::t('admin/app_manage', '已安装');?></el-button>
                                        <el-button v-if="plugin.new_version" type="warning" size="small"
                                                   @click="showNewVersion = true"><?= \Yii::t('admin/app_manage', '有更新');?>
                                        </el-button>
                                        <el-button @click="uninstall" size="small" :loading="uninstallLoading"><?= \Yii::t('admin/app_manage', '卸载');?></el-button>
                                    </template>
                                    <template v-else>
                                        <template v-if="plugin.type === 'local'">
                                            <el-button @click="install" type="primary" size="small" :loading="installLoading"><?= \Yii::t('admin/app_manage', '安装');?>
                                            </el-button>
                                        </template>
                                        <template v-else>
                                            <template v-if="plugin.order">
                                                <template v-if="plugin.order.is_pay === 1">
                                                    <el-button @click="downloadConfirm" type="primary" size="small"
                                                               :loading="installLoading"><?= \Yii::t('admin/app_manage', '安装');?>
                                                    </el-button>
                                                </template>
                                                <template v-else>
                                                    <el-button @click="payDialogVisible = true" type="primary" size="small"
                                                               :loading="payLoading"><?= \Yii::t('admin/app_manage', '付款');?>
                                                    </el-button>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <el-button @click="buy" type="primary" size="small" :loading="buyLoading"><?= \Yii::t('admin/app_manage', '购买');?>
                                                </el-button>
                                            </template>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </template>
                        <!-- 子账号 -->
                        <template v-else>
                            <div flex="box:last cross:center">
                                <div style="color: #FF4544;">
                                    <template v-if="plugin.app_manage && !plugin.is_buy">
                                        <span v-if="plugin.app_manage.price > 0">
                                            ￥<span style="font-size: 20px;">{{plugin.app_manage.price}}</span>
                                        </span>
                                        <span style="font-size: 20px;" v-else><?= \Yii::t('admin/app_manage', '免费');?></span>
                                    </template>
                                </div>
                                <div>
                                    <el-button
                                            v-if="!plugin.is_buy"
                                            @click="previewOrder"
                                            type="primary"
                                            size="small"
                                            :loading="buyLoading">
                                        <?= \Yii::t('admin/app_manage', '购买');?>
                                    </el-button>
                                    <el-tag type="info" v-else><?= \Yii::t('admin/app_manage', '已购买');?></el-tag>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div v-html="plugin.content"></div>

        </template>
    </el-card>

    <el-dialog :visible.sync="payDialogVisible" width="480px">
        <template v-if="plugin && plugin.order">
            <div style="margin-bottom: 20px"><?= \Yii::t('admin/app_manage', '请联系管理员完成付款操作');?></div>
            <div flex="box:first" style="margin-bottom: 12px">
                <div style="width: 80px"><?= \Yii::t('admin/app_manage', '订单号');?></div>
                <div>{{plugin.order.order_no}}</div>
            </div>
            <div flex="box:first" style="margin-bottom: 12px">
                <div style="width: 80px"><?= \Yii::t('admin/app_manage', '金额');?></div>
                <div>{{plugin.order.pay_price}}<?= \Yii::t('admin/app_manage', '元');?></div>
            </div>
            <div flex="box:first">
                <div style="width: 80px"><?= \Yii::t('admin/app_manage', '状态');?></div>
                <div v-if="plugin.order.is_pay==0" style="color: #E6A23C"><?= \Yii::t('admin/app_manage', '待付款');?></div>
                <div v-if="plugin.order.is_pay==1" style="color: #67C23A"><?= \Yii::t('admin/app_manage', '已付款');?></div>
            </div>
        </template>
    </el-dialog>

    <!-- 线上支付 -->
    <el-dialog @close="previewOrderDialogClose" :show-close="false" class="online-pay" :visible.sync="previewOrderDialog.visible" width="725px">
        <template slot="title">
            <div flex="box:last"  class="header">
                <div style="width: 200px;">{{previewOrderDialog.title}}</div>
                <div><?= \Yii::t('admin/app_manage', '开通账号');?>{{previewOrderDialog.nickname}}</div>
            </div>
        </template>
        <template v-if="plugin">
            <div flex="box:first" class="item-box">
                <div class="label"><?= \Yii::t('admin/app_manage', '订单编号');?></div>
                <div>{{previewOrderDialog.order_no}}</div>
            </div>
            <div flex="box:first" class="item-box">
                <div class="label"><?= \Yii::t('admin/app_manage', '支付金额');?></div>
                <div style="color: #ff4544;font-size: 18px;">{{previewOrderDialog.order.pay_price}}元</div>
            </div>

            <div flex="box:first" class="item-box">
                <div class="label"><?= \Yii::t('admin/app_manage', '支付方式');?></div>
                <div>
                    <el-radio
                            @change="payTypeChange"
                            v-for="(item, index) in plugin.setting.pay_list"
                            :key="index"
                            v-model="previewOrderDialog.order.pay_type"
                            :label="item">
                        <div style="display: inline-block;">
                            <div flex="dir:left cross:center">
                                <img style="width: 23px;height: 20px;margin-right: 3px;" v-if="item == '<?= \Yii::t('admin/app_manage', '微信');?>'" src="statics/img/admin/app_manage/wechat_icon.png">
                                <img style="width: 20px;height: 20px;margin-right: 3px;" v-if="item == '<?= \Yii::t('admin/app_manage', '支付宝');?>'" src="statics/img/admin/app_manage/alipay_icon.png">
                                <span>{{item}}</span>
                            </div>
                        </div>
                    </el-radio>
                </div>
            </div>

            <div flex="box:first" class="item-box">
                <div class="label"></div>
                <div flex="dir:left cross:center">
                    <div v-loading="previewOrderDialog.loading" class="qrcode-bg">
                        <img style="width: 100%;height: 100%;" :src="previewOrderDialog.code_url">
                    </div>
                    <div style="margin-left: 15px;font-size: 15px;" flex="dir:top">
                        <span><?= \Yii::t('admin/app_manage', '打开');?>{{previewOrderDialog.order.pay_type}},</span>
                        <span style="margin-top: 5px;"><?= \Yii::t('admin/app_manage', '扫描二维码支付');?></span>
                    </div>
                </div>
            </div>

            <div style="text-align: right;">
                <el-button
                        @click="hintDialog.visible = true"
                        type="primary"
                        size="small">
                    <?= \Yii::t('admin/app_manage', '关闭');?>
                </el-button>
            </div>
        </template>
    </el-dialog>

    <el-dialog class="hint-box" :visible.sync="hintDialog.visible" :show-close="false" width="301px">
        <template slot="title">
            <div flex="dir:top cross:center">
                <img class="icon" src="statics/img/admin/app_manage/hint_icon.png">
            </div>
        </template>
        <div flex="dir:top cross:center">
            <div class="title"><?= \Yii::t('admin/app_manage', '确定要关闭支付');?></div>
            <div class="content"><?= \Yii::t('admin/app_manage', '你是否要关闭支付');?></div>
            <div class="content"><?= \Yii::t('admin/app_manage', '别手误哦');?>~</div>
            <div style="width: 252px;margin-top: 20px;" flex="dir:left box:mean">
                <div style="text-align: center;"><el-button @click="cancelPayment"><?= \Yii::t('admin/app_manage', '确认取消');?></el-button></div>
                <div style="text-align: center;"><el-button @click="hintDialog.visible = false" type="primary"><?= \Yii::t('admin/app_manage', '继续支付');?></el-button></div>
            </div>
        </div>
    </el-dialog>



    <!-- 客服支付 -->
    <el-dialog :visible.sync="serviceDialog.visible" width="480px">
        <template v-if="plugin">
            <div class="service-box" flex="dir:top cross:center">
                <div style="font-size: 18px;" v-if="plugin.app_manage" class="item"><?= \Yii::t('admin/app_manage', '金额');?><span style="color: #ff4544;">{{plugin.app_manage.price}}<?= \Yii::t('admin/app_manage', '元');?></span></div>
                <div
                        flex="dir:top"
                        v-for="(service, index) in plugin.setting.customer_service_list"
                        v-if="index == plugin.random_number"
                        :key="index">
                    <div class="item image-box" flex="dir:top">
                        <img style="width: 180px;height: 180px;" :src="service.qrcode_url">
                        <span style="margin-top: 10px;"><?= \Yii::t('admin/app_manage', '微信号');?>{{service.wechat_name}}</span>
                    </div>
                    <div style="margin-left: 10px;">
                        <div class="item"><?= \Yii::t('admin/app_manage', '联系人');?>{{service.name}}</div>
                        <div class="item"><?= \Yii::t('admin/app_manage', '联系电话');?>{{service.mobile}}</div>
                        <div class="item" v-if="service.is_all_day"><?= \Yii::t('admin/app_manage', '工作时间：全天在线');?></div>
                        <div class="item" v-else><?= \Yii::t('admin/app_manage', '工作时间');?>{{service.start_time}}-{{service.end_time}}</div>
                    </div>
                </div>
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
                previewOrderDialog: {
                    visible: false,
                    loading: false,
                    order: {
                        pay_price: null,
                        pay_type: '',
                    },
                    code_url: '',
                    order_no: '',
                    title: '',
                    nickname: '<?= $nickname ?>',
                },
                serviceDialog: {
                    visible: false,
                },
                hintDialog: {
                    visible: false
                },
                intervalTime: null,
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
                        r: 'admin/app-manage/detail',
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
                this.$confirm('<?= \Yii::t('admin/app_manage', '确认购买该插件');?>', '<?= \Yii::t('admin/app_manage', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('admin/app_manage', '确认');?>',
                    cancelButtonText: '<?= \Yii::t('admin/app_manage', '取消');?>',
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
                            this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '提示');?>', {
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
            previewOrder() {
                let self = this;
                if (self.plugin.app_manage.pay_type == 'online') {
                    if (!self.plugin.setting.pay_list) {
                        self.$message.warning('<?= \Yii::t('admin/app_manage', '未设置支付方式');?>');
                        return false;
                    }
                    self.previewOrderDialog.order.pay_price = self.plugin.app_manage.price;
                    self.previewOrderDialog.title = self.plugin.app_manage.display_name + '<?= \Yii::t('admin/app_manage', '购买');?>';
                    self.previewOrderDialog.order.pay_type = self.plugin.setting.pay_list[0];

                    self.previewOrderSubmit();
                } else {
                    self.serviceDialog.visible = true;
                }

            },
            previewOrderSubmit() {
                this.buyLoading = true;
                this.previewOrderDialog.loading = true;
                this.$request({
                    method: 'post',
                    params: {
                        r: 'admin/app-manage/preview-order',
                    },
                    data: {
                        name: this.plugin.name,
                        pay_type: this.previewOrderDialog.order.pay_type
                    }
                }).then(e => {
                    this.buyLoading = false;
                    if (e.data.code === 0) {
                        if (e.data.data.is_success) {
                            this.$message.success('<?= \Yii::t('admin/app_manage', '支付成功');?>');
                            this.loadData();
                        } else {
                            this.previewOrderDialog.visible = true;
                            this.previewOrderDialog.loading = false;
                            this.previewOrderDialog.code_url = e.data.data.code_url;
                            this.previewOrderDialog.order_no = e.data.data.order_no;
                            this.queryOrder(e.data.data.order_no);
                        }
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '提示');?>', {
                            type: 'error'
                        });
                    }
                }).catch(e => {
                });
            },
            payTypeChange() {
                this.previewOrderSubmit();
            },
            previewOrderDialogClose() {
                this.loadData();
            },
            queryOrder(orderNo) {
                let self = this;
                self.intervalTime = setInterval(function() {
                    self.$request({
                        params: {
                            r: 'admin/app-manage/query-order',
                            order_no: orderNo,
                        },
                    }).then(e => {
                        if (e.data.code === 0 && e.data.data.is_pay) {
                            clearInterval(self.intervalTime);
                            self.previewOrderDialog.visible = false;
                        }
                    }).catch(e => {
                    });
                }, 1000);
            },
            cancelPayment() {
                this.previewOrderDialog.visible = false;
                this.hintDialog.visible = false;
                clearInterval(this.intervalTime);
            },
            downloadConfirm() {
                this.$confirm('<?= \Yii::t('admin/app_manage', '安装过程请勿关闭或刷新浏览器');?>', '<?= \Yii::t('admin/app_manage', '注意');?>', {
                    confirmButtonText: '<?= \Yii::t('admin/app_manage', '确认');?>',
                    cancelButtonText: '<?= \Yii::t('admin/app_manage', '取消');?>',
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
                        this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '提示');?>', {
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
                this.$confirm('<?= \Yii::t('admin/app_manage', '将开始安装插件');?>', '<?= \Yii::t('admin/app_manage', '提示');?>', {
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
                            this.$alert('<?= \Yii::t('admin/app_manage', '安装成功');?>', '<?= \Yii::t('admin/app_manage', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '安装失败');?>', {
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
                this.$prompt('<?= \Yii::t('admin/app_manage', '如果要卸载该插件');?>', '<?= \Yii::t('admin/app_manage', '警告');?>', {}).then(({value}) => {
                    if (!value || typeof value !== 'string') {
                        return;
                    }
                    value = value.replace(/(^\s*)|(\s*$)/g, "").toLowerCase();
                    if (value !== 'yes') {
                        this.$message.warning('<?= \Yii::t('admin/app_manage', '输入内容不正确');?>');
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
                            this.$alert('<?= \Yii::t('admin/app_manage', '卸载成功');?>', '<?= \Yii::t('admin/app_manage', '提示');?>', {
                                type: 'success',
                                callback: action => {
                                    location.reload();
                                }
                            });
                        } else {
                            this.$alert(e.data.msg, '<?= \Yii::t('admin/app_manage', '卸载失败');?>', {
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
                this.$confirm('<?= \Yii::t('admin/app_manage', '确认更新版本');?>', '<?= \Yii::t('admin/app_manage', '提示');?>').then(e => {
                    this.updateBtnLoading = true;
                    this.download();
                }).catch(e => {
                });
            },
        }
    });
</script>
