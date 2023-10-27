<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-goods');
$mchId = Yii::$app->user->identity->mch_id;
?>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span style="color: #409EFF;cursor: pointer"
                          @click="$navigate({r:'plugin/flash_sale/mall/activity/index'})">
                        <?= \Yii::t('plugins/flash-sale', '限时抢购');?>
                    </span>
                </el-breadcrumb-item>
                <el-breadcrumb-item v-if="goods_id > 0"><?= \Yii::t('plugins/flash-sale', '详情');?></el-breadcrumb-item>
                <el-breadcrumb-item v-else><?= \Yii::t('plugins/flash-sale', '商品编辑');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods
                :is_show="0"
                :is_info="0"
                :is_mch="is_mch"
                :mch_id="mch_id"
                :is_member="1"
                :is_video_url="0"
                :is_original_price="0"
                :is_copy_id="0"
                :is_cats="0"
                :is_name="0"
                :is_pic_url="0"
                :is_detail="0"
                sign="flash_sale"
                :is_show="0"
                :is_display_setting="0"
                :is_product_info="0"
                url="plugin/flash_sale/mall/activity/edit-activity-goods"
                get_goods_url="plugin/flash_sale/mall/activity/edit-activity-goods"
                :referrer="url"
                ref="appGoods">
            <template slot="member_route_setting">
                 <span class="red"><?= \Yii::t('plugins/flash-sale', '必须在');?>
                    <el-button type="text" @click="$navigate({r: 'plugin/flash_sale/mall/setting'}, true)"><?= \Yii::t('plugins/flash-sale', '限时抢购设置');?></el-button>
                    <?= \Yii::t('plugins/flash-sale', '中开启');?>
                </span>
            </template>
        </app-goods>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                url: '',
                is_mch: <?= $mchId > 0 ? 1 : 0 ?>,
                mch_id: <?= $mchId ?>,
                goods_id: null
            }
        },
        created() {
            if(getQuery('page') > 1) {
                this.url = {
                    r: 'plugin/flash_sale/mall/activity/edit-activity-goods',
                    page: getQuery('page')
                }
            }
            this.url = {
                r: 'plugin/flash_sale/mall/activity/edit',
                id: getQuery('activity_id'),
                edit: getQuery('edit')
            }
        },

    });
</script>
