<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
$baseUrl = Yii::$app->request->baseUrl;
$err = Yii::$app->cache->get(\app\plugins\wxapp\models\WxappWxminiprograms::ERR_MSG_KEY.Yii::$app->mall->id) ?: '';
if($err){
    Yii::$app->cache->delete(\app\plugins\wxapp\models\WxappWxminiprograms::ERR_MSG_KEY.Yii::$app->mall->id);
}
?>
<style>
    .form_box {
        margin-top: 10px;
        background-color: #fff;
    }

    .form_box_box {
        padding: 30px 20px;
    }

    .form_box_qx {
        padding: 24px 20px 30px;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }

    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 0;
    }

    .choose {
        margin-left: 10px;
        width: 364px;
        height: 93px;
        border-radius: 6px;
        background-color: #e1f0ff;
        color: #3a3a3a;
    }

    .choose .left {
        width: 57px;
        height: 57px;
        background-color: #fff;
        border-radius: 8px;
        margin: 0 18px;
    }

    .choose .left img {
        height: 38px;
        width: 38px;
    }

    .choose .button-item {
        margin-top: 0;
        margin-left: 50px;
    }

    .version {
        width: 414px;
        height: 93px;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        background-image: linear-gradient(to right, #409eff, #40b9ff);
        color: #fff;
        margin-left: 10px;
        position: relative;
    }

    .version .left {
        width: 57px;
        height: 57px;
        background-color: #fff;
        border-radius: 8px;
        margin: 0 18px;
    }

    .version .setting-url {
        position: absolute;
        right: 24px;
        top: 20px;
        height: 24px;
        width: 104px;
        line-height: 24px;
        text-align: center;
        border-radius: 12px;
        color: #fff;
        border:  1px solid #fff;
        cursor: pointer;
    }

    .version-big {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .program-info {
        margin-left: 10px;
        width: 414px;
        border: 1px solid #e2e2e2;
        border-top: 0;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
        padding: 25px 0;
    }

    .program-info img {
        height: 148px;
        width: 148px;
        margin: 0 auto 25px;
        display: block;
    }

    .program-info div .program-text {
        padding: 0 20px;
        color: #606266;
        margin-top: 10px;
    }

    .program-info .program-tip div {
        color: #606266;
        height: 50px;
        line-height: 30px;
    }

    .program-info .program-tip .number {
        width: 30px;
        height: 30px;
        text-align: center;
        border-radius: 50%;
        margin-right: 22px;
        color: #409eff;
        background-color: #e1f0ff;
        margin-left: 22px;
    }

    .program-info .choose-version {
        margin: 10px 20px;
    }

    .program-info .choose-version>div:first-of-type {
        margin-right: 20px;
    }

    .program-info .button {
        width: 372px;
        text-align: center;
        height: 38px;
        border-radius: 4px;
        border: 1px solid #409eff;
        margin-left: 20px;
        margin-top: 15px;
        cursor: pointer;
        background-color: #409eff;
        color: #fff;
    }

    .program-info .to-test {
        margin-top: 30px;
        color: #409eff;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0"
             body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <el-tabs v-model="activeName">
            <el-tab-pane label="<?= \Yii::t('plugins/wxapp', '小程序配置');?>" name="first">
                <div class="form_box" :class="{'form_box_box': !has_fast_create_wxapp_permission}">
                    <div v-if="has_fast_create_wxapp_permission" flex="dir:left cross:center"
                         style="background-color: #e1f0ff;padding: 10px 24px">
                        <div style="color: #409EFF;padding-right: 24px"><?= \Yii::t('plugins/wxapp', '快速注册小程序');?></div>
                        <el-button type="primary" size="small"
                                   @click="$navigate({r:'plugin/wxapp/third-platform/fast-create'})"><?= \Yii::t('plugins/wxapp', '立即注册');?>
                        </el-button>
                    </div>
                    <el-row :class="{'form_box_qx' : has_fast_create_wxapp_permission}">
                        <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm1" label-width="150px">
                            <el-col :span="12">
<!--                                @czs 去掉选择 -->
<!--                                <el-form-item v-if="has_third_permission" label="是否使用第三方授权" prop="is_third">-->
<!--                                    <el-radio-group :disabled="third && third.id > 0" v-model="is_third">-->
<!--                                        <el-radio :label="0">否</el-radio>-->
<!--                                        <el-radio :label="1">是</el-radio>-->
<!--                                    </el-radio-group>-->
<!--                                </el-form-item>-->
                                <el-form-item v-if="is_third == 0" label="<?= \Yii::t('plugins/wxapp', '小程序appId');?>" prop="appid">
                                    <el-input v-model.trim="ruleForm.appid"></el-input>
                                </el-form-item>
                                <el-form-item v-if="is_third == 0" label="<?= \Yii::t('plugins/wxapp', '小程序appSecret');?>" prop="appsecret">
                                    <el-input @focus="hidden.appsecret = false"
                                              v-if="hidden.appsecret"
                                              readonly
                                              placeholder="<?= \Yii::t('plugins/wxapp', '已隐藏内容');?>">
                                    </el-input>
                                    <el-input v-else v-model.trim="ruleForm.appsecret"></el-input>
                                </el-form-item>
                                <el-form-item v-if="is_third == 0" label="<?= \Yii::t('plugins/wxapp', '小程序上传密钥');?>" prop="wx_mini_upload_key">
                                    <el-input type="textarea" :rows="5" v-model="ruleForm.wx_mini_upload_key"></el-input>
                                    <div style=""><b><?= \Yii::t('plugins/wxapp', '小程序上传密钥提示');?></b></div>
                                </el-form-item>
                                <div v-if="is_third == 1 && !third" class="choose" flex="dir:left cross:center">
                                    <div class="left" flex="main:center cross:center">
                                        <img src="statics/img/mall/mini-program.png" alt="">
                                    </div>
                                    <div><?= \Yii::t('plugins/wxapp', '选择已有小程序');?></div>
                                    <el-button class='button-item' type="primary" @click="auth" size="small"><?= \Yii::t('plugins/wxapp', '立即授权');?></el-button>
                                </div>
                                <div v-if="is_third == 1 && third && !have_version" class="choose" flex="dir:left cross:center">
                                    <div class="left" flex="main:center cross:center">
                                        <img src="statics/img/mall/mini-program.png" alt="">
                                    </div>
                                    <div><?= \Yii::t('plugins/wxapp', '等待发布版本');?></div>
                                </div>
                                <div v-if="is_third == 1 && third && have_version">
                                    <div class="version" flex="dir:left cross:center">
                                        <div class="left" flex="main:center cross:center">
                                            <img src="statics/img/mall/mini-program.png" alt="">
                                        </div>
                                        <div>
                                            <div class="version-big"><?= \Yii::t('plugins/wxapp', '线上版本');?></div>
                                            <div><?= \Yii::t('plugins/wxapp', '版本号');?>：{{releaseVersion ? releaseVersion.version : '-'}}</div>
                                        </div>
                                        <div @click="urlVisible=true;" class="setting-url"><?= \Yii::t('plugins/wxapp', '配置业务域名');?></div>
                                        <div @click="$navigate({r:'plugin/wxapp/wx-app-config/privacy-setting'})" class="setting-url" style="width: 160px; top: 55px">
                                            <?= \Yii::t('plugins/wxapp', '隐私设置');?>{{third.user_privacy ? '<?= \Yii::t('plugins/wxapp', '（已填写）');?>' : '<?= \Yii::t('plugins/wxapp', '（未填写）');?>'}}
                                        </div>
                                    </div>
                                    <div class="program-info" v-if="!is_pass">
                                        <div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '小程序appId');?>：{{third.authorizer_appid}}</div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '名称');?>：{{third.nick_name}}</div>
                                        </div>
                                    </div>
                                    <div class="program-info" v-if="!is_pass">
                                        <div class="program-tip">
                                            <div flex="dir:left">
                                                <div class="number">1</div>
                                                <div><?= \Yii::t('plugins/wxapp', '审核时间');?></div>
                                            </div>
                                            <div flex="dir:left">
                                                <div class="number">2</div>
                                                <div><?= \Yii::t('plugins/wxapp', '通过后自动发布');?></div>
                                            </div>
                                        </div>
                                        <div class="choose-version" flex="dir:left">
                                            <div><?= \Yii::t('plugins/wxapp', '选择版本');?></div>
                                            <div>
                                                <div style="margin-bottom: 15px">
                                                    <el-radio v-model="is_plugin" :label="0"><?= \Yii::t('plugins/wxapp', '基础');?>({{template.user_version}})</el-radio>
                                                </div>
                                                <div>
                                                    <el-radio v-model="is_plugin" :label="1"><?= \Yii::t('plugins/wxapp', '基础含直播');?>({{template.user_version}})</el-radio>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="line-height: 38px;" class="to-test button" @click="toUpVersion"><?= \Yii::t('plugins/wxapp', '生成体验版本');?></div>
                                        <el-button @click="toSubmitReview" :loading="submitLoading" v-if="check_status == -1 || check_status == 3" class="button"><?= \Yii::t('plugins/wxapp', '提交审核');?></el-button>
                                        <el-button @click="unDoCodeAudit" :loading="submitLoading" v-if="check_status == 2" class="button"><?= \Yii::t('plugins/wxapp', '撤回审核');?></el-button>
                                        <el-button @click="toSubmitReview" :loading="submitLoading" v-if="check_status == 1" class="button"><?= \Yii::t('plugins/wxapp', '失败再审核');?></el-button>
                                        <el-button @click="release" :loading="submitLoading" v-if="check_status == 0" class="button"><?= \Yii::t('plugins/wxapp', '通过并发布');?></el-button>
                                        <div v-if="check_status == 1 && errorMsg" style="color: #ff4544;margin: 8px 20px"><?= \Yii::t('plugins/wxapp', '失败原因');?>：
                                            <span v-html="errorMsg"></span>
                                        </div>
                                    </div>
                                    <!-- 小程序信息 -->
                                    <div v-loading="app_qrcode_loading" class="program-info" v-if="is_pass">
                                        <img :src="app_qrcode">
                                        <div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '小程序appId');?>：{{third.authorizer_appid}}</div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '名称');?>：{{third.nick_name}}</div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '版本时间');?>：{{releaseVersion.created_at}}</div>
                                            <div class="program-text"><?= \Yii::t('plugins/wxapp', '发布时间');?>：{{releaseVersion.release_at}}</div>

                                            <el-button @click="is_pass = false;check_status = -1"
                                                       :loading="submitLoading" v-if="is_new" class="button"><?= \Yii::t('plugins/wxapp', '有新版本');?>
                                            </el-button>
                                        </div>
                                    </div>
                                </div>
                            </el-col>
                        </el-form>
                    </el-row>
                </div>
            </el-tab-pane>
        </el-tabs>
        <el-dialog title="<?= \Yii::t('plugins/wxapp', '体验小程序');?>" :visible.sync="dialogVisible" :close-on-click-modal="false" width="360px">
            <div v-loading="previewLoading">
                <img id="code" style="display: block;margin: 15px auto 50px" width="180" height="180" alt="">
            </div>
        </el-dialog>
        <el-dialog title="<?= \Yii::t('plugins/wxapp', '配置业务域名');?>" :visible.sync="urlVisible" width="785px">
            <div style="padding-left: 30px;">
                <div style="max-height: 400px;overflow: auto;">
                    <el-form size="small" label-width="120px" label-position="left">
                        <el-form-item v-for="(item,index) in domain" :key="index">
                            <label slot="label"><?= \Yii::t('plugins/wxapp', '配置业务域名');?>
                                <el-tooltip class="item" effect="dark"
                                            :content="'<?= \Yii::t('plugins/wxapp', '例:https://');?>' + host"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <div flex="dir:left cross:center">
                                <el-input style="width: 520px;margin-right: 20px;" v-model="domain[index]"></el-input>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/wxapp', '删除');?>" placement="top">
                                    <img style="cursor: pointer;" @click="delDomain(index)" src="statics/img/mall/del.png" alt="">
                                </el-tooltip>
                            </div>
                        </el-form-item>
                    </el-form>
                </div>
                <div style="color: #999999;margin: 20px 0 10px;"><?= \Yii::t('plugins/wxapp', '最多加20条域名');?></div>
                <el-button v-if="domain.length < 20" class="add-btn" @click="addUrl" size="small" plain>+<?= \Yii::t('plugins/wxapp', '添加域名');?></el-button>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button size="small" @click="cancel"><?= \Yii::t('plugins/wxapp', '取消');?></el-button>
                <el-button size="small" type="primary" :loading="urlLoading" @click="submitUrl"><?= \Yii::t('plugins/wxapp', '确定');?></el-button>
            </span>
        </el-dialog>
        <el-button v-if="activeName != 'first' || is_third == 0" class='button-item' :loading="btnLoading" type="primary" @click="store('ruleForm1')" size="small"><?= \Yii::t('plugins/wxapp', '保存');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                has_fast_create_wxapp_permission: false,
                host: '',
                is_pass: false, // 小程序是否通过审核
                is_plugin: 0, // 小程序版本是否包含直播 0 基础 1 含直播
                check_status: -1, // 小程序审核状态 -1 未审核  0为审核成功，1为审核失败，2为审核中，3已撤回, 4已发布
                errorMsg: '', // 审核失败原因
                is_new: null, // 是否有新版本
                have_version: false, //后台是否上传版本
                third: null, // 版本信息
                is_third: 1, //是否开启第三方授权  @czs 默认开启第三方授权
                releaseVersion: null, // 发布的版本信息
                template: null, // 后台最新上传版本
                has_third_permission: false,
                activeName: 'first',
                dialogVisible: false,
                urlVisible: false,
                previewLoading: false,
                submitLoading: false,
                urlLoading: false,
                domain: [''],
                hidden: {
                    appid: true,
                    appsecret: true,
                },
                ruleForm: {
                    appid: '',
                    appsecret: '',
                    wx_mini_upload_key: '',
                },
                rules: {
                    appid: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '输入');?>appid', trigger: 'change'},
                    ],
                    appsecret: [
                        {required: true, message: '<?= \Yii::t('plugins/wxapp', '输入');?>appsecret', trigger: 'change'},
                    ]
                },
                btnLoading: false,
                cardLoading: false,
                app_qrcode_loading: false,
                app_qrcode: null,
                err: '<?= $err ?>',
            };
        },
        methods: {
            delDomain(index) {
                this.domain.splice(index,1)
            },
            addUrl() {
                this.domain.push('')
            },
            cancel() {
                this.domain = JSON.parse(JSON.stringify(this.third.domain));
                this.urlVisible = false;
            },
            submitUrl() {
                for(let item of this.domain) {
                    const reg = /(https):\/\/([\w.]+\/?)\S*/
                    if (!reg.test(item)) {
                        this.$message.error('<?= \Yii::t('plugins/wxapp', '输入正确网址');?>');
                        return false
                    }
                }
                this.urlLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/third-platform/business-domain',
                    },
                    data: {
                        domain: this.domain
                    },
                    method: 'post'
                }).then(e => {
                    this.urlLoading = false;
                    if (e.data.code === 0) {
                        this.$message({
                          message: e.data.msg,
                          type: 'success'
                        });
                        this.urlVisible = false;
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('plugins/wxapp', '提示');?>');
                    }
                }).catch(e => {
                    this.submitLoading = false;
                })
            },
            getQr() {
                this.app_qrcode_loading = true;
                this.$request({
                    params: {
                        r: 'plugin/wxapp/app-upload/app-qrcode',
                    },
                }).then(e => {
                    this.app_qrcode_loading = false;
                    if (e.data.code === 0) {
                        this.app_qrcode = e.data.data.qrcode;
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('plugins/wxapp', '提示');?>');
                    }
                }).catch(e => {
                    this.app_qrcode_loading = false;
                })
            },
            // 授权弹窗
            auth() {
                window.open('<?=$baseUrl?>/index.php?r=plugin/wxapp/third-platform/authorizer');
                this.$confirm('<?= \Yii::t('plugins/wxapp', '新窗口授权');?>', '<?= \Yii::t('plugins/wxapp', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/wxapp', '成功授权');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/wxapp', '重试授权');?>',
                    type: 'warning'
                }).then(() => {
                    this.$message({
                        type: 'success',
                        message: '<?= \Yii::t('plugins/wxapp', '已完成授权');?>'
                    });
                    this.getDetail();
                }).catch(() => {
                });
            },
            // 上传代码
            uploadCode(type) {
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/upload',
                    },
                    data: {
                        template_id: this.template.template_id,
                        user_version: this.template.user_version,
                        is_plugin: this.is_plugin
                    },
                    method: 'post',
                }).then(e => {
                    if (e.data.code === 0) {
                        if(type === 1) {
                            this.preview();
                        }else{
                            this.submitReview();
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            // 生成体验版本
            toUpVersion() {
                let self = this;
                self.previewLoading = true;
                self.dialogVisible = true;
                self.uploadCode(1);
            },
            // 提交审核 @czs
            toSubmitReview(){
                let self = this;
                if(!self.third.user_privacy){
                    this.$message.error('<?= \Yii::t('plugins/wxapp', '隐私协议未配置');?>');
                    return;
                }
                self.submitLoading = true;
                self.uploadCode(2);
            },
            // 预览
            preview() {
                let self = this;
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/preview',
                    },
                    responseType: 'blob',
                    method: 'get',
                }).then(e => {
                    self.previewLoading = false;
                    var blob = e.data;
                    var url = window.URL.createObjectURL(blob);
                    document.getElementById("code").src = url
                }).catch(e => {
                    self.previewLoading = false;
                    var blob = e.data;
                    var url = window.URL.createObjectURL(blob);
                    document.getElementById("code").src = url
                });
            },
            // 提交审核
            submitReview() {
                let self = this;
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/submit-review',
                    },
                    data: {
                        template_id: self.template.template_id,
                        version: self.template.user_version
                    },
                    method: 'post',
                }).then(e => {
                    if (e.data.code == 0) {
                        if(e.data.data && e.data.data.retry === 1){
                            setTimeout(() => {
                                self.submitReview();
                            }, 1000)
                        }else{
                            self.submitLoading = false;
                            self.$message({
                                message: e.data.msg,
                                type: 'success'
                            });
                            self.checkSubmit();
                        }
                    } else {
                        self.submitLoading = false;
                        self.$message.error(e.data.msg);
                    }
                })
            },
            // 审核结果查询
            checkSubmit() {
                let self = this;
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/get-last-audit',
                    },
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.check_status = e.data.data.status;
                        self.releaseVersion = e.data.data.last;
                        if(e.data.data.status == 1) {
                            self.errorMsg = e.data.data.reason;
                        }
                        if(e.data.data.last) {
                            self.have_version = true;
                            self.getQr();
                            if(e.data.data.last.version == e.data.data.audit.version) {
                                self.is_pass = true;
                            }
                            let nowVersion = e.data.data.last.version.split(".");
                            let newVersion = self.template.user_version.split(".");
                            for(let i = 0;i < newVersion.length;i++) {
                                if(+newVersion[i] > +nowVersion[i]) {
                                    self.is_new = true;
                                    break;
                                }
                            }
                        }
                    }
                })
            },
            // 撤回审核
            unDoCodeAudit() {
                let self = this;
                self.$confirm('<?= \Yii::t('plugins/wxapp', '审核撤回次数');?>', '<?= \Yii::t('plugins/wxapp', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/wxapp', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/wxapp', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.submitLoading = true;
                    request({
                        params: {
                            r: 'plugin/wxapp/third-platform/un-do-code-audit',
                        },
                    }).then(e => {
                        self.submitLoading = false;
                        if (e.data.code == 0) {
                            self.$message({
                              message: e.data.msg,
                              type: 'success'
                            });
                            self.check_status = -1;
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    })

                })
            },
            // 发布版本
            release() {
                let self = this;
                self.submitLoading = true;
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/release',
                    },
                }).then(e => {
                    self.submitLoading = false;
                    if (e.data.code == 0) {
                        self.$message({
                          message: e.data.msg,
                          type: 'success'
                        });
                        self.getDetail();
                    } else {
                        self.$message.error(e.data.msg);
                    }
                })
            },
            // 获取版本
            getVersion() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/wxapp/third-platform/template-list',
                    },
                    method: 'get',
                }).then(e => {
                    self.checkSubmit();
                    if (e.data.code == 0) {
                        if(e.data.data.list.template_list.length > 0) {
                            self.template = e.data.data.list.template_list.pop();
                            self.have_version = true;
                        }else {
                            self.cardLoading = false;
                        }
                    } else {
                        self.cardLoading = false;
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            store(formName1) {
                let self = this;
                this.$refs[formName1].validate((valid) => {
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/wxapp/wx-app-config/setting'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        self.activeName = 'first';
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/wxapp/wx-app-config/setting',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    self.has_third_permission = e.data.data.has_third_permission;
                    self.has_fast_create_wxapp_permission = e.data.data.has_fast_create_wxapp_permission;
                    self.third = e.data.data.third;
                    if(!self.has_third_permission) {
                        self.is_third = 0;
                    }
                    if(self.third && self.has_third_permission) {
                        self.is_third = 1;
                        self.getVersion();
                    }
                    if (e.data.code == 0) {
                        if(e.data.data.third) {
                            self.domain = JSON.parse(JSON.stringify(e.data.data.third.domain));
                        }
                        self.ruleForm = Object.assign(self.ruleForm, e.data.data.detail);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
        },
        mounted: function () {
            this.host = window.location.host;
            if(this.err){
                this.$confirm(this.err, '<?= \Yii::t('plugins/wxapp', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/wxapp', '重新授权');?>',
                    showCancelButton: false,
                    type: 'warning'
                }).then(() => {
                    location.href = '<?=$baseUrl?>/index.php?r=plugin/wxapp/third-platform/authorizer';
                }).catch(() => {
                });
            }
            this.getDetail();
        }
    });
</script>
