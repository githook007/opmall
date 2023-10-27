<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-goods-list');

Yii::$app->loadViewComponent('goods/app-add-cat');
?>
<style>

</style>
<div id="app" v-cloak>
    <app-goods-list
            :is-show-export-goods="isShowExportGoods"
            @get-all-checked="getAllChecked"
            :is_edit_goods_name='true'
            :is_add_goods='false'
            :is-goods-type="is_goods_type"
            ref="goodsList"
            :is-show-svip="isShowSvip"
            :is-show-integral="isShowIntegral"
            :is-show-update="isShowUpdate"
            :goods_url="goods_url"
            :edit_goods_url="edit_goods_url"
            :batch-list="batchList">
        <template slot="batch" slot-scope="item">
            <div v-if="item.item === catLabel">
                <el-form-item label="<?= \Yii::t('mall/goods', '修改分类');?>">
                    <el-tag style="margin-right: 5px;margin-bottom:5px" v-for="(item,index) in catsForm"
                            :key="index" type="warning" closable disable-transitions
                            @close="destroyCat(index)"
                    >{{item.label}}
                    </el-tag>
                    <app-add-cat ref="cats" :append-to-body="true" :new-cats="batchList[2].params.cat_ids"
                                 @select="selectCat"
                                 style="display: inline-block">
                        <template slot="open">
                            <el-button type="primary" size="small" style="margin: 0 5px"><?= \Yii::t('mall/goods', '选择分类');?></el-button>
                        </template>
                    </app-add-cat>
                    <el-button type="text" @click="$navigate({r:'mall/cat/edit'}, true)"><?= \Yii::t('mall/goods', '添加分类');?></el-button>
                </el-form-item>
            </div>
        </template>
    </app-goods-list>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            let catLabel = 'cats';
            return {
                catLabel,
                batchList: [
                    {
                        name: '<?= \Yii::t('mall/goods', '分类');?>',
                        key: catLabel,
                        url: 'mall/goods/batch-update-cats',
                        content: '<?= \Yii::t('mall/goods', '批量设置商品分类,是否继续');?>',
                        params: {
                            cat_ids: []
                        }
                    },
                ],
                isShowIntegral: true,
                isShowSvip: true,
                isAllChecked: false,
                isShowExportGoods: true,
                isShowUpdate: true,
                is_goods_type: true,
                catsForm: [],
                goods_url: 'plugin/supply_goods/mall/goods/index',
                edit_goods_url: 'plugin/supply_goods/mall/goods/edit',
            };
        },
        watch: {
            'catsForm'(newData) {
                let cat_ids = newData.map(item => {
                    return item.value;
                })
                Object.assign(this.batchList[2].params, {cat_ids})
            },
        },
        methods: {
            destroyCat(index) {
                this.catsForm.splice(index, 1);
            },
            selectCat(e) {
                this.catsForm = e;
            },
        },
        mounted() {}
    });
</script>
