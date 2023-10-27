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
                          @click="$navigate({r:'mall/goods/index'})">
                        <?= \Yii::t('mall/goods', '商品列表');?>
                    </span>
                </el-breadcrumb-item>
                <el-breadcrumb-item v-if="form.goods_id > 0"><?= \Yii::t('mall/goods', '详情');?></el-breadcrumb-item>
                <el-breadcrumb-item v-else><?= \Yii::t('mall/goods', '添加商品');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods :is_member="is_mch ? 0 : 1"
                   :is_cats="1"
                   :is_video_channel="is_mch ? 0 : 1"
                   :is_show="1"
                   :is_info="1"
                   :form="form"
                   :is_edit="is_edit"
                   :is_detail="1"
                   :is_mch="is_mch"
                   :mch_id="mch_id"
                   :referrer="url"
                   :is_display_setting="0"
                   sign="mall"
                   :is_e_card="1"
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
                url: 'mall/goods/index',
                is_mch: <?= $mchId > 0 ? 1 : 0 ?>,
                mch_id: <?= $mchId ?>,
                is_edit: 0,
            }
        },
        created() {
            if(getQuery('page') > 1) {
                this.url = {
                    r: 'mall/goods/index',
                    page: getQuery('page')
                }
            }
            if (getQuery('id')) {
                this.is_edit = 1;
            }
        },

    });
</script>
