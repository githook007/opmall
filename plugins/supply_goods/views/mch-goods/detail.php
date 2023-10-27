<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

//Yii::$app->loadViewComponent('app-goods', __DIR__."/../");
Yii::$app->loadViewComponent('app-goods');
?>
<style>
    /* STOP */
    #pane-first,#pane-second {
        pointer-events: none !important;
    }
    .el-switch {
        opacity: .6;
    }
    /* input hidden */
    .el-input__inner, .el-textarea__inner {
        background-color: #F5F7FA;
        border-color: #E4E7ED;
        color: #C0C4CC;
    }

    .el-input .el-input__count .el-input__count-inner,.el-textarea .el-input__count {
        background-color: #F5F7FA;
    }

    .el-form-item.is-success .el-input__inner, .el-form-item.is-success .el-input__inner:focus, .el-form-item.is-success .el-textarea__inner, .el-form-item.is-success .el-textarea__inner:focus {
        border-color: #E4E7ED
    }
    /* preview */
    .el-dialog__footer {
        opacity: 0;
    }
</style>
<style>
    .noInput .el-input__inner{
        pointer-events: all;
        background-color: #fff !important;
        color: #000 !important;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span style="color: #409EFF;cursor: pointer"
                          @click="$navigate({r:'plugin/mch/mall/goods/index'})">
                        商品管理
                    </span>
                </el-breadcrumb-item>
                <el-breadcrumb-item>详情</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
<!--        :no_price="0"-->
        <app-goods :is_member="0"
                   :is_cats="1"
                   :is_show="1"
                   :is_info="1"
                   :form="form"
                   :rule="rule"
                   :is_detail="1"
                   :is_mch_button="1"
                   :is_display_setting="0"
                   :referrer="referrer"
                   :is_share="0"
                   @goods-success="goodsSuccess"
                   sign=""
                   :is_marketing="0"
                   price_label="零售价"
                   cost_price_label="拿货价"
                   :get_goods_url="'plugin/supply_goods/mall/mch-goods/goods-detail/&mch_id=' + mch_id"
                   :url="url"
                   ref="appGoods">
            <template slot="before_price">
<!--                <el-form-item label="售价" class="noInput">-->
<!--                    <el-input type="number"-->
<!--                              :min="0"-->
<!--                              v-model="form.price">-->
<!--                        <template slot="append">元</template>-->
<!--                    </el-input>-->
<!--                </el-form-item>-->
            </template>
        </app-goods>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {
                    price: 0,
                    //extra: {
                    //    price23: "<?//= \Yii::t('components/goods', '价格3');?>//",
                    //},
                },
                url: 'plugin/supply_goods/mall/goods/save',
                referrer: 'plugin/supply_goods/mall/goods/index',
                mch_id: parseInt(getQuery('mch_id')),
                rule: {
                    price: [
                        {required: true, message: '请输入商品价格', trigger: 'change'}
                    ],
                },
            }
        },
        created() {},
        mounted(){},
        methods: {
            goodsSuccess(detail) {
                this.form.price = detail.price;
            },
        }
    });
</script>
