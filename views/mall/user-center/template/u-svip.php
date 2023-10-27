<style>
    .diy-component-edit .diy-svip .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-svip .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
    .diy-component-edit .diy-svip .mode-choose {
    }
    .diy-component-edit .diy-svip .mode-choose .classic {
        width: 98px;
        height: 70px;
        border-radius: 3px;
        border: 2px solid #DCDFE6;
        color: #606266;
        font-size: 14px;
        margin-right: 20px;
    }
    .diy-component-edit .diy-svip .mode-choose .classic.active {
        color: #409EFF;
        border-color: #409EFF;
    }
    .diy-component-edit .diy-svip .mode-choose .classic img {
        margin-bottom: 6px;
        display: block;
    }
    .diy-component-edit .diy-svip .mode-choose .classic .text {
        line-height: 1;
    }
    .diy-component-edit .diy-svip .color-picker {
        margin-left: 10px;
    }
    .diy-component-edit .diy-svip .label {
        font-size: 18px;
        font-weight: 600;
        color: #303133;
        margin: 20px;
    }
</style>
<template id="u-svip">
    <div>
        <div ref="component" class="diy-component-preview diy-svip">
            <p-svip v-model="data"></p-svip>
        </div>
        <div class="diy-component-edit">
            <div class="diy-svip">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '超级会员卡');?></div>
                </div>
                <el-form ref="data" :model="data" label-width="132px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '超级会员卡背景');?>">
                        <app-radio turn v-model="data.bg_style" :label="1"><?= \Yii::t('mall/user_center', '背景颜色');?></app-radio>
                        <app-radio turn v-model="data.bg_style" :label="2"><?= \Yii::t('mall/user_center', '背景图片');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景颜色');?>" v-if="data.bg_style == 1">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景图片');?>" prop="bg_pic" v-if="data.bg_style == 2">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="bgPicUrl">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>: 750 * 140"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.bg_pic" :show-delete="true" @deleted="data.bg_pic = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片上下间距');?>">
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
                    <div class="label"><?= \Yii::t('mall/user_center', '未购买用户');?></div>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景图');?>" prop="buy_bg">
                        <div style="position: relative">
                            <app-attachment :multiple="false" :max="1" @selected="buyBgUrl">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:702*120" placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择文件');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin: 10px 0;position: relative;width: 80px;">
                                <app-image width="80px"
                                           height="80px"
                                           mode="aspectFill"
                                           :src="data.buy_bg">
                                </app-image>
                                <el-button v-if="data.buy_bg != ''" class="del-btn" @click="data.buy_bg = ''" size="mini" type="danger" icon="el-icon-close" circle></el-button>
                            </div>
                            <el-button size="mini" @click="resetImg('buy_bg')" class="reset" type="primary"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '文字');?>" prop="buy_text">
                        <div flex="dir:left cross:center">
                            <el-input maxlength="17" style="margin-left: 10px;width: 240px" size="small" v-model="data.buy_big" placeholder="<?= \Yii::t('mall/user_center', '大标题（字符限制17）');?>"></el-input>
                            <el-color-picker class="color-picker" size="small" v-model="data.buy_big_color"></el-color-picker>
                        </div>
                        <div flex="dir:left cross:center">
                            <el-input maxlength="10" style="margin-left: 10px;width: 240px" size="small" v-model="data.buy_small" placeholder="<?= \Yii::t('mall/user_center', '小标题（字符限制10）');?>"></el-input>
                            <el-color-picker class="color-picker" size="small" v-model="data.buy_small_color"></el-color-picker>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '按钮颜色');?>" prop="buy_btn_bg_color">
                        <el-color-picker class="color-picker" style="margin-top: 10px;" size="small" v-model="data.buy_btn_bg_color"></el-color-picker>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '按钮文字');?>" prop="buy_btn_text">
                        <div flex="dir:left cross:center">
                            <el-input maxlength="4" style="margin-left: 10px;width: 240px" size="small" v-model="data.buy_btn_text" placeholder="<?= \Yii::t('mall/user_center', '大标题（字符限制4）');?>"></el-input>
                            <el-color-picker class="color-picker" size="small" v-model="data.buy_btn_color"></el-color-picker>
                        </div>
                    </el-form-item>
                    <div class="label"><?= \Yii::t('mall/user_center', '已购买用户');?></div>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景图');?>" prop="renew_bg">
                        <div style="position: relative">
                            <app-attachment :multiple="false" :max="1" @selected="renewBgUrl">
                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:702*120" placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择文件');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin: 10px 0;position: relative;width: 80px;">
                                <app-image width="80px"
                                           height="80px"
                                           mode="aspectFill"
                                           :src="data.renew_bg">
                                </app-image>
                                <el-button v-if="data.renew_bg != ''" class="del-btn" @click="data.renew_bg == ''" size="mini" type="danger" icon="el-icon-close" circle></el-button>
                            </div>
                            <el-button size="mini" @click="resetImg('renew_bg')" class="reset" type="primary"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '文字');?>" prop="renew_text">
                        <div flex="dir:left cross:center">
                            <el-input maxlength="17" style="margin-left: 10px;width: 240px" size="small" v-model="data.renew_text" placeholder="<?= \Yii::t('mall/user_center', '标题（字符限制17）');?>"></el-input>
                            <el-color-picker class="color-picker" size="small" v-model="data.renew_text_color"></el-color-picker>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '按钮颜色');?>" prop="renew_btn_bg_color">
                        <el-color-picker class="color-picker" style="margin-top: 10px;" size="small" v-model="data.renew_btn_bg_color"></el-color-picker>
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
    Vue.component('u-svip', {
        template: '#u-svip',
        props: {
            value: Object,
            bg: Object,
            default: Object
        },
        data() {
            return {
                data: {}
            }
        },
        created() {
            this.data = JSON.parse(JSON.stringify(this.default));
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
                this.data.margin = this.data.margin ? this.data.margin : 0;
                this.data.card_margin = this.data.card_margin ? this.data.card_margin : 10;
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
            resetImg() {
                this.data.bg_pic = this.default.bg_pic
            },
            bgPicUrl(e) {
                if (e.length) {
                    this.data.bg_pic = e[0].url;
                }
            },
            buyBgUrl(e) {
                if (e.length) {
                    this.data.buy_bg = e[0].url;
                }
            },
            renewBgUrl(e) {
                if (e.length) {
                    this.data.renew_bg = e[0].url;
                }
            }
        },
    });
</script>
