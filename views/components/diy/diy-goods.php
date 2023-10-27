<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 2019/4/29
 * Time: 10:05
 */
$baseUrl = \Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl;
Yii::$app->loadViewComponent('u-new-price');
?>
<style>
    /*-----------------设置部分--------------*/
    .diy-goods .diy-component-edit .diy-goods-label {
        width: 85px;

    }

    .diy-goods .diy-component-edit .cat-item {
        border: 1px solid #e2e2e2;
        margin-bottom: 5px;
        padding: 15px;
        max-width: 400px;
    }

    .diy-goods .diy-component-edit .goods-list {
        flex-wrap: wrap;
        display: flex;
    }

    .diy-goods .diy-component-edit .goods-item,
    .diy-goods .diy-component-edit .goods-add {
        width: 50px;
        height: 50px;
        position: relative;
        margin-right: 15px;
        margin-bottom: 15px;
    }

    .diy-goods .diy-component-edit .goods-add .el-button {
        width: 100%;
        height: 100%;
        border-radius: 0;
        padding: 0;
    }

    .diy-goods .diy-component-edit .goods-pic {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
    }

    .diy-goods .diy-component-edit .goods-delete {
        position: absolute;
        left: calc(100% - 13px);
        top: -13px;
        width: 25px;
        height: 25px;
        line-height: 25px;
        padding: 0 0;
        visibility: hidden;
        z-index: 1;
    }

    .diy-goods .diy-component-edit .goods-item:hover .goods-delete {
        visibility: visible;
    }

    .diy-goods .diy-component-edit .cat-item-options {
        position: relative;
    }

    .diy-goods .diy-component-edit .cat-item-options .el-button {
        height: 25px;
        line-height: 25px;
        width: 25px;
        padding: 0;
        text-align: center;
        border: none;
        border-radius: 0;
        position: absolute;
        margin-left: 0;
        top: -16px;
        right: -40px;
    }

    /*-----------------预览部分--------------*/

    .diy-goods .diy-component-preview .cat-list {
    }

    .diy-goods .diy-component-preview .cat-list-top {
        margin-bottom: 12px;
    }

    .diy-goods .diy-component-preview .cat-list-left {
        width: 160px;
        margin-right: 12px;
    }

    .diy-goods .diy-component-preview .cat-item {
        height: 104px;
        padding: 0 10px;
        text-align: center;
        max-width: 100%;
        white-space: nowrap;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-name {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .diy-goods .diy-component-preview .cat-item.active {
        color: #ff4544;
    }

    .diy-goods .diy-component-preview .cat-list-top .cat-item {
        margin: 0 20px;
    }

    .diy-goods .diy-component-preview .cat-list-top {
        overflow-x: auto;
    }

    .diy-goods .diy-component-preview .cat-list-top.cat-style-1 .cat-item {
        border-bottom: 4px solid transparent;
    }

    .diy-goods .diy-component-preview .cat-list-top.cat-style-2 .cat-name {
        background: #ff4544;
        /*color: #fff;*/
        border-radius: 100px;
        padding: 0 18px;
    }

    .diy-goods .diy-component-preview .cat-list-top .cat-item.active {
        border-bottom-color: #ff4544;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-item {
        border-left: 2px solid transparent;
    }

    .diy-goods .diy-component-preview .cat-list-left .cat-item.active {
        border-left-color: #ff4544;
    }

    .diy-goods .diy-component-preview .goods-list {
        margin: -11px;
    }

    .diy-goods .diy-component-preview .goods-item {
        padding: 11px;
    }

    .diy-goods .diy-component-preview .goods-pic {
        background-size: cover;
        background-position: center;
        width: 100%;
        height: 700px;
        background-color: #f6f6f6;
        background-repeat: no-repeat;
        position: relative;
        border-radius: 10px 10px 0 0;
    }

    .diy-goods .diy-component-preview .goods-item-style {
        overflow: hidden;
    }

    .diy-goods .diy-component-preview .goods-pic-3-2 {
        height: 471px;
    }

    .diy-goods .diy-component-preview .goods-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 10px;
        font-size: 28px;
    }

    .diy-goods .diy-component-preview .goods-name.goods-two {
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
        height: 76px;
        white-space: normal !important;
    }

    .diy-goods .diy-component-preview .goods-name-static {
        height: 94px;
    }

    .diy-goods .diy-component-preview .goods-price {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #ff4544;
    }
    .diy-goods .diy-component-preview .goods-price .u-new-price {
        width: auto;
    }

    .diy-goods .diy-component-preview .goods-underline-price {
        margin-left: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 22px;
        color: #999;
        text-decoration: line-through;
    }

    .diy-goods .diy-component-preview .goods-list-style--1 .goods-item,
    .diy-goods .diy-component-preview .goods-list-style-1 .goods-item {
        width: 100%;
    }

    .diy-goods .diy-component-preview .goods-list-style-2 .goods-item {
        width: 50%;
    }

    .diy-goods .diy-component-preview .goods-list-style-3 .goods-item {
        width: 33.333333%;
    }

    .diy-goods .diy-component-preview .goods-list-style-0 .goods-item {
        width: 249px;
    }

    .diy-goods .diy-component-preview .goods-list-style--1 .goods-pic {
        width: 256px;
        height: 256px;
        border-radius: 10px 0 0 10px;
    }

    .diy-goods .diy-component-preview .goods-list-style-2 .goods-pic {
        height: 342px;
        border-radius: 10px 10px 0 0;
    }

    .diy-goods .diy-component-preview .goods-list-style-0 .goods-pic {
        height: 212px;
        border-radius: 10px 10px 0 0;
    }

    .diy-goods .diy-component-preview .goods-list-style-3 .goods-pic {
        height: 224px;
        border-radius: 10px 10px 0 0;
    }

    .diy-goods .diy-component-preview .goods-pic-fill-0 {
        background-size: contain;
    }

    .diy-goods .diy-component-preview .buy-btn {
        border-color: #ff4544;
        color: #ff4544;
        padding: 0 20px;
        height: 48px;
        line-height: 50px;
        font-size: 24px;
    }

    .diy-goods .diy-component-preview .buy-btn.el-button--primary {
        background-color: #ff4544;
        color: #fff;
    }

    .diy-goods .diy-component-preview .goods-tag {
        position: absolute;
        top: 0;
        left: 0;
        width: 64px;
        height: 64px;
        z-index: 1;
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }

    .diy-goods hr {
        border: none;
        height: 1px;
        background-color: #e2e2e2;
    }

    .diy-goods .diy-component-preview .goods-item .buy-btn.is-round {
        border-radius: 24px;
    }

    .diy-goods .goods-item.goods-cat-list {
        /*border-top: 1px solid #e2e2e2;*/
    }

    .diy-goods .goods-item.goods-cat-list:first-of-type {
        border-top: 0;
    }

    .diy-goods .cat-list {
        /*max-height: 500px;*/
        overflow: auto;
    }
    .diy-goods__cart {
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAB4CAMAAAAOusbgAAACFlBMVEUAAAD///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8YiqUCAAAAsXRSTlMAAQIDBAUGCAkKCwwNDhAREhMUFRYXGBkaGx0eHyAhIiMmKSosLS4vMDIzNDk6Oz1CREZHSElKS0xNTk9SU1RZWl1eYGJkZWZnaWprbnBxcnN0eXqBgoOEhYaJi4yNjo+QkpSVl5iZmpyen6KjpaanqKmrra6vsLS2t7i6vb/AwcPExcbHyMnKy8zNzs/Q0dLT1NXW2drc3d7f4eLj5OXn6Onr7O3u7/X29/j5+vv8/f7qhvdcAAAGtklEQVRo3sWb/V9TVRzH31sMhXxgmWIaPsHKkilCTqeh0gMYRmaaFFIZaZFl5noALXqQGYXasllmkzLwCWS68x/2w9kYg3vPORuH1/38er/3vHfP7j3n+3QQxWo8EetsaQqHaoLl5cGaULippTOWGC96GIqyTvY01/hxkL+muSc5T+DR3r0rUKp6b++obfBELBrAQIFobMIieKhtybRZXbut4/jZ84nrY/fvj11PnD97vGPb2mnzv2TfkCXwwNapQRdFuocdn2hiuDuyaMps64AFcH84N1zdkXhaZZmOH6nL2Yb75wi+sCk70vKOiyYTeLFjefaGTRfmAL7V6pOjNPSlTd/WdF+DvMfXeqtEcKY3KIfYES9ubYjvkPcFezOlgFNb5O27Lxe9KolLzfLeLaniwX3ycWsHREkaqJUP3VckOH3QB1DZNSlK1GRXJYDvYLoY8Eg9AJGUmINSEQDqR8zByVUAgaMZMSdljgYAViVNwfEgwOq4mLPiqwGCcTNwfwVA9LawoNtRgIp+E/DpMoD2h8KKHrYDlJ3Wg/vLAA4LazoMUNavA8crAP8JYVEn/EBFXA1OBgH/GWFVZ/xAMKkCj6wCOCEs6wTAqhF3cLre8v9b8D/Xp13BBwHaxTyoHeCgG7jPB0Qfzgf4YRTw9TmDU0Fg9W0xL7q9GgimnMCZLUAgLuZJ8QCwJeMA7gU4KuZNRwF6Z4NvBYGI0340eWXUBjgTAYK3ZoFbgUqn/fe7lZQdyFggpyqB1pngCz6gy8H8XhXAZzaeuQvwXZgB3gTUOvk53wMQtQGerAU2FYL7ARz9up8BeNzK+zUA0F8ADgO7neNEGSVet0LeDWyeDh4AcPGfZRDzuRXw5fzESvBWYIeL8X4AXrPzMe8AtubBQwBua9YnMvyztH4BDE2B24AGN9urACyYtENuANpy4IklgGuskVnK1M+cu/qAJRNZcAxY7h6HNsp49933dOr5In5XG8UuB2JZcBTo0KzuhloYPa1ZXTuAnRI8GgAU8f6dRylGG79V5wyAwKgQyP2wTmX7ZlFgfJ1Kcp3cHRFiL6C2bSuOzMuqwY4Ae4VAiBWKjzjrMX20sjhyl+ZTrhYCkQQW6XIr9745dqBDp1d3Lcsl4QYV7/UiICkQPcB2S+5N+mQ2y7ZZYbQd6BGI54Fua57VtY2SfNbdpBtoFogaYNieT/dnBQAvulsMA2sE437wT9gDi24AHnvgajDhB/84CWCtTTf27gKd57AWSBADtll1oGXW9by7wTYgRqd6oS5BUQC+Vi/XnbQAx62CNwCgWLGPAy00AudscscfAeCKu8U5oJF6YNBsyN8PNTwVmq6mUy5bPSxWRLuDQJgQ8JsJ9sF+h8LPB7PtZOI2ohgpAYSoMXSaMy857QbrZtmNlQPwqWKo60ANQWDMAOy8KS+dZfe+rJqofKAxIEg5cN8goq90BLfMMnxGvyPfB8pNwaccuc/NKjv8Ki/8qAcbTvUhmeofTkzXv86LA6xXjiWn2vDlOgDAW9pIVLoCbyuN5MsVAhJ68DEAdunMvpIOyA2lkfycwuoFPacfAFimM9sJQJPa6DwQpkntLkwthPLzvKq2+q/MJKY9CzQZbxKyPHJSbfSOrKdq6udykzDdFl/Xf59ChAB4ReijmE5jR+BLADYobX6RH/FPmqGkI2Dq+tyU4YmyUCn3h1rdUNL1MXb21oEqjBZCfCgfWJeWzDp7xu7tC5o/+Y9Wya26oxko696KZkOHPvs8T6xx1uLc+n3MxP9tLiKEubnAKF5br02WRLIhjFHQJkQ2va/Tk9e08VUuaDMIU6VGN+i56/42yjitmArMj5h4Xf+ENNjKN+7oR8kH5tpURN7f+/jZha7UsqcP3TQZJJ+K0CVfCpMDqb+cNfLAbIBpyRddusmuOrLZb4MEm1UVJNjUKUW7KkgpqpOodtUA7DNLG1vVjLSxMlGe14091dV7bpR21TlRLksDl3TcKoCqG6VczerSzNKAohiS1x65UOwp5eo0T6GgGKIo/+RVnW0JLOWqyE9sYfnHveBlEexU8HIv8VmcascSn3tR09rL5VzUVJRxLX1ObmVczwrX3pXqPWtO8K4dw7sGFO9abjxrMvKurcq7RjLvWue8axb0rj3Su4ZQ71pgvWv69a7NWXjW2C3m1sp+eXfprezeNe8Lz44rCOHZAQ0hPDuSIkThIZzt7odwtls+hCOE07Gjc4O5Y0eD52YeO2qzdexICM8OWsnEnvZo2Yp5OFqW2z28OEyX03gi1tnSWJ87PljfWNrxwf8BBAsysnK763gAAAAASUVORK5CYII=');
        background-size: 100% 100%;
        background-repeat: no-repeat;
        height: 50px;
        width: 50px;
        background-color: #ff4544;
    }
</style>
<template id="diy-goods">
    <div class="diy-goods">
        <div class="diy-component-preview" :style="cListStyle">
            <div :flex="cMainFlex" :style="styleA">
                <div :flex="cCatFlex" class="cat-list"
                     :style="{backgroundColor: data.catBgColor}"
                     v-if="data.showCat && (data.catPosition=='left'||(data.catPosition=='top'&&cCatList.length>1))"
                     :class="'cat-list-'+data.catPosition+' cat-style-'+data.catStyle">
                    <div class="cat-item" v-for="(item,index) in cCatList" :class="index===0?'active':''"
                         :style="index ===0 ? data.catPosition==='left'
                         ? `color: ${data.catSelectedColor};border-left-color: ${data.tagColor}`
                         : `color: ${data.catSelectedColor};border-bottom-color: ${data.tagColor}`
                         : `color: ${data.catUnselectedColor}`"
                         flex="main:center cross:center">
                        <div :style="data.catStyle == 2 && index === 0 && data.catPosition!=='left'
                            ? `background-color: ${data.tagColor} `:'background: none'"
                             class="cat-name">{{item.menuName}}
                        </div>
                    </div>
                </div>
                <div class="goods-list" :class="'goods-list-style-'+data.listStyle">
                    <div v-for="(cat,catIndex) in cCatList">
                        <div v-if="data.catPosition==='left'" style="color: #666666;margin: 24px 11px;font-size: 24px">
                            {{cat.menuName}}
                        </div>
                        <div :style="cGoodsListStyle" flex>
                            <div v-for="(goods,goodsIndex) in cCatGoodsList(cat, catIndex)"
                                 class="goods-item"

                                 :class="data.catPosition==='left'?'goods-cat-list':''">
                                <div :style="cGoodsItemWidth">
                                    <div class="goods-item-style" :style="cGoodsItemStyle" :flex="cGoodsItemFlex" style="position: relative;">
                                        <div class="goods-pic"
                                             :class="'goods-pic-'+data.goodsCoverProportion+' goods-pic-fill-'+data.fill"
                                             :style="'background-image: url('+goods.picUrl+')'">
                                            <div v-if="data.showGoodsTag" class="goods-tag"
                                                 :style="{backgroundImage: `url(${data.goodsTagPicUrl})`, borderTopLeftRadius: `${data.goodsStyle == 2 ? '16px' : ''}`}"></div>
                                        </div>
                                        <div :style="cGoodsItemInfoStyle" flex="dir:top" v-if="data.showGoodsName || cShowBuyBtn || data.showGoodsPrice || data.isUnderLinePrice">
                                            <div class="goods-name goods-two" v-if="data.showGoodsName"
                                                 :class="data.listStyle!==-1 ? data.listStyle == 1 ? '' : '':'goods-name-static'">
                                                <template >{{goods.name}}</template>
                                            </div>
                                            <div flex="box:last cross:bottom">
                                            <div :flex="priceFlex" :style="{width: data.listStyle == 2 || data.listStyle == -1 || data.listStyle == 1 ? '' : '100%','min-height': priceFlex == 'dir:top main:center' && data.listStyle != 2 ? data.listStyle == 1 || data.listStyle == 2 ? '70px':'62px':''}">
                                                <div class="goods-price">
                                                    <template v-if="data.showGoodsPrice">
                                                        <u-new-price :price-content="`￥${goods.price}`" :list-style="data.listStyle"></u-new-price>
                                                    </template>
                                                    <span v-if="data.isUnderLinePrice"
                                                          :style="{display: data.listStyle == -1 ? 'inline' : 'block'}"
                                                          class="goods-underline-price">
                                                        ￥{{goods.original_price}}
                                                    </span>
                                                </div>
                                            </div>
                                                <div>
                                                    <template v-if="cShowBuyBtn">
                                                        <template v-if="data.buyBtn==='cart'">
                                                            <div style="font-size: 48px;color: #ff4544;"
                                                               class="diy-goods__cart"></div>
                                                        </template>
                                                        <template v-if="data.buyBtn==='add'">
                                                            <i style="font-size: 48px;color: #ff4544;"
                                                               class="el-icon-circle-plus-outline"></i>
                                                        </template>
                                                        <template v-if="data.buyBtn==='text'">
                                                            <div :style="cButtonStyle"
                                                                 style="font-size: 24px;border: 1px solid;color: #ffffff;">
                                                                {{data.buyBtnText}}
                                                            </div>
                                                        </template>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-show="!hidden" class="diy-component-edit">
            <div class="app-form-title">
                <div><?= \Yii::t('components/diy', '商品');?></div>
            </div>
            <el-form label-width="150px" @submit.native.prevent style="padding: 20px 0">
                <el-form-item label="<?= \Yii::t('components/diy', '显示分类');?>">
                    <el-switch v-model="data.showCat" @change="showCatChange"></el-switch>
                </el-form-item>
                <template v-if="data.showCat">
                    <el-form-item label="<?= \Yii::t('components/diy', '分类栏位置');?>">
                        <app-radio turn v-model="data.catPosition" label="top" @change="catPositionChange">
                            <?= \Yii::t('components/diy', '顶部');?>
                        </app-radio>
                        <app-radio turn v-model="data.catPosition" label="left" @change="catPositionChange">
                            <?= \Yii::t('components/diy', '左侧');?>
                        </app-radio>
                        <div style="color: #909399;line-height: normal;"><?= \Yii::t('components/diy', '只有一个分类时顶部不会显示分类栏');?></div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '标签颜色');?>">
                        <el-color-picker @change="(row) => {row == null ? data.tagColor = '#FF4040' : ''}"
                                         size="small" v-model="data.tagColor"></el-color-picker>
                        <el-input size="small" style="width: 64px;margin-right: 25px;"
                                  v-model="data.tagColor"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '选中文字颜色');?>">
                        <el-color-picker @change="(row) => {row == null ? data.catSelectedColor = '#FFFFFF' : ''}"
                                         size="small" v-model="data.catSelectedColor"></el-color-picker>
                        <el-input size="small" style="width: 64px;margin-right: 25px;"
                                  v-model="data.catSelectedColor"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '未选中文字颜色');?>">
                        <el-color-picker @change="(row) => {row == null ? data.catUnselectedColor = '#FFFFFF' : ''}"
                                         size="small" v-model="data.catUnselectedColor"></el-color-picker>
                        <el-input size="small" style="width: 64px;margin-right: 25px;"
                                  v-model="data.catUnselectedColor"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '背景颜色');?>">
                        <el-color-picker @change="(row) => {row == null ? data.catBgColor = '#FFFFFF' : ''}"
                                         size="small" v-model="data.catBgColor"></el-color-picker>
                        <el-input size="small" style="width: 64px;margin-right: 25px;"
                                  v-model="data.catBgColor"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '分类样式');?>" v-if="data.catPosition==='top'">
                        <app-radio turn v-model="data.catStyle" :label="1"><?= \Yii::t('components/diy', '样式1');?></app-radio>
                        <app-radio turn v-model="data.catStyle" :label="2"><?= \Yii::t('components/diy', '样式2');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('components/diy', '分类列表');?>">
                        <draggable v-model="data.catList" style="cursor:move" :options="{filter:'.item-drag',preventOnFilter:false}">
                        <div v-for="(cat,catIndex) in data.catList" class="cat-item">
                            <div class="cat-item-options">
                                <el-button type="primary" icon="el-icon-delete"
                                           @click="deleteCat(catIndex)"></el-button>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label"><?= \Yii::t('components/diy', '商品分类');?></div>
                                <div>{{cat.name}}</div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label"><?= \Yii::t('components/diy', '菜单名称');?></div>
                                <div class="item-drag">
                                    <el-input v-model="cat.menuName" size="small"></el-input>
                                </div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label"><?= \Yii::t('components/diy', '商品数量');?></div>
                                <div class="item-drag">
                                    <el-input v-model.number="cat.goodsNum" type="number" min="1" max="30"
                                              size="small" :disabled="cat.staticGoods"
                                              @change="catGoodsNumChange(catIndex)"></el-input>
                                </div>
                            </div>
                            <div flex="box:first">
                                <div class="diy-goods-label"><?= \Yii::t('components/diy', '自定义商品');?></div>
                                <div class="item-drag">
                                    <el-switch v-model="cat.staticGoods"></el-switch>
                                </div>
                            </div>
                            <div flex="box:first" v-if="cat.staticGoods">
                                <div class="diy-goods-label"><?= \Yii::t('components/diy', '商品列表');?></div>
                                <div class="item-drag">
                                    <draggable v-model="cat.goodsList" flex class="goods-list">
                                        <div class="goods-item" v-for="(goods,goodsIndex) in cat.goodsList">
                                            <el-tooltip effect="dark" content="<?= \Yii::t('components/diy', '移除商品');?>" placement="top">
                                                <el-button @click="deleteGoods(goodsIndex,catIndex)" circle
                                                           class="goods-delete" type="danger"
                                                           icon="el-icon-close"></el-button>
                                            </el-tooltip>
                                            <div class="goods-pic" :style="'background-image:url('+goods.picUrl+')'"></div>
                                        </div>
                                    </draggable>
                                    <div class="goods-add">
                                        <el-button @click="showGoodsDialog(catIndex)" icon="el-icon-plus"></el-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </draggable>
                        <el-button size="small" @click="catDialog.visible=true"><?= \Yii::t('components/diy', '添加分类');?></el-button>
                    </el-form-item>
                </template>
                <template v-else>
                    <el-form-item label="<?= \Yii::t('components/diy', '商品添加');?>">
                        <app-radio turn v-model="data.addGoodsType" :label="0"><?= \Yii::t('components/diy', '自动添加');?></app-radio>
                        <app-radio turn v-model="data.addGoodsType" :label="1"><?= \Yii::t('components/diy', '手动添加');?></app-radio>
                    </el-form-item>
                    <el-form-item v-show="data.addGoodsType == 0" label="<?= \Yii::t('components/diy', '商品数量');?>">
                        <el-input size="small" v-model.number="data.goodsLength" type="number"></el-input>
                    </el-form-item>
                    <el-form-item v-show="data.addGoodsType == 1" label="<?= \Yii::t('components/diy', '商品列表');?>">
                        <draggable v-model="data.list" flex class="goods-list">
                            <div class="goods-item"
                                 v-for="(goods,goodsIndex) in data.list">
                                <el-tooltip effect="dark" content="<?= \Yii::t('components/diy', '移除商品');?>" placement="top">
                                    <el-button @click="deleteGoods(goodsIndex,null)" circle class="goods-delete"
                                               type="danger"
                                               icon="el-icon-close"></el-button>
                                </el-tooltip>
                                <div class="goods-pic"
                                     :style="'background-image:url('+goods.picUrl+')'"></div>
                            </div>
                        </draggable>
                        <div class="goods-add">
                            <el-button size="small" @click="showGoodsDialog(null)" icon="el-icon-plus"></el-button>
                        </div>
                    </el-form-item>
                </template>
                <hr>
                <el-form-item label="<?= \Yii::t('components/diy', '列表样式');?>" v-if="data.catPosition==='top'">
                    <app-radio turn v-model="data.listStyle" :label="-1" @change="listStyleChange"><?= \Yii::t('components/diy', '列表模式');?></app-radio>
                    <app-radio turn v-model="data.listStyle" :label="0" @change="listStyleChange"><?= \Yii::t('components/diy', '左右滑动');?></app-radio>
                    <app-radio turn v-model="data.listStyle" :label="1" @change="listStyleChange"><?= \Yii::t('components/diy', '一行一个');?></app-radio>
                    <app-radio turn v-model="data.listStyle" :label="2" @change="listStyleChange"><?= \Yii::t('components/diy', '一行两个');?></app-radio>
                    <app-radio turn v-model="data.listStyle" :label="3" @change="listStyleChange"><?= \Yii::t('components/diy', '一行三个');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '商品封面图宽高比例');?>" v-if="data.listStyle==1">
                    <app-radio turn v-model="data.goodsCoverProportion" label="1-1">1:1</app-radio>
                    <app-radio turn v-model="data.goodsCoverProportion" label="3-2">3:2</app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '商品封面图填充');?>">
                    <app-radio turn v-model="data.fill" :label="1"><?= \Yii::t('components/diy', '填充');?></app-radio>
                    <app-radio turn v-model="data.fill" :label="0"><?= \Yii::t('components/diy', '留白');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '商品样式');?>">
                    <app-radio turn v-model="data.goodsStyle" :label="1"><?= \Yii::t('components/diy', '白底无边框');?></app-radio>
                    <app-radio turn v-model="data.goodsStyle" :label="2"><?= \Yii::t('components/diy', '白底有边框');?></app-radio>
                    <app-radio v-if="false" turn v-model="data.goodsStyle" :label="3"><?= \Yii::t('components/diy', '无底无边框');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '显示商品名称');?>">
                    <el-switch v-model="data.showGoodsName"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '显示商品价格');?>">
                    <el-switch v-model="data.showGoodsPrice"></el-switch>
                </el-form-item>
                <el-form-item v-if="data.listStyle!==-1" label="<?= \Yii::t('components/diy', '文本样式');?>">
                    <app-radio turn v-model="data.textStyle" :label="1"><?= \Yii::t('components/diy', '左对齐');?></app-radio>
                    <app-radio turn v-model="data.textStyle" :label="2"><?= \Yii::t('components/diy', '居中');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '显示购买按钮');?>"
                              v-if="data.textStyle !== 2 && data.listStyle !== 0">
                    <el-switch v-model="data.showBuyBtn"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '购买按钮样式');?>" v-if="data.textStyle !== 2 && data.showBuyBtn">
                    <app-radio turn v-model="data.buyBtn" label="cart"><?= \Yii::t('components/diy', '购物车');?></app-radio>
                    <app-radio turn v-model="data.buyBtn" label="add"><?= \Yii::t('components/diy', '加号');?></app-radio>
                    <app-radio turn v-if="data.listStyle!=3" v-model="data.buyBtn" label="text"><?= \Yii::t('components/diy', '文字');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '购买按钮文字样式');?>"
                              v-if="data.textStyle !== 2 && data.showBuyBtn && data.buyBtn=='text'">
                    <app-radio turn v-model="data.buyBtnStyle" :label="1"><?= \Yii::t('components/diy', '填充');?></app-radio>
                    <app-radio turn v-model="data.buyBtnStyle" :label="2"><?= \Yii::t('components/diy', '线条');?></app-radio>
                    <app-radio turn v-model="data.buyBtnStyle" :label="3"><?= \Yii::t('components/diy', '圆角填充');?></app-radio>
                    <app-radio turn v-model="data.buyBtnStyle" :label="4"><?= \Yii::t('components/diy', '圆角线条');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '购买按钮颜色');?>"
                              v-if="data.textStyle !== 2 && data.showBuyBtn && data.buyBtn=='text'">
                    <el-color-picker v-model="data.buttonColor"></el-color-picker>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '购买按钮文字');?>"
                              v-if="data.textStyle !== 2 && data.showBuyBtn && data.buyBtn=='text'">
                    <el-input maxlength="4" size="small" v-model="data.buyBtnText"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '显示商品角标');?>">
                    <el-switch v-model="data.showGoodsTag"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '商品角标');?>" v-if="data.showGoodsTag">
                    <app-radio turn v-model="data.goodsTagPicUrl" v-for="tag in goodsTags" :label="tag.picUrl"
                               :key="tag.name"
                               @change="goodsTagChange">
                        {{tag.name}}
                    </app-radio>
                    <app-radio turn v-model="data.customizeGoodsTag" :label="true" @change="customizeGoodsTagChange">
                        <?= \Yii::t('components/diy', '自定义');?>
                    </app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '自定义商品角标');?>" v-if="data.showGoodsTag && data.customizeGoodsTag">
                    <app-image-upload v-model="data.goodsTagPicUrl" width="64" height="64"></app-image-upload>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '显示划线价');?>" v-if="[-1,0,1,2,3].indexOf(data.listStyle) != -1">
                    <el-switch v-model="data.isUnderLinePrice"></el-switch>
                </el-form-item>
                <app-padding @ss="setStyle" v-model="data">
                    <template slot="bg">
                        <el-form-item label="<?= \Yii::t('components/diy', '底部背景颜色');?>" v-if="value">
                            <el-color-picker @change="(row) => {row == null ? value.backgroundColor = '#ffffff' : ''}"
                                             size="small"
                                             v-model="value.backgroundColor"></el-color-picker>
                            <el-input size="small" class="c-input-big"
                                      v-model="value.backgroundColor"></el-input>
                        </el-form-item>
                    </template>
                </app-padding>
            </el-form>
        </div>
        <el-dialog title="<?= \Yii::t('components/diy', '选择分类');?>" :visible.sync="catDialog.visible" :close-on-click-modal="false"
                   @open="loadCatData">
            <el-table class="cat-list" :data="catDialog.list" v-loading="catDialog.loading"
                      @selection-change="catSelectionChange">
                <el-table-column label="<?= \Yii::t('components/diy', '选择');?>" type="selection"></el-table-column>
                <el-table-column label="ID" prop="id" width="100px"></el-table-column>
                <el-table-column label="<?= \Yii::t('components/diy', '名称');?>" prop="name"></el-table-column>
            </el-table>
            <div style="text-align: center">
                <el-pagination
                        v-if="catDialog.pagination"
                        style="display: inline-block;"
                        background
                        @current-change="catDialogPageChange"
                        layout="prev, pager, next, jumper"
                        :page-size.sync="catDialog.pagination.pageSize"
                        :total="catDialog.pagination.totalCount">
                </el-pagination>
            </div>
            <div slot="footer">
                <el-button @click="catDialog.visible = false"><?= \Yii::t('components/diy', '取消');?></el-button>
                <el-button type="primary" @click="addCat"><?= \Yii::t('components/diy', '确定');?></el-button>
            </div>
        </el-dialog>
        <el-dialog title="<?= \Yii::t('components/diy', '选择商品');?>" :visible.sync="goodsDialog.visible" :close-on-click-modal="false"
                   @open="loadGoodsData">
            <el-input size="mini" v-model="goodsDialog.keyword" placeholder="<?= \Yii::t('components/diy', '根据名称搜索');?>" :clearable="true"
                      @clear="goodsDialogPageChange(1)" @keyup.enter.native="goodsDialogPageChange(1)">
                <el-button slot="append" @click="goodsDialogPageChange(1)"><?= \Yii::t('components/diy', '搜索');?></el-button>
            </el-input>
            <el-table :data="goodsDialog.list" v-loading="goodsDialog.loading" @selection-change="goodsSelectionChange">
                <el-table-column label="<?= \Yii::t('components/diy', '选择');?>" type="selection"></el-table-column>
                <el-table-column label="ID" prop="id" width="100px"></el-table-column>
                <el-table-column label="<?= \Yii::t('components/diy', '名称');?>" prop="name">
                    <template slot-scope="props">
                        <div flex="cross:center dir:left">
                            <img width="50" height="50" style="margin-right: 10px" :src="props.row.cover_pic" alt="">
                            <div>{{props.row.name}}</div>
                        </div>
                    </template>
                </el-table-column>
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
                <el-button @click="goodsDialog.visible = false"><?= \Yii::t('components/diy', '取消');?></el-button>
                <el-button type="primary" @click="addGoods"><?= \Yii::t('components/diy', '确定');?></el-button>
            </div>

        </el-dialog>
    </div>
</template>
<script>
    Vue.component('diy-goods', {
        template: '#diy-goods',
        props: {
            value: Object,
            hidden: Boolean
        },
        data() {
            return {
                catDialog: {
                    visible: false,
                    page: 1,
                    loading: null,
                    pagination: null,
                    list: null,
                    selectedList: null,
                },
                goodsDialog: {
                    visible: false,
                    page: 1,
                    catIndex: null,
                    catId: null,
                    loading: null,
                    pagination: null,
                    list: null,
                    selectedList: null,
                    keyword: null
                },
                goodsTags: [
                    {
                        name: '<?= \Yii::t('components/diy', '热销');?>',
                        picUrl: "<?= $baseUrl ?>" + '/statics/img/mall/mch/weitao/goods-tag-rx.png',
                    },
                    {
                        name: '<?= \Yii::t('components/diy', '新品');?>',
                        picUrl: "<?= $baseUrl ?>" + '/statics/img/mall/mch/weitao/goods-tag-xp.png',
                    },
                    {
                        name: '<?= \Yii::t('components/diy', '折扣');?>',
                        picUrl: "<?= $baseUrl ?>" + '/statics/img/mall/mch/weitao/goods-tag-zk.png',
                    },
                    {
                        name: '<?= \Yii::t('components/diy', '推荐');?>',
                        picUrl: "<?= $baseUrl ?>" + '/statics/img/mall/mch/weitao/goods-tag-tj.png',
                    },
                ],
                data: {
                    showCat: false,
                    catPosition: 'top',
                    catStyle: 1,
                    catList: [],
                    list: [],
                    addGoodsType: 0,
                    goodsLength: 10,
                    listStyle: 1,
                    goodsCoverProportion: '1-1',
                    fill: 1,
                    goodsStyle: 1,
                    textStyle: 1,
                    showGoodsName: true,
                    showGoodsPrice: true,
                    showBuyBtn: true,
                    buyBtn: 'cart',
                    buyBtnStyle: 1,
                    buyBtnText: '<?= \Yii::t('components/diy', '购买');?>',
                    buttonColor: '#ff4544',
                    showGoodsTag: false,
                    customizeGoodsTag: false,
                    goodsTagPicUrl: "<?= $baseUrl ?>" + '/statics/img/mall/mch/weitao/goods-tag-rx.png',
                    showImg: false,
                    backgroundColor: '#fff',
                    backgroundPicUrl: '',
                    position: 5,
                    mode: 1,
                    backgroundHeight: 100,
                    backgroundWidth: 100,
                    isUnderLinePrice: true,
                    tagColor: '#FF4040',
                    catSelectedColor: '#ff4040',
                    catUnselectedColor: '#353535',
                    catBgColor: '#FFFFFF',
                    c_padding_top: 0,
                    c_padding_lr: 24,
                    c_padding_bottom: 24,
                    c_border_top: 16,
                    c_border_bottom: 16,
                    bg: '#FFFFFF',
                },
                position: 'center center',
                repeat: 'no-repeat',
                styleA: {},
                styleB: {},
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
                if (!this.data.buttonColor) {
                    this.$set(this.data, 'buttonColor', '#ff4544');
                }
            }
        },
        computed: {
            priceFlex() {
                if (!(this.data.listStyle == 1 && this.data.textStyle == 1) && this.data.listStyle != -1) {
                    return 'dir:top main:center';
                } else {
                    return 'dir:left cross:bottom';
                }
            },
            cListStyle() {
                if (this.data.backgroundColor) {
                    return `background-color:${this.data.backgroundColor};background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
                } else {
                    return `background-image:url(${this.data.backgroundPicUrl});background-size:${this.data.backgroundWidth}% ${this.data.backgroundHeight}%;background-repeat:${this.repeat};background-position:${this.position}`
                }
            },
            cMainFlex() {
                if (this.data.catPosition === 'left') {
                    return 'dir:left box:first';
                }
                if (this.data.catPosition === 'top') {
                    return 'dir:top';
                }
            },
            cCatFlex() {
                if (this.data.catPosition === 'left') {
                    return 'dir:top';
                }
                if (this.data.catPosition === 'top') {
                    return 'dir:left';
                }
            },
            cCatList() {
                if (this.data.showCat) {
                    if (this.data.catList && this.data.catList.length) {
                        return this.data.catList;
                    } else {
                        const defaultCatItem = {
                            id: 0,
                            name: '<?= \Yii::t('components/diy', '分类名称');?>',
                            menuName: '<?= \Yii::t('components/diy', '分类名称');?>',
                            goodsList: [],
                            goodsNum: 3,
                        };
                        return [defaultCatItem, defaultCatItem];
                    }
                } else {
                    return [{
                        id: null,
                        name: null,
                        menuName: null,
                        goodsList: this.data.list,
                    }];
                }
            },
            cCatStyle() {
            },
            cGoodsListStyle() {
                if (this.data.listStyle === 0) {
                    return 'flex-wrap: nowrap;overflow-x:auto;';
                } else {
                    return 'flex-wrap: wrap;';
                }
            },
            cGoodsItemFlex() {
                if (this.data.listStyle === -1) {
                    return 'dir:left box:first cross:center';
                }
                return 'dir:top';
            },
            cGoodsItemStyle() {
                let style = Object.assign({}, this.styleB,{overflow: 'none'})
                if (this.data.goodsStyle == 2) {
                    Object.assign(style, {border: '1px solid #e2e2e2', background: '#FFFFFF'});
                }
                if(this.data.goodsStyle == 3) {
                    Object.assign(style,{background: 'none'});
                }
                return style;
            },
            cShowBuyBtn() {
                if (!this.data.showBuyBtn) {
                    return false;
                }
                if (this.data.textStyle === 2 || this.data.listStyle === 0) {
                    return false;
                }
                return true;
            },
            cGoodsItemInfoStyle() {
                let style = '';
                if (this.data.textStyle === 2) {
                    style += `text-align: center;`;
                }
                if (this.data.listStyle === -1) {
                    style += `height: 256px;padding: 20px;justify-content:space-between;`;
                } else {
                    style += `padding:24px 24px;`;
                }
                return style;
            },
            cGoodsItemWidth() {
                if (this.data.listStyle === 0) {
                    return 'width: 200px;';
                }
                return 'width: 100%;';
            },
            cButtonStyle() {
                let style = `background: ${this.data.buttonColor};border-color: ${this.data.buttonColor};height:48px;line-height:46px;padding:0 20px;`;
                if (this.data.buyBtnStyle === 3 || this.data.buyBtnStyle === 4) {
                    style += `border-radius:999px;`;
                }
                if (this.data.buyBtnStyle === 2 || this.data.buyBtnStyle === 4) {
                    style += `background:#fff;color:${this.data.buttonColor}`;
                }
                return style;
            },
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            },
            'data.listStyle': {
                handler(newVal) {
                    if (newVal === 3 && this.data.buyBtn === 'text' && this.data.showBuyBtn) {
                        this.data.buyBtn = 'cart';
                    }
                }
            }
        },
        methods: {
            setStyle(styleA, styleB) {
                this.styleA = Object.assign(styleA, {
                    background: this.data.backgroundColor,
                });
                this.styleB = styleB;
            },
            cCatGoodsList(cat, catIndex) {
                const goodsList = cat.goodsList;
                let newList = [];
                if (this.data.catPosition == 'top' && catIndex > 0) {
                    newList = [];
                } else {
                    if (goodsList && goodsList.length) {
                        newList = goodsList;
                    } else {
                        const defaultGoodsItem = {
                            id: 0,
                            name: '<?= \Yii::t('components/diy', '商品名称');?>',
                            picUrl: '',
                            price: '100.00',
                            original_price: '200.00',
                        };
                        newList = new Array(cat.goodsNum).fill(defaultGoodsItem);
                    }
                }
                return newList;
            },
            goodsTagChange(e) {
                this.data.goodsTagPicUrl = e;
                this.data.customizeGoodsTag = false;
            },
            catPositionChange(e) {
                if (e === 'left') {
                    this.data.listStyle = -1;
                }
            },
            customizeGoodsTagChange(e) {
                this.data.goodsTagPicUrl = '';
                this.data.customizeGoodsTag = true;
            },
            loadCatData() {
                this.catDialog.loading = true;
                this.catDialog.selectedList = null;
                this.$request({
                    params: {
                        r: 'mall/cat/index',
                        page: this.catDialog.page,
                    }
                }).then(response => {
                    this.catDialog.loading = false;
                    if (response.data.code === 0) {
                        let list = this.transCatList(response.data.data.list);
                        let newList = [];
                        for (let i in list) {
                            if (list[i].status == 1) {
                                newList.push(list[i]);
                            }
                        }
                        this.catDialog.list = newList;
                        // this.catDialog.pagination = response.data.data.pagination;
                    } else {
                    }
                }).catch(e => {
                });
            },
            transCatList(list) {
                let newList = [];
                for (let i in list) {
                    if (list[i].child && list[i].child.length) {
                        let listJ = list[i].child;
                        delete list[i].child;
                        newList.push(list[i]);
                        for (let j in listJ) {
                            if (listJ[j].child && listJ[j].child.length) {
                                let listK = listJ[j].child;
                                delete listJ[j].child;
                                newList.push(listJ[j]);
                                for (let k in listK) {
                                    newList.push(listK[k]);
                                }
                            } else {
                                newList.push(listJ[j]);
                            }
                        }
                    } else {
                        newList.push(list[i]);
                    }
                }
                return newList;
            },
            catDialogPageChange(page) {
                this.catDialog.page = page;
                this.loadCatData();
            },
            catSelectionChange(e) {
                this.catDialog.selectedList = e;
            },
            addCat() {
                this.catDialog.visible = false;
                for (let i in this.catDialog.selectedList) {
                    this.data.catList.push({
                        id: this.catDialog.selectedList[i].id,
                        name: this.catDialog.selectedList[i].name,
                        menuName: this.catDialog.selectedList[i].name,
                        goodsNum: 30,
                        staticGoods: false,
                        goodsList: [],
                    });
                }
                this.catDialog.selectedList = null;
            },
            deleteCat(index) {
                this.data.catList.splice(index, 1);
            },
            loadGoodsData() {
                const mchId = "<?= \Yii::$app->getMchId() ?>";
                this.goodsDialog.loading = true;
                let search = {
                    keyword: this.goodsDialog.keyword
                };
                if (this.goodsDialog.catId) {
                    search.cats = [this.goodsDialog.catId]
                }
                this.$request({
                    params: {
                        r: mchId > 0 ? 'mall/goods/index' : 'mall/goods/search-goods',
                        page: this.goodsDialog.page,
                        mch_id: 0,
                        cat_id: this.goodsDialog.catId,
                        keyword: this.goodsDialog.keyword,
                        search: mchId > 0 ? JSON.stringify(search) : '',
                    },
                }).then(response => {
                    this.goodsDialog.loading = false;
                    if (response.data.code === 0) {
                        let list = response.data.data.list;
                        if (mchId > 0)
                            list = list.map(item => {
                                item = Object.assign({}, item.goodsWarehouse, item);
                                return item;
                            })
                        this.goodsDialog.list = list;
                        this.goodsDialog.pagination = response.data.data.pagination;
                    } else {
                    }
                }).catch(e => {
                });
            },
            goodsDialogPageChange(page) {
                this.goodsDialog.page = page;
                this.loadGoodsData();
            },
            showGoodsDialog(catIndex) {
                if (catIndex !== null) {
                    this.goodsDialog.catIndex = catIndex;
                    this.goodsDialog.catId = this.data.catList[catIndex].id;
                } else {
                    this.goodsDialog.catIndex = null;
                    this.goodsDialog.catId = null;
                }
                this.goodsDialog.visible = true;
            },
            goodsSelectionChange(e) {
                this.goodsDialog.selectedList = e;
            },
            addGoods() {
                this.goodsDialog.visible = false;
                for (let i in this.goodsDialog.selectedList) {
                    console.log(this.goodsDialog.selectedList[i]);
                    const item = {
                        id: this.goodsDialog.selectedList[i].id,
                        name: this.goodsDialog.selectedList[i].name,
                        picUrl: this.goodsDialog.selectedList[i].cover_pic,
                        price: this.goodsDialog.selectedList[i].price,
                        original_price: this.goodsDialog.selectedList[i].original_price,
                    };
                    if (this.goodsDialog.catIndex !== null) {
                        this.data.catList[this.goodsDialog.catIndex].goodsList.push(item);
                    } else {
                        this.data.list.push(item);
                    }
                }
            },
            deleteGoods(goodsIndex, catIndex) {
                if (catIndex !== null) {
                    this.data.catList[catIndex].goodsList.splice(goodsIndex, 1);
                } else {
                    this.data.list.splice(goodsIndex, 1);
                }
            },
            listStyleChange(listStyle) {
                if (listStyle === -1 && this.data.textStyle === 2) {
                    this.data.textStyle = 1;
                }
                if (this.data.textStyle === 2 && this.data.buyBtn === 'text') {
                    this.data.buyBtn = 'cart';
                }
                if (this.data.listStyle === 0) {
                    this.data.showBuyBtn = false;
                }
                if (listStyle === 3 || listStyle === 0) {
                    this.data.isUnderLinePrice = false;
                }
            },
            catGoodsNumChange(catIndex) {
                if (this.data.catList[catIndex].goodsNum > 30) {
                    this.data.catList[catIndex].goodsNum = 30;
                }
                if (this.data.catList[catIndex].goodsNum < 1) {
                    this.data.catList[catIndex].goodsNum = 1;
                }
            },
            showCatChange(value) {
                if (!value) {
                    this.data.catPosition = 'top';
                }
            },
        }
    });
</script>
