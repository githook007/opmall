<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>

<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 50%;
    }

    .form-button {
        margin: 0!important;
    }

    .form-button .el-form-item__content {
        margin-left: 0!important;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;" v-loading="cardLoading">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'mall/home-nav/index'})"><?= \Yii::t('mall/home_nav', '导航图标列表');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/home_nav', '导航图标编辑');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form_box">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="120px">
                <div class="form-body">
                    <el-form-item label="<?= \Yii::t('mall/home_nav', '名称');?>" prop="name">
                        <el-input v-model="ruleForm.name" placeholder="<?= \Yii::t('mall/home_nav', '请输入名称');?>"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/home_nav', '排序');?>" prop="sort">
                        <el-input type="number" v-model="ruleForm.sort" placeholder="<?= \Yii::t('mall/home_nav', '请输入排序');?>"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/home_nav', '导航图标');?>" prop="icon_url">
                        <app-attachment :multiple="false" :max="1" @selected="picUrl">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/home_nav', '建议尺寸88');?>" placement="top">
                                <el-button size="mini"><?= \Yii::t('mall/home_nav', '选择文件');?></el-button>
                            </el-tooltip>
                        </app-attachment>
                        <app-image width="80px" height="80px" mode="aspectFill" :src="ruleForm.icon_url"></app-image>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/home_nav', '导航链接');?>" prop="url">
                        <div flex="box:last">
                            <el-input disabled v-model="ruleForm.url"></el-input>
                            <app-pick-link @selected="linkUrl">
                                <el-button><?= \Yii::t('mall/home_nav', '选择链接');?></el-button>
                            </app-pick-link>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/home_nav', '是否显示');?>" prop="status">
                        <el-switch
                                v-model="ruleForm.status"
                                :active-value="1"
                                :inactive-value="0">
                        </el-switch>
                    </el-form-item>
                </div>
                <el-form-item class="form-button">
                    <el-button :loading="btnLoading" class="button-item" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('mall/home_nav', '保存');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    name: '',
                    sort: 100,
                    url: '',
                    icon_url: '',
                    status: 1,
                    params: [],
                    sign: '',
                },
                rules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('mall/home_nav', '请输入导航名称');?>', trigger: 'change'},
                    ],
                    sort: [
                        {required: true, message: '<?= \Yii::t('mall/home_nav', '请输入导航排序');?>', trigger: 'change'},
                    ],
                    icon_url: [
                        {required: true, message: '<?= \Yii::t('mall/home_nav', '请选择导航图标');?>', trigger: 'change'},
                    ],
                    url: [
                        {required: true, message: '<?= \Yii::t('mall/home_nav', '请选择导航链接');?>', trigger: 'change'},
                    ],
                    status: [
                        {required: true, message: '<?= \Yii::t('mall/home_nav', '请选择是否显示');?>', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            // 返回上一页
            cancel(){
                window.history.go(-1)
            },
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/home-nav/edit'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'mall/home-nav/index'
                                })
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
                        r: 'mall/home-nav/edit',
                        id: getQuery('id')
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
            },
            picUrl(e) {
                if (e.length) {
                    this.ruleForm.icon_url = e[0].url;
                    this.$refs.ruleForm.validateField('icon_url');
                }
            },
            linkUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    self.ruleForm.url = item.new_link_url
                    self.ruleForm.open_type = item.open_type
                    self.ruleForm.params = item.params
                    self.ruleForm.sign = item.key
                });
            }
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getDetail();
            }
        }
    });
</script>
