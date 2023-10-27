<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('poster/app-style-multi-map');
?>
<style>
    .app-style-three .user {
        margin-top: 40px;
        margin-bottom: 38px;
    }

    .app-style-three .user img {
        height: 97px;
        width: 97px;
        border-radius: 50%;
        margin-left: 35px;
        display: block;
    }

    .app-style-three .user div {
        line-height: 1.2;
        color: #353535;
        margin-left: 30px;
    }

    .app-style-three .user div:after {
        content: "";
        background-repeat: no-repeat;
        background-size: 100% 100%;
        background-image: url("statics/img/mall/poster/icon/three-love.png");
        height: 24px;
        width: 24px;
        display: inline-block;
        margin-left: 8px;
    }

    .app-style-three .goods-image {
        height: 680px;
        width: 680px;
        display: block;
        margin: 0 auto;
    }

    .app-style-three .qrcode {
        width: 702px;
        border-top: 1px solid #c9c9c9;
        height: 278px;
        margin: 0 auto;
        margin-top: 24px;
    }

    .app-style-three .qrcode img {
        height: 230px;
        width: 230px;
    }

    .app-style-three .qrcode div {
        margin-top: 20px;
        margin-left: 24px;
        font-size: 28px;
        color: #353535;
    }

    .app-style-three .goods-name {
        font-size: 34px;
        padding-top: 28px;
        color: #353535;
    }

    .app-style-three .price {
        padding-top: 28px;
        color: #ff4544;
    }
</style>
<template id="app-style-three">
    <div class="app-style-three">
        <div class="user" flex="dir:left cross:center">
            <img src="statics/img/mall/poster/default_head.png"></img>
            <div>
                <?= \Yii::t('components/poster', '我看上了这款商品');?> <br>
                <?= \Yii::t('components/poster', '帮我看看咋样啊');?>~ <br>
                <?= \Yii::t('components/poster', '比心');?>
            </div>
        </div>
        <div class="goods-image">
            <app-style-multi-map v-model="typesetting"></app-style-multi-map>
        </div>
        <div flex="dir:top cross:center">
            <div class="goods-name"><?= \Yii::t('components/poster', '商品名称');?>|<?= \Yii::t('components/poster', '商品名称');?></div>
            <div class="price" flex="dir:left cross:bottom">
                <div style="font-size:32px;line-height: 1">￥</div>
                <div style="font-size:56px;line-height: 1">160</div>
            </div>
        </div>
        <div class="qrcode" flex="dir:left cross:center main:center">
            <img src="statics/img/mall/poster/default_qr_code.png" alt="">
            <div><?= \Yii::t('components/poster', '长按识别小程序码');?></div>
        </div>
    </div>
</template>

<script>
    Vue.component('app-style-three', {
        template: '#app-style-three',
        props: {
            typesetting: {
                type: Number,
                default: 3
            },
        },
        data() {
            return {};
        },
        methods: {},
    });
</script>
