<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-goods');
?>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span style="color: #409EFF;cursor: pointer"
                          @click="$navigate({r:'mall/goods/index'})">
                        商品列表
                    </span>
                </el-breadcrumb-item>
                <el-breadcrumb-item>详情</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods :is_cats="1"
                   :is_show="1"
                   :is_info="1"
                   :form="form"
                   :is_edit="is_edit"
                   :is_detail="1"
                   :referrer="referrer"
                   :get_goods_url="url"
                   :url="url"
                   :is_display_setting="0"
                   sign=""
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
                referrer: 'plugin/supply_goods/mall/goods/index',
                url: 'plugin/supply_goods/mall/goods/edit',
                is_edit: 0,
            }
        },
        created() {
            if(getQuery('page') > 1) {
                this.referrer = {
                    r: 'plugin/supply_goods/mall/goods/index',
                    page: getQuery('page')
                }
            }
            if (getQuery('id')) {
                this.is_edit = 1;
            }
        },

    });
</script>
