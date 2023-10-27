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
                          @click="$navigate({r:'plugin/pick/mall/activity/index'})">
                        <?= \Yii::t('plugins/pick', 'N元任选活动');?>
                    </span>
                </el-breadcrumb-item>
                <el-breadcrumb-item v-if="form.goods_id > 0"><?= \Yii::t('plugins/pick', '详情');?></el-breadcrumb-item>
                <el-breadcrumb-item v-else><?= \Yii::t('plugins/pick', '商品编辑');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods
                :is_show="0"
                :is_info="0"
                :form="form"
                :is_mch="is_mch"
                :mch_id="mch_id"
                :referrer="url"
                :is_member="0"
                :is_video_url="0"
                :is_original_price="0"
                :is_copy_id="0"
                :is_cats="0"
                :is_name="0"
                :is_pic_url="0"
                :is_forehead_integral="0"
                :is_detail="0"
                sign="pick"
                :is_show="0"
                :is_display_setting="0"
                :is_product_info="0"
                url="plugin/pick/mall/activity/edit-activity-goods"
                get_goods_url="plugin/pick/mall/activity/edit-activity-goods"
                referrer="plugin/pick/mall/activity/index"
                ref="appGoods">
        </app-goods>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {},
                url: 'plugin/pick/mall/activity/edit-activity-goods',
                is_mch: <?= $mchId > 0 ? 1 : 0 ?>,
                mch_id: <?= $mchId ?>,
            }
        },
        created() {
            if(getQuery('page') > 1) {
                this.url = {
                    r: 'plugin/pick/mall/activity/edit-activity-goods',
                    page: getQuery('page')
                }
            }
        },

    });
</script>
