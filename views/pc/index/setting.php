<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/8 18:12
 */
?>
<style>
    .el-card{
        background-color: #F3F3F3;
    }
    .title {
        margin-top: 10px;
        padding: 18px 20px;
        border-top: 1px solid #F3F3F3;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .form-body {
        background-color: #fff;
        padding: 20px 30% 20px 0;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }

    .link-item {
        border: 1px solid #EBEEF5;
        padding: 10px;
    }

    .link-item .bg {
        height: 44px;
        padding: 0 10px;
        background: #f8f8f8;
    }

    .link-item .del-img {
        height: 20px;
        width: 20px;
        cursor: pointer;
    }

    .column {
        max-width: 500px;
    }

    .list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0 0;
    }

    .item {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        border-radius: 6px;
    }

    .item:hover {
        background: #f1f2f3;
        color: black;
        cursor: pointer;
    }

    .item > span {
        flex: 1;
        overflow: hidden;
    }

</style>
<div id="app" v-cloak>
    <el-card v-loading="loading" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;">
        <el-form :model="ruleForm"
                 :rules="rules"
                 ref="ruleForm"
                 label-width="172px"
                 size="small">
            <div class="title">
                <span><?= \Yii::t('pc/index', '基本设置');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="<?= \Yii::t('pc/index', 'PC商城地址');?>">
                    <span id="target">{{ruleForm.pcUrl}}</span>
                    <el-button v-if="ruleForm.pcUrl" id="copy_btn"
                               data-clipboard-action="copy"
                               data-clipboard-target="#target"
                               size="mini"><?= \Yii::t('pc/index', '复制链接');?>
                    </el-button>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '商城名称');?>" prop="store_name">
                    <el-input v-model="ruleForm.store_name"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '商城logo');?>" prop="store_logo">
                    <app-attachment style="margin-bottom:10px" :multiple="false" :max="1"
                                    @selected="selectPic">
                        <el-tooltip effect="dark"
                                    content="<?= \Yii::t('pc/index', '建议尺寸');?>"
                                    placement="top">
                            <el-button size="mini"><?= \Yii::t('pc/index', '选择图标');?></el-button>
                        </el-tooltip>
                    </app-attachment>
                    <div style="margin-right: 20px;display:inline-block;position: relative;cursor: move;">
                        <app-attachment :multiple="false" :max="1"
                                        @selected="selectPic">
                            <app-image mode="aspectFill"
                                       width="100px"
                                       height='80px'
                                       :src="ruleForm.store_logo">
                            </app-image>
                        </app-attachment>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '备案号');?>" prop="record_number">
                    <el-input v-model="ruleForm.record_number"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '备案号跳转地址');?>" prop="record_number_url">
                    <el-input v-model="ruleForm.record_number_url"></el-input>
                </el-form-item>
                <el-form-item label="ICP" prop="icp">
                    <el-input v-model="ruleForm.icp"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', 'ICP跳转地址');?>" prop="icp_url">
                    <el-input v-model="ruleForm.icp_url"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '版权信息');?>" prop="copyright">
                    <el-input v-model="ruleForm.copyright"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '商家简介信息');?>" prop="mall_desc">
                    <el-input v-model="ruleForm.mall_desc" type="textarea" :rows="5"></el-input>
                </el-form-item>
            </div>
            <div class="title">
                <span><?= \Yii::t('pc/index', '公告');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="<?= \Yii::t('pc/index', '公告标题');?>" prop="announcement_title">
                    <el-input v-model="ruleForm.announcement_title"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '链接地址');?>" prop="announcement_url">
                    <el-input v-model.trim="ruleForm.announcement_url"></el-input>
                </el-form-item>
            </div>
            <div class="title">
                <span><?= \Yii::t('pc/index', '为你推荐');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="<?= \Yii::t('pc/index', '标题');?>" prop="icp">
                    <el-input v-model="ruleForm.recommend_title"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '分类');?>">
                    <el-tag style="margin-right: 5px;margin-bottom:5px" v-for="(item,index) in ruleForm.recommend_cat_list"
                            :key="index" type="warning" @close="catDel(index)" closable :disable-transitions="true">{{item.name}}
                    </el-tag>
                    <el-button size="small" @click="openCatsSetting"><?= \Yii::t('pc/index', '添加');?></el-button>
                </el-form-item>
            </div>
            <div class="title">
                <span><?= \Yii::t('pc/index', 'QQ客服');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="" prop="qq_customer_service">
                    <div class="link-item">
                        <div v-for="(item, i) in ruleForm.qq_customer_service" :key="i" style="margin-bottom: 16px">
                            <div flex="dir:left cross:center" class="bg">
                                <span style="width: 100px;"><?= \Yii::t('pc/index', '名称');?>:</span>
                                <el-input type="text" v-model.trim="item.name" size="small"
                                          style="margin:0 16px"></el-input>
                                <span style="width: 100px;">QQ:</span>
                                <el-input type="text" v-model.trim="item.number" size="small"
                                          style="margin:0 16px"></el-input>
                                <div @click="deleteLink(i, 2)" style="margin-left: auto;line-height: 1">
                                    <el-image class="del-img" src="statics/img/mall/order/del.png"></el-image>
                                </div>
                            </div>
                        </div>
                        <div flex="dir:left cross:center" class="bg">
                            <el-button @click="addLink(2)" style="margin-left: 12px"><?= \Yii::t('pc/index', '添加QQ');?></el-button>
                        </div>
                    </div>
                </el-form-item>
            </div>
            <div class="title">
                <span><?= \Yii::t('pc/index', '外链客服');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="" prop="web_service_url">
                    <div class="link-item">
                        <div v-for="(item, i) in ruleForm.web_service_url" :key="i" style="margin-bottom: 16px">
                            <div flex="dir:left cross:center" class="bg">
                                <span style="width: 100px;"><?= \Yii::t('pc/index', '名称');?>:</span>
                                <el-input type="text" v-model.trim="item.name" size="small"
                                          style="margin:0 16px"></el-input>
                                <span style="width: 100px;"><?= \Yii::t('pc/index', '链接');?>:</span>
                                <el-input type="text" v-model.trim="item.url" size="small"
                                          style="margin:0 16px"></el-input>
                                <div @click="deleteLink(i, 3)" style="margin-left: auto;line-height: 1">
                                    <el-image class="del-img" src="statics/img/mall/order/del.png"></el-image>
                                </div>
                            </div>
                        </div>
                        <div flex="dir:left cross:center" class="bg">
                            <el-button @click="addLink(3)" style="margin-left: 12px"><?= \Yii::t('pc/index', '添加外链');?></el-button>
                        </div>
                    </div>
                </el-form-item>
            </div>
            <div class="title">
                <span><?= \Yii::t('pc/index', '底部信息');?></span>
            </div>
            <div class="form-body">
                <el-form-item label="<?= \Yii::t('pc/index', '底部图片');?>" prop="bottom_pic_list">
                    <el-button type="primary" @click="picEdit('-1')" size="small"><?= \Yii::t('pc/index', '添加');?></el-button>
                    <div flex="main:left">
                        <draggable flex="main:left" style="flex-wrap: wrap" v-model="ruleForm.bottom_pic_list">
                            <div @mouseenter="picEnter(index)" @mouseleave="picAway"
                                 style="position: relative;height:150px;margin-right: 10px;" flex="dir:top box:mean"
                                 v-for="(item, index) in ruleForm.bottom_pic_list">
                                <div flex="main:center cross:center">
                                    <img :src="item.pic_url" width="150" height="150">
                                </div>
                                <div v-show="picIndex == index" flex="box:mean"
                                     style="position: absolute;bottom: 0;width: 100%;height: 25px;cursor: pointer;">
                                        <span @click="picEdit(index)" style="background: rgba(64, 158, 255, 0.9);"
                                              flex="main:center cross:center">
                                            <?= \Yii::t('pc/index', '编辑');?>
                                        </span>
                                    <span @click="picDestroy(index)"
                                          style="background: rgba(245, 108, 108, 0.9);"
                                          flex="main:center cross:center">
                                            <?= \Yii::t('pc/index', '删除');?>
                                        </span>
                                </div>
                            </div>
                        </draggable>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('pc/index', '友情链接');?>" prop="friendship_link">
                    <div class="link-item">
                        <div v-for="(item, i) in ruleForm.friendship_link" :key="i" style="margin-bottom: 16px">
                            <div flex="dir:left cross:center" class="bg">
                                <span style="width: 100px;"><?= \Yii::t('pc/index', '名称');?>:</span>
                                <el-input type="text" v-model.trim="item.name" size="small"
                                          style="margin:0 16px"></el-input>
                                <span style="width: 180px;"><?= \Yii::t('pc/index', '链接地址');?>:</span>
                                <el-input type="text" label="asdf" v-model.trim="item.jump_url" size="small"
                                          style="margin:0 16px"></el-input>
                                <div @click="deleteLink(i, 1)" style="margin-left: auto;line-height: 1">
                                    <el-image class="del-img" src="statics/img/mall/order/del.png"></el-image>
                                </div>
                            </div>
                        </div>
                        <div flex="dir:left cross:center" class="bg">
                            <el-button @click="addLink(1)" style="margin-left: 12px"><?= \Yii::t('pc/index', '添加友情链接');?></el-button>
                        </div>
                    </div>
                </el-form-item>
            </div>
            <el-button :loading="submitLoading" class="button-item" size="small" type="primary"
                       @click="submit('ruleForm')"><?= \Yii::t('pc/index', '保存');?>
            </el-button>
        </el-form>
    </el-card>
    <el-dialog title="<?= \Yii::t('pc/index', '操作');?>" :visible.sync="dialogFormVisible" @close="dialogClose">
        <el-form :model="dialogRuleForm" :rules="dialogRules" size="small" ref="dialogRuleForm" label-width="120px">
            <el-row>
                <el-col :span="18">
                    <el-form-item label="<?= \Yii::t('pc/index', '图片');?>" prop="pic_url">
                        <app-attachment :multiple="false" :max="1" @selected="dialogPic">
                            <el-tooltip effect="dark" content="<?= \Yii::t('pc/index', '建议尺寸235');?>" placement="top">
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
            <el-button size="small" @click="dialogFormVisible = false"><?= \Yii::t('pc/index', '取');?></el-button>
            <el-button size="small" type="primary" @click="dialogFormSubmit"><?= \Yii::t('pc/index', '提');?></el-button>
        </div>
    </el-dialog>
    <el-dialog title="<?= \Yii::t('pc/index', '一级分类选择');?>" :visible.sync="catsVisible">
        <el-form @submit.native.prevent label-width="50px">
            <el-row>
                <el-col class="column">
                    <el-card shadow="never">
                        <ul class="list">
                            <li v-for="(item,index) in catsList" class="item" @click="itemClick(index)">
                                <span>{{ item.name }}</span>
                            </li>
                        </ul>
                    </el-card>
                </el-col>
            </el-row>
        </el-form>
    </el-dialog>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/clipboard.min.js"></script>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                submitLoading: false,
                ruleForm: {
                    store_name: '',
                    store_logo: '',
                    bottom_pic_list: [],
                    friendship_link: [],
                    recommend_cat_list: [],
                    qq_customer_service: [],
                    web_service_url: [],
                },
                rules: {
                    store_name: [
                        {required: true, message: '<?= \Yii::t('pc/index', '请填写商城名称');?>', trigger: 'change'},
                        {max: 64, message: '<?= \Yii::t('pc/index', '最多64个字');?>'},
                    ],
                    store_logo: [
                        {required: true, message: '<?= \Yii::t('pc/index', '请上传商城logo');?>'},
                    ],
                    friendship_link: [
                        {required: true, message: '<?= \Yii::t('pc/index', '请添加友情链接');?>'},
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
                picEditIndex: -1,
                picIndex: -1,
                catsVisible: false,
                catsList: [],
            };
        },
        created() {
            this.loadData();
        },
        methods: {
            loadData() {
                this.loading = true;
                request({
                    params: {
                        r: 'pc/index/setting',
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.ruleForm = e.data.data;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
                this.loading = false;
            },
            submit(formName) {
                this.$refs[formName].validate((valid,mes) => {
                    if (valid) {
                        this.submitLoading = true;
                        request({
                            params: {
                                r: 'pc/index/setting',
                            },
                            method: 'post',
                            data: {
                                ruleForm: JSON.stringify(this.ruleForm)
                            },
                        }).then(e => {
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                        this.submitLoading = false;
                    } else {
                        this.$message.error(Object.values(mes).shift().shift().message);
                    }
                });
            },
            selectPic(e) {
                if (e.length) {
                    this.ruleForm.store_logo = e[0].url;
                }
            },
            dialogPic(e) {
                if (e.length) {
                    this.dialogRuleForm.pic_url = e[0].url;
                }
            },
            picEnter(index) {
                this.picIndex = index;
            },
            picAway() {
                this.picIndex = -1;
            },
            picEdit(index) {
                this.dialogFormVisible = true;
                if (index != -1) {
                    this.picEditIndex = index;
                    this.dialogRuleForm = this.ruleForm.bottom_pic_list[index]
                }
            },
            dialogClose() {
                this.adEditIndex = -1;
                this.clearDialogData();
            },
            picDestroy(index) {
                this.ruleForm.bottom_pic_list.splice(index, 1)
            },
            dialogFormSubmit() {
                this.$refs.dialogRuleForm.validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.dialogFormVisible = false;
                        if (self.picEditIndex != -1) {
                            self.ruleForm.bottom_pic_list[self.picEditIndex] = self.dialogRuleForm;
                        } else {
                            self.ruleForm.bottom_pic_list.push(self.dialogRuleForm)
                        }
                        self.picEditIndex = -1;
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
            // 添加链接
            addLink(type) {
                if(type === 1){
                    this.ruleForm.friendship_link.push({
                        name: '',
                        jump_url: '',
                    });
                }else if(type === 2){
                    this.ruleForm.qq_customer_service.push({

                    });
                }else{
                    this.ruleForm.web_service_url.push({
                        name: "",
                        url: ""
                    });
                }
            },
            // 删除链接
            deleteLink(index, type) {
                if(type === 1) {
                    this.ruleForm.friendship_link.splice(index, 1)
                }else if(type === 2){
                    this.ruleForm.qq_customer_service.splice(index, 1)
                }else{
                    this.ruleForm.web_service_url.splice(index, 1)
                }
            },
            openCatsSetting() {
                if(this.ruleForm.recommend_cat_list && this.ruleForm.recommend_cat_list.length >= 5){
                    this.$message.error('<?= \Yii::t('pc/index', '只能选择5个分类');?>');
                    return;
                }
                this.$request({
                    params: {
                        r: 'pc/index/cat',
                    }
                }).then(response => {
                    this.catsVisible = true;
                    if (response.data.code === 0) {
                        this.catsList = response.data.data;
                    }else{
                        this.$alert(response.data.msg, '<?= \Yii::t('pc/index', '提示');?>');
                    }
                }).catch(e => {
                });
            },
            itemClick(index){
                this.ruleForm.recommend_cat_list.push(this.catsList[index]);
                this.catsVisible = false;
            },
            catDel(index){
                this.ruleForm.recommend_cat_list.splice(index, 1)
            },
        },
        computed: {}
    });
    var clipboard = new Clipboard('#copy_btn');

    var self = this;
    clipboard.on('success', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('pc/index', '复制成功');?>');
        e.clearSelection();
    });
    clipboard.on('error', function (e) {
        self.ELEMENT.Message.success('<?= \Yii::t('pc/index', '复制失败');?>');
    });
</script>
