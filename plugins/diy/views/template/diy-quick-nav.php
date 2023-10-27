<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/5/6
 * Time: 16:03
 */
?>
<style>
    .diy-quick-nav .pic-select {
        width: 72px;
        height: 72px;
        color: #00a0e9;
        border: 1px solid #ccc;
        line-height: normal;
        text-align: center;
        cursor: pointer;
        font-size: 12px;
    }

    .diy-quick-nav .pic-preview {
        width: 72px;
        height: 72px;
        border: 1px solid #ccc;
        cursor: pointer;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
    }

    .diy-quick-nav .edit-item {
        border: 1px solid #e2e2e2;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
<template id="diy-quick-nav">
    <div class="diy-quick-nav">
        <div class="diy-component-preview">
            <div style="padding: 20px 0;text-align: center;">
                <div><?= \Yii::t('plugins/diy', '快捷导航设置');?></div>
                <div style="font-size: 22px;color: #909399"><?= \Yii::t('plugins/diy', '本条内容不占高度');?></div>
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form @submit.native.prevent label-width="100px">
                <el-form-item label="<?= \Yii::t('plugins/diy', '快捷导航开关');?>">
                    <el-switch v-model="data.navSwitch" :inactive-value="0" :active-value="1"></el-switch>
                </el-form-item>
                <el-form-item v-if="data.navSwitch == 1" label="<?= \Yii::t('plugins/diy', '使用商城配置');?>">
                    <el-switch v-model="data.useMallConfig"></el-switch>
                </el-form-item>

                <template v-if="!data.useMallConfig && data.navSwitch == 1">
                    <el-form-item label="<?= \Yii::t('plugins/diy', '获取商城配置');?>">
                        <el-button size="small" @click="getMallNav"><?= \Yii::t('plugins/diy', '获取');?></el-button>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '导航样式');?>">
                        <app-radio v-model="data.navStyle" :label="1"><?= \Yii::t('plugins/diy', '样式1点击收起');?></app-radio>
                        <app-radio v-model="data.navStyle" :label="2"><?= \Yii::t('plugins/diy', '样式2全部展示');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '收起图标');?>">
                        <app-image-upload width="100" height="100" v-model="data.closedPicUrl" tag="quick_navigation"></app-image-upload>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '展开图标');?>">
                        <app-image-upload width="100" height="100" v-model="data.openedPicUrl" tag="quick_navigation"></app-image-upload>
                    </el-form-item>

                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '返回首页');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.home.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.home.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.home.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" v-model="data.home.picUrl" tag="quick_navigation"></app-image-upload>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '小程序客服');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.customerService.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.customerService.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.customerService.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" tag="quick_navigation"
                                              v-model="data.customerService.picUrl"></app-image-upload>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '一键拨号');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.tel.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.tel.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.tel.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" v-model="data.tel.picUrl" tag="quick_navigation"></app-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.tel.opened" label="<?= \Yii::t('plugins/diy', '电话号码');?>">
                            <el-input v-model="data.tel.number"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '网页链接');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.web.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.web.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.web.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" v-model="data.web.picUrl" tag="quick_navigation"></app-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.web.opened" label="<?= \Yii::t('plugins/diy', '网址');?>">
                            <el-input v-model="data.web.url"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '跳转小程序');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.mApp.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.mApp.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" v-model="data.mApp.picUrl" tag="quick_navigation"></app-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="appId">
                            <el-input v-model="data.mApp.appId"></el-input>
                        </el-form-item>
                        <el-form-item v-if="data.mApp.opened" label="<?= \Yii::t('plugins/diy', '页面路径');?>">
                            <el-input v-model="data.mApp.page"></el-input>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '地图导航');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.mapNav.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.mapNav.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" v-model="data.mapNav.picUrl" tag="quick_navigation"></app-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="<?= \Yii::t('plugins/diy', '详细地址');?>">
                            <el-input v-model="data.mapNav.address"></el-input>
                        </el-form-item>
                        <el-form-item v-if="data.mapNav.opened" label="<?= \Yii::t('plugins/diy', '经纬度');?>">
                            <app-map @map-submit="mapEvent">
                                <el-input v-model="data.mapNav.location" placeholder="<?= \Yii::t('plugins/diy', '点击进入地图选择');?>" readonly></el-input>
                            </app-map>
                        </el-form-item>
                    </div>
                    <div class="edit-item">
                        <div style="margin-bottom: 10px;"><?= \Yii::t('plugins/diy', '自定义按钮');?></div>
                        <el-form-item label="<?= \Yii::t('plugins/diy', '是否开启');?>">
                            <el-switch v-model="data.customize.opened"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.navStyle == 1" label="<?= \Yii::t('plugins/diy', '是否默认展示');?>">
                            <el-switch v-model="data.customize.is_show"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.customize.opened" label="<?= \Yii::t('plugins/diy', '图标');?>">
                            <app-image-upload width="100" height="100" tag="quick_navigation"
                                              v-model="data.customize.picUrl"></app-image-upload>
                        </el-form-item>
                        <el-form-item v-if="data.customize.opened" label="<?= \Yii::t('plugins/diy', '跳转链接');?>">
                            <el-input :disabled="true" size="small"
                                      v-model="data.customize.link_url" autocomplete="off">
                                <app-pick-link slot="append" @selected="selectQuickCustomize">
                                    <el-button size="mini"><?= \Yii::t('plugins/diy', '选择链接');?></el-button>
                                </app-pick-link>
                            </el-input>
                        </el-form-item>
                    </div>
                </template>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-quick-nav', {
        template: '#diy-quick-nav',
        props: {
            value: Object,
        },
        data() {
            return {
                data: {
                    navSwitch: 0,
                    useMallConfig: true,
                    navStyle: 1,
                    closedPicUrl: '',
                    openedPicUrl: '',
                    num: 0,
                    home: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                    },
                    customerService: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                    },
                    tel: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                        number: '',
                    },
                    web: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                        url: '',
                    },
                    mApp: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                        appId: '',
                        page: '',
                    },
                    mapNav: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                        address: '',
                        location: '',
                    },
                    customize: {
                        opened: false,
                        is_show: false,
                        picUrl: '',
                        open_type: '',
                        params: '',
                        link_url: '',
                        key: '',
                    }
                }
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = Object.assign({}, this.data, this.value);
                //this.data = JSON.parse(JSON.stringify(this.value));
            }
        },
        computed: {},
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        methods: {
            getMallNav() {
                console.log(this.value);
                request({
                    params: {
                        r: 'plugin/diy/mall/tpl-func/quick-nav-get-mall-config'
                    }
                }).then(response => {
                    if (response.data.code === 0) {
                        let data = response.data.data;
                        Object.assign(this.value, data);
                    }
                    this.$message.success(response.data.msg);
                });
            },
            selectQuickCustomize(e) {
                e.map(item => {
                    this.data.customize.link_url = item.new_link_url;
                    this.data.customize.open_type = item.open_type;
                    this.data.customize.params = item.params;
                    this.data.customize.key = item.key ? item.key : '';
                });
            },
            mapEvent(e) {
                this.data.mapNav.location = e.lat + ',' + e.long;
                this.data.mapNav.address = e.address;
            },
        }
    });
</script>
