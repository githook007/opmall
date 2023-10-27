<style>
    .diy-component-edit .diy-order .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-order .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
    .diy-component-edit .diy-order .mode-choose {
    }
    .diy-component-edit .diy-order .mode-choose .classic {
        width: 98px;
        height: 70px;
        border-radius: 3px;
        border: 2px solid #DCDFE6;
        color: #606266;
        font-size: 14px;
        margin-right: 20px;
    }
    .diy-component-edit .diy-order .mode-choose .classic.active {
        color: #409EFF;
        border-color: #409EFF;
    }
    .diy-component-edit .diy-order .mode-choose .classic img {
        margin-bottom: 6px;
        display: block;
    }
    .diy-component-edit .diy-order .mode-choose .classic .text {
        line-height: 1;
    }
</style>
<template id="u-order">
    <div>
        <div ref="component" class="diy-component-preview diy-order">
            <p-order v-model="data"></p-order>
        </div>
        <div class="diy-component-edit">
            <div class="diy-order">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '订单栏');?></div>
                </div>
                <el-form ref="data" :model="data" label-width="104px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景开关');?>">
                        <el-switch v-model="data.bg" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '订单栏背景');?>" v-if="data.bg == 1">
                        <app-radio turn v-model="data.bg_style" :label="1"><?= \Yii::t('mall/user_center', '背景颜色');?></app-radio>
                        <app-radio turn v-model="data.bg_style" :label="2"><?= \Yii::t('mall/user_center', '背景图片');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景颜色');?>" v-if="data.bg == 1 && data.bg_style == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.bg_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.bg_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.bg_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景图片');?>" prop="bg_pic" v-if="data.bg == 1 && data.bg_style == 2">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="bgPicUrl">
                                <el-tooltip effect="dark"
                                            :content="'<?= \Yii::t('mall/user_center', '建议尺寸');?>: 750 * ' + height"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('bg_pic')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.bg_pic" :show-delete="true" @deleted="data.bg_pic = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '模块显示');?>">
                        <div flex="dir:left cross:center" class="mode-choose">
                            <div flex="dir:top main:center cross:center" @click="changeMode(1)" class="classic" :class="{'active' : data.mode == 1}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <img src="statics/img/mall/user-center/order-style-1.png" width="47" height="22" alt="">
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式一');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="changeMode(2)" class="classic" :class="{'active' : data.mode == 2}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <img src="statics/img/mall/user-center/order-style-2.png" width="47" height="20" alt="">
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式二');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="changeMode(3)" class="classic" :class="{'active' : data.mode == 3}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <img src="statics/img/mall/user-center/order-style-3.png" width="47" height="14" alt="">
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式三');?></div>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '待付款图标');?>" prop="pay_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="PayPicUrl" tag="user_order">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('pay_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.pay_icon" :show-delete="true" @deleted="data.pay_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '待发货图标');?>" prop="send_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="sendPicUrl" tag="user_order">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('send_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.send_icon" :show-delete="true" @deleted="data.send_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '待收货图标');?>" prop="confirm_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="confirmPicUrl" tag="user_order">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('confirm_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.confirm_icon" :show-delete="true" @deleted="data.confirm_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '已完成图标');?>" prop="sale_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="salePicUrl" tag="user_order">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('sale_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.sale_icon" :show-delete="true" @deleted="data.sale_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '售后图标');?>" prop="refund_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="refundPicUrl" tag="user_order">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('refund_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.refund_icon" :show-delete="true" @deleted="data.refund_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>

                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片颜色');?>" v-if="data.mode != 3">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.card_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.card_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.card_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片上下间距');?>" v-if="data.mode != 3">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.card_margin"
                                       :max="200" :min="0"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.card_margin" :min="0"
                                             :max="200"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '我的订单颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.title_color = '#999999' : ''}"
                                                 size="small"
                                                 v-model="data.title_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.title_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '我的订单字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.title_size"
                                       :max="60" :min="12"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.title_size" :min="24"
                                             :max="60"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '图标标题颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.icon_color = '#333333' : ''}"
                                                 size="small"
                                                 v-model="data.icon_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.icon_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '图标标题字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.icon_size"
                                       :max="40" :min="24"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.icon_size" :min="24"
                                             :max="40"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '组件上边距');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.margin"
                                       :max="200" :min="0"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.margin" :min="0"
                                             :max="200"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('u-order', {
        template: '#u-order',
        props: {
            value: Object,
            bg: Object,
            default: Object
        },
        data() {
            return {
                height: 136,
                data: {}
            }
        },
        created() {
            this.data = JSON.parse(JSON.stringify(this.default));
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
            }
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        methods: {
            changeMode(e) {
                this.data.mode = e;
                // this.data.bg_style = e != 1 ? 1 : this.data.bg_style
            },
            resetImg(obj) {
                this.data[obj] = this.default[obj]
            },
            bgPicUrl(e) {
                if (e.length) {
                    this.data.bg_pic = e[0].url;
                    // this.$refs.data.validateField('member_bg_pic_url');
                }
            },
            refundPicUrl(e){
                if(e.length){
                    this.data.refund_icon = e[0].url;
                }
            },
            salePicUrl(e){
                if(e.length){
                    this.data.sale_icon = e[0].url;
                }
            },
            confirmPicUrl(e){
                if(e.length){
                    this.data.confirm_icon = e[0].url;
                }
            },
            sendPicUrl(e){
                if(e.length){
                    this.data.send_icon = e[0].url;
                }
            },
            PayPicUrl(e){
                if(e.length){
                    this.data.pay_icon = e[0].url;
                }
            },
        },
    });
</script>
