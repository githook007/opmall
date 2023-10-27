<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: wxf
 */
Yii::$app->loadViewComponent('app-setting-index');
require __DIR__ . '/template/dothing/plugin.php';
?>

<style>
    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
    }

    .reset {
        position: absolute;
        top: 3px;
        left: 90px;
    }
    .mobile-box {
        width: 400px;
        height: 740px;
        padding: 35px 11px;
        background-color: #fff;
        border-radius: 30px;
        background-size: cover;
        position: relative;
        font-size: .85rem;
        float: left;
        margin-right: 1rem;
    }
    .mobile-box .show-box {
        height: 606px;
        width: 375px;
        overflow: auto;
        font-size: 12px;
    }

    .show-box::-webkit-scrollbar { /*滚动条整体样式*/
        width: 1px; /*高宽分别对应横竖滚动条的尺寸*/
    }

    .new-mobile-box {
        overflow-y: auto;
        height: 705px;
        width: 383px;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .new-mobile-box::-webkit-scrollbar { /*滚动条整体样式*/
        width: 1px; /*高宽分别对应横竖滚动条的尺寸*/
    }

    .show-box::-webkit-scrollbar { /*滚动条整体样式*/
        width: 1px; /*高宽分别对应横竖滚动条的尺寸*/
    }

    .order-box {
        height: 80px;
        padding-top: 10px;
        border: 1px solid #eeeeee;
        margin-left: -1px;
        cursor: pointer;
        min-width: 60px;
    }

    .menus-box {
        border: 1px solid #eeeeee;
        background: #F6F8F9;
    }

    .menu-add {
        text-align: right;
        background: #ffffff;
        height: 40px;
        line-height: 40px;
        padding-right: 10px;
    }

    .top-box {
        width: 100%;
        height: 150px;
        background: #F5F7F9;
    }

    .top-box .top-style-1 {
        width: 100%;
        height: 100%;
    }

    .top-box .top-style-1 .head {
        width: 40px;
        height: 40px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        border: 2px solid #ffffff;
        background: #E3E3E3;
        margin-left: 20px;
    }

    .top-box .top-style-2 {
        width: 100%;
        height: 100%;
    }

    .top-box .top-style-2 .head {
        width: 40px;
        height: 40px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        border: 2px solid #ffffff;
        background: #E3E3E3;
    }

    .top-box .top-style-3 {
        width: 100%;
        height: 100%;
    }

    .top-box .top-style-3 .head {
        width: 40px;
        height: 40px;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
        border: 2px solid #ffffff;
        background: #E3E3E3;
    }

    .top-box .top-style-3 .center-box {
        width: 81%;
        height: 120px;
        background: #ffffff;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        padding: 0 20px;
    }

    .account-box {
        width: 100%;
        height: 60px;
        background-color: #f7f7f7;
        padding: 0 8px 8px;
    }

    .account-box > div {
        background-color: #fff;
        border-radius: 4px;
        padding: 8px 0;
        height: 100%;
    }

    .order-bar-box {
        width: 100%;
        background-color: #f7f7f7;
        padding: 0 8px 1px;
        margin-bottom: 8px;
    }

    .order-bar-box > div {
        background-color: #fff;
        border-radius: 8px;
        height: 100%;
    }

    .mobile-menus-box {
        width: 100%;
        background-color: #f7f7f7;
        padding: 0 8px;
    }

    .mobile-menus-box > div {
        background-color: #fff;
        border-radius: 8px;
        height: 100%;
    }

    .mobile-menus-box .mobile-menu-title {
        padding: 10px 16px;
        font-size: 14px;
    }

    .menus-box .menu-item {
        cursor: move;
        background-color: #fff;
        margin: 5px 0;
    }

    .button-item {
        padding: 9px 25px;
        margin-left: 420px;
        margin-top: 10px;
    }

    .head-bar {
        width: 378px;
        height: 64px;
        position: relative;
        background: url('statics/img/mall/home_block/head.png') center no-repeat;
    }

    .head-bar div {
        position: absolute;
        text-align: center;
        width: 378px;
        font-size: 16px;
        font-weight: 600;
        height: 64px;
        line-height: 88px;
    }

    .head-bar img {
        width: 378px;
        height: 64px;
    }

    .form-body {
        width: 0;
        height: 740px;
        flex-grow: 1;
    }

    .form-button {
        margin: 0;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }

    .topic-style {
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }

    .account-item {
        width: 25%;
        border: 1px solid #eeeeee;
    }

    .foot-box {
        position: relative;
        background-color: #f7f7f7;
    }

    .foot-box-line {
        position: absolute;
        height: 20px;
        width: 1px;
        background-color: #666666;
        top: 22px;
        left: 50%;
        margin-left: -1px;
    }

    .foot-box-item {
        height: 64px;
        color: #666666;
        font-size: 13px;
        width: 50%;
    }

    .foot-box-num {
        font-size: 16px;
        margin-bottom: 6px;
    }

    .foot-box-info {
        padding-top: 8px;
        margin-left: 8.5px;
        text-align: center;
    }

    .form-body .title {
        position: relative;
        margin-top: 10px;
        padding: 12px 20px;
        border-top: 1px solid #F3F3F3;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .table-body {
        padding: 16px 28px;
        background-color: #fff;
    }

    .show-box .address-icon {
        background-repeat: no-repeat;
        background-size: 100% 100%;
        height: 24px;
        width: 24px;
    }
    .address-text {

    }
    .mobile-framework {
        position: relative;
        width: 375px;
        height: 100%;
    }

    .mobile-framework-header {
        position: absolute;
        height: 88px;
        width: 375px;
        left: 0;
        top: 0;
        z-index: 1000;
        /*line-height: 60px;*/
        background: #333;
        color: #fff;
        text-align: center;
        font-size: 15px;
        padding-top: 20px;
        cursor: pointer;
        background: url('statics/img/mall/head-user.png') no-repeat;
    }

    .mobile-framework-header .search div {
        position: absolute;
        top: 7px;
        line-height: 1;
        left: 12px;
        font-size: 11px;
        max-width: 110px;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }

    .mobile-framework-body {
        min-height: 645px;
        border: 1px solid #e2e2e2;
        /* background: #f5f7f9; */
    }

    .mobile-framework .diy-preview {
        del-cursor: pointer;
        position: relative;
        zoom: 0.5;
        -moz-transform: scale(0.5);
        -moz-transform-origin: top left;
        font-size: 28px;
    }
    .mobile-framework .diy-component-preview {
        del-cursor: pointer;
        position: relative;
        zoom: 0.5;
        -moz-transform: scale(0.5);
        -moz-transform-origin: top left;
        font-size: 28px;
    }
    @-moz-document url-prefix() {
        .mobile-framework .diy-component-preview {
            cursor: pointer;
            position: relative;
            -moz-transform: scale(0.5);
            -moz-transform-origin: top left;
            font-size: 28px;
            width: 200% !important;
            height: 100%;
            margin-bottom: auto;
        }
    }
    .table-body .el-table__body-wrapper {
        overflow: auto;
    }
</style>
<style>
    .danger {
        background-color: #fce9e6;
        width: 100%;
        border-color: #edd7d4;
        color: #e55640;
        border-radius: 2px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="cardLoading">
        <div slot="header">
            <div>
                <span v-if="id == 0 && !newCreate"><?= \Yii::t('mall/user_center', '用户中心设置');?></span>
                <el-breadcrumb v-else separator="/">
                    <el-breadcrumb-item>
                        <span
                                style="color: #409EFF;cursor: pointer"
                                @click="$navigate({r:'mall/user-center/setting'})"
                        ><?= \Yii::t('mall/user_center', '用户中心设置');?></span>
                    </el-breadcrumb-item>
                    <el-breadcrumb-item>{{id > 0 ? '<?= \Yii::t('mall/user_center', '编辑');?>':'<?= \Yii::t('mall/user_center', '新增');?>'}}</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
        </div>
        <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="150px">
            <div style="display: flex;">
                <div class="mobile-box" v-if="!isNew">
                    <div class="head-bar" flex="main:center cross:center">
                        <div><?= \Yii::t('mall/user_center', '用户中心');?></div>
                    </div>
                    <div class="show-box">
                        <div class="top-box">
                            <div :style="{'background-image':'url('+ruleForm.top_pic_url+')'}"
                                 class="topic-style top-style-1"
                                 flex="dir:left main:justify cross:center" v-if="ruleForm.top_style == 1">
                                <div flex="cross:center">
                                    <div class="head"></div>
                                    <span style="margin-left: 10px;"
                                          :style="{color: ruleForm.user_name_color}"><?= \Yii::t('mall/user_center', '用户昵称');?></span>
                                </div>
                                <div flex="dir:left cross:center" class="address" v-if="ruleForm.address.status == 1"
                                     :style="{background: ruleForm.address.bg_color}"
                                     style="padding: 4.5px 5px;border-radius:25px 0 0 25px;">
                                    <div class="address-icon" :style="{backgroundImage: `url(${ruleForm.address.pic_url})`}"></div>
                                    <div class="address-text" style="margin:0 6px"
                                         :style="{color: ruleForm.address.text_color}"><?= \Yii::t('mall/user_center', '收货地址');?></div>
                                </div>
                            </div>

                            <div :style="{'background-image':'url('+ruleForm.top_pic_url+')'}"
                                 class="topic-style top-style-2"
                                 flex="main:center cross:center dir:top"
                                 v-if="ruleForm.top_style == 2">
                                <div class="head"></div>
                                <span :style="{color: ruleForm.user_name_color}"><?= \Yii::t('mall/user_center', '用户昵称');?></span>
                            </div>

                            <div :style="{'background-image':'url('+ruleForm.top_pic_url+')'}"
                                 class="topic-style top-style-3"
                                 flex="main:center cross:center"
                                 v-if="ruleForm.top_style == 3">
                                <div class="center-box"
                                     :style="{'background-image': 'url('+ruleForm.style_bg_pic_url+')'}"
                                     style="background-size: 100%;background-repeat:no-repeat;background-position: center"
                                     flex="dir:left cross:center">
                                    <div class="head"></div>
                                    <span style="margin-left: 10px;"
                                          :style="{color: ruleForm.user_name_color}"><?= \Yii::t('mall/user_center', '用户昵称');?></span>
                                    <div style="margin-left: auto" v-if="ruleForm.address.status == 1">
                                        <div flex="dir:top cross:center" class="address">
                                            <div class="address-icon" :style="{backgroundImage: `url(${ruleForm.address.pic_url})`}"></div>
                                            <div class="address-text" style="margin-top: 3px"
                                                 :style="{color: ruleForm.address.text_color}"><?= \Yii::t('mall/user_center', '收货地址');?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="ruleForm.is_foot_bar_status == 1" class="foot-box" flex="main:center cross:center">
                            <div flex="main:center" class="foot-box-item" v-for="item in ruleForm.foot_bar">
                                <app-image style="margin-top: 33px;" width="20px" height="20px" mode="aspectFill"
                                           :src="item.icon_url"></app-image>
                                <div class="foot-box-info">
                                    <div class="foot-box-num">0</div>
                                    <div>{{item.name}}</div>
                                </div>
                            </div>
                            <div class="foot-box-line"></div>
                        </div>
                        <div v-if="ruleForm.account_bar.status == 1" class="account-box">
                            <div flex="dir:left box:mean">
                                <!-- 积分 -->
                                <div style="padding: 5px 0;border-right: 1px solid #e2e2e2;"
                                     flex="main:center cross:center dir:top">
                                    <div style="color: #ffbb43;">0</div>
                                    <app-ellipsis style="margin-top: 5px;" :line="1">
                                        <app-image style="display: inline-block"
                                                   width="10px"
                                                   height="10px"
                                                   mode="aspectFill"
                                                   :src="ruleForm.account_bar && ruleForm.account_bar.integral ? ruleForm.account_bar.integral.icon : ''">
                                        </app-image>
                                        {{ruleForm.account_bar && ruleForm.account_bar.integral ?
                                        ruleForm.account_bar.integral.text : '<?= \Yii::t('mall/user_center', '积分');?>'}}
                                    </app-ellipsis>
                                </div>
                                <!-- 余额 -->
                                <div style="padding: 5px 0;border-right: 1px solid #e2e2e2;"
                                     flex="main:center cross:center dir:top">
                                    <div style="color: #ffbb43;">0</div>
                                    <app-ellipsis style="margin-top: 5px;" :line="1">
                                        <app-image style="display: inline-block"
                                                   width="10px"
                                                   height="10px"
                                                   mode="aspectFill"
                                                   :src="ruleForm.account_bar && ruleForm.account_bar.balance ? ruleForm.account_bar.balance.icon : ''">
                                        </app-image>
                                        {{ruleForm.account_bar && ruleForm.account_bar.balance ?
                                        ruleForm.account_bar.balance.text : '<?= \Yii::t('mall/user_center', '余额');?>'}}
                                    </app-ellipsis>
                                </div>
                                <!-- 优惠券 -->
                                <div style="padding: 5px 0;border-right: 1px solid #e2e2e2;"
                                     flex="main:center cross:center dir:top">
                                    <div style="color: #ffbb43;">0</div>
                                    <app-ellipsis style="margin-top: 5px;" :line="1">
                                        <app-image style="display: inline-block"
                                                   width="10px"
                                                   height="10px"
                                                   mode="aspectFill"
                                                   :src="ruleForm.account_bar && ruleForm.account_bar.coupon ? ruleForm.account_bar.coupon.icon : ''">
                                        </app-image>
                                        {{ruleForm.account_bar && ruleForm.account_bar.coupon ?
                                        ruleForm.account_bar.coupon.text : '<?= \Yii::t('mall/user_center', '优惠券');?>'}}
                                    </app-ellipsis>
                                </div>
                                <!-- 卡券 -->
                                <div style="padding: 5px 0;" flex="main:center cross:center dir:top">
                                    <div style="color: #ffbb43;">0</div>
                                    <app-ellipsis style="margin-top: 5px;" :line="1">
                                        <app-image style="display: inline-block"
                                                   width="10px"
                                                   height="10px"
                                                   mode="aspectFill"
                                                   :src="ruleForm.account_bar && ruleForm.account_bar.card ? ruleForm.account_bar.card.icon : ''">
                                        </app-image>
                                        {{ruleForm.account_bar && ruleForm.account_bar.card ?
                                        ruleForm.account_bar.card.text : '<?= \Yii::t('mall/user_center', '卡券');?>'}}
                                    </app-ellipsis>
                                </div>
                            </div>
                        </div>

                        <div v-if="ruleForm.is_order_bar_status == 1" class="order-bar-box">
                            <div>
                                <div style="padding: 10px;" flex="main:justify cross:center">
                                    <div><?= \Yii::t('mall/user_center', '我的订单');?></div>
                                    <div style="color: #999999"><?= \Yii::t('mall/user_center', '查看更多');?>></div>
                                </div>
                                <div flex="dir:left box:mean"
                                     style="margin: 10px 0;padding-bottom: 10px">
                                    <div v-for="item in ruleForm.order_bar" flex="main:center cross:center dir:top">
                                        <app-image width="30px"
                                                   height="30px"
                                                   mode="aspectFill"
                                                   :src="item.icon_url">
                                        </app-image>
                                        <app-ellipsis style="margin-top: 5px;" :line="1">{{item.name}}</app-ellipsis>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="ruleForm.is_menu_status == 1 && ruleForm.menu_style == 1"
                             class="mobile-menus-box">
                            <div flex="dir:top">
                                <div class="mobile-menu-title">{{ruleForm.menu_title}}</div>
                                <div style="padding: 8px 16px" v-for="item in ruleForm.menus"
                                     flex="dir:left cross:center">
                                    <app-image width="25px"
                                               height="25px"
                                               mode="aspectFill"
                                               :src="item.icon_url">
                                    </app-image>
                                    <app-ellipsis style="margin-left: 10px;" :line="1">{{item.name}}</app-ellipsis>
                                </div>
                            </div>
                        </div>

                        <div v-if="ruleForm.is_menu_status == 1 && ruleForm.menu_style == 2"
                             class="mobile-menus-box">
                            <div class="mobile-menu-title">{{ruleForm.menu_title}}</div>
                            <div flex="wrap:wrap">
                                <div v-for="item in ruleForm.menus"
                                     style="width: 25%;margin-bottom: 18px"
                                     flex="cross:center main:center dir:top">
                                    <app-image width="25px"
                                               height="25px"
                                               style="margin-bottom: 8px"
                                               mode="aspectFill"
                                               :src="item.icon_url">
                                    </app-image>
                                    <app-ellipsis :line="1">{{item.name}}</app-ellipsis>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="new-mobile-box">
                    <div class="mobile-framework" style="height: 705px;">
                        <div class="mobile-framework-header"
                             flex="dir:left main:center"
                             style="color: #242424;">
                        </div>
                        <div id="mobile-framework-body" class="mobile-framework-body"
                             :style="'background-color:'+ bg.backgroundColor+';background-image:url('+bg.backgroundPicUrl+');background-size:'+bg.backgroundWidth+'% '+bg.backgroundHeight+'%;background-repeat:'+bg.repeatText+';background-position:'+bg.positionText">
                            <div v-for="(component, index) in components" :key="index">
                                <p-user :bg="bg" v-if="component.id == 'user'" v-model="component.data"></p-user>
                                <p-foot v-if="component.id == 'foot'" v-model="component.data"></p-foot>
                                <p-svip v-if="component.id == 'svip'" v-model="component.data"></p-svip>
                                <p-order v-if="component.id == 'order'" v-model="component.data"></p-order>
                                <p-member v-if="component.id == 'member'" v-model="component.data"></p-member>
                                <p-account v-if="component.id == 'account'" v-model="component.data"></p-account>
                                <p-menu v-if="component.id == 'menu'" v-model="component.data"></p-menu>
                                <diy-banner v-if="component.id == 'banner'" v-model="component.data" :hidden="true"></diy-banner>
                                <diy-customer v-if="component.id == 'customer'" v-model="component.data" :hidden="true"></diy-customer>
                                <diy-empty v-if="component.id == 'empty'" v-model="component.data" :hidden="true"></diy-empty>
                                <diy-link v-if="component.id == 'link'" v-model="component.data" :hidden="true"></diy-link>
                                <diy-notice v-if="component.id == 'notice'" v-model="component.data" :hidden="true"></diy-notice>
                                <diy-rubik v-if="component.id == 'rubik'" v-model="component.data" :hidden="true"></diy-rubik>
                                <diy-goods v-if="component.id == 'goods'" v-model="component.data" :hidden="true"></diy-goods>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="id == 0 && !newCreate" class="form-body">
                    <div class="title" flex="main:justify cross:center">
                        <span><?= \Yii::t('mall/user_center', '用户中心列表');?></span>
                        <el-button type="primary" size="small" @click="$navigate({r: 'mall/user-center/detail'})"><?= \Yii::t('mall/user_center', '新增');?></el-button>
                    </div>
                    <div class="table-body">
                        <el-tabs v-model="activeName" @tab-click="getList">
                            <el-tab-pane label="<?= \Yii::t('mall/user_center', '全部');?>" name="all"></el-tab-pane>
                            <el-tab-pane label="<?= \Yii::t('mall/user_center', '回收站');?>" name="recycle"></el-tab-pane>
                        </el-tabs>
                        <el-table class="table-info" :data="list" border max-height="500" v-loading="listLoading">
                            <el-table-column prop="id" label="ID" width="100"></el-table-column>
                            <el-table-column prop="name" label="<?= \Yii::t('mall/user_center', '名称');?>" width="350">
                            </el-table-column>
                            <el-table-column label="<?= \Yii::t('mall/user_center', '用户端');?>">
                                <template slot-scope="scope">
                                    <img style="margin: 0 3px;width: 24px;height: 24px;"
                                         v-for="item in scope.row.platform" :key="item.icon" :src="item.icon" alt="">
                                </template>
                            </el-table-column>
                            <el-table-column label="<?= \Yii::t('mall/user_center', '操作');?>" fixed="right" width="240">
                                <template slot-scope="scope">
                                    <el-tooltip v-if="activeName == 'all'" class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '编辑');?>"
                                                placement="top">
                                        <el-button circle type="text" size="mini" @click="$navigate({r: 'mall/user-center/detail', id:scope.row.id})">
                                            <img src="statics/img/mall/edit.png" alt="">
                                        </el-button>
                                    </el-tooltip>
                                    <el-tooltip v-if="activeName == 'all'" class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '设置用户端');?>"
                                                placement="top">
                                        <el-button circle type="text" size="mini" @click="settingIndex(scope.row)">
                                            <img src="statics/img/plugins/setting.png" alt="">
                                        </el-button>
                                    </el-tooltip>
                                    <el-tooltip v-if="activeName == 'all'" class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '移入回收站');?>"
                                                placement="top">
                                        <el-button circle type="text" size="mini"
                                                   @click="toOperate(scope.row, 'recycle')">
                                            <img src="statics/img/mall/del.png" alt="">
                                        </el-button>
                                    </el-tooltip>
                                    <el-tooltip v-if="activeName == 'recycle'" class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '恢复');?>"
                                                placement="top">
                                        <el-button circle type="text" size="mini"
                                                   @click="toOperate(scope.row, 'resume')">
                                            <img src="statics/img/mall/order/renew.png" alt="">
                                        </el-button>
                                    </el-tooltip>
                                    <el-tooltip v-if="activeName == 'recycle'" class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '删除');?>"
                                                placement="top">
                                        <el-button circle type="text" size="mini"
                                                   @click="toOperate(scope.row, 'delete')">
                                            <img src="statics/img/mall/del.png" alt="">
                                        </el-button>
                                    </el-tooltip>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div flex="main:right cross:center" style="margin-top: 20px;">
                            <div v-if="pagination && pagination.page_count > 0">
                                <el-pagination
                                        @current-change="changePage"
                                        background
                                        :current-page="pagination.current_page"
                                        layout="prev, pager, next, jumper"
                                        :page-count="pagination.page_count">
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </el-form>

        <app-setting-index title="<?= \Yii::t('mall/user_center', '设置用户端');?>" :list="platform" :show="indexDialog" :loading="platformLoading" @cancel="cancel"
                           @click="submitPlatform"></app-setting-index>
    </el-card>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/unpkg/sortablejs@1.8.4/Sortable.min.js"></script>
<!-- CDNJS :: Vue.Draggable (https://cdnjs.com/) -->
<script src="<?= Yii::$app->request->baseUrl ?>/statics/unpkg/vuedraggable@2.18.1/dist/vuedraggable.umd.min.js"></script>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                isNew: false,
                components: [],
                bg: {},
                id: 0,
                activeName: 'all',
                changeId: 0,
                indexDialog: false,
                listLoading: false,
                platformLoading: false,
                newCreate: false,
                platform: '',
                name: '',
                list: [],
                pagination: [],
                page: 1,
                mobile_bg: _baseUrl + '/statics/img/mall/mobile-background.png',
                ruleForm: {
                    top_pic_url: '',
                    top_style: '1',
                    is_order_bar_status: '1', // 订单栏显示
                    is_foot_bar_status: '1', // 订单栏显示
                    is_menu_status: '1',
                    menu_title: '<?= \Yii::t('mall/user_center', '我的服务');?>',
                    menu_style: '1',
                    menus: [],
                    address: {
                        status: 1,
                        bg_color: '#ff4544',
                        text_color: '#FFFFFF'
                    },
                    order_bar: [
                        {
                            id: 1,
                            name: '<?= \Yii::t('mall/user_center', '待付款');?>',
                            icon_url: '',
                        },
                        {
                            id: 2,
                            name: '<?= \Yii::t('mall/user_center', '待发货');?>',
                            icon_url: '',
                        },
                        {
                            id: 3,
                            name: '<?= \Yii::t('mall/user_center', '待收货');?>',
                            icon_url: '',
                        },
                        {
                            id: 4,
                            name: '<?= \Yii::t('mall/user_center', '已完成');?>',
                            icon_url: '',
                        },
                        {
                            id: 5,
                            name: '<?= \Yii::t('mall/user_center', '售后');?>',
                            icon_url: '',
                        },
                    ],
                    foot_bar: [
                        {
                            id: 1,
                            name: '<?= \Yii::t('mall/user_center', '我的收藏');?>',
                            icon_url: '',
                        },
                        {
                            id: 2,
                            name: '<?= \Yii::t('mall/user_center', '我的足迹');?>',
                            icon_url: '',
                        }
                    ],
                    account: [
                        {
                            id: 2,
                            name: '<?= \Yii::t('mall/user_center', '积分');?>',
                            icon_url: '',
                        },
                        {
                            id: 3,
                            name: '<?= \Yii::t('mall/user_center', '余额');?>',
                            icon_url: '',
                        },
                    ],
                    account_bar: {
                        status: '1',
                        integral: {
                            status: '1',
                            text: '<?= \Yii::t('mall/user_center', '积分');?>',
                            icon: '',
                        },
                        balance: {
                            status: '1',
                            text: '<?= \Yii::t('mall/user_center', '余额');?>',
                            icon: '',
                        },
                        coupon: {
                            status: '1',
                            text: '<?= \Yii::t('mall/user_center', '优惠券');?>',
                            icon: '',
                        },
                        card: {
                            status: '1',
                            text: '<?= \Yii::t('mall/user_center', '卡券');?>',
                            icon: '',
                        },
                    },
                },
                rules: {
                    top_pic_url: [
                        {required: true, message: '<?= \Yii::t('mall/user_center', '请选择顶部背景图片');?>', trigger: 'change'},
                    ],
                    member_pic_url: [
                        {required: true, message: '<?= \Yii::t('mall/user_center', '请选择会员图标');?>', trigger: 'change'},
                    ],
                    member_bg_pic_url: [
                        {required: true, message: '<?= \Yii::t('mall/user_center', '请选择普通会员背景图');?>', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
                dialogForm: {},
                dialogFormVisible: false,
                dialogFormType: '',
                dialogFormIndex: '',
            };
        },
        methods: {
            toOperate(item, operate) {
                let text = '';
                if (operate == 'recycle') {
                    text = "<?= \Yii::t('mall/user_center', '是否放入回收站(可在回收站中恢复)');?>";
                }
                if (operate == 'resume') {
                    text = "<?= \Yii::t('mall/user_center', '是否移出回收站');?>";
                }
                if (operate == 'delete') {
                    text = "<?= \Yii::t('mall/user_center', '删除该条数据，是否继续');?>";
                }
                this.$confirm(text, '<?= \Yii::t('mall/user_center', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/user_center', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/user_center', '取消');?>',
                    type: 'warning',
                    center: operate != 'delete'
                }).then(() => {
                    request({
                        params: {
                            r: 'mall/user-center/operate',
                            id: item.id,
                            type: operate
                        },
                        method: 'get',
                    }).then(e => {
                        if (e.data.code == 0) {
                            this.$message.success(e.data.msg);
                            this.changeId = 0;
                            this.getList();
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    })
                })
            },
            cancel() {
                this.platformLoading = false;
                this.indexDialog = false;
            },
            submitPlatform(e) {
                this.platformLoading = true;
                request({
                    params: {
                        r: 'mall/user-center/operate',
                        id: this.changeId,
                        type: 'choose',
                        platform: e.length > 0 ? e : []
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code == 0) {
                        this.$message.success(e.data.msg);
                        this.platformLoading = false;
                        this.indexDialog = false;
                        this.changeId = 0;
                        this.platform = '';
                        this.getList();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                })
            },
            settingIndex(row) {
                this.changeId = row.id;
                this.platform = row.platform.length > 0 ? JSON.stringify(row.platform) : ''
                this.indexDialog = true;
            },
            changePage(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            getList() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/user-center/list',
                        page: this.page,
                        type: this.activeName == 'recycle' ? 'recycle' : ''
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.list = e.data.data.list;
                        self.pagination = e.data.data.pagination;
                        let id = e.data.data.list.length > 0 ? e.data.data.list[0].id : 0
                        this.getDetail(id);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            getDetail(id) {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/user-center/setting',
                        id: id
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        if (e.data.data.detail) {
                            self.ruleForm = e.data.data.detail;
                            self.name = e.data.data.detail.name;
                            self.isNew = e.data.data.detail.data ? true : false;
                            self.components = e.data.data.detail.data ? e.data.data.detail.data : [];
                            if(self.isNew) {
                                for(let item of self.components) {
                                    if(item.id == 'background') {
                                        self.bg = item.data;
                                    }
                                }
                            }else {
                                if(!self.ruleForm.foot_bar) {
                                    self.ruleForm.foot_bar = [
                                        {
                                            id: 1,
                                            name: '<?= \Yii::t('mall/user_center', '我的收藏');?>',
                                            icon_url: '',
                                        },
                                        {
                                            id: 2,
                                            name: '<?= \Yii::t('mall/user_center', '我的足迹');?>',
                                            icon_url: '',
                                        }
                                    ]
                                }
                            }
                        }
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
