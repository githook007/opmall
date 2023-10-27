<?php defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-goods');
?>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" style="justify-content:space-between;display: flex">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                          @click="$navigate({r:'plugin/lottery/mall/lottery/index'})"><?= \Yii::t('plugins/lottery', '商品管理');?></span>
                </el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('plugins/lottery', '添加商品');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods ref="appGoods"
                   :is_price="1"
                   :is_member="0"
                   :is_attr="0"
                   :rule="FormRules"
                   :is_show="0"
                   :is_detail="0"
                   :is_share="0"
                   :is_marketing="0"
                   :is_goods="0"
                   :is_sharing_setting="1"
                   :has_goods_param="false"
                   :is_display_setting="0"
                   :is_purchase_settings="0"
                   :is_cats="0"
                   :form="form"
                   :preview-info="previewInfo"
                   @handle-preview="handlePreview"
                   url="plugin/lottery/mall/lottery/edit"
                   get_goods_url="plugin/lottery/mall/lottery/edit"
                   referrer="plugin/lottery/mall/lottery/index">
            <template slot="before_attr">
                <el-card shadow="never" class="mt-24">
                    <div slot="header">
                        <span><?= \Yii::t('plugins/lottery', '自定义设置');?></span>
                    </div>
                    <el-row>
                        <el-col :xl="12" :lg="16">
                            <el-form-item label="<?= \Yii::t('plugins/lottery', '抽奖时间');?>" prop="time">
                                <el-date-picker v-model="form.time" unlink-panels type="datetimerange" size="small"
                                                value-format="yyyy-MM-dd HH:mm:ss" range-separator="至"
                                                start-placeholder="<?= \Yii::t('plugins/lottery', '开始日期');?>" end-placeholder="<?= \Yii::t('plugins/lottery', '结束日期');?>"></el-date-picker>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/lottery', '奖品数量');?>" prop="stock">
                                <el-input type="number" min="1" v-model.number="form.stock"
                                          autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item prop="join_min_num">
                                <template slot='label'>
                                    <span><?= \Yii::t('plugins/lottery', '开奖最低限制');?></span>
                                    <el-tooltip effect="dark" content="<?= \Yii::t('plugins/lottery', '值必须大于等于0');?>"
                                                placement="top">
                                        <i class="el-icon-info"></i>
                                    </el-tooltip>
                                </template>
                                <el-input type="number" min="0" v-model.number="form.join_min_num"
                                          placeholder="<?= \Yii::t('plugins/lottery', '参与人数少于该限制');?>"
                                          autocomplete="on"></el-input>
                            </el-form-item>
                            <el-form-item prop="deplete_integral_num" label="<?= \Yii::t('plugins/lottery', '消耗积分');?>">
                                <el-input type="number" min="1"
                                          oninput="this.value = this.value.match(/^\d{0,8}/g)"
                                          v-model.number="form.deplete_integral_num"
                                          autocomplete="off">
                                    <template slot="append"><?= \Yii::t('plugins/lottery', '积分');?></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/lottery', '状态');?>" prop="status">
                                <el-radio-group v-model="form.status">
                                    <el-radio :label="0"><?= \Yii::t('plugins/lottery', '关闭');?></el-radio>
                                    <el-radio :label="1"><?= \Yii::t('plugins/lottery', '开启');?></el-radio>
                                </el-radio-group>
                            </el-form-item>
                        </el-col>
                    </el-row>
                </el-card>
            </template>
            <template slot="preview">
                <div v-if="previewData" flex="dir:top">
                    <el-image style="height:28px;width:170px;position: relative;left:97px;top:-65px;z-index:9"
                              src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/img/goods-end.png"></el-image>
                    <div class="goods" style="margin-top:-28px">
                        <div class="goods-name">{{previewData.name}}</div>
                        <div flex="dir:left" style="font-size:14px">
                            <div flex="dir:left" style="font-size: 18px;color:#ff4544;">
                                <div flex="dir:top" style="font-size: 14px;margin-top:6px;color:#999999">
                                    <div flex="dir:left">
                                        <div flex="dir:left" style="margin-right: 6px">
                                            <div><?= \Yii::t('plugins/lottery', '共');?></div>
                                            <div style="font-size: 16px;color:#ff4544">{{form.stock}}</div>
                                            <div><?= \Yii::t('plugins/lottery', '份');?></div>
                                        </div>
                                        <div><?= \Yii::t('plugins/lottery', '100人参与');?></div>
                                    </div>
                                    <div flex="dir:left" style="margin-top: 10px">
                                        <div style="color:#ff4544;margin-right: 6px">￥0</div>
                                        <div style="text-decoration: line-through;"><?= \Yii::t('plugins/lottery', '原价￥');?>{{previewData.original_price}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="share" flex="dir:top main:center cross:center">
                                <el-image src="statics/img/mall/goods/icon-share.png"></el-image>
                                <div><?= \Yii::t('plugins/lottery', '分享');?></div>
                            </div>
                        </div>
                    </div>
                    <el-image style="height:169px;margin-top:12px"
                              src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/img/6565.png"></el-image>
                </div>
            </template>
        </app-goods>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                previewData: null,
                previewInfo: {
                    is_head: false,
                    is_cart: false,
                    is_attr: false,
                    is_content: false,
                },
                form: {
                    time: '',
                    stock: 0,
                    status: 0,
                    use_attr: 0,
                    join_min_num: 0,
                    sort: 0,
                    deplete_integral_num: 0,
                },
                listLoading: false,
                btnLoading: false,
                keyword: '',
                attr_list: [],
                FormRules: {
                    attr_id: [
                        {required: true, message: '<?= \Yii::t('plugins/lottery', '商品规格不能为空');?>', trigger: 'blur'},
                    ],
                    time: [
                        {required: true, message: '<?= \Yii::t('plugins/lottery', '抽奖时间不能为空');?>', trigger: 'blur'},
                    ],
                    stock: [
                        {required: true, message: '<?= \Yii::t('plugins/lottery', '奖品数量不能为空');?>', trigger: 'blur'},
                        {type: 'number', min: 1, message: '<?= \Yii::t('plugins/lottery', '奖品数量不能小于1');?>'}
                    ],
                    join_min_num: [
                        {type: 'number', min: 0, message: '<?= \Yii::t('plugins/lottery', '开奖数量不能小于0');?>'}
                    ],
                    deplete_integral_num: [
                        //  {type: 'number', min: 0, message: 'x'}
                    ],
                    status: [
                        {required: true, message: '<?= \Yii::t('plugins/lottery', '状态不能为空');?>', trigger: 'blur'},
                    ],
                },
            };
        },
        methods: {
            handlePreview(e) {
                console.log(e, this.form);
                this.previewData = e;
            },
            //old
            changeAttr() {
                this.$refs.form.validateField('attr_id');
            },
            //搜索
            querySearchAsync(queryString, cb) {
                this.keyword = queryString;
                this.clerkUser(cb);
            },

            clerkClick(row) {
                this.form.attr_id = null;
                this.attr_list = row.attr;
            },

            clerkUser(cb) {
                request({
                    params: {
                        r: 'plugin/lottery/mall/lottery/search',
                        keyword: this.keyword,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        cb(e.data.data);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },

            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        this.form.start_at = this.form.time[0];
                        this.form.end_at = this.form.time[1];
                        let para = Object.assign({}, this.form);
                        request({
                            params: {
                                r: 'plugin/lottery/mall/lottery/edit',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            if (e.data.code === 0) {
                                navigateTo({r: 'plugin/lottery/mall/lottery'});
                            } else {
                                this.$message.error(e.data.msg);
                            }
                            this.btnLoading = false;
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
        },

        mounted() {
        }
    })
</script>