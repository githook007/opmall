<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('pick-link/app-article');
Yii::$app->loadViewComponent('pick-link/app-live');
Yii::$app->loadViewComponent('pick-link/app-goods-detail');
Yii::$app->loadViewComponent('pick-link/app-topic-type');
Yii::$app->loadViewComponent('pick-link/app-topic');
Yii::$app->loadViewComponent('pick-link/app-store');
Yii::$app->loadViewComponent('pick-link/app-coupon');
Yii::$app->loadViewComponent('pick-link/app-pintuan');
Yii::$app->loadViewComponent('pick-link/app-link-integral-mall');
Yii::$app->loadViewComponent('pick-link/app-link-step');
Yii::$app->loadViewComponent('pick-link/app-link-mch');
Yii::$app->loadViewComponent('pick-link/app-link-mch-goods');
Yii::$app->loadViewComponent('pick-link/app-link-lottery');
Yii::$app->loadViewComponent('pick-link/app-link-booking');
Yii::$app->loadViewComponent('pick-link/app-link-bargain');
Yii::$app->loadViewComponent('pick-link/app-link-composition');
Yii::$app->loadViewComponent('pick-link/app-link-advance');
Yii::$app->loadViewComponent('pick-link/app-link-flash-sale');
Yii::$app->loadViewComponent('pick-link/app-link-exchange');
Yii::$app->loadViewComponent('pick-link/app-link-wholesale');
Yii::$app->loadViewComponent('pick-link/app-show-link');
Yii::$app->loadViewComponent('pick-link/app-link-cats');
Yii::$app->loadViewComponent('pick-link/app-link-miaosha');
?>

<style>
    .app-pick-link .el-checkbox + .el-checkbox {
        margin-left: 0;
    }

    .app-pick-link .checkbox-div-box {
        height: 350px;
        overflow: auto;
    }

    .app-pick-link .edit-img {
        width: 18px;
        height: 18px;
        display: inline-block;
        margin-left: 10px;
        cursor: pointer;
    }

    .app-pick-link .el-dialog {
        width: 800px;
    }

    .app-pick-link .el-checkbox {
        margin-right: 0;
        height: 32px;
    }

    .app-pick-link .el-checkbox__input {
        margin-top: 4px;
    }
</style>

<template id="app-pick-link">
    <div>
        <el-dialog class="app-pick-link" :title="title ? title : '<?= \Yii::t('components/other', '选择链接');?>'"
                   :visible.sync="dialogFormVisible"
                   @opened="dialogOpened"
                   :close-on-click-modal="false"
                   append-to-body>
            <div style="margin-bottom: 10px;">
                <el-input @keyup.enter.native='search' size="small" placeholder="<?= \Yii::t('components/other', '请输入名称搜索');?>"
                          v-model="keyword" clearable @clear="search">
                    <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
                </el-input>
            </div>
            <el-form v-loading="loading" :rules="form_rules" size="small" @submit.native.prevent label-width="60px">
                <div flex="main:justify">
                    <div style="min-width: 330px">
                        <el-card class="box-card" shadow="never">
                            <el-tabs v-model="activeName" @tab-click="handleClick">
                                <el-tab-pane v-if="options.base && options.base.length > 0" label="<?= \Yii::t('components/other', '基础');?>" name="base">
                                    <div class="checkbox-div-box">
                                        <el-checkbox-group v-model="checkedCities">
                                            <div flex="dir:left cross:center" v-for="(item, key) in options.base"
                                                 :key="item.id">
                                                <el-checkbox flex="dir:left cross:center" @change="selectChecked(item,key)"
                                                             :label="item.value">
                                                    <div flex="cross:center">
                                                        <img style="width: 18px;height: 18px;margin-right: 5px"
                                                             :src="item.icon">
                                                        {{item.name}}
                                                    </div>
                                                </el-checkbox>
                                                <img class="edit-img"
                                                     v-if="isShowParamsButton(item)"
                                                     @click="pickLinkEdit(item)"
                                                     src="statics/img/mall/icon_pick_link_edit.png">
                                            </div>
                                        </el-checkbox-group>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane v-if="options.order && options.order.length > 0" label="<?= \Yii::t('components/other', '订单');?>" name="order">
                                    <div class="checkbox-div-box">
                                        <el-checkbox-group v-model="checkedCities">
                                            <div flex="dir:left cross:center" v-for="(item, key) in options.order"
                                                 :key="item.id">
                                                <el-checkbox flex="dir:left cross:center" @change="selectChecked(item,key)"
                                                             :label="item.value">
                                                    <div flex="cross:center" style="margin-top: 1px">
                                                        <img style="width: 18px;height: 18px;margin-right: 5px"
                                                             :src="item.icon">
                                                        {{item.name}}
                                                    </div>
                                                </el-checkbox>
                                                <img class="edit-img"
                                                     v-if="isShowParamsButton(item)"
                                                     @click="pickLinkEdit(item)"
                                                     src="statics/img/mall/icon_pick_link_edit.png">
                                            </div>
                                        </el-checkbox-group>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane v-if="options.marketing && options.marketing.length > 0" label="<?= \Yii::t('components/other', '营销');?>" name="marketing">
                                    <div class="checkbox-div-box">
                                        <el-checkbox-group v-model="checkedCities">
                                            <div flex="dir:left cross:center" v-for="(item, key) in options.marketing"
                                                 :key="item.id">
                                                <el-checkbox flex="dir:left cross:center" @change="selectChecked(item,key)"
                                                             :label="item.value">
                                                    <div flex="cross:center" style="margin-top: 1px">
                                                        <img style="width: 18px;height: 18px;margin-right: 5px"
                                                             :src="item.icon">
                                                        {{item.name}}
                                                    </div>
                                                </el-checkbox>
                                                <img class="edit-img"
                                                     v-if="isShowParamsButton(item)"
                                                     @click="pickLinkEdit(item)"
                                                     src="statics/img/mall/icon_pick_link_edit.png">
                                            </div>
                                        </el-checkbox-group>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane v-if="options.plugin && options.plugin.length > 0" label="<?= \Yii::t('components/other', '插件');?>" name="plugin">
                                    <div class="checkbox-div-box">
                                        <el-checkbox-group v-model="checkedCities">
                                            <div flex="dir:left cross:center" v-for="(item, key) in options.plugin"
                                                 :key="item.id">
                                                <el-checkbox flex="dir:left cross:center" @change="selectChecked(item,key)"
                                                             :label="item.value">
                                                    <div flex="cross:center" style="margin-top: 1px">
                                                        <img style="width: 18px;height: 18px;margin-right: 5px"
                                                             :src="item.icon">
                                                        {{item.name}}
                                                    </div>
                                                </el-checkbox>
                                                <img class="edit-img"
                                                     v-if="isShowParamsButton(item)"
                                                     @click="pickLinkEdit(item)"
                                                     src="statics/img/mall/icon_pick_link_edit.png">
                                            </div>
                                        </el-checkbox-group>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane v-if="options.diy && options.diy.length > 0" label="<?= \Yii::t('components/other', '微页面');?>" name="diy" v-if="options.diy">
                                    <div class="checkbox-div-box">
                                        <el-checkbox-group v-model="checkedCities">
                                            <div flex="dir:left cross:center" v-for="(item, key) in options.diy"
                                                 :key="key">
                                                <el-checkbox flex="dir:left cross:center" @change="selectChecked(item,key)"
                                                             :label="item.value">
                                                    <div flex="cross:center" style="margin-top: 1px">
                                                        <img style="width: 18px;height: 18px;margin-right: 5px"
                                                             :src="item.icon">
                                                        {{item.name}}
                                                    </div>
                                                </el-checkbox>
                                                <img class="edit-img"
                                                     v-if="isShowParamsButton(item)"
                                                     @click="pickLinkEdit(item)"
                                                     src="statics/img/mall/icon_pick_link_edit.png">
                                            </div>
                                        </el-checkbox-group>
                                    </div>
                                    <div v-if="loadText" flex="main:center cross:center" style="padding: 20px 0;">{{loadText}}</div>
                                </el-tab-pane>
                            </el-tabs>
                        </el-card>
                    </div>
                    <div v-if="isShowParams">
                        <el-card shadow="never" style="width: 420px;height: 100%;">
                            <div slot="header">
                                <span>{{currentCheckedItem.name}}</span>
                            </div>
                            <el-form @submit.native.prevent label-width="130px">
                                <template v-if="currentCheckedItem.value === '/pages/article/article-detail/article-detail'">
                                    <el-form-item label="<?= \Yii::t('components/other', '标题');?>" required>
                                        <app-article @confirm="customer"></app-article>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '直播详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '房间名');?>" required>
                                        <app-live @confirm="customer"></app-live>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-goods-detail @confirm="customer"></app-goods-detail>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '专题列表');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '标签名');?>">
                                        <app-topic-type @confirm="customer"></app-topic-type>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '专题详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '标签名');?>" required>
                                        <app-topic @confirm="customer"></app-topic>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '门店详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '门店名称');?>" required>
                                        <app-store @confirm="customer"></app-store>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '优惠券详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '优惠券名称');?>" required>
                                        <app-coupon @confirm="customer"></app-coupon>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/pintuan', '拼团商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-pintuan @confirm="customer"></app-pintuan>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/integral_mall', '积分商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-integral-mall @confirm="customer"></app-link-integral-mall>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/step', '步数宝商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-step @confirm="customer"></app-link-step>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/mch', '多商户店铺');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '店铺名称');?>" required>
                                        <app-link-mch @confirm="customer"></app-link-mch>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/mch', '多商户商品');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-mch-goods @confirm="customer"></app-link-mch-goods>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/lottery', '抽奖商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-lottery @confirm="customer"></app-link-lottery>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/booking', '预约商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-booking @confirm="customer"></app-link-booking>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/bargain', '砍价商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-bargain @confirm="customer"></app-link-bargain>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/composition', '套餐详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '套餐名称');?>" required>
                                        <app-link-composition @confirm="customer"></app-link-composition>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/advance', '预售商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-advance @confirm="customer"></app-link-advance>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/flash-sale', '限时抢购详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-flash-sale @confirm="customer"></app-link-flash-sale>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/exchange', '礼品卡详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '礼品卡名称');?>" required>
                                        <app-link-exchange @confirm="customer"></app-link-exchange>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/wholesale', '批发商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '商品名称');?>" required>
                                        <app-link-wholesale @confirm="customer"></app-link-wholesale>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '分类');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '选择分类');?>">
                                        <app-link-cats @confirm="customer" type="cat"></app-link-cats>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('common', '商品列表');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '选择分类');?>">
                                        <app-link-cats @confirm="customer" type="goods"></app-link-cats>
                                    </el-form-item>
                                </template>
                                <template v-else-if="currentCheckedItem.name === '<?= \Yii::t('plugins/miaosha', '秒杀商品详情');?>'">
                                    <el-form-item label="<?= \Yii::t('components/other', '选择商品');?>" required>
                                        <app-link-miaosha @confirm="customer"></app-link-miaosha>
                                    </el-form-item>
                                </template>
                                <template v-else>
                                    <el-form-item v-if="!item.is_show && item.is_show != false" style="margin-bottom: 0" v-for="item in currentCheckedItem.params"
                                                  :key="item.key" :prop="item.is_required ? 'key_name' : ''">
                                        <template slot='label'>
                                            <span>{{item.key}}</span>
                                            <el-tooltip v-if="item.desc" effect="dark" :content="item.desc"
                                                        placement="top">
                                                <i class="el-icon-info"></i>
                                            </el-tooltip>
                                        </template>
                                        <el-input size="small" :type="item.data_type ? item.data_type : ''"
                                                  v-model="item.value"
                                                  :placeholder="item.desc">
                                        </el-input>
                                        <span v-if="item.page_url">
                                            <?= \Yii::t('components/other', '所需数据请到');?>“<el-button type="text" @click="$navigate({r:item.page_url}, true)">
                                                {{item.page_url_text}}
                                            </el-button>”<?= \Yii::t('components/other', '查看');?>
                                        </span>
                                    </el-form-item>
                                </template>
                            </el-form>
                            <div style="margin: 0 20px 10px" v-if="currentCheckedItem.remark">
                                <div style="color: #ff4544;">{{currentCheckedItem.remark}}</div>
                            </div>
                            <div v-if="item.pic_url && item.is_show != false" v-for="item in currentCheckedItem.params">
                                <div style="margin: 15px 0 10px 10px;"><?= \Yii::t('components/other', '示例');?>:</div>
                                <img style="width: 400px;" :src="item.pic_url" alt="">
                            </div>
                        </el-card>
                    </div>
                </div>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false"><?= \Yii::t('components/other', '取消');?></el-button>
                <el-button type="primary" @click="confirm"><?= \Yii::t('components/other', '确定');?></el-button>
            </div>
        </el-dialog>
        <template v-if="showCustomer">
            <div @click="dialogFormVisible = !dialogFormVisible" style="display: inline-block">
                <slot></slot>
            </div>
        </template>
        <template v-else>
            <app-show-link :link="link">
                <el-button style="background-color: #F5F7FA" @click="dialogFormVisible = !dialogFormVisible" size="mini"><?= \Yii::t('components/other', '选择链接');?></el-button>
            </app-show-link>
        </template>
    </div>
</template>
<script>
    Vue.component('app-pick-link', {
        template: '#app-pick-link',
        props: {
            mallId: {
                default: null,
            },
            title: String,
            type: {
                type: String,
                default: 'single',// single|单个,multiple|多个
            },
            params: Object,
            ignore: String, // navigate|导航底栏
            use: String, // navigate|导航底栏
            showCustomer: {
                type: Boolean,
                default: true
            },
            link: Object,
        },
        data() {
            return {
                loadText: '',
                dialogFormVisible: false,
                loading: true,
                options: [],
                activeName: 'base',
                keyword: '',
                keywords: '',
                currentCheckedItem: {},//当前选择链接
                checkedList: [],// 全部选中链接
                checkedCities: [],
                form_rules: {
                    key_name: [
                        {required: true, message: '<?= \Yii::t('components/other', '请输入名称');?>', trigger: 'change'},
                    ],
                },
                page: 1,
                load: false
            };
        },
        created() {
        },
        computed: {
            isShowParams() {
                let self = this;
                let num = 0;
                if (self.currentCheckedItem.params) {
                    self.currentCheckedItem.params.forEach(function(item) {
                        if (item.is_show != false) {
                            num += 1;
                        }
                    })
                }
                if (num && self.currentCheckedItem.params && self.currentCheckedItem.params.length > 0 || self.currentCheckedItem.remark) {
                    return true;
                } else {
                    return false;
                }
            }
        },
        methods: {
            dialogOpened() {
                this.currentCheckedItem = {};
                this.checkedList = [];
                this.checkedCities = [];
                if (this.options.length == 0) {
                    this.loadList({})
                }
            },
            search() {
                this.loadList();
            },

            loadList() {
                this.loading = true;
                let url = 'mall/link/index';
                if (this.ignore == 'admin_copyright') {
                    url = 'admin/setting/copyright-link'
                }
                const params = {
                    r: url,
                    type: this.type,
                    keyword: this.keyword,
                    ignore: this.ignore,
                    use: this.use,
                };
                if (this.mallId) {
                    params.mall_id = this.mallId;
                }
                request({
                    params: params,
                }).then(e => {
                    if (e.data.code === 0) {
                        this.options = e.data.data.list;
                        this.loading = false;
                        if(e.data.data.name){
                            this.activeName = e.data.data.name
                        }
                    } else {
                        this.$message.error(e.data.msg);
                        this.dialogFormVisible = false;
                    }
                }).catch();
            },
            confirm() {
                let self = this;
                let sign = true;
                self.checkedList.forEach(function (cItem, cIndex) {
                    if (cItem.params) {
                        let params = '';
                        // 拼接路由参数
                        cItem.params.forEach(function (pItem, pIndex) {
                            if (!pItem.value && pItem.is_required === true) {
                                sign = false;
                                self.$message.error(cItem.name + '->' + pItem.desc)
                            }
                            if (pItem['key'] === 'tel') {
                                let sentinel = /(^1\d{10}$)|(^([0-9]{3,4}-)?\d{7,8}$)|(^400[0-9]{7}$)|(^800[0-9]{7}$)|(^(400)-(\d{3})-(\d{4})(.)(\d{1,4})$)|(^(400)-(\d{3})-(\d{4}$))/.test(pItem.value);
                                if (!sentinel) {
                                    sign = false;
                                    self.$message.error('<?= \Yii::t('components/other', '请填写有效的联系电话或手机');?>');
                                }
                            }
                            let value = pItem['value'];
                            if (pItem['key'] === 'we_path') {
                                value = value.slice(1);
                            }
                            if (pItem['key'] === 'url') {
                                value = encodeURIComponent(value);
                            }
                            params += pItem['key'] + '=' + value + '&';
                        });
                        params = params.substr(0, params.length - 1);

                        // 拼接路由、参数
                        cItem.new_link_url = cItem['value'] + '?' + params;
                    } else {
                        cItem.new_link_url = cItem['value'];
                    }
                });
                if (!sign) {
                    return;
                }
                self.$emit('selected', self.checkedList, self.params);
                self.dialogFormVisible = false;
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            selectChecked(item, index) {
                let self = this;
                let newItem = JSON.parse(JSON.stringify(item));
                self.currentCheckedItem = newItem;

                // 如果是单选 只能勾选一个
                if (self.type === 'single') {
                    self.checkedCities = [];
                    self.checkedCities.push(item.value)
                }

                let sign = true;
                self.checkedList.forEach(function (cItem, cIndex) {
                    if (cItem.id === item.id) {
                        self.checkedList.splice(cIndex, 1);
                        if (self.type === 'multiple') {
                            self.currentCheckedItem = {};
                        }
                        sign = false;
                    }
                });
                if (sign) {
                    // 如果是单选 只能勾选一个
                    if (self.type === 'single') {
                        self.checkedList = [];
                        self.checkedList.push(newItem);
                    } else if (self.type === 'multiple') {
                        self.checkedList.push(newItem);
                    } else {
                        console.log('pickLink 组件参数type错误：请检查')
                    }
                }
            },
            pickLinkEdit(item) {
                let self = this;
                let sign = true;
                self.checkedList.forEach(function (cItem, cIndex) {
                    if (cItem.id === item.id) {
                        self.currentCheckedItem = cItem;
                        sign = false;
                    }
                });
                if (sign) {
                    self.currentCheckedItem = item;
                }
            },
            isShowParamsButton(currentItem) {
                let num = 0;
                if (currentItem.params) {
                    currentItem.params.forEach(function(item) {
                        if (item.is_show !== false) {
                            num += 1;
                        }
                    })
                }

                return num > 0;
            },
            customer(param) {
                this.currentCheckedItem.button_text = param;
                this.currentCheckedItem.button_text.link_name = this.currentCheckedItem.name;
                this.currentCheckedItem.params.forEach(e => {
                    e.value = param[e.key];
                })
                console.log(param);
                console.log(this.currentCheckedItem);
            }
        },
    });
</script>
