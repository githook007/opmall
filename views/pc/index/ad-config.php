<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .nav-action {
        cursor: pointer;
    }

    .nav-add {
        border: 1px dashed #eeeeee;
        cursor: pointer;
    }

    .nav-add-icon {
        font-size: 50px;
        color: #eeeeee;
    }

    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .button-item {
        padding: 9px 25px;
    }

    .screen .foot .nav-icon + div {
        margin-top: -10px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('pc/index', '首页广告图');?></span>
        </div>
        <div class="body">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="120px">
                <div class='form-body'>
                    <el-form-item label="<?= \Yii::t('pc/index', '标题');?>" prop="ad_title">
                        <el-input v-model.trim="ruleForm.ad_title"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('pc/index', '广告图');?>" prop="ad_list">
                        <div @click="adPicEdit('-1')" flex="main:center cross:center" class="nav-add" style="width: 150px;">
                            <i class="el-icon-plus nav-add-icon"></i>
                            <div style="color: #C0C4CC;font-size: 16px"><?= \Yii::t('pc/index', '添加广告图');?></div>
                        </div>
                        <div flex="main:left">
                            <draggable flex="main:left" style="flex-wrap: wrap" v-model="ruleForm.ad_list">
                                <div @mouseenter="adEnter(index)" @mouseleave="adAway"
                                     style="position: relative;height:150px;margin-right: 10px;" flex="dir:top box:mean"
                                     v-for="(item, index) in ruleForm.ad_list">
                                    <div flex="main:center cross:center">
                                        <img :src="item.pic_url" width="150" height="100">
                                    </div>
                                    <div v-show="adIndex == index" class="nav-action"
                                         style="position: absolute;bottom: 0;width: 100%;height: 25px;" flex="box:mean">
                                        <span @click="adPicEdit(index)" style="background: rgba(64, 158, 255, 0.9);"
                                              flex="main:center cross:center">
                                            <?= \Yii::t('pc/index', '编辑');?>
                                        </span>
                                        <span @click="adPicDestroy(index)"
                                              style="background: rgba(245, 108, 108, 0.9);"
                                              flex="main:center cross:center">
                                            <?= \Yii::t('pc/index', '删除');?>
                                        </span>
                                    </div>
                                </div>
                            </draggable>
                        </div>
                    </el-form-item>
                </div>
                <el-button class="button-item" :loading="btnLoading" type="primary" @click="save"
                           size="small"><?= \Yii::t('pc/index', '保存');?>
                </el-button>
            </el-form>
        </div>
    </el-card>
    <el-dialog title="<?= \Yii::t('pc/index', '导航菜单编辑');?>" :visible.sync="dialogFormVisible" @close="dialogClose">
        <el-form :model="dialogRuleForm" :rules="dialogRules" size="small" ref="dialogRuleForm" label-width="120px">
            <el-row>
                <el-col :span="18">
                    <el-form-item label="<?= \Yii::t('pc/index', '图片');?>" prop="pic_url">
                        <app-attachment :multiple="false" :max="1" @selected="picUrl">
                            <el-tooltip effect="dark" content="<?= \Yii::t('pc/index', '建议尺寸228');?>" placement="top">
                                <el-button size="mini"><?= \Yii::t('pc/index', '选择文件');?></el-button>
                            </el-tooltip>
                        </app-attachment>
                        <app-image mode="aspectFill" width="80px" height="80px"
                                   :src="dialogRuleForm.pic_url"></app-image>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('pc/index', '链接地址');?>" prop="jump_url">
                        <el-input v-model.trim="dialogRuleForm.jump_url"></el-input>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
        <div slot="footer">
            <el-button size="small" @click="dialogFormVisible = false"><?= \Yii::t('pc/index', '取消');?></el-button>
            <el-button size="small" type="primary" @click="dialogFormSubmit"><?= \Yii::t('pc/index', '提交');?></el-button>
        </div>
    </el-dialog>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    ad_title: '',
                    ad_list: [],
                },
                rules: {
                    ad_list: [
                        {required: true, message: '<?= \Yii::t('pc/index', '请选择广告图');?>', trigger: 'change'},
                    ],
                    ad_title: [
                        {required: true, message: '<?= \Yii::t('pc/index', '标题不能为空');?>', trigger: 'change'},
                    ],
                },
                dialogFormVisible: false,
                dialogRuleForm: {
                    jump_url: "",
                    pic_url: "",
                },
                dialogRules: {
                    pic_url: [
                        {required: true, message: '<?= \Yii::t('pc/index', '请选择图片');?>', trigger: 'change'},
                    ],
                },
                adEditIndex: -1,
                adIndex: -1,
                btnLoading: false,
            };
        },
        methods: {
            getDetail() {
                this.loading = true;
                request({
                    params: {
                        r: 'pc/index/ad-config',
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data;
                    }
                }).catch(e => {
                });
                this.loading = false;
            },

            adEnter(index) {
                this.adIndex = index;
            },
            adAway() {
                this.adIndex = -1;
            },

            adPicEdit(index) {
                this.dialogFormVisible = true;
                if (index != -1) {
                    this.adEditIndex = index;
                    this.dialogRuleForm = this.ruleForm.ad_list[index]
                }
            },

            dialogClose() {
                this.adEditIndex = -1;
                this.clearDialogData();
            },

            adPicDestroy(index) {
                this.ruleForm.ad_list.splice(index, 1)
            },
            dialogFormSubmit() {
                this.$refs.dialogRuleForm.validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.dialogFormVisible = false;
                        if (self.adEditIndex != -1) {
                            self.ruleForm.ad_list[self.adEditIndex] = self.dialogRuleForm;
                        } else {
                            self.ruleForm.ad_list.push(self.dialogRuleForm)
                        }
                        self.adEditIndex = -1;
                        this.clearDialogData();
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },

            clearDialogData() {
                this.dialogRuleForm = {
                    jump_url: "",
                    pic_url: "",
                };
            },

            picUrl(e) {
                if (e.length) {
                    this.dialogRuleForm.pic_url = e[0].url;
                    this.$refs.dialogRuleForm.validateField('pic_url');
                }
            },

            save(){
                this.$refs.ruleForm.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        request({
                            params: {
                                r: 'pc/index/ad-config',
                            },
                            data: this.ruleForm,
                            method: 'post'
                        }).then(e => {
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                            }
                        }).catch(e => {
                        });
                        this.btnLoading = false;
                    }
                });
            },
        },
        mounted() {
            this.getDetail();
        }
    })
</script>