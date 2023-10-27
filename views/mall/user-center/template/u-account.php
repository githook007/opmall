<style>
    .diy-component-edit .diy-account .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-account .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
    .diy-component-edit .diy-account .mode-choose {
    }
    .diy-component-edit .diy-account .mode-choose .classic {
        width: 98px;
        height: 70px;
        border-radius: 3px;
        border: 2px solid #DCDFE6;
        color: #606266;
        font-size: 14px;
        margin-right: 20px;
    }
    .diy-component-edit .diy-account .mode-choose .classic.active {
        color: #409EFF;
        border-color: #409EFF;
    }
    .diy-component-edit .diy-account .mode-choose .classic img {
        margin-bottom: 6px;
        display: block;
    }
    .diy-component-edit .diy-account .mode-choose .classic .text {
        line-height: 1;
    }
</style>
<template id="u-account">
    <div>
        <div ref="component" class="diy-component-preview diy-account">
            <p-account v-model="data"></p-account>
        </div>
        <div class="diy-component-edit">
            <div class="diy-account">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '账户栏');?></div>
                </div>
                <el-form ref="data" :model="data" label-width="104px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景开关');?>">
                        <el-switch v-model="data.bg" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '账户栏背景');?>" v-if="data.bg == 1">
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
                        </div>
                        <app-gallery :url="data.bg_pic" :show-delete="true" @deleted="data.bg_pic = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '积分开关');?>">
                        <el-switch @change="checkMode" v-model="data.integral" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '余额开关');?>">
                        <el-switch @change="checkMode" v-model="data.balance" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '优惠券开关');?>">
                        <el-switch @change="checkMode" v-model="data.coupon" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡券开关');?>">
                        <el-switch @change="checkMode" v-model="data.card" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '模块样式');?>" v-if="!hideMode">
                        <div flex="dir:left cross:center" class="mode-choose">
                            <div flex="dir:top main:center cross:center" @click="chooseMode(1)" class="classic" :class="{'active' : data.mode == 1}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <img src="statics/img/mall/user-center/account-style-1.png" width="47" height="22" alt="">
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式一');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="chooseMode(2)" class="classic" :class="{'active' : data.mode == 2}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <img src="statics/img/mall/user-center/account-style-2.png" width="46" height="15" alt="">
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式二');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="chooseMode(3)" class="classic" :class="{'active' : data.mode == 3}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <div style="width: 10px;height: 10px;border-radius: 50%;margin: 0 1.5px;background: #D1E8FF;"></div>
                                    <div style="width: 10px;height: 10px;border-radius: 50%;margin: 0 1.5px;background: #D1E8FF;"></div>
                                    <div style="width: 10px;height: 10px;border-radius: 50%;margin: 0 1.5px;background: #D1E8FF;"></div>
                                    <div style="width: 10px;height: 10px;border-radius: 50%;margin: 0 1.5px;background: #D1E8FF;"></div>
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式三');?></div>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '图标开关');?>" v-if="data.mode != 1">
                        <el-switch v-model="data.show_icon" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '积分图标');?>" prop="integral_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="integralPicUrl" tag="user_account">
                                <el-tooltip effect="dark"
                                            :content="data.mode == 1 ? '<?= \Yii::t('mall/user_center', '建议尺寸');?>:100 * 100' : '<?= \Yii::t('mall/user_center', '建议尺寸');?>:28 * 28'"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('integral_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.integral_icon" :show-delete="true" @deleted="data.integral_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '积分背景色');?>" v-if="data.mode == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.integral_color = '#F7F7F7' : ''}"
                                                 size="small"
                                                 v-model="data.integral_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.integral_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '余额图标');?>" prop="balancePicUrl">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="balancePicUrl" tag="user_account">
                                <el-tooltip effect="dark"
                                            :content="data.mode == 1 ? '<?= \Yii::t('mall/user_center', '建议尺寸');?>:100 * 100' : '<?= \Yii::t('mall/user_center', '建议尺寸');?>:28 * 28'"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('balance_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.balance_icon" :show-delete="true" @deleted="data.balance_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '余额背景色');?>" v-if="data.mode == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.balance_color = '#F7F7F7' : ''}"
                                                 size="small"
                                                 v-model="data.balance_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.balance_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '优惠券图标');?>" prop="coupon_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="couponPicUrl" tag="user_account">
                                <el-tooltip effect="dark"
                                            :content="data.mode == 1 ? '<?= \Yii::t('mall/user_center', '建议尺寸');?>:100 * 100' : '<?= \Yii::t('mall/user_center', '建议尺寸');?>:28 * 28'"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('coupon_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.coupon_icon" :show-delete="true" @deleted="data.coupon_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '优惠券背景色');?>" v-if="data.mode == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.coupon_color = '#F7F7F7' : ''}"
                                                 size="small"
                                                 v-model="data.coupon_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.coupon_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡券图标');?>" prop="card_icon">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="cardPicUrl" tag="user_account">
                                <el-tooltip effect="dark"
                                            :content="data.mode == 1 ? '<?= \Yii::t('mall/user_center', '建议尺寸');?>:100 * 100' : '<?= \Yii::t('mall/user_center', '建议尺寸');?>:28 * 28'"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('card_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.card_icon" :show-delete="true" @deleted="data.card_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡券背景色');?>" v-if="data.mode == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.card_color = '#F7F7F7' : ''}"
                                                 size="small"
                                                 v-model="data.card_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.card_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片颜色');?>" v-if="data.mode == 2">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.card_bg = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.card_bg"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.card_bg"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片上下间距');?>" v-if="data.mode == 2">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.card_margin"
                                       :max="200" :min="0"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.card_margin" :min="0"
                                             :max="200" label="<?= \Yii::t('mall/user_center', '卡片上下间距');?>"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '标题颜色');?>">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '标题字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.title_size"
                                       :max="60" :min="24"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.title_size" :min="24"
                                             :max="60" label="<?= \Yii::t('mall/user_center', '标题文字字号');?>"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '数值颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.number_color = '#333333' : ''}"
                                                 size="small"
                                                 v-model="data.number_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.number_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '数值字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.number_size"
                                       :max="60" :min="24"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.number_size" :min="24"
                                             :max="60" label="<?= \Yii::t('mall/user_center', '图标标题字号');?>"></el-input-number>
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
                                             :max="200" label="<?= \Yii::t('mall/user_center', '头像大小');?>"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('u-account', {
        template: '#u-account',
        props: {
            value: Object,
            bg: Object,
            default: Object
        },
        data() {
            return {
                height: 136,
                hideMode: false,
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
            chooseMode(e) {
                this.data.mode = e;
                if(e != 1) {
                    this.data.integral_icon = this.data.integral_icon == '' || this.data.integral_icon == this.default.integral_icon ? this.default.s_integral_icon : this.data.integral_icon
                    this.data.balance_icon = this.data.balance_icon == '' || this.data.balance_icon == this.default.balance_icon ? this.default.s_balance_icon : this.data.balance_icon
                    this.data.coupon_icon = this.data.coupon_icon == '' || this.data.coupon_icon == this.default.coupon_icon ? this.default.s_coupon_icon : this.data.coupon_icon
                    this.data.card_icon = this.data.card_icon == '' || this.data.card_icon == this.default.card_icon ? this.default.s_card_icon : this.data.card_icon
                }else {
                    this.data.integral_icon = this.data.integral_icon == '' || this.data.integral_icon == this.default.s_integral_icon ? this.default.integral_icon : this.data.integral_icon
                    this.data.balance_icon = this.data.balance_icon == '' || this.data.balance_icon == this.default.s_balance_icon ? this.default.balance_icon : this.data.balance_icon
                    this.data.coupon_icon = this.data.coupon_icon == '' || this.data.coupon_icon == this.default.s_coupon_icon ? this.default.coupon_icon : this.data.coupon_icon
                    this.data.card_icon = this.data.card_icon == '' || this.data.card_icon == this.default.s_card_icon ? this.default.card_icon : this.data.card_icon
                }
            },
            checkMode() {
                this.hideMode = false;
                if(this.data.integral == 0 && this.data.balance == 0 && this.data.coupon == 0) {
                    this.data.mode = '1';
                    this.hideMode = true;
                    this.data.integral_icon = this.data.integral_icon == '' || this.data.integral_icon == this.default.s_integral_icon ? this.default.integral_icon : this.data.integral_icon
                    this.data.balance_icon = this.data.balance_icon == '' || this.data.balance_icon == this.default.s_balance_icon ? this.default.balance_icon : this.data.balance_icon
                    this.data.coupon_icon = this.data.coupon_icon == '' || this.data.coupon_icon == this.default.s_coupon_icon ? this.default.coupon_icon : this.data.coupon_icon
                    this.data.card_icon = this.data.card_icon == '' || this.data.card_icon == this.default.s_card_icon ? this.default.card_icon : this.data.card_icon
                    if(this.data.card == 0) {
                        this.$emit('close','')
                    }
                }
            },
            resetImg(obj) {
                let text = this.data.mode != 1 ? 's_' + obj : obj
                this.data[obj] = this.default[text]
            },
            bgPicUrl(e) {
                if (e.length) {
                    this.data.bg_pic = e[0].url;
                    // this.$refs.data.validateField('member_bg_pic_url');
                }
            },
            cardPicUrl(e){
                if(e.length){
                    this.data.card_icon = e[0].url;
                }
            },
            couponPicUrl(e){
                if(e.length){
                    this.data.coupon_icon = e[0].url;
                }
            },
            balancePicUrl(e){
                if(e.length){
                    this.data.balance_icon = e[0].url;
                }
            },
            integralPicUrl(e){
                if(e.length){
                    this.data.integral_icon = e[0].url;
                }
            },
        },
    });
</script>
