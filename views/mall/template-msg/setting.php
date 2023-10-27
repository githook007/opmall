<?php defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-wechat-info');
?>
<style>
    .mp-input {
        width: 30%
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 0;
    }

    .title {
        padding: 18px 20px;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .form-body {
        background-color: #fff;
        padding: 20px 50% 20px 0;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
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

    .title {
        padding: 10px 15px;
        background-color: #F4F4F5;
        color: #909399;
        margin-bottom: 20px;
        font-size: 15px;
    }
</style>
<section id="app" v-cloak>
    <el-card v-loading="listLoading" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div class="text item">
            <el-form :model="form" label-width="210px" :rules="FormRules" ref="form">
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane label="<?= \Yii::t('mall/template_msg', '管理员使用');?>" name="first">
                        <el-card shadow="never" style="margin-top: 12px;">
                            <div slot="header">
                                <span><?= \Yii::t('mall/template_msg', '模板消息ID配置');?></span>
                            </div>
                            <app-wechat-info :float="true" :setting="form.is_setting" :top="56"></app-wechat-info>
                            <el-row>
                                <div class="title"><?= \Yii::t('mall/template_msg', '温馨提示');?></div>
                                <el-form-item style="margin: 10px 0">
                                    <el-button type="primary" size="mini" @click="getTemplate"><?= \Yii::t('mall/template_msg', '一键添加模板消息');?></el-button>
                                </el-form-item>
                                <el-form-item v-for="(item, index) in form.template_list" :label="item.name" prop="app_id">
                                    <el-input size="small" class="mp-input" v-model="item[item.key_name]" placeholder="<?= \Yii::t('mall/template_msg', '请输入模板Id');?>" autocomplete="off"></el-input>
                                    <el-button size="small" @click="openDialog(item)"><?= \Yii::t('mall/template_msg', '查看模板消息示例');?></el-button>
                                    <el-button size="small" @click="test(item)"><?= \Yii::t('mall/template_msg', '发送测试');?></el-button>
                                </el-form-item>
                            </el-row>
                        </el-card>
                        <el-card shadow="never" style="margin-top: 12px;">
                            <div slot="header">
                                <span><?= \Yii::t('mall/template_msg', '管理员配置');?></span>
                            </div>
                            <el-row>
                                <el-form-item v-for="(item, index) in form.admin_open_list" :prop="'admin_open_list.' + index + '.open_id'" :rules="{
                                        required: true, message: '<?= \Yii::t('mall/template_msg', 'openId不能为空');?>', trigger: 'blur'
                                    }" :label="'<?= \Yii::t('mall/template_msg', '管理员公众号OpenId');?>' + index">
                                    <el-input size="small" class="mp-input" v-model="item.open_id" placeholder="<?= \Yii::t('mall/template_msg', '请输入OpenId');?>" autocomplete="off"></el-input>
                                    <div v-if="form.admin_open_list.length > 1" class="outline">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/template_msg', '删除');?>" placement="top">
                                            <i @click.prevent="removeOpenid(item)" class="el-icon-remove-outline"></i>
                                        </el-tooltip>
                                    </div>
                                    <el-button v-if="index == 0" size="small" @click="openDialog2"><?= \Yii::t('mall/template_msg', '查看获取管理员openid示例');?></el-button>
                                </el-form-item>
                                <el-form-item style="margin-bottom: 5px">
                                    <el-button size="small" type="text" @click="addOpenid">
                                        <i class="el-icon-plus" style="font-weight: bolder;margin-left: 5px;"></i>
                                        <span style="color: #353535;font-size: 14px"><?= \Yii::t('mall/template_msg', '添加管理员openid');?></span>
                                    </el-button>
                                </el-form-item>
                            </el-row>
                        </el-card>
                    </el-tab-pane>
                    <el-tab-pane label="<?= \Yii::t('mall/template_msg', '用户使用');?>" name="two" v-if="form.show_user == 1">
                        <el-card shadow="never" style="margin-top: 12px;">
                            <el-row>
                                <div class="title"><?= \Yii::t('mall/template_msg', '温馨提示');?></div>
                                <el-form-item style="margin: 10px 0">
                                    <el-button type="primary" size="mini" @click="getUserTemplate"><?= \Yii::t('mall/template_msg', '一键添加模板消息');?></el-button>
                                    <el-button type="primary" size="mini" @click="testQrcode"><?= \Yii::t('mall/template_msg', '生成测试二维码');?></el-button>
                                </el-form-item>
                                <el-form-item v-for="(item, index) in form.user_template" :label="item.name">
                                    <el-input size="small" class="mp-input" v-model="item[item.tpl_name]" placeholder="<?= \Yii::t('mall/template_msg', '请输入模板Id');?>" autocomplete="off"></el-input>
                                </el-form-item>
                            </el-row>
                        </el-card>
                    </el-tab-pane>
                </el-tabs>
                <el-button size="small" class="button-item" :loading="btnLoading" type="primary" @click="onSubmit" size="small"><?= \Yii::t('mall/template_msg', '保存');?>
                </el-button>
            </el-form>
        </div>
        <el-dialog title="<?= \Yii::t('mall/template_msg', '模板消息格式');?>" :visible.sync="dialogVisible">
            <div class="dialog">
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '登录微信公众号平台');?></div>
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '搜索模板');?>{{selectName}}<?= \Yii::t('mall/template_msg', '并添加');?></div>
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '模板格式如图');?></div>
                <img style="width: 100%;" :src="dialogImgUrl">
            </div>
        </el-dialog>
        <el-dialog title="<?= \Yii::t('mall/template_msg', '如何获取OpenId');?>" :visible.sync="dialogVisible2">
            <div class="dialog">
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '关注公众号');?></div>
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '点击刚刚回复的消息');?></div>
                <img style="width: 100%;" :src="openIdPicUrl_1">
                <div class="dialog-text"><?= \Yii::t('mall/template_msg', '复制OpenID');?></div>
                <img style="width: 100%;" :src="openIdPicUrl_2">
            </div>
        </el-dialog>
        <el-dialog title="<?= \Yii::t('mall/template_msg', '日志');?>" :visible.sync="testModel">
            <el-table :data="testForm" border style="width: 100%">
                <el-table-column label="OPEN_ID" prop="open_id"></el-table-column>
                <el-table-column prop="sub_price" label="<?= \Yii::t('mall/template_msg', '发送状态');?>">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.status == 1"> <?= \Yii::t('mall/template_msg', '发送成功');?></el-tag>
                        <el-tag type="danger" v-else><?= \Yii::t('mall/template_msg', '发送失败');?></el-tag>
                    </template>
                </el-table-column>
                <el-table-column prop="error" label="<?= \Yii::t('mall/template_msg', '错误日志');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/template_msg', '发送时间');?>" prop="created_at"></el-table-column>
            </el-table>
        </el-dialog>
        <el-dialog title="<?= \Yii::t('mall/template_msg', '测试二维码');?>" :visible.sync="templateVisible">
            <div style="text-align: center">
                <div style="margin-bottom: 10px"><?= \Yii::t('mall/template_msg', '请先保存好二维码之后在进行测试');?></div>
                <div v-if="qrcode">
                    <img :src="qrcode" alt="" width="430px" height="430px">
                </div>
                <div v-loading="!qrcode"></div>
            </div>
        </el-dialog>
    </el-card>
</section>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                dialogImgUrl: '',
                dialogVisible: false,
                dialogVisible2: false,
                openIdPicUrl_1: _baseUrl + '/statics/img/mall/wechatplatform/open_id_1.png',
                openIdPicUrl_2: _baseUrl + '/statics/img/mall/wechatplatform/open_id_2.png',
                activeName: 'first',
                form: {},
                listLoading: false,
                btnLoading: false,
                FormRules: {},
                testModel: false,
                testForm: [],
                selectName: '',
                templateVisible: false,
                qrcode: '',
            };
        },
        methods: {
            test(item) {
                request({
                    params: {
                        r: 'mall/template-msg/test',
                        key: item.key_name,
                        template_id: item[item.key_name],
                        app_id: this.form.app_id,
                        app_secret: this.form.app_secret,
                        admin_open_list: this.form.admin_open_list,
                    },
                }).then(e => {
                    if (e.data.code == 0) {
                        this.testModel = true;
                        this.testForm = e.data.data;
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },

            getTemplate() {
                request({
                    params: {
                        r: 'mall/template-msg/mp-template',
                        app_id: this.form.app_id,
                        app_secret: this.form.app_secret,
                    },
                }).then(e => {
                    if (e.data.code == 0) {
                        this.form.template_list.forEach(v => {
                            if (v.key_name == 'new_order_tpl') {
                                v.new_order_tpl = e.data.data.newOrderTpl;
                            }
                            if (v.key_name == 'share_apply_tpl') {
                                v.share_apply_tpl = e.data.data.shareApplyTpl;
                            }
                            if (v.key_name == 'share_withdraw_tpl') {
                                v.share_withdraw_tpl = e.data.data.shareWithdrawTpl;
                            }
                            if (v.key_name == 'mch_apply_tpl') {
                                v.mch_apply_tpl = e.data.data.mchApplyTpl;
                            }
                            if (v.key_name == 'mch_good_apply_tpl') {
                                v.mch_good_apply_tpl = e.data.data.mchGoodApplyTpl;
                            }
                            if (v.key_name == 'cancel_order_tpl') {
                                v.cancel_order_tpl = e.data.data.cancelOrderTpl;
                            }
                            if (v.key_name == 'sale_order_tpl') {
                                v.sale_order_tpl = e.data.data.saleOrderTpl;
                            }
                            if(v.key_name == 'apply_submit_tpl') {
                                v.apply_submit_tpl = e.data.data.applySubmitTpl;
                            }
                        });
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {});
            },

            //
            addOpenid() {
                this.form.admin_open_list.push({
                    open_id: '',
                });
            },
            removeOpenid(item) {
                let index = this.form.admin_open_list.indexOf(item)
                if (index !== -1) {
                    this.form.admin_open_list.splice(index, 1)
                }
            },
            openDialog(item) {
                switch(item.name) {
                    case '<?= \Yii::t('mall/template_msg', '新订单通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '新订单通知');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '分销商入驻申请通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '入驻申请提醒');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '多商户入驻申请通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '入驻申请提醒');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '分销商提现通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '提现申请通知');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '入驻商商品上架申请通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '服务申请通知');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '订单申请取消通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '订单取消通知');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '订单申请售后通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '订单售后通知');?>';
                        break;
                    case '<?= \Yii::t('mall/template_msg', '自定义表单通知');?>':
                        this.selectName = '<?= \Yii::t('mall/template_msg', '申请提交成功通知');?>';
                        break;
                    default:
                }
                this.dialogVisible = true;
                this.dialogImgUrl = item.pic_url;
            },
            openDialog2() {
                this.dialogVisible2 = true;
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            //

            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign({}, { form: this.form });
                        request({
                            params: {
                                r: 'mall/template-msg/setting'
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            if (e.data.code === 0) {
                                navigateTo({ r: 'mall/template-msg/setting' });
                            } else {
                                this.$message.error(e.data.msg);
                            }
                            this.btnLoading = false;
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },

            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/template-msg/setting',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    if (e.data.code == 0) {
                        if (e.data.data) {
                            this.form = e.data.data.detail;
                        }
                    }
                    this.listLoading = false;
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            getUserTemplate() {
                request({
                    params: {
                        r: 'mall/index/template-one',
                        add: true,
                        platform: 'wechat',
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code == 0) {
                        this.form.user_template = e.data.data.list[0]['list'];
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            testQrcode() {
                this.templateVisible = true;
                if (this.qrcode) {
                    return ;
                }
                this.$request({
                    params: {
                        r: 'mall/template-msg/qrcode',
                        platform: 'wechat',
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code == 0) {
                        this.qrcode = e.data.data
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },

        mounted() {
            this.getList();
        }
    })
</script>
