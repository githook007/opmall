<?php
/**
 * Created by PhpStorm.
 * Date: 2019/4/25
 * Time: 10:02
 * @copyright: ©2019 hook007
 * @link: https://www.opmall.com/
 */
$pluginUrl = Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/img/mall/mch/diy';
?>
<style>
    .diy-notice {
        width: 100%;
        height: 80px;
        padding: 0 24px;
        justify-content: space-between;
    }

    .diy-notice .content {
        flex-grow: 1;
    }

    .diy-notice .content div {
        white-space: nowrap;
        overflow-x: hidden;
    }

    .diy-notice-dialog {
        min-width: 900px;
    }

    .diy-notice-dialog .left .phone {
        width: 750px;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 80px;
        zoom: 0.5;
        font-size: 28px;
        position: relative;
    }

    .diy-notice-dialog .left .notice-dialog {
        width: 100%;
        max-height: 800px;
        background-color: #FFFFFF;
        padding: 24px;
    }
</style>
<template id="diy-notice">
    <div>
        <div class="diy-component-preview">
            <div class="diy-notice" :style="cStyle" flex="dir:left cross:center">
                <img :src="data.icon" style="width: 36px;height: 36px;margin-right: 24px;">
                <div class="content" flex="dir:left box:first">
                    <div style="min-width: 2rem">{{data.name}}</div>
                    <div style="margin: 0 12px;">{{data.content}}</div>
                </div>
                <img src="<?= $pluginUrl ?>/icon-left-arrow.png" style="width: 10px;height: 18px;">
            </div>
        </div>
        <div v-show="!hidden" class="diy-component-edit">
            <div class="app-form-title">
                <div><?= \Yii::t('components/diy', '公告');?></div>
            </div>
            <el-form label-width="100px" @submit.native.prevent style="padding: 20px 0">
                <el-form-item label="<?= \Yii::t('components/diy', '公告名称');?>">
                    <el-input v-model="data.name" :maxlength="8"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '公告内容');?>">
                    <el-input type="textarea" :rows="3" v-model="data.content"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '公告图标');?>">
                    <app-attachment title="<?= \Yii::t('components/diy', '选择图片');?>" :multiple="false" :max="1" type="image"
                                    v-model="data.icon">
                        <el-tooltip class="item" effect="dark"
                                    content="<?= \Yii::t('components/diy', '建议尺寸');?>：36*36"
                                    placement="top">
                            <el-button size="mini"><?= \Yii::t('components/diy', '选择图片');?></el-button>
                        </el-tooltip>
                    </app-attachment>
                    <app-gallery :list="[{url:data.icon}]" :show-delete="false"
                                 @deleted="deletePic('icon')"></app-gallery>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '文字颜色');?>">
                    <el-color-picker size="small" v-model="data.textColor"></el-color-picker>
                    <el-input size="small" style="width: 64px;margin-right: 25px;" v-model="data.textColor"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '背景颜色');?>">
                    <el-color-picker size="small" v-model="data.background"></el-color-picker>
                    <el-input size="small" style="width: 64px;margin-right: 25px;" v-model="data.background"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '弹窗设置');?>">
                    <el-button @click="dialogVisible = !dialogVisible" size="small"><?= \Yii::t('components/diy', '弹窗设置');?></el-button>
                </el-form-item>
            </el-form>
        </div>
        <el-dialog title="<?= \Yii::t('components/diy', '弹窗设置');?>" :visible.sync="dialogVisible" custom-class="diy-notice-dialog">
            <div flex="dir:left box:mean">
                <div class="left" flex="main:center cross:center">
                    <div class="phone" flex="main:center cross:center">
                        <div style="width: 600px">
                            <img :src="data.headerUrl" style="width: 600px;height: 150px;display: block">
                            <div class="notice-dialog">
                                <div style="margin-bottom: 24px;max-height: 600px;overflow-y: auto;">{{data.content}}</div>
                                <div flex="dir:left main:center">
                                    <app-ellipsis :line="1">
                                        <div :style="btnStyle" style="text-align: center;">{{data.btnText}}</div>
                                    </app-ellipsis>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <el-form label-width="100px" @submit.native.prevent>
                        <el-form-item label="<?= \Yii::t('components/diy', '头部图片');?>">
                            <app-attachment title="<?= \Yii::t('components/diy', '选择图片');?>" :multiple="false" :max="1" type="image"
                                            v-model="data.headerUrl">
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('components/diy', '建议尺寸');?>：600*150"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('components/diy', '选择图片');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <app-gallery :url="data.headerUrl" :show-delete="false"
                                         @deleted="deletePic('headerUrl')"></app-gallery>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮颜色');?>">
                            <el-color-picker v-model="data.btnColor"></el-color-picker>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮宽度');?>">
                            <label slot="label"><?= \Yii::t('components/diy', '按钮宽度');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('components/diy', '最大宽度');?>：500px"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model.number="data.btnWidth" type="number" max="500">
                                <template slot="append">
                                    <span>px</span>
                                </template>
                            </el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮高度');?>">
                            <label slot="label"><?= \Yii::t('components/diy', '按钮高度');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('components/diy', '最大高度');?>：80px"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model.number="data.btnHeight" type="number" max="80">
                                <template slot="append">
                                    <span>px</span>
                                </template>
                            </el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮圆角');?>">
                            <label slot="label"><?= \Yii::t('components/diy', '按钮圆角');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('components/diy', '最大圆角');?>：40px"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input v-model.number="data.btnRadius" type="number" max="40">
                                <template slot="append">
                                    <span>px</span>
                                </template>
                            </el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮文本内容');?>">
                            <el-input v-model="data.btnText"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('components/diy', '按钮文本颜色');?>">
                            <el-color-picker v-model="data.btnTextColor"></el-color-picker>
                        </el-form-item>
                    </el-form>
                </div>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button type="primary" size="small" @click="dialogVisible = false"><?= \Yii::t('components/diy', '确定');?></el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    Vue.component('diy-notice', {
        template: '#diy-notice',
        props: {
            value: Object,
            hidden: Boolean
        },
        data() {
            return {
                data: {
                    name: '<?= \Yii::t('components/diy', '公告');?>',
                    content: '',
                    icon: '<?= $pluginUrl?>/icon-notice.png',
                    textColor: '#ffffff',
                    background: '#f67f79',
                    headerUrl: '<?= $pluginUrl?>/icon-notice-title.png',
                    btnColor: '#F9726B',
                    btnWidth: 500,
                    btnHeight: 80,
                    btnRadius: 40,
                    btnText: '<?= \Yii::t('components/diy', '我知道了');?>',
                    btnTextColor: '#ffffff'
                },
                dialogVisible: false
            }
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
            }
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            },
            'data.btnWidth': {
                handler(newVal, oldVal) {
                    if (newVal > 500) {
                        this.data.btnWidth = 500;
                    }
                },
                immediate: true,
            }
        },
        computed: {
            cStyle() {
                if(this.data.background) {
                    return `background: ${this.data.background};`
                        + `color: ${this.data.textColor};`
                }else {
                    return `color: ${this.data.textColor};`
                    }
            },
            btnStyle() {
                return `background: ${this.data.btnColor};`
                    + `color: ${this.data.btnTextColor};`
                    + `border-radius: ${this.data.btnRadius}px;`
                    + `width: ${this.data.btnWidth}px;`
                    + `height: ${this.data.btnHeight}px;`
                    + `line-height: ${this.data.btnHeight}px;`
            }
        },
        methods: {
            coverPic(e) {
                if (e) {
                    this.data.icon = e[0].url
                }
            },
            picHeader(e) {
                if (e) {
                    this.data.headerUrl = e[0].url
                }
            },
            deletePic(param) {
                this.data[param] = '';
            }
        }
    });
</script>
