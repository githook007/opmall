<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: wxf
 */
?>
<style>
    .form_box {
        background-color: #fff;
        padding: 30px 20px;
        padding-right: 40%;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }

    .template-box {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .template-item {
        width: 208px;
        margin: 0px 10px;
        border: 1px solid #e2e2e2;
        position: relative;
    }

    .template-img {
        width: 187.5px;
    }

    .action-box {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .action-box:hover {
        background: rgba(0,0,0,50%);
        cursor: pointer;
    }

    .action-button {
        height: 30px;
        border: 1px solid #ffffff;
        color: #ffffff;
        border-radius: 5px;
        margin: 5px 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .action-button:hover {
        background: #409eff;
        border: 1px solid #409eff;
    }

    .input-item {
        display: inline-block;
        width: 250px;
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
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('mall/wechat', '视频号设置');?></span>
        </div>
        <div class="form_box">
            <el-form :model="ruleForm"
                     ref="ruleForm"
                     label-width="172px"
                     size="small">
                <el-form-item label="<?= \Yii::t('mall/wechat', '开关');?>" prop="is_video_number">
                    <el-switch v-model="ruleForm.is_video_number"
                               :active-value="1"
                               :inactive-value="0">
                    </el-switch>
                </el-form-item>
                <el-form-item>
                    <template slot='label'>
                        <span><?= \Yii::t('mall/wechat', '接受消息用户');?></span>
                        <el-tooltip effect="dark" content="<?= \Yii::t('mall/wechat', '接受消息用户公众号');?>openId"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-form-item label="openId1" label-width="100px">
                        <el-input v-model="ruleForm.video_number_user_1"
                                  placeholder="<?= \Yii::t('mall/wechat', '接受消息用户公众号');?>openId1">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="openId2" label-width="100px">
                        <el-input v-model="ruleForm.video_number_user_2"
                                  placeholder="<?= \Yii::t('mall/wechat', '接受消息用户公众号');?>openId2">
                        </el-input>
                        <div style="color: #409EFF;cursor: pointer;" @click="openDialog2"><?= \Yii::t('mall/wechat', '查看获取管理员openid示例');?></div>
                    </el-form-item>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/wechat', '分享标题');?>" prop="video_number_share_title">
                    <el-input v-model="ruleForm.video_number_share_title"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/wechat', '分销等级可用权限');?>" prop="is_video_number_member">
                    <el-switch v-model="ruleForm.is_video_number_member"
                               :active-value="1"
                               :inactive-value="0">
                    </el-switch>
                </el-form-item>
                <el-form-item v-if="ruleForm.is_video_number_member" label="<?= \Yii::t('mall/wechat', '分销等级');?>" prop="video_number_member_list">
                    <el-tag
                            style="margin-right: 5px;margin-bottom: 5px;"
                            v-for="(tag, index) in ruleForm.video_number_member_list"
                            :key="tag.name"
                            @close="memberHandleClose(index)"
                            closable>
                        {{tag.name}}
                    </el-tag>
                    <el-button @click="getShareLevel" size="small"><?= \Yii::t('mall/wechat', '选择分销等级');?></el-button>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/wechat', '公众号模板');?>" prop="video_number_template_list">
                    <el-tag
                            style="margin-right: 5px;margin-bottom: 5px;"
                            v-for="(tag, index) in ruleForm.video_number_template_list"
                            :key="tag.name"
                            @close="templateHandleClose(index)"
                            closable>
                        {{tag.name}}
                    </el-tag>
                    <el-button v-if="ruleForm.video_number_template_list && ruleForm.video_number_template_list.length == 0" @click="getTemplates" size="small"><?= \Yii::t('mall/wechat', '选择模板');?></el-button>
                </el-form-item>
            </el-form>
            <el-dialog title="<?= \Yii::t('mall/wechat', '选择分销等级');?>" :visible.sync="memberDialogVisible">
                <div class="input-item">
                    <el-input @keyup.enter.native="getShareLevel" size="small" placeholder="<?= \Yii::t('mall/wechat', '请输入分销等级名称');?>" v-model="shareKeyword" clearable @clear="getShareLevel">
                        <el-button slot="append" icon="el-icon-search" @click="getShareLevel"></el-button>
                    </el-input>
                </div>
                <el-table v-loading="shareListLoading" :data="shareList" @selection-change="handleSelectionChange">
                    <el-table-column type="selection" width="55"></el-table-column>
                    <el-table-column property="name" label="<?= \Yii::t('mall/wechat', '分销等级名称');?>"></el-table-column>
                </el-table>
                <div style="margin-top: 10px; text-align: right;">
                    <el-pagination @current-change="memberPagination" hide-on-single-page background layout="prev, pager, next" :page-count="memberPageCount" :current-page="memberCurrentPage"></el-pagination>
                </div>
                <div slot="footer" class="dialog-footer">
                    <el-button size="small" @click="memberDialogVisible = false"><?= \Yii::t('mall/wechat', '取消');?></el-button>
                    <el-button size="small" type="primary" @click="memberSubmit"><?= \Yii::t('mall/wechat', '确定');?></el-button>
                </div>
            </el-dialog>

            <el-dialog
                    title="<?= \Yii::t('mall/wechat', '选择模板');?>"
                    :visible.sync="templateDialogVisible"
                    width="60%">
                <div class="template-box">
                    <div @mouseover="mouseover(item)" @mouseleave="mouseleave" class="template-item" v-for="item in templateList">
                        <img class="template-img" :src="item.pic_url">
                        <div class="action-box" v-if="currentTemplateId == item.id">
                            <div @click="templateSubmit(item)" class="action-button"><?= \Yii::t('mall/wechat', '选择模板');?></div>
                            <div @click="templatePreview(item)" class="action-button"><?= \Yii::t('mall/wechat', '预览模板');?></div>
                        </div>
                    </div>
                </div>
            </el-dialog>

            <el-dialog
                    title="<?= \Yii::t('mall/wechat', '手机端预览');?>"
                    :visible.sync="previewDialogVisible"
                    width="30%">
                <div style="height: 500px;overflow-y: scroll;">
                    <img style="width: 100%;" :src="currentTemplate.preview_pic_url">
                </div>
                <span slot="footer" class="dialog-footer">
                <el-button size="small" @click="previewDialogVisible = false"><?= \Yii::t('mall/wechat', '取消');?></el-button>
                <el-button size="small" type="primary" @click="templateSubmit(currentTemplate)"><?= \Yii::t('mall/wechat', '选择模板');?></el-button>
              </span>
            </el-dialog>

            <el-dialog title="如何获取OpenId" :visible.sync="dialogVisible2">
                <div class="dialog">
                    <div class="dialog-text">1.<?= \Yii::t('mall/wechat', '关注公众号，在公众号中回复任意消息');?></div>
                    <div class="dialog-text">2.<?= \Yii::t('mall/wechat', '登录微信公众号平台，进入消息管理，点击刚刚回复的消息');?></div>
                    <img style="width: 100%;" :src="openIdPicUrl_1">
                    <div class="dialog-text">3.<?= \Yii::t('mall/wechat', '复制');?>OpenID</div>
                    <img style="width: 100%;" :src="openIdPicUrl_2">
                </div>
            </el-dialog>
        </div>
        <el-button class='button-item' :loading="btnLoading" @click="store" type="primary" size="small"><?= \Yii::t('mall/wechat', '保存');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                // 视频号参数
                memberDialogVisible: false,
                shareListLoading: false,
                memberPage: 1,
                memberPageCount: 0,
                memberCurrentPage: null,
                shareList: [],
                multipleSelection: [],
                shareKeyword: '',
                templateDialogVisible: false,
                currentTemplateId: null,
                templateList: [
                    {
                        id: 1,
                        name: '<?= \Yii::t('mall/wechat', '模板一');?>',
                        pic_url: 'statics/img/mall/sph/template-1-1.png',
                        preview_pic_url: 'statics/img/mall/sph/template-2-1.png'
                    },
                    {
                        id: 2,
                        name: '<?= \Yii::t('mall/wechat', '模板二');?>',
                        pic_url: 'statics/img/mall/sph/template-1-2.png',
                        preview_pic_url: 'statics/img/mall/sph/template-2-2.png'
                    },
                    {
                        id: 3,
                        name: '<?= \Yii::t('mall/wechat', '模板三');?>',
                        pic_url: 'statics/img/mall/sph/template-1-3.png',
                        preview_pic_url: 'statics/img/mall/sph/template-2-3.png'
                    },
                    {
                        id: 4,
                        name: '<?= \Yii::t('mall/wechat', '模板四');?>',
                        pic_url: 'statics/img/mall/sph/template-1-4.png',
                        preview_pic_url: 'statics/img/mall/sph/template-2-4.png'
                    },
                ],
                previewDialogVisible: false,
                currentTemplate: {
                    preview_pic_url: ''
                },
                dialogVisible2: false,
                openIdPicUrl_1: _baseUrl + '/statics/img/mall/wechatplatform/open_id_1.png',
                openIdPicUrl_2: _baseUrl + '/statics/img/mall/wechatplatform/open_id_2.png',
                ruleForm: {},
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            store() {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: 'mall/wechat/video'
                    },
                    method: 'post',
                    data: self.ruleForm
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
            },
            getShareLevel() {
                this.memberDialogVisible = true;
                this.shareListLoading = true;
                request({
                    params: {
                        r: 'mall/share/level',
                        page: this.memberPage,
                        keyword: this.shareKeyword
                    },
                    method: 'get',
                }).then(e => {
                    this.shareListLoading = false;
                    if (e.data.code === 0) {
                        this.shareList = e.data.data.list;
                        if (this.memberPage == 1) {
                            this.shareList.unshift({
                                id: -1,
                                name: '<?= \Yii::t('mall/wechat', '默认等级');?>',
                                level: -1
                            })
                        }
                        this.memberPageCount = e.data.data.pagination.page_count;
                        this.memberCurrentPage = e.data.data.pagination.current_page;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            handleSelectionChange(val) {
                this.multipleSelection = val;
            },
            memberPagination(currentPage) {
                this.memberPage = currentPage;
                this.getShareLevel();
            },
            memberSubmit() {
                let self = this;
                let shareList = self.ruleForm.video_number_member_list;
                self.multipleSelection.forEach(function(item) {
                    let sign = true;
                    shareList.forEach(function(item2) {
                        if (item.id == item2.id) {
                            sign = false;
                        }
                    })

                    if (sign) {
                        shareList.push({
                            id: item.id,
                            level: item.level,
                            name: item.name
                        })
                    }
                });

                self.memberDialogVisible = false;
            },
            memberHandleClose(index) {
                let self = this;
                let shareList = self.ruleForm.video_number_member_list;
                shareList.splice(index, 1);
            },
            getTemplates() {
                this.templateDialogVisible = true;
            },
            mouseover(item) {
                this.currentTemplateId = item.id;
            },
            mouseleave() {
                this.currentTemplateId = null;
            },
            templateSubmit(item) {
                this.ruleForm.video_number_template_list = [];
                this.ruleForm.video_number_template_list.push({
                    id: item.id,
                    name: item.name
                });
                this.templateDialogVisible = false;
                this.previewDialogVisible = false;
            },
            templateHandleClose(index) {
                let templateList = this.ruleForm.video_number_template_list;
                templateList.splice(index, 1);
            },
            templatePreview(item) {
                this.previewDialogVisible = true;
                this.currentTemplate = item;
            },
            openDialog2() {
                this.dialogVisible2 = true;
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: '/mall/wechat/video'
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.detail;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            }
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
