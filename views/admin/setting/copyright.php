<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: wxf
 */
?>

<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 50%;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }

</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" class="box-card" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="cardLoading">
        <div slot="header">
            <div>
                <span><?= \Yii::t('admin/setting', '版权设置');?></span>
            </div>
        </div>
        <div class="form-body">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="120px">
                <el-form-item label="<?= \Yii::t('admin/setting', '版权开关');?>" prop="status">
                    <el-switch
                            v-model="ruleForm.status"
                            active-value="1"
                            inactive-value="0">
                    </el-switch>
                </el-form-item>
                <template v-if="ruleForm.status == 1">
                    <el-form-item label="<?= \Yii::t('admin/setting', '版权信息');?>" prop="description">
                        <el-input
                                maxlength="20"
                                show-word-limit
                                type="textarea"
                                :rows="3"
                                placeholder="<?= \Yii::t('admin/setting', '请输入版权信息,如：xx商城 版权所有');?>"
                                v-model="ruleForm.description">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/setting', '版权辅助信息');?>" prop="sub_description">
                        <el-input
                                maxlength="32"
                                show-word-limit
                                type="textarea"
                                :rows="3"
                                placeholder="<?= \Yii::t('admin/setting', '请输入版权辅助信息');?>"
                                v-model="ruleForm.sub_description">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/setting', '版权图标');?>" prop="pic_url">
                        <app-attachment v-model="ruleForm.pic_url">
                            <el-tooltip effect="dark"
                                        content="<?= \Yii::t('admin/setting', '建议尺寸');?>:160 * 50"
                                        placement="top">
                            <el-button><?= \Yii::t('admin/setting', '上传图片');?></el-button>
                            </el-tooltip>
                        </app-attachment>
                        <div flex="dir:top" style="position: relative;display: inline-block;margin-top: 10px;">
                            <app-image width="100px" height="100px" mode="aspectFill" :src="ruleForm.pic_url"></app-image>
                            <el-button class="del-btn"
                                       v-if="ruleForm.pic_url"
                                       size="mini" type="danger" icon="el-icon-close"
                                       @click="delPic"></el-button>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/setting', '版权链接');?>" prop="link_url">
                        <div flex="box:last">
                            <el-input disabled v-model="ruleForm.link_url"></el-input>
                            <app-pick-link @selected="selectLinkUrl" ignore="admin_copyright">
                                <el-button><?= \Yii::t('admin/setting', '选择链接');?></el-button>
                            </app-pick-link>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/copyright', '备案信息');?>" prop="record_info">
                        <el-input
                                type="textarea"
                                :rows="2"
                                placeholder="<?= \Yii::t('mall/copyright', '请输入备案信息');?>"
                                v-model="ruleForm.record_info">
                        </el-input>
                    </el-form-item>
                </template>
            </el-form>
        </div>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('admin/setting', '保存');?>
        </el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    pic_url: '',
                    description: '',
                    sub_description: '',
                    type: '1',
                    link_url: '',
                    mobile: '',
                },
                rules: {
                    description: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请输入版权信息');?>', trigger: 'change'},
                    ],
                    sub_description: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请输入版权辅助信息');?>', trigger: 'change'},
                    ],
                    type: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请选择类型');?>', trigger: 'change'},
                    ],
                    mobile: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请输入电话');?>', trigger: 'change'},
                    ],
                    link_url: [
                        {required: true, message: '<?= \Yii::t('admin/setting', '请选择链接');?>', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'admin/setting/copyright'
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
                        r: 'admin/setting/copyright',
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        if (e.data.data.detail) {
                            self.ruleForm = e.data.data.detail;
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            picUrl(e) {
                if (e.length) {
                    this.ruleForm.pic_url = e[0].url;
                    this.$refs.ruleForm.validateField('pic_url');
                }
            },
            selectLinkUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    self.ruleForm.link_url = item.new_link_url;
                    self.ruleForm.link = item;
                })
            },
            delPic() {
                this.ruleForm.pic_url = '';
            }
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
