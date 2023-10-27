<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-poster');
?>

<div id="app" v-cloak>
    <app-poster request_url="plugin/bargain/mall/poster/index"
                submit_url="plugin/bargain/mall/poster/save"
                :goods_component="goodsComponent"
                :goods_component_key_tmp="goodsComponentKey"
    ></app-poster>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                goodsComponent: [
                    {
                        key: 'head',
                        icon_url: 'statics/img/mall/poster/icon_head.png',
                        title: "<?= \Yii::t('plugins/bargain', '头像');?>",
                        is_active: true
                    },
                    {
                        key: 'nickname',
                        icon_url: 'statics/img/mall/poster/icon_nickname.png',
                        title: "<?= \Yii::t('plugins/bargain', '昵称');?>",
                        is_active: true
                    },
                    {
                        key: 'pic',
                        icon_url: 'statics/img/mall/poster/icon_pic.png',
                        title: "<?= \Yii::t('plugins/bargain', '商品图片');?>",
                        is_active: true
                    },
                    {
                        key: 'name',
                        icon_url: 'statics/img/mall/poster/icon_name.png',
                        title: "<?= \Yii::t('plugins/bargain', '商品名称');?>",
                        is_active: true
                    },
                    {
                        key: 'price',
                        icon_url: 'statics/img/mall/poster/icon_price.png',
                        title: "<?= \Yii::t('plugins/bargain', '商品价格');?>",
                        is_active: true
                    },
                    {
                        key: 'desc',
                        icon_url: 'statics/img/mall/poster/icon_desc.png',
                        title: "<?= \Yii::t('plugins/bargain', '海报描述');?>",
                        is_active: true
                    },
                    {
                        key: 'qr_code',
                        icon_url: 'statics/img/mall/poster/icon_qr_code.png',
                        title: "<?= \Yii::t('plugins/bargain', '二维码');?>",
                        is_active: true
                    },
                    {
                        key: 'poster_bg',
                        icon_url: 'statics/img/mall/poster/icon-mark.png',
                        title: "<?= \Yii::t('plugins/bargain', '标识');?>",
                        is_active: true
                    },
                    {
                        key: 'time_str',
                        icon_url: 'statics/img/mall/poster/icon_time.png',
                        title: "<?= \Yii::t('plugins/bargain', '时间');?>",
                        is_active: true
                    },
                ],
                goodsComponentKey: 'head',
            }
        },

        methods: {

        }
    })
</script>