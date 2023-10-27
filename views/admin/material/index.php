<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .material-list {
        width: 750px;
        padding: 20px;
    }

    .material-list * {
        box-sizing: border-box;
    }

    .material-list:after {
        clear: both;
        display: block;
        content: " ";
    }

    .material-item {
        display: inline-block;
        cursor: pointer;
        position: relative;
        float: left;
        width: 160px;
        height: 180px;
        margin: 7.5px;
        text-align: center;
        padding: 10px 10px 0;
    }
    .material-item.checked,
    .material-item.selected {
        box-shadow: 0 0 0 1px #1ed0ff;
        background: #daf5ff;
        border-radius: 5px;
    }

    .material-item .material-img {
        display: block;
    }

    .material-item .file-type-icon {
        width: 30px;
        height: 30px;
        border-radius: 30px;
        background: #666;
        color: #fff;
        text-align: center;
        line-height: 30px;
        font-size: 24px;
    }

    .material-upload {
        box-shadow: none;
        border: 1px dashed #b2b6bd;
        height: 140px;
        width: 140px;
        margin: 17.5px;
        padding: 0;
    }

    .material-upload i {
        font-size: 30px;
        color: #909399;
    }

    .material-upload:hover {
        box-shadow: none;
        border: 1px dashed #409EFF;
    }

    .material-upload:hover i {
        color: #409EFF;
    }

    .material-upload:active {
        border: 1px dashed #20669c;
    }

    .material-upload:active i {
        color: #20669c;
    }

    .material-dialog .group-menu {
        border-right: none;
        width: 250px;
    }

    .material-dialog .group-menu .el-menu-item {
        padding-left: 10px !important;
        padding-right: 10px;
    }

    .material-dialog .group-menu .el-menu-item .el-button {
        padding: 3px 0;
    }

    .del-material-dialog .group-menu .el-menu-item .el-button:hover {
        background: #e2e2e2;
    }

    .material-dialog .group-menu .el-menu-item .el-button i {
        margin-right: 0;
    }

    .material-simple-upload i {
        font-size: 32px;
    }

    .material-dialog .material-name {
        color: #666666;
        font-size: 13px;
        margin-top: 0px;
        margin-right: auto;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }

    .search .el-input__inner {
        border-right: 0;
    }

    .search .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .search .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .search .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .search .el-input-group__append .el-button {
        padding: 0;
    }

    .search .el-input-group__append .el-button {
        margin: 0;
    }

    .box {
        border: 1px solid #e3e3e3;
    }

    .box .el-scrollbar__wrap {
        overflow-y: hidden;
    }

    /* https://github.com/ElemeFE/element/pull/15359 */
    .el-input .el-input__count .el-input__count-inner {
        background: #FFF;
        display: inline-block;
        padding: 0 5px;
        line-height: normal;
    }

    .tag-box{
        padding: 15px;
        overflow: auto;
        display: flex;
    }

    .tag-box::-webkit-scrollbar{
        position: relative;
        display: block;
        width: 100%;
        height: 7px;
        cursor: pointer;
        border-radius: inherit;
        background-color: rgba(144,147,153,.3);
        -webkit-transition: .3s background-color;
        transition: .3s background-color;
    }

    .tag-box::-webkit-scrollbar:hover{
        background-color: rgba(144,147,153,.8);
    }
</style>
<div id="app" v-cloak class="material material-dialog">
    <el-card shadow="never" body-style="background-color: #f3f3f3;padding: 0;">
        <div class="table-body">
            <div flex="cross:center" style="margin-bottom: 12px;">
                <div v-if="is_recycle" style="width:80px"></div>
                <el-button size="small" v-else type="primary" @click="showAddGroup(-1)"><?= \Yii::t('admin/material', '添加分组');?></el-button>
                <div style="margin-left:200px">
                    <el-button type="primary" @click="recoverClick(1)" size="small" v-if="!is_recycle"><?= \Yii::t('admin/material', '回收站');?></el-button>
                    <el-button type="primary" @click="recoverClick(0)" size="small" v-else><?= \Yii::t('admin/material', '退出回收站');?></el-button>
                </div>
                <div flex="cross:center" style="margin-left:auto">
                    <div class="search" style="margin-right: 12px">
                        <el-input placeholder="<?= \Yii::t('admin/material', '请输入名称搜索');?>" v-model="keyword"
                                  clearable @clear="dialogOpened"
                                  size="small"
                                  @keyup.enter.native="dialogOpened"
                                  class="input-with-select">
                            <el-button @click="dialogOpened" slot="append" icon="el-icon-search"></el-button>
                        </el-input>
                    </div>
                    <el-checkbox v-model="selectAll"
                                 @change="selectAllChange"
                                 label="<?= \Yii::t('admin/material', '全选');?>"
                                 size="small"
                                 style="margin-right: 12px;margin-bottom: 0"></el-checkbox>
                    <el-button v-if="is_recycle"
                               :loading="deleteLoading"
                               @click="deleteItems(2)"
                               size="small"
                               style="margin-right: 12px"><?= \Yii::t('admin/material', '还原');?>
                    </el-button>
                    <el-button :loading="deleteLoading"
                               @click="deleteItems(is_recycle?3:1)"
                               size="small"
                               style="margin-right: 12px"><?= \Yii::t('admin/material', '删除');?>
                    </el-button>
                    <el-dropdown v-if="!is_recycle && currentAttachmentGroupId != -1"
                                 v-loading="moveLoading"
                                 trigger="click"
                                 :split-button="true"
                                 size="small"
                                 @command="moveItems">
                        <span><?= \Yii::t('admin/material', '移动至');?></span>
                        <el-dropdown-menu slot="dropdown" style="height: 250px;overflow-y:scroll">
                            <el-dropdown-item v-for="(item, index) in groupList"
                                              :command="index"
                                              :key="index">
                                {{item.name}}
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </div>
            <div flex="box:first" style="margin-bottom: 10px;min-height: 68vh">
                <div style="border: 1px solid #e3e3e3;margin-right:15px">
                    <el-menu class="group-menu"
                             mode="vertical"
                             v-loading="groupListLoading">
                        <el-menu-item index="-1" @click="switchGroup(-2)">
                            <i class="el-icon-tickets"></i>
                            <span><?= \Yii::t('admin/material', '效果图');?></span>
                        </el-menu-item>
                        <div style="background: rgba(0, 0, 0, 0.05);height: 10px;"></div>
                        <el-scrollbar style="height:635px;width:100%">
                            <el-menu-item index="all" @click="switchGroup(-1)">
                                <i class="el-icon-tickets"></i>
                                <span><?= \Yii::t('admin/material', '全部');?></span>
                            </el-menu-item>
                            <template v-for="(item, index) in groupItem">
                                <el-menu-item :index="'' + index" @click="switchGroup(index)">
                                    <div flex="dir:left box:last">
                                        <div style="overflow: hidden;text-overflow: ellipsis">
                                            <i class="el-icon-tickets"></i>
                                            <span>{{item.name}}</span>
                                        </div>
                                        <div v-if="is_recycle" flex="dir:left">
                                            <el-button @click.stop="deleteGroup(index,2)" type="text"><?= \Yii::t('admin/material', '还原');?></el-button>
                                            <div style="color:#409EFF;margin:0 2px">|</div>
                                            <el-button type="text" @click.stop="deleteGroup(index,3)"><?= \Yii::t('admin/material', '删除');?></el-button>
                                        </div>
                                        <div v-else flex="dir:left">
                                            <el-button type="text" @click.stop="showAddGroup(index)"><?= \Yii::t('admin/material', '编辑');?>
                                            </el-button>
                                            <div style="color:#e3e3e3;margin:0 2px">|</div>
                                            <el-button type="text" @click.stop="deleteGroup(index,1)"><?= \Yii::t('admin/material', '删除');?></el-button>
                                        </div>
                                    </div>
                                </el-menu-item>
                            </template>
                        </el-scrollbar>
                    </el-menu>
                </div>
                <div v-loading="loading" flex="dir:top" class="box">
                    <div class="tag-box" v-if="currentAttachmentGroupId != -1">
                        <el-tag style="margin-right: 10px;cursor: pointer;"
                                v-for="(item, index) in tags"
                                :key="index"
                                @click="navbar(index)"
                                :effect="tag == index ? 'dark' : 'plain'"
                                type="success">
                            {{item}}
                        </el-tag>
                    </div>
                    <el-scrollbar>
                        <div class="material-list">
                            <div v-if="!is_recycle" class="material-item material-upload">
                                <app-upload
                                        v-if="currentAttachmentGroupId == -1"
                                        v-loading="uploading"
                                        :disabled="uploading"
                                        @start="handleStart"
                                        @success="handleSuccess"
                                        @complete="handleComplete"
                                        :multiple="true"
                                        :max="10"
                                        :params="uploadParams"
                                        :accept="accept"
                                        flex="main:center cross:center"
                                        style="width: 140px;height: 140px">
                                    <div v-if="uploading">{{uploadCompleteFilesNum}}/{{uploadFilesNum}}</div>
                                    <i v-else class="el-icon-upload"></i>
                                </app-upload>
                                <i v-else @click="addMaterial({})" class="el-icon-plus" flex="main:center cross:center" style="width: 140px;height: 140px"></i>
                            </div>
                            <template v-for="(item, index) in attachments">
                                <div :key="index" :class="'material-item'+(item.selected ?' selected':'')"
                                     @click="selectItem(item)">
                                    <!-- 图片 -->
                                    <img v-if="item.type == 1" class="material-img"
                                         :src="item.thumb_url"
                                         style="width: 140px;height: 140px;">
                                    <!-- 名称 -->
                                    <div flex="dir:left" style="margin-top:5px">
                                        <div class="material-name">{{item.name}}</div>
                                        <div style="margin:0 5px">|</div>
                                        <div>
                                            <el-button @click.stop="showPicModel(index)" type="text"
                                                       style="padding:0"><?= \Yii::t('admin/material', '编辑');?>
                                            </el-button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </el-scrollbar>
                    <div style="padding: 5px;text-align: right;margin-top:auto">
                        <el-pagination
                                v-if="pagination"
                                background
                                @size-change="handleLoadMore"
                                @current-change="handleLoadMore"
                                :current-page.sync="page"
                                :page-size="pagination.pageSize"
                                layout="prev, pager, next, jumper"
                                :total="pagination.totalCount">
                        </el-pagination>
                    </div>
                </div>
            </div>

            <!-- 分类 -->
            <el-dialog append-to-body title="<?= \Yii::t('admin/material', '分组管理');?>" :visible.sync="addGroupVisible" :close-on-click-modal="false"
                       width="30%">
                <el-form @submit.native.prevent label-width="90px" ref="groupForm" :model="groupForm"
                         :rules="groupFormRule">
                    <el-form-item label="<?= \Yii::t('admin/material', '分组名称');?>" prop="name" style="margin-bottom: 22px;">
                        <el-input v-model="groupForm.name" maxlength="8" show-word-limit></el-input>
                    </el-form-item>
                    <el-form-item style="text-align: right">
                        <el-button type="primary" @click="groupFormSubmit('groupForm')" :loading="groupFormLoading"
                                   size="medium"><?= \Yii::t('admin/material', '保存');?>
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
            <!-- 名称修改 -->
            <el-dialog append-to-body title="名称修改"
                       :visible.sync="addPicVisible" :close-on-click-modal="false"
                       width="30%">
                <el-form @submit.native.prevent label-width="90px" ref="picForm" :model="picForm"
                         :rules="picFormRule">
                    <el-form-item label="名称" prop="name"
                                  style="margin-bottom: 22px;">
                        <el-input v-model="picForm.name"></el-input>
                    </el-form-item>
                    <el-form-item style="text-align: right">
                        <el-button type="primary" @click="picFormSubmit('picForm')" :loading="picFormLoading"
                                   size="medium"><?= \Yii::t('admin/material', '保存');?>
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>

            <!-- 添加素材 -->
            <el-dialog append-to-body title="添加素材"
                       :visible.sync="addMaterialForm.visible" :close-on-click-modal="false"
                       width="60%">
                <el-form @submit.native.prevent label-width="90px" ref="addMaterialForm" :model="addMaterialForm"
                         :rules="addMaterialForm.rule">
                    <el-form-item label="素材" prop="attachments">
                        <div class="material-list">
                            <div class="material-item material-upload" style="margin-top: 0;" v-if="!addMaterialForm.is_edit">
                                <app-upload
                                        v-loading="uploading"
                                        :disabled="uploading"
                                        @start="handleStart"
                                        @success="handleMaterialSuccess"
                                        @complete="handleComplete"
                                        :multiple="true"
                                        :max="10"
                                        :params="uploadParams"
                                        :accept="accept"
                                        flex="main:center cross:center"
                                        style="width: 140px;height: 140px">
                                    <div v-if="uploading">{{uploadCompleteFilesNum}}/{{uploadFilesNum}}</div>
                                    <i v-else class="el-icon-upload"></i>
                                </app-upload>
                            </div>
                            <template v-for="(item, index) in addMaterialForm.attachments">
                                <img class="material-img" :src="item.thumb_url" style="width: 140px;height: 140px;">
                            </template>
                        </div>
                    </el-form-item>
                    <el-form-item label="名称" v-if="addMaterialForm.is_edit" style="margin-bottom: 22px;">
                        <el-input v-model="addMaterialForm.name"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/material', '分组');?>" size="small">
                        <el-select style="width: 180px;" size="small" v-model="addMaterialForm.group_id">
                            <el-option v-for="item in groupItem" :key="item.id" :value="item.id" :label="item.name"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/material', '定位标签');?>" size="small">
                        <el-select style="width: 180px;" size="small" v-model="addMaterialForm.tag">
                            <el-option v-for="(tag, index) in tags" v-if="index != ''" :key="index" :value="index" :label="tag"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/material', '效果图');?>" size="small">
                        <app-attachment @selected="picUrl" :effect="1">
                            <el-tooltip effect="dark"
                                        content="<?= \Yii::t('admin/setting', '建议尺寸');?>:160 * 50"
                                        placement="top">
                                <el-button><?= \Yii::t('admin/setting', '选择图片');?></el-button>
                            </el-tooltip>
                        </app-attachment>
                        <div flex="dir:top" style="position: relative;display: inline-block;margin-top: 10px;">
                            <app-image v-if="!addMaterialForm.url"></app-image>
                            <el-image v-else :src="addMaterialForm.thumb_url" :preview-src-list="[addMaterialForm.url]"></el-image>
                        </div>
                    </el-form-item>
                    <el-form-item style="text-align: right">
                        <el-button type="primary" @click="materialSubmit('addMaterialForm')" :loading="addMaterialForm.loading"
                                   size="medium"><?= \Yii::t('admin/material', '保存');?>
                        </el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
        </div>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: '#app',
        computed: {
            accept: {
                get() {
                    return 'image/*';
                },
            },
        },
        data() {
            return {
                mall: -1,
                tabs: 'image',
                is_recycle: 0,
                keyword: '',

                uploading: false,
                loading: true,
                noMore: false,
                attachments: [],
                checkedAttachments: [],
                uploadParams: {
                    mall_id: -1
                },
                uploadCompleteFilesNum: 0,
                uploadFilesNum: 0,
                page: 0,
                addGroupVisible: false,
                groupList: [],
                groupItem: [],
                groupListLoading: false,
                groupForm: {
                    id: null,
                    name: '',
                },
                groupFormRule: {
                    name: [
                        {required: true, message: '<?= \Yii::t('admin/material', '请填写分组名称');?>',}
                    ],
                },
                groupFormLoading: false,
                selectAll: false,
                deleteLoading: false,
                moveLoading: false,
                currentAttachmentGroupId: null,
                pagination: null,

                addPicVisible: false,
                picForm: {
                    id: null,
                    name: '',
                },
                picFormRule: {
                    name: [
                        {required: true, message: '<?= \Yii::t('admin/material', '请填写名称');?>',}
                    ],
                },
                picFormLoading: false,

                addMaterialForm: {},
                tags: [],
                tag: null,
            };
        },
        mounted() {
            this.dialogOpened();
        },
        methods: {
            navbar(index) {
                this.tag = index;
                this.loadList({})
            },
            picUrl(e) {
                if (e.length) {
                    this.addMaterialForm.effect_id = e[0].id;
                    this.addMaterialForm.thumb_url = e[0].thumb_url;
                    this.addMaterialForm.url = e[0].url;
                }
            },
            materialSubmit(formName){
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.addMaterialForm.loading = true;
                        this.$request({
                            params: {r: 'common/attachment/effect'},
                            method: 'post',
                            data: Object.assign({mall_id: this.mall}, this.addMaterialForm),
                        }).then(e => {
                            this.addMaterialForm.loading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                this.$refs[formName].clearValidate()
                                this.addMaterialForm.visible = false;
                                this.loadList({})
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.addMaterialForm.loading = false;
                        });
                    }
                });
            },
            recoverClick(is_recycle) {
                this.is_recycle = is_recycle;
                this.dialogOpened();
            },
            dialogOpened() {
                this.page = 1;
                this.loadGroups();
                this.loadList({})
            },
            deleteItems(type) {
                const itemIds = [];
                for (let i in this.attachments) {
                    if (this.attachments[i].selected) {
                        itemIds.push(this.attachments[i].id);
                    }
                }
                if (!itemIds.length) {
                    this.$message.warning('<?= \Yii::t('admin/material', '请先选择需要处理的图片');?>');
                    return;
                }

                let title;
                switch (type) {
                    case 1:
                        title = '<?= \Yii::t('admin/material', '是否确认将选中素材放入回收站中');?>';
                        break;
                    case 2:
                        title = '<?= \Yii::t('admin/material', '确认还原选择素材');?>';
                        break;
                    case 3:
                        title = '<?= \Yii::t('admin/material', '素材删除后将无法恢复');?>';
                        break;
                    default:
                        title = '';
                        break;
                }
                this.$confirm(title, '<?= \Yii::t('admin/material', '提示');?>', {
                    type: 'warning'
                }).then(() => {
                    this.deleteLoading = true;
                    this.$request({
                        params: {
                            r: 'common/attachment/delete'
                        },
                        method: 'post',
                        data: {
                            ids: itemIds,
                            mall_id: this.mall,
                            //type 1回收 2还原 3删除
                            type,
                        },
                    }).then(e => {
                        this.deleteLoading = false;
                        if (e.data.code === 0) {
                            this.$message.success(e.data.msg);
                            for (let i in itemIds) {
                                for (let j in this.attachments) {
                                    if (this.attachments[j].id == itemIds[i]) {
                                        this.attachments.splice(j, 1);
                                        break;
                                    }
                                }
                            }
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.deleteLoading = false;
                    });
                }).catch(() => {
                });
            },
            selectAllChange(value) {
                for (let i in this.attachments) {
                    this.attachments[i].selected = value;
                }
            },
            selectItem(item) {
                item.selected = !item.selected;
            },
            moveItems(index) {
                const itemIds = [];
                for (let i in this.attachments) {
                    if (this.attachments[i].selected) {
                        itemIds.push(this.attachments[i].id);
                    }
                }
                if (!itemIds.length) {
                    this.$message.warning('<?= \Yii::t('admin/material', '请先选择需要移动的图片');?>');
                    return;
                }
                this.$confirm('<?= \Yii::t('admin/material', '确认移动所选的');?>' + itemIds.length + '<?= \Yii::t('admin/material', '张图片');?>', '<?= \Yii::t('admin/material', '提示');?>', {
                    type: 'warning'
                }).then(() => {
                    this.moveLoading = true;
                    this.$request({
                        params: {
                            r: 'common/attachment/move'
                        },
                        method: 'post',
                        data: {
                            ids: itemIds,
                            attachment_group_id: this.groupItem[index].id,
                            mall_id: this.mall,
                        },
                    }).then(e => {
                        this.moveLoading = false;
                        if (e.data.code === 0) {
                            this.$message.success(e.data.msg);
                            this.switchGroup(index);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.moveLoading = false;
                    });
                }).catch(() => {
                });
            },
            loadGroups() {
                this.groupListLoading = true;
                this.$request({
                    params: {
                        r: 'common/attachment/group-list',
                        is_recycle: this.is_recycle,
                        type: this.tabs,
                        mall_id: this.mall,
                    },
                }).then(e => {
                    this.groupListLoading = false;
                    if (e.data.code === 0) {
                        this.groupItem = e.data.data.list;
                        this.groupList = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.groupListLoading = false;
                });
            },
            showAddGroup(index) {
                if (index > -1) {
                    this.groupForm.id = this.groupItem[index].id;
                    this.groupForm.name = this.groupItem[index].name;
                } else {
                    this.groupForm.id = null;
                    this.groupForm.name = '';
                }
                this.groupForm.edit_index = index;
                this.addGroupVisible = true;
            },
            deleteGroup(index, type) {
                let title;
                switch (type) {
                    case 1:
                        title = '<?= \Yii::t('admin/material', '是否确认将分组放入回收站中');?>';
                        break;
                    case 2:
                        title = '<?= \Yii::t('admin/material', '确认还原选择分组');?>';
                        break;
                    case 3:
                        title = '<?= \Yii::t('admin/material', '分组删除后将无法恢复');?>';
                        break;
                    default:
                        title = '';
                        break;
                }
                this.$confirm(title, '<?= \Yii::t('admin/material', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('admin/material', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('admin/material', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    this.$request({
                        params: {r: 'common/attachment/group-delete'},
                        method: 'POST',
                        data: {
                            id: this.groupItem[index].id,
                            mall_id: this.mall,
                            type,
                        }
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.$message.success(e.data.msg);
                            this.groupItem.splice(index, 1);
                            location.reload();
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                    });
                }).catch(() => {
                });
            },
            showPicModel(index) {
                let attachment = this.attachments[index];
                if(attachment.mall_id === '-1' && this.currentAttachmentGroupId !== -1){
                    let item = {
                        attachments: [attachment],
                        name: attachment.name,
                        group_id: parseInt(attachment.attachment_group_id) || null,
                        effect_id: attachment.effect ? attachment.effect.effect_id : '',
                        tag: attachment.effect ? attachment.effect.tag : null,
                        thumb_url: attachment.effect && attachment.effect.attachment ? attachment.effect.attachment.thumb_url : '',
                        url: attachment.effect && attachment.effect.attachment ? attachment.effect.attachment.url : '',
                        is_edit: 1
                    };
                    this.addMaterial(item);
                }else {
                    this.picForm = {
                        id: attachment.id,
                        name: attachment.name,
                        edit_index: index,
                    };
                    this.addPicVisible = true;
                }
            },
            addMaterial(item){
                this.addMaterialForm = Object.assign({
                    visible: true,
                    rule: {
                        attachments: [
                            {required: true, message: '<?= \Yii::t('admin/material', '请选择素材');?>',}
                        ],
                    },
                    loading: false,
                    attachments: [],
                    url: '',
                }, item);
            },
            picFormSubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.picFormLoading = true;
                        this.$request({
                            params: {
                                r: 'common/attachment/rename',
                            },
                            method: 'post',
                            data: Object.assign({mall_id: this.mall}, this.picForm),
                        }).then(e => {
                            this.picFormLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                this.addPicVisible = false;
                                this.attachments[this.picForm.edit_index].name = this.picForm.name;
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.picFormLoading = false;
                        });
                    }
                })
            },
            groupFormSubmit(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.groupFormLoading = true;
                        this.$request({
                            params: {
                                r: 'common/attachment/group-update',
                            },
                            method: 'post',
                            data: Object.assign({}, this.groupForm, {'type': this.tabs,  mall_id: this.mall}),
                        }).then(e => {
                            this.groupFormLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                this.addGroupVisible = false;
                                if (this.groupForm.edit_index > -1) {
                                    this.groupItem[this.groupForm.edit_index] = e.data.data;
                                } else {
                                    this.groupList.push(e.data.data);
                                }
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.groupFormLoading = false;
                        });
                    }
                })
            },
            switchGroup(index) {
                this.attachments = [];
                this.page = 0;
                this.noMore = false;
                let groupId = null;
                if(index > -1){
                    groupId = this.groupItem[index].id;
                }else if(index === -2){ // 效果图
                    groupId = -1;
                }
                this.uploadParams = {
                    attachment_group_id: groupId,
                    mall_id: this.mall
                };
                this.currentAttachmentGroupId = groupId;
                this.loadList({});
            },
            loadList(params) {
                this.loading = true;
                this.noMore = false;
                this.selectAll = false;
                params['r'] = 'common/attachment/list';
                params['page'] = this.page;
                params['attachment_group_id'] = this.currentAttachmentGroupId;
                params['type'] = this.tabs;
                params['is_recycle'] = this.is_recycle;
                params['keyword'] = this.keyword;
                params['mall_id'] = this.mall;
                params['limit'] = 15;
                params['tag'] = this.tag;
                this.$request({
                    params: params,
                }).then(e => {
                    if (e.data.code === 0) {
                        if (!e.data.data.list.length) {
                            this.noMore = true;
                        }
                        for (let i in e.data.data.list) {
                            e.data.data.list[i].checked = false;
                            e.data.data.list[i].selected = false;
                            e.data.data.list[i].duration = null;
                        }
                        this.attachments = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                        this.tags = e.data.data.tags;
                        this.checkedAttachments = [];
                        this.loading = false;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            confirm() {
                this.$emit('selected', this.checkedAttachments, this.params);
                const urls = [];
                for (let i in this.checkedAttachments) {
                    urls.push(this.checkedAttachments[i].url);
                }
                for (let i in this.attachments) {
                    this.attachments[i].checked = false;
                }
                this.checkedAttachments = [];
                if (!urls.length) {
                    return;
                }
                if (this.multiple) {
                    this.$emit('input', urls);
                } else {
                    this.$emit('input', urls[0]);
                }
            },
            handleStart(files) {
                this.uploading = true;
                this.uploadFilesNum = files.length;
                this.uploadCompleteFilesNum = 0;
            },
            handleSuccess(file) {
                if (file.response && file.response.data && file.response.data.code === 0) {
                    this.attachments.unshift(this.materialSuccess(file.response.data.data));
                    this.uploadCompleteFilesNum++;
                }
            },
            materialSuccess(response) {
                return {
                    checked: false,
                    selected: false,
                    created_at: response.created_at,
                    deleted_at: response.deleted_at,
                    id: `${response.id}`,
                    is_delete: response.is_delete,
                    mall_id: response.mall_id,
                    name: response.name,
                    size: response.size,
                    storage_id: response.storage_id,
                    thumb_url: response.thumb_url,
                    type: response.type,
                    updated_at: response.updated_at,
                    url: response.url,
                    user_id: response.user_id,
                    duration: null,
                    cover_pic_src: null,
                };
            },
            handleMaterialSuccess(file) {
                if (file.response && file.response.data && file.response.data.code === 0) {
                    this.addMaterialForm.attachments.unshift(this.materialSuccess(file.response.data.data));
                    this.uploadCompleteFilesNum++;
                }
            },
            handleComplete(files) {
                this.uploading = false;
            },
            handleLoadMore(currentPage) {
                if (this.noMore) {
                    return;
                }
                this.page = currentPage;
                this.loadList({});
            },
        }
    });
</script>
                 