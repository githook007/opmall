<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/5/8
 * Time: 11:02
 */
Yii::$app->loadViewComponent('diy/diy-bg');
?>
<style>
    .diy-miaosha .diy-component-edit .goods-list {
        line-height: normal;
        flex-wrap: wrap;
    }

    .diy-miaosha .diy-component-edit .goods-item,
    .diy-miaosha .diy-component-edit .goods-add {
        width: 50px;
        height: 50px;
        border: 1px solid #e2e2e2;
        background-position: center;
        background-size: cover;
        margin-right: 15px;
        margin-bottom: 15px;
        position: relative;
    }

    .diy-miaosha .diy-component-edit .goods-add {
        cursor: pointer;
    }

    .diy-miaosha .diy-component-edit .goods-delete {
        position: absolute;
        top: -11px;
        right: -11px;
        width: 25px;
        height: 25px;
        line-height: 25px;
        padding: 0 0;
        visibility: hidden;
    }

    .diy-miaosha .diy-component-edit .goods-item:hover .goods-delete {
        visibility: visible;
    }

    /*-------------------- 预览部分 --------------------*/
    .diy-miaosha .diy-component-preview .goods-list {
        flex-wrap: wrap;
        /* background-color: #fff; */
        padding: 10px;
    }

    .diy-miaosha .diy-component-preview .goods-item {
        position: relative;
    }

    .diy-miaosha .diy-component-preview .goods-tag {
        position: absolute;
        top: 0;
        left: 0;
        width: 64px;
        height: 64px;
        background-position: center;
        background-size: cover;
    }

    .diy-miaosha .diy-component-preview .goods-pic {
        width: 100%;
        height: 706px;
        background-color: #e2e2e2;
        background-position: center;
        background-size: cover;
        position: relative;
        background-repeat: no-repeat;
    }

    .diy-miaosha .diy-component-preview .goods-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }


    .diy-miaosha .diy-component-preview .goods-name.goods-two {
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        white-space: normal !important;
    }

    .diy-miaosha .diy-component-preview .goods-cover-3-2 .goods-pic {
        height: 470px;
    }

    .diy-miaosha .diy-component-preview .goods-list-2 .goods-pic {
        height: 343px;
    }

    .diy-miaosha .diy-component-preview .goods-list--1 .goods-pic {
        width: 200px;
        height: 200px;
        margin-right: 20px;
    }

    .diy-miaosha .diy-component-preview .goods-list--1 .goods-item {
        margin-bottom: 20px;
    }

    .diy-miaosha .diy-component-preview .goods-list--1 .goods-item:last-child {
        margin-bottom: 0;
    }

    .diy-miaosha .diy-component-preview .goods-name-static {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        word-break: break-all;
        margin-bottom: 12px;
    }

    .diy-miaosha .diy-component-preview .goods-price {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ff4544;
        line-height: 48px;
    }

    .diy-miaosha .diy-component-preview .goods-miaosha-timer {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 80px;
        line-height: 80px;
        padding: 0 20px;
        background: -webkit-linear-gradient(left, #f44, #ff8b8b);
        background: -webkit-gradient(linear, left top, right top, from(#f44), to(#ff8b8b));
        background: -moz-linear-gradient(left, #f44, #ff8b8b);
        background: linear-gradient(90deg, #f44, #ff8b8b);
        color: #fff;
    }

    .diy-miaosha .plugin-name {
        height: 28px;
        line-height: 28px;
        padding: 0 8px;
        color: #ff4544;
        font-size: 24px;
        background-color: #feeeee;
        border-radius: 14px;
        margin-right: 8px;
    }
</style>
<style>
    .diy-miaosha .m-diy-list__box {
        padding: 20px;
    }

    .diy-miaosha .m-label {
        width: 100%;
        height: 80px;
        padding: 0 24px;
        border-radius: 16px 16px 0 0;
        flex-shrink: 0;
    }

    .diy-miaosha .m-label .title {
        font-size: 28px;
    }

    .diy-miaosha .m-label .desc {
        color: #ffffff;
        font-size: 20px;
        margin-left: 20px;
    }

    .diy-miaosha .m-label .time {
        margin-left: 12px;
    }

    .diy-miaosha .m-label .time .colon {
        color: #ffffff;
        width: 22px;
    }


    .diy-miaosha .m-label .time .box-m {
        font-size: 20px;
        height: 36px;
        width: 40px;
        border-radius: 4px;
        background: #FFFFFF;
    }


    .diy-miaosha .m-label .m-label-right {
        font-size: 22px;
        color: #FFFFFF;
        flex-shrink: 0;
    }

    .diy-miaosha .m-goods {
        padding: 20px 0 24px 24px;
        width: 100%;
        border-radius: 0 0 16px 16px;
    }

    .diy-miaosha .m-goods .m-goods-box {
        margin-right: 12px;
        position: relative;
        background-color: #FFFFFF;
        width: 260px;
        border-radius: 16px;
        flex-shrink: 0;
    }

    .diy-miaosha .m-goods .tag {
        position: absolute;
        left: 0;
        top: 0;
        z-index: 10;
        width: 64px;
        height: 64px;
    }

    .diy-miaosha .m-goods .pic-url {
        height: 260px;
        width: 100%;
        display: block;
        border-radius: 16px 16px 0 0;

    }

    .diy-miaosha .m-goods .goods-end {
        width: 100%;
        padding: 20px 8px;
    }

    .diy-miaosha .m-goods .goods-end .goods-name-m {
        font-size: 24px;
        color: #353535;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        white-space: normal !important;
    }

    .diy-miaosha .m-goods .goods-end .goods-name-m:before {
        content: '秒杀';
        padding: 0 10px;
        margin-right: 8px;
        font-size: 22px;
        border-radius: 28px;
        color: #FF4544;
        text-align: center;
        background: #FFCEC0;
        display: inline-block;

    }

    .diy-miaosha .m-goods .goods-end .goods-fold {
        padding: 5px 10px;
        font-size: 10px;
        color: #fff;
        line-height: 1;
        border-radius: 14px;
        margin-right: 10px;
        display: inline-block;
    }

    .diy-miaosha .m-goods .goods-end .goods-progress {
        width: 100%;
        height: 20px;
        border-radius: 20px;
        overflow: hidden;
        margin-top: 8px;
    }

    .diy-miaosha .m-goods .goods-end .goods-progress-view {
        width: 50%;
        height: 100%;
        border-radius: inherit;
    }

    .diy-miaosha .m-goods .goods-end .goods-num {
        font-size: 20px;
        color: #999999;
    }

    .diy-miaosha .m-goods .goods-end .goods-price {
        font-size: 28px;
    }

    .diy-miaosha .m-goods .goods-end .goods-under-line-price {
        font-size: 20px;
        color: #999999;
        margin-left: 5px;
        text-decoration: line-through;
    }
</style>

<template id="diy-miaosha">
    <div class="diy-miaosha">
        <div class="diy-component-preview" :style="cListStyle">
            <div v-if="data.addGoodsType == 1" class="goods-list" :class="'goods-list-'+data.listStyle"
                 :flex="cListFlex">
                <div v-for="item in cList" style="padding: 10px;" :style="cItemStyle">
                    <div class="goods-item"
                         :flex="cGoodsFlex"
                         :class="'goods-cover-'+data.goodsCoverProportion"
                         :style="cGoodsStyle">
                        <div class="goods-pic" :style="cGoodsPicStyle(item.picUrl)">
                            <div :style="cTimerStyle" :flex="cTimerFlex" class="goods-miaosha-timer"
                                 v-if="data.listStyle===1 || data.listStyle===2">
                                <div v-if="data.listStyle===1"><?= \Yii::t('plugins/diy', '秒杀');?></div>
                                <div><?= \Yii::t('plugins/diy', '距结束');?> xx:xx:xx</div>
                            </div>
                        </div>
                        <div v-if="data.showGoodsTag" class="goods-tag"
                             :style="'background-image: url('+data.goodsTagPicUrl+');'"></div>
                        <div :style="cGoodsInfoStyle">
                            <div class="goods-name" :class="data.listStyle!==-1 ? data.listStyle == 1 ? 'goods-two' : '':'goods-name-static'">
                                <template v-if="data.showGoodsName"><span v-if="data.listStyle!=1" class="plugin-name"><?= \Yii::t('plugins/diy', '秒杀');?></span>{{item.name}}</template>
                            </div>
                            <div v-if="data.listStyle===-1" style="color: #909399;">
                                <i class="el-icon-time"></i>
                                <?= \Yii::t('plugins/diy', '距结束');?> <span style="color: #FF4544;">xx:xx:xx</span>
                            </div>
                            <div flex="box:last cross:bottom">
                                <div class="goods-price" :style="cPriceStyle">
                                    <div style="color: #ff4544;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;letter-spacing: -1px;">
                                        <span>￥{{item.price}}</span>
                                    </div>
                                    <div v-if="data.listStyle == -1 && data.isUnderLinePrice"
                                         style="color: #909399;text-decoration: line-through;position: absolute;left: 0;bottom: 0;font-size: 24px;">
                                        <span>￥{{item.originalPrice}}</span>
                                    </div>
                                    <div v-else-if="data.isUnderLinePrice"
                                         style="color: #909399;text-decoration: line-through;font-size: 24px;line-height: 1">
                                        <span>￥{{item.originalPrice}}</span>
                                    </div>
                                </div>
                                <div v-if="cShowBuyBtn" style="padding: 0 10px;">
                                    <el-button :style="cButtonStyle" type="primary" style="font-size: 24px;">
                                        {{data.buyBtnText}}
                                    </el-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="data.addGoodsType == 0" class="m-diy-list__box" flex="dir:top">
                <div flex="main:justify cross:center dir:left" class="m-label box-grow-0"
                     :style="{background: 'linear-gradient( to right, ' + data.mBgColor +', '+ (  data.mBgType === 'gradient' ?   data.mBgGradientColor:   data.mBgColor) + ')'}">
                    <div flex="dir:left cross:center" class="box-grow-0">
                        <div class="title" :style="{color: data.mColor}">{{ data.mTitle }}</div>
                        <div class="desc"><?= \Yii::t('plugins/diy', '12点场');?></div>
                        <div flex="dir:left cross:center" class="time">
                            <div flex="main:center cross:center"
                                 :style="{backgroundColor: data.mTimeBgColor, color: data.mTimeColor}" class="box-m">03
                            </div>
                            <div flex="main:center cross:center" class="colon">:</div>
                            <div flex="main:center cross:center"
                                 :style="{backgroundColor: data.mTimeBgColor, color: data.mTimeColor}" class="box-m">01
                            </div>
                            <div flex="main:center cross:center" class="colon">:</div>
                            <div flex="main:center cross:center"
                                 :style="{backgroundColor: data.mTimeBgColor, color: data.mTimeColor}" class="box-m">02
                            </div>
                        </div>
                    </div>
                    <div flex="dir:left cross:center" class="m-label-right box-grow-0">
                        <div><?= \Yii::t('plugins/diy', '更多');?></div>
                        <i style="color:#FFFFFF" class="el-icon-arrow-right"></i>
                    </div>
                </div>
                <div class="m-goods" :style="{backgroundColor: data.mGoodsBgColor}">
                    <div flex="dir:left" style="height: 100%;width: 100%;overflow-x: auto">
                        <div flex="dir:top" class="m-goods-box" v-for="(goods,index) in cList">
                            <image class="box-grow-0 pic-url" :src="goods.picUrl"></image>
                            <div class="goods-end"
                                 v-if="data.showGoodsName || data.showGoodsPric || data.isUnderLinePrice">
                                <div v-if="data.showGoodsName" class="goods-name-m">{{ goods.name }}</div>
                                <div flex="dir:left cross:center">
                                    <div v-if="data.showGoodsPrice" class="goods-price">￥{{goods.price}}</div>
                                    <div v-if="data.isUnderLinePrice" class="goods-under-line-price">
                                        ￥{{goods.originalPrice}}
                                    </div>
                                </div>
                            </div>
                            <div v-if="data.showGoodsTag" class="tag">
                                <image :src="data.goodsTagPicUrl" width="64px" height="64px"></image>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form @submit.native.prevent label-width="150px">
                <el-form-item label="<?= \Yii::t('plugins/diy', '商品添加');?>">
                    <app-radio v-model="data.addGoodsType" :label="0"><?= \Yii::t('plugins/diy', '自动添加');?></app-radio>
                    <app-radio v-model="data.addGoodsType" :label="1"><?= \Yii::t('plugins/diy', '手动添加');?></app-radio>
                </el-form-item>
                <!--————————————————————————————————————————————————-->
                <template v-if="data.addGoodsType == 0">
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品数量');?>">
                        <el-input size="small" v-model.number="data.goodsLength" type="number"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '秒杀标题');?>">
                        <el-input size="small" v-model.number="data.mTitle"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '标题文字颜色');?>">
                        <div flex="dir:left cross:center">
                            <el-color-picker @change="(row) => {row == null ? data.mColor = '#ffffff' : ''}"
                                             size="small"
                                             v-model="data.mColor"></el-color-picker>
                            <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mColor"
                            ></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '标题背景颜色');?>">
                        <el-radio v-model="data.mBgType" label="pure"><?= \Yii::t('plugins/diy', '纯色');?></el-radio>
                        <el-radio v-model="data.mBgType" label="gradient"><?= \Yii::t('plugins/diy', '渐变');?></el-radio>
                        <div flex="dir:left cross:center">
                            <div>
                                <el-color-picker @change="(row) => {row == null ? data.mBgColor = '#FF366F' : ''}"
                                                 size="small" v-model="data.mBgColor"></el-color-picker>
                                <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mBgColor"
                                ></el-input>
                            </div>
                            <div style="margin-left: 24px">
                                <el-color-picker @change="(row) => {row == null ? data.mBgGradientColor = '#FF4242' : ''}"
                                                 size="small" v-model="data.mBgGradientColor"></el-color-picker>
                                <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mBgGradientColor"
                                ></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '倒计时文字颜色');?>">
                        <div flex="dir:left cross:center">
                            <el-color-picker @change="(row) => {row == null ? data.mTimeColor = '#353535' : ''}"
                                             size="small"
                                             v-model="data.mTimeColor"></el-color-picker>
                            <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mTimeColor"
                            ></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '倒计时背景颜色');?>">
                        <div flex="dir:left cross:center">
                            <el-color-picker @change="(row) => {row == null ? data.mTimeBgColor = '#FFFFFF' : ''}"
                                             size="small"
                                             v-model="data.mTimeBgColor"></el-color-picker>
                            <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mTimeBgColor"
                            ></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品背景颜色');?>">
                        <div flex="dir:left cross:center">
                            <el-color-picker @change="(row) => {row == null ? data.mGoodsBgColor = '#FFE7E7' : ''}"
                                             size="small"
                                             v-model="data.mGoodsBgColor"></el-color-picker>
                            <el-input size="small" style="width: 80px;margin-left: 5px;" v-model="data.mGoodsBgColor"
                            ></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '显示商品名称');?>">
                        <el-switch v-model="data.showGoodsName"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '显示商品价格');?>">
                        <el-switch v-model="data.showGoodsPrice"></el-switch>
                    </el-form-item>
                </template>
                <!--————————————————————————————————————————————————-->
                <template v-if="data.addGoodsType == 1">
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品列表');?>">
                        <draggable class="goods-list" flex v-model="data.list" ref="parentNode">
                            <div class="goods-item drag-drop" v-for="(goods,goodsIndex) in data.list"
                                 :style="'background-image: url('+goods.picUrl+');'">
                                <el-button @click="deleteGoods(goodsIndex)" class="goods-delete"
                                           size="small" circle type="danger"
                                           icon="el-icon-close"></el-button>
                            </div>
                            <div class="goods-add" flex="main:center cross:center"
                                 @click="goodsDialog.visible=true">
                                <i class="el-icon-plus"></i>
                            </div>
                        </draggable>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '购买按钮颜色');?>">
                        <el-color-picker v-model="data.buttonColor"></el-color-picker>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '列表样式');?>">
                        <app-radio v-model="data.listStyle" :label="-1" @change="listStyleChange"><?= \Yii::t('plugins/diy', '列表模式');?></app-radio>
                        <app-radio v-model="data.listStyle" :label="1" @change="listStyleChange"><?= \Yii::t('plugins/diy', '一行一个');?></app-radio>
                        <app-radio v-model="data.listStyle" :label="2" @change="listStyleChange"><?= \Yii::t('plugins/diy', '一行两个');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品封面图宽高比例');?>" v-if="data.listStyle==1">
                        <app-radio v-model="data.goodsCoverProportion" label="1-1">1:1</app-radio>
                        <app-radio v-model="data.goodsCoverProportion" label="3-2">3:2</app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品封面图填充');?>">
                        <app-radio v-model="data.fill" :label="1"><?= \Yii::t('plugins/diy', '填充');?></app-radio>
                        <app-radio v-model="data.fill" :label="0"><?= \Yii::t('plugins/diy', '留白');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '商品样式');?>">
                        <app-radio v-model="data.goodsStyle" :label="1"><?= \Yii::t('plugins/diy', '白底无边框');?></app-radio>
                        <app-radio v-model="data.goodsStyle" :label="2"><?= \Yii::t('plugins/diy', '白底有边框');?></app-radio>
                        <app-radio v-model="data.goodsStyle" :label="3"><?= \Yii::t('plugins/diy', '无底无边框');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('plugins/diy', '显示商品名称');?>">
                        <el-switch v-model="data.showGoodsName"></el-switch>
                    </el-form-item>
                    <el-form-item v-if="data.listStyle!==-1" label="<?= \Yii::t('plugins/diy', '文本样式');?>">
                        <app-radio v-model="data.textStyle" :label="1"><?= \Yii::t('plugins/diy', '左对齐');?></app-radio>
                        <app-radio v-model="data.textStyle" :label="2"><?= \Yii::t('plugins/diy', '居中');?></app-radio>
                    </el-form-item>
                    <template v-if="cShowEditBuyBtn">
                        <el-form-item label="<?= \Yii::t('plugins/diy', '显示购买按钮');?>">
                            <el-switch v-model="data.showBuyBtn"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="data.showBuyBtn" label="<?= \Yii::t('plugins/diy', '购买按钮样式');?>">
                            <app-radio v-model="data.buyBtnStyle" :label="1"><?= \Yii::t('plugins/diy', '填充');?></app-radio>
                            <app-radio v-model="data.buyBtnStyle" :label="2"><?= \Yii::t('plugins/diy', '线条');?></app-radio>
                            <app-radio v-model="data.buyBtnStyle" :label="3"><?= \Yii::t('plugins/diy', '圆角填充');?></app-radio>
                            <app-radio v-model="data.buyBtnStyle" :label="4"><?= \Yii::t('plugins/diy', '圆角线条');?></app-radio>
                        </el-form-item>
                        <el-form-item v-if="data.showBuyBtn" label="<?= \Yii::t('plugins/diy', '购买按钮文字');?>">
                            <el-input maxlength="4" size="small" v-model="data.buyBtnText"></el-input>
                        </el-form-item>
                    </template>
                </template>
                <el-form-item label="<?= \Yii::t('plugins/diy', '显示商品角标');?>">
                    <el-switch v-model="data.showGoodsTag"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '商品角标样式');?>" v-if="data.showGoodsTag">
                    <app-radio v-model="data.goodsTagPicUrl" v-for="tag in goodsTags" :label="tag.picUrl" :key="tag.name"
                              @change="goodsTagChange">
                        {{tag.name}}
                    </app-radio>
                    <app-radio v-model="data.customizeGoodsTag" :label="true" @change="customizeGoodsTagChange"><?= \Yii::t('plugins/diy', '自定义');?>
                    </app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '自定义商品角标');?>" v-if="data.showGoodsTag&&data.customizeGoodsTag">
                    <app-image-upload width="64" height="64" v-model="data.goodsTagPicUrl"></app-image-upload>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '显示划线价');?>" v-if="[-1,1,2].indexOf(data.listStyle) != -1">
                    <el-switch v-model="data.isUnderLinePrice"></el-switch>
                </el-form-item>
                <diy-bg :data="data" @update="updateData" @toggle="toggleData" @change="changeData"></diy-bg>
            </el-form>
        </div>
        <el-dialog title="<?= \Yii::t('plugins/diy', '选择商品');?>" :visible.sync="goodsDialog.visible" @open="goodsDialogOpened">
            <el-input size="mini" v-model="goodsDialog.keyword" placeholder="<?= \Yii::t('plugins/diy', '根据名称搜索');?>" :clearable="true"
                      @clear="loadGoodsList(1)" @keyup.enter.native="loadGoodsList(1)">
                <el-button slot="append" @click="loadGoodsList(1)"><?= \Yii::t('plugins/diy', '搜索');?></el-button>
            </el-input>
            <el-table :data="goodsDialog.list" v-loading="goodsDialog.loading" @selection-change="goodsSelectionChange">
                <el-table-column type="selection" width="50px"></el-table-column>
                <el-table-column label="ID" prop="id" width="100px"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/diy', '名称');?>" prop="name"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/diy', '时间');?>" prop="open_date"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/diy', '场次');?>" prop="open_time"></el-table-column>
            </el-table>
            <div style="text-align: center">
                <el-pagination
                        v-if="goodsDialog.pagination"
                        style="display: inline-block"
                        background
                        @current-change="goodsDialogPageChange"
                        layout="prev, pager, next, jumper"
                        :page-size.sync="goodsDialog.pagination.pageSize"
                        :total="goodsDialog.pagination.totalCount">
                </el-pagination>
            </div>
            <div slot="footer">
                <el-button type="primary" @click="addGoods"><?= \Yii::t('plugins/diy', '确定');?></el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    Vue.component('diy-miaosha', {
        template: '#diy-miaosha',
        props: {
            value: Object,
        },
        data() {
            return {
                goodsDialog: {
                    visible: false,
                    page: 1,
                    keyword: '',
                    loading: false,
                    list: [],
                    pagination: null,
                    selected: null,
                },
                goodsTags: [
                    {
                        name: "<?= \Yii::t('plugins/diy', '热销');?>",
                        picUrl: _currentPluginBaseUrl + '/images/goods-tag-rx.png',
                    },
                    {
                        name: "<?= \Yii::t('plugins/diy', '新品');?>",
                        picUrl: _currentPluginBaseUrl + '/images/goods-tag-xp.png',
                    },
                    {
                        name: "<?= \Yii::t('plugins/diy', '折扣');?>",
                        picUrl: _currentPluginBaseUrl + '/images/goods-tag-zk.png',
                    },
                    {
                        name: "<?= \Yii::t('plugins/diy', '推荐');?>",
                        picUrl: _currentPluginBaseUrl + '/images/goods-tag-tj.png',
                    },
                ],
                data: {
                    buttonColor: '#ff4544',
                    /* */
                    addGoodsType: 0,
                    goodsLength: 10,

                    mTitle: "<?= \Yii::t('plugins/diy', '圣诞秒杀整点抢');?>",
                    mColor: '#FFFFFF',
                    mBgType: 'gradient',
                    mBgColor: '#FF366F',
                    mBgGradientColor: '#FF4242',
                    mTimeColor: '#353535',
                    mTimeBgColor: '#FFFFFF',
                    mGoodsBgColor: '#FFE7E7',

                    showGoodsPrice: true,
                    /* */
                    list: [],
                    listStyle: 1,
                    fill: 1,
                    goodsCoverProportion: '1-1',
                    goodsStyle: 1,
                    textStyle: 1,
                    showGoodsName: true,
                    showBuyBtn: true,
                    buyBtnStyle: 1,
                    buyBtnText: "<?= \Yii::t('plugins/diy', '马上秒');?>",
                    showGoodsTag: false,
                    customizeGoodsTag: false,
                    goodsTagPicUrl: '',
                    showImg: false,
                    backgroundColor: '#fff',
                    backgroundPicUrl: '',
                    position: 5,
                    mode: 1,
                    backgroundHeight: 100,
                    backgroundWidth: 100,
                    isUnderLinePrice: true,
                },
                position: 'center center',
                repeat: 'no-repeat',
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
            }
        },
        computed: {
            cListStyle() {
                if(this.data.backgroundColor) {
                    return `background-color:${this.data.backgroundColor};background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
                }else {
                    return `background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
                }
            },
            cList() {
                if (!this.data.list || !this.data.list.length) {
                    const item = {
                        id: 0,
                        name: "<?= \Yii::t('plugins/diy', '演示商品名称');?>",
                        picUrl: '',
                        price: '100.00',
                        originalPrice: '300.00',
                    };
                    return [item, item,];
                } else {
                    return this.data.list;
                }
            },
            cListFlex() {
                if (this.data.listStyle === -1) {
                    return 'dir:top';
                } else {
                    return 'dir:left';
                }
            },
            cItemStyle() {
                if (this.data.listStyle === 2) {
                    return 'width: 50%;';
                } else {
                    return 'width: 100%;';
                }
            },
            cGoodsStyle() {
                let style = 'border-radius:5px;';
                if (this.data.goodsStyle === 2) {
                    style += 'border: 1px solid #e2e2e2;';
                }
                if (this.data.goodsStyle != 3) {
                    style += 'background-color:#ffffff';
                }
                return style;
            },
            cGoodsInfoStyle() {
                let style = 'position:relative;';
                if (this.data.listStyle !== -1) {
                    style += 'padding:20px;';
                }else {
                    style += 'padding: 15px 20px 0 0;';
                }
                if (this.data.textStyle === 2) {
                    style += 'text-align: center;';
                }
                return style;
            },
            cPriceStyle() {
                let style = 'margin-top: 10px;';
                if (this.data.textStyle === 2) {
                    style += 'text-align: center;width: 100%;';
                }
                return style;
            },
            cGoodsFlex() {
                if (this.data.listStyle === -1) {
                    return 'dir:left box:first';
                } else {
                    return 'dir:top';
                }
            },
            cButtonStyle() {
                console.log(this.data.buyBtnStyle);
                let style = `background: ${this.data.buttonColor};border-color: ${this.data.buttonColor};height:48px;line-height:50px;padding: 0 20px;`;
                if (this.data.buyBtnStyle === 3 || this.data.buyBtnStyle === 4) {
                    style += `border-radius:24px;`;
                }
                if (this.data.buyBtnStyle === 2 || this.data.buyBtnStyle === 4) {
                    style += `background:#fff;color:${this.data.buttonColor}`;
                }
                return style;
            },
            cTimerStyle() {
                if (this.data.listStyle === 2) {
                    return 'height:60px;line-height:60px;font-size:24px;text-align:center;';
                } else {
                    return '';
                }
            },
            cTimerFlex() {
                if (this.data.listStyle === 2) {
                    return 'main:center';
                } else {
                    return 'box:last';
                }
            },
            cShowBuyBtn() {
                return this.data.textStyle !== 2
                    && this.data.showBuyBtn;
            },
            cShowEditBuyBtn() {
                return this.data.textStyle !== 2
            },
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        methods: {
            updateData(e) {
                this.data = e;
            },
            toggleData(e) {
                this.position = e;
            },
            changeData(e) {
                this.repeat = e;
            },
            cGoodsPicStyle(picUrl) {
                let style = `background-image: url(${picUrl});`
                    + `background-size: ${(this.data.fill === 1 ? 'cover' : 'contain')};`;
                return style;
            },
            listStyleChange(listStyle) {
                if (listStyle === -1 && this.data.textStyle === 2) {
                    this.data.textStyle = 1;
                }
            },
            goodsDialogOpened() {
                this.loadGoodsList(1);
            },
            loadGoodsList(page = 1) {
                this.goodsDialog.loading = true;
                this.$request({
                    params: {
                        r: 'plugin/diy/mall/template/get-goods',
                        page: page,
                        keyword: this.goodsDialog.keyword,
                        sign: 'miaosha',
                    }
                }).then(response => {
                    this.goodsDialog.loading = false;
                    if (response.data.code === 0) {
                        this.goodsDialog.list = response.data.data.list;
                        this.goodsDialog.pagination = response.data.data.pagination;
                    }
                }).catch(e => {
                });
            },
            goodsDialogPageChange(page) {
                this.loadGoodsList(page);
            },
            goodsSelectionChange(e) {
                if (e && e.length) {
                    this.goodsDialog.selected = e;
                } else {
                    this.goodsDialog.selected = null;
                }
            },
            addGoods() {
                if (!this.goodsDialog.selected || !this.goodsDialog.selected.length) {
                    this.goodsDialog.visible = false;
                    return;
                }
                for (let i in this.goodsDialog.selected) {
                    const item = {
                        id: this.goodsDialog.selected[i].id,
                        name: this.goodsDialog.selected[i].name,
                        picUrl: this.goodsDialog.selected[i].cover_pic,
                        price: this.goodsDialog.selected[i].price,
                        originalPrice: this.goodsDialog.selected[i].original_price,
                    };
                    this.data.list.push(item);
                }
                this.goodsDialog.selected = null;
                this.goodsDialog.visible = false;
            },
            deleteGoods(index) {
                this.data.list.splice(index, 1);
            },
            goodsTagChange(e) {
                this.data.goodsTagPicUrl = e;
                this.data.customizeGoodsTag = false;
            },
            customizeGoodsTagChange(e) {
                this.data.goodsTagPicUrl = '';
                this.data.customizeGoodsTag = true;
            },
        }
    });
</script>
