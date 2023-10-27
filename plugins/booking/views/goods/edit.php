<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/7
 * Time: 11:46
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('app-goods');
Yii::$app->loadViewComponent('goods/app-goods-form');
?>
<style>
    .el-textarea__inner {
        padding: 5px 15px;
    }

    .sortable-chosen {
        border: 0px solid #3399ff !important;
    }

    .el-select__tags-text {
        max-width: 150px;
        display: inline-block;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
    }

    .el-select .el-tag__close.el-icon-close {
        top: -5px !important;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <el-dialog
            title="<?= \Yii::t('plugins/booking', '选择门店');?>"
            :visible.sync="storeDialogVisible"
            width="30%"
            >
            <div style="margin-bottom: 20px">
                <el-input @keyup.enter.native="clerkUser" clearable @clear="clerkUser" placeholder="<?= \Yii::t('plugins/booking', '请输入门店名称');?>" v-model="storeKeyword">
                    <el-button slot="append" @click="clerkUser"><?= \Yii::t('plugins/booking', '搜索');?></el-button>
                </el-input>
            </div>
            <el-table
                    ref="multipleTable"
                    :data="store"
                    border
                    v-loading="storeLoading"
                    @selection-change="handleSelectionChange"
                    style="width: 100%">
                <el-table-column
                        type="selection"
                        :selectable="checkSelectable"
                        width="55">
                </el-table-column>
                <el-table-column
                        prop="name"
                        label="<?= \Yii::t('plugins/booking', '门店名称');?>">
                </el-table-column>

            </el-table>

            <span slot="footer" flex="main:justify cross:center" class="dialog-footer">
                    <div v-if="pageCount > 0">
                        <el-pagination
                                @current-change="pagination"
                                background
                                :current-page="current_page"
                                layout="prev, pager, next, jumper"
                                :page-count="pageCount">
                        </el-pagination>
                    </div>
                <div>
                    <el-button type="primary" @click="sureStore()"><?= \Yii::t('plugins/booking', '确定');?></el-button>
                </div>
            </span>
        </el-dialog>
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'plugin/booking/mall/goods/index'})"><?= \Yii::t('plugins/booking', '预约商品');?></span></el-breadcrumb-item>
                <el-breadcrumb-item v-if="form.id > 0"><?= \Yii::t('plugins/booking', '详情');?></el-breadcrumb-item>
                <el-breadcrumb-item v-else><?= \Yii::t('plugins/booking', '添加商品');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods
            ref="appGoods"
            sign="booking"
            :is_attr="1"
            :is_form="0"
            :is_cats="0"
            :is_display_setting="0"
            :is_show="0"
            :form="form"
            :rule="rule"
            :is_area_limit="0"
            :no_price="0"
            :is_shipping="0"
            :is_shipping_rules="0"
            :is_edit="is_edit"
            :is_virtual_sales="0"
            sign_cn="<?= \Yii::t('plugins/booking', '预约');?>"
            :is_shipping="0"
            :preview-info="previewInfo"
            @handle-preview="handlePreview"
            @goods-success="childrenGoods"
            url="plugin/booking/mall/goods/edit"
            get_goods_url="plugin/booking/mall/goods/edit"
            referrer="plugin/booking/mall/goods/index">
            <template slot="member_route_setting">
                 <span class="red"><?= \Yii::t('plugins/booking', '注：必须在');?>
                    “<el-button type="text" @click="$navigate({r: 'plugin/booking/mall/setting'}, true)"><?= \Yii::t('plugins/booking', '预约设置=>基本设置=>优惠叠加设置');?></el-button>”
                    <?= \Yii::t('plugins/booking', '中开启，才能使用');?>
                </span>
            </template>
            
            <template slot="before_area_limit">
                <el-form-item label="<?= \Yii::t('plugins/booking', '门店选择');?>" prop="store">
                   <div flex="dir:top">
                       <div>
                           <el-tag v-for="(item, index) in form.store"
                                   @close="storeDelete(index)"
                                   :key="index" :disable-transitions="true"
                                   style="margin: 0 10px 10px 0;" closable>
                               {{item.name}}
                           </el-tag>
                       </div>
                   </div>
                    <el-button type="button" size="mini" @click="storeDialogVisible = true"><?= \Yii::t('plugins/booking', '选择门店');?>
                    </el-button>
<!--                    <el-select v-model="chooseStore" @change="showStore" multiple filterable placeholder="请选择">-->
<!--                        <el-option v-for="item in store" :key="item.id" :label="item.name" :value="item.id">-->
<!--                        </el-option>-->
<!--                    </el-select>-->
                </el-form-item>
            </template>

            <template slot="before_price">
                <el-form-item label="<?= \Yii::t('plugins/booking', '预约价');?>" prop="price">
                    <el-input type="number"
                              placeholder="<?= \Yii::t('plugins/booking', '请输入商品预约价');?>"
                              :disabled="use_attr === 1"
                              oninput="this.value = this.value.replace(/[^0-9\.]/, '');" min="0"
                              v-model="form.price">
                        <template slot="append"><?= \Yii::t('plugins/booking', '元');?></template>
                    </el-input>
                </el-form-item>
            </template>

            <template slot="before_virtual_sales">
                <el-form-item prop="virtual_sales" >
                    <template slot='label'>
                        <span><?= \Yii::t('plugins/booking', '已预约量');?></span>
                        <el-tooltip effect="dark" content="<?= \Yii::t('plugins/booking', '前端展示的销量=实际销量+已预约量');?>" placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input type="number" oninput="this.value = this.value.replace(/[^0-9]/, '')" min="0" v-model="form.virtual_sales">
                        <template slot="append">{{form.unit}}</template>
                    </el-input>
                </el-form-item>
            </template>

            <template slot="before_marketing">
                <el-card shadow="never" style="margin: 24px 0">
                    <div slot="header"><?= \Yii::t('plugins/booking', '表单设置');?></div>
                    <el-form-item label="<?= \Yii::t('plugins/booking', '表单状态');?>" >
                        <el-switch
                                v-model="form.is_order_form"
                                :active-value="1"
                                :inactive-value="0">
                        </el-switch>
                    </el-form-item>
                    <template v-if="form.is_order_form === 1">
                        <el-form-item label="<?= \Yii::t('plugins/booking', '表单设置');?>">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/booking', '表单设置');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/booking', '选择第一项（默认表单）将会根据表单列表的（默认表单）变化而变化');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <div>
                                <el-radio v-model="radio" :label="3">
                                    <el-tag @close="selectForm(null)" v-if="form.form"
                                            :key="form.form.name"
                                            :disable-transitions="true"
                                            style="margin-right: 10px;"
                                            closable>
                                        {{form.form.name}}
                                    </el-tag>
                                    <el-button type="button" size="mini" @click="open"><?= \Yii::t('plugins/booking', '选择表单');?></el-button>
                                </el-radio>
                            </div>
                            <div  style="margin-top: 20px">
                                <el-radio v-model="radio" :label="6"><?= \Yii::t('plugins/booking', '自定义表单');?></el-radio>
                            </div>
                        </el-form-item>
                        <el-form-item >
                            <app-form v-if="radio === 6" :is_date_range="true" :is_time_range="true" :value.sync="form.form_data"></app-form>
                            <div class="app-goods-form" v-if="radio === 3">
                                <el-dialog title="<?= \Yii::t('plugins/booking', '选择表单');?>" :visible.sync="dialog" width="30%">
                                    <el-card shadow="never" flex="dir:left" style="flex-wrap: wrap" >
                                        <el-radio-group v-model="checked">
                                            <el-radio style="padding: 10px;" v-for="item in list"
                                                      :label="item.id" :key="item.id">{{item.name}}
                                            </el-radio>
                                        </el-radio-group>
                                    </el-card>
                                    <div slot="footer" class="dialog-footer">
                                        <el-button @click="cancel"><?= \Yii::t('plugins/booking', '取消');?></el-button>
                                        <el-button type="primary" @click="confirm"><?= \Yii::t('plugins/booking', '确定');?></el-button>
                                    </div>
                                </el-dialog>
                            </div>
                        </el-form-item>
                    </template>
                </el-card>
            </template>

            <template slot="preview_end">
                <div v-if="previewData" flex="dir:top">
                    <el-image style="margin-top:12px;height:161px"
                              src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/img/store.png"></el-image>
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
                rule: {
                    store: [
                        {required: true, message: "<?= \Yii::t('plugins/booking', '请选择门店列表');?>", trigger: 'change'},
                    ],
                    price: [
                        {required: true, message: "<?= \Yii::t('plugins/booking', '请输入商品预约价');?>", trigger: 'change'},
                    ]
                },
                form: {
                    form_data: [],
                    store: [],
                    price: '',
                    unit: '件',
                    virtual_sales: 0,
                    is_order_form: 0,
                    order_form_type: 1,
                    extra: {
                        price: "<?= \Yii::t('plugins/booking', '预约价');?>"
                    },
                    form: null

                },
                use_attr: 0,
                cats: [],
                attrGroups: [],
                store: [],
                chooseStore: [],
                previewData: null,
                previewInfo: {},
                radio: 3,
                checked: {},
                dialog: false,
                list: [],
                is_edit: 0,
                checkedRadio: {},
                storeDialogVisible: false,
                pageCount: 1,
                current_page: 1,
                storeKeyword: '',
                storeLoading: false,
                page: 1
            };
        },
        mounted() {
            let id = getQuery('id');
            if (id) {
                this.getDetail(id);
                this.is_edit = 1;
            }
            this.clerkUser();
        },
        methods: {
            handlePreview(e) {
                this.previewData = e;
            },
            clerkUser() {
                this.storeLoading = true;
                request({
                    params: {
                        r: 'plugin/booking/mall/goods/store-search',
                        keyword: this.storeKeyword,
                        page: this.page
                    },
                }).then(e => {
                    this.storeLoading = false;
                    if (e.data.code === 0) {
                        this.store = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            showStore(row) {
                this.chooseStore = row;
                this.form.store = [];
                row.forEach(idx=>{
                    for(let index in this.store) {
                        if(this.store[index].id == idx) {
                            this.form.store.push(this.store[index])
                        }
                    }
                })
            },
            childrenGoods(e) {
                this.form.price = e.price;
                this.use_attr = e.use_attr;
            },
            getDetail(id) {
                this.cardLoading = true;
                request({
                    params: {
                        r: 'plugin/booking/mall/goods/edit',
                        id: id
                    },
                    method: 'get'
                }).then(e => {
                    this.cardLoading = false;
                    if (e.data.code === 0) {
                        let plugin = e.data.data.detail.plugin;
                        this.form.id = getQuery('id');
                        this.form.form_data = plugin.form_data? plugin.form_data : [];
                        this.form.store = plugin.store ? plugin.store : [];
                        this.form.is_order_form = plugin.is_order_form;
                        this.form.form = e.data.data.detail.form;
                        console.log(this.form.form);
                        this.form.order_form_type = plugin.order_form_type;
                        this.loadData();
                        if (this.form.order_form_type === 1) {
                            this.radio = 3;
                        } else {
                            this.radio = 6;
                        }
                        this.form.price = e.data.data.detail.price;
                        this.form.virtual_sales = e.data.data.detail.virtual_sales;
                        if(this.form.store) {

                            for(let i = 0;i < this.form.store.length;i++) {
                                this.chooseStore.push(this.form.store[i]);
                            }
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(() => {
                    this.cardLoading = false;
                });
            },

            selectForm(data) {
                if (data === null ) {
                    this.checked = null;
                }
                this.form.form = data;
                this.form.form_id = data ? data.id : -1;
                console.log(this.form.form);
            },

            cancel() {
                this.dialog = false;
            },

            loadData() {
                request({
                    params: {
                        r: `mall/order-form/all-list`
                    }
                }).then(response => {
                    if (response.data.code == 0) {
                        this.list = response.data.data.list;
                        if (this.form.form) {
                            this.checked = this.form.form.id;
                        }
                    } else {
                        this.$message.error(response.data.msg);
                    }
                })
            },

            open() {
                this.dialog = true;
                this.loadData();
            },
            confirm() {
                this.selectForm(this.checkedRadio);
                this.cancel();
            },
            storeDelete(index) {
                this.form.store.splice(index, 1);
            },
            handleSelectionChange(index) {
                this.chooseStore = index;
            },
            checkSelectable(row) {
                for (let i = 0; i < this.form.store.length; i++) {
                    if (this.form.store[i].id == row.id) {
                        return false;
                    }
                }
                return true;
            },
            pagination(e) {
                this.page = e;
                this.clerkUser();
            },
            sureStore() {
                this.storeDialogVisible = false;
                let choose = [];
                let newChoose = JSON.parse(JSON.stringify(this.form.store));
                choose = newChoose.concat(this.chooseStore);
                let obj = {};
                choose = choose.reduce(function(item, next) {
                   obj[next.id] ? '' : obj[next.id] = true && item.push(next);
                      return item;
                }, []);
                this.form.store = choose;
            }
        },

        watch: {
            radio: {
                handler(data) {
                    if (data == 3) {
                        this.form.order_form_type = 1;
                    } else if(data == 6) {
                        this.form.form = null;
                        this.checkedRadio = null;
                        this.checked = null;
                        this.form.order_form_type = 2;
                    }
                },
                immediate: true
            },
            checked: {
                handler(data) {
                    if (data === null) {
                        this.checkedRadio = null;
                    }
                    for (let i = 0 ; i < this.list.length; i++) {
                        if (this.list[i].id === data) {
                            this.checkedRadio = this.list[i];
                        }
                    }
                },
            },
            'form.store': {
                handler(data) {
                },
            }
        }
    });
</script>
