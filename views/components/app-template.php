<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/3
 * Time: 9:30
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
?>
<style>
    .app-template .dialog {
        height: 500px;
        overflow: auto;
    }

    .app-template .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
        margin-bottom: 10px;
    }

    .app-template .export-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 2;
    }

    .app-template .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 10px;
    }

    .app-template .form-button {
        margin: 0;
    }

    .app-template .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .app-template .button-item {
        padding: 9px 25px;
    }
</style>
<template id="app-template">
    <div class="app-template" v-cloak>
        <el-card style="border:0" v-loading="cardLoading" shadow="never"
                 body-style="background-color: #f3f3f3;padding: 0 0;position: relative;">
            <el-form size="small" class="export-btn" :inline="true">
                <el-form-item>
                    <template v-if="showOneKey">
                        <el-button type="primary" size="small"
                                   @click="getTemplate"><?= \Yii::t('components/other', '一键添加');?>{{labelTitle}}
                        </el-button>
                    </template>
                </el-form-item>
            </el-form>
            <el-form @submit.native.prevent size="small" label-width="300px">
                <el-tabs v-model="activeName" @tab-click="handleClick">
                    <el-tab-pane v-for="(item, index) in list" v-if="item.list.length > 0" :key="index"
                                 :label="item.name" :name="item.key">
                        <el-row class="form-body">
                            <div style="margin: 0 20px 20px;background-color: #F4F4F5;padding: 10px 15px;color: #909399;display: inline-block;font-size: 15px">
                                <?= \Yii::t('components/other', '获取前请先确认您已获得');?>{{labelTitle}}<?= \Yii::t('components/other', '的使用权限');?>{{labelTitle}}<?= \Yii::t('components/other', '中没有任何数据');?>{{labelTitle}}，<?= \Yii::t('components/other', '否则会影响');?>{{labelTitle}}<?= \Yii::t('components/other', '正常使用');?>
                            </div>
                            <slot name="after_remind"></slot>
                            <el-col :span="24">
                                <template v-if="item.list.length">
                                    <el-form-item v-for="(tpl, index) in item.list" :key="index" :label="tpl.name">
                                        <el-input style="width: 30%" v-model.trim="tpl[tpl.tpl_name]"></el-input>
                                        <el-button size="small" @click="openDialog(tpl.img_url)"><?= \Yii::t('components/other', '查看');?>{{labelTitle}}<?= \Yii::t('components/other', '示例');?>
                                        </el-button>
                                    </el-form-item>
                                </template>
                            </el-col>
                        </el-row>
                    </el-tab-pane>
                </el-tabs>
                <el-button class="button-item" :loading="btnLoading" type="primary" @click="store" size="small"><?= \Yii::t('components/other', '保存');?>
                </el-button>
                <el-button class="button-item" :loading="btnLoading" type="primary" @click="testQrcode" size="small">
                    <?= \Yii::t('components/other', '生成测试二维码');?>
                </el-button>
            </el-form>
            <el-dialog :title="labelTitle+ `<?= \Yii::t('components/other', '格式');?>`" :visible.sync="dialogVisible">
                <div class="dialog">
                    <img style="width: 100%;" :src="dialogImgUrl">
                </div>
            </el-dialog>
            <el-dialog :title="labelTitle +`<?= \Yii::t('components/other', '测试二维码');?>`" :visible.sync="templateVisible">
                <div style="text-align: center">
                    <div style="margin-bottom: 10px"><?= \Yii::t('components/other', '请先保存好');?>{{labelTitle}}<?= \Yii::t('components/other', '之后在进行测试');?></div>
                    <div v-if="qrcode">
                        <img :src="qrcode" alt="" width="430px" height="430px">
                    </div>
                    <div v-loading="!qrcode"></div>
                </div>
            </el-dialog>
        </el-card>
    </div>
</template>
<script>
    Vue.component('app-template', {
        template: '#app-template',
        props: {
            url: String, // 获取信息的地址
            submitUrl: String, // 表单提交的地址
            addUrl: String, // 一键添加的地址
            oneKey: {  //是否显示一键获取模板
                type: Boolean,
                default: true
            },
            sign: String,
        },
        computed: {
            labelTitle: function () {
                let arr = ['wxapp'];
                if (arr.indexOf(this.sign) === -1 && arr.indexOf(this.activeName) === -1) {
                    return '<?= \Yii::t('components/other', '模板消息');?>';
                } else {
                    return '<?= \Yii::t('components/other', '订阅消息');?>';
                }
            }
        },
        data() {
            return {
                list: [],
                activeName: 'store',
                btnLoading: false,
                cardLoading: false,
                dialogVisible: false,
                dialogImgUrl: '',

                platform: '',

                showOneKey:'',
                templateVisible: false,
                qrcode: '',
            };
        },
        methods: {
            store() {
                let self = this;
                self.btnLoading = true;
                request({
                    params: {
                        r: this.submitUrl
                    },
                    method: 'post',
                    data: {
                        list: self.list,
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

            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: this.url,
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.list = e.data.data.list;
                        if (self.list.length > 0) {
                            self.activeName = self.list[0].key
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            handleClick(tab, event) {
                if (['wxapp','bdapp','aliapp','ttapp', 'wechat'].indexOf(tab.name) >= 0) {
                    if (['wxapp','bdapp', 'wechat'].indexOf(tab.name) >= 0) {
                        this.showOneKey = true;
                    } else {
                        this.showOneKey = false;
                    }
                    this.platform = tab.name;
                } else {
                    this.showOneKey = this.oneKey;
                }
            },
            openDialog(imgUrl) {
                this.dialogVisible = true;
                this.dialogImgUrl = imgUrl;
            },
            getTemplate() {
                request({
                    params: {
                        r: this.addUrl,
                        add: true,
                        platform:this.platform,
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            getPlatform() {
                let vars = getQuery('r').split("/");
                let p = vars[1];
                this.showOneKey = this.oneKey;
                if (['wxapp','bdapp','aliapp','ttapp'].indexOf(p) >= 0) {
                    this.platform = p;
                } else {
                    this.platform = 'wxapp';
                }
            },
            testQrcode() {
                this.templateVisible = true;
                if (this.qrcode) {
                    return ;
                }
                this.$request({
                    params: {
                        r: 'mall/template-msg/qrcode',
                        platform: this.platform,
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
        mounted: function () {
            this.getPlatform();
            this.getDetail();
        },
    });
</script>
