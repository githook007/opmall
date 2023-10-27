<style>
    .diy-component-edit .diy-foot .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-foot .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
</style>
<template id="u-foot">
    <div>
        <div ref="component" class="diy-component-preview diy-foot">
            <p-foot v-model="data"></p-foot>
        </div>
        <div class="diy-component-edit">
            <div class="diy-foot">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '收藏足迹');?></div>
                </div>
                <el-form ref="data" :model="data" label-width="104px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景开关');?>">
                        <el-switch @change="toggle" v-model="data.bg" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收藏足迹背景');?>" v-if="data.bg == 1">
                        <app-radio @change="toggle" turn v-model="data.bg_style" :label="1"><?= \Yii::t('mall/user_center', '背景颜色');?></app-radio>
                        <app-radio @change="toggle" turn v-model="data.bg_style" :label="2"><?= \Yii::t('mall/user_center', '背景图片');?></app-radio>
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
                        <app-radio turn v-model="data.mode" :label="1"><?= \Yii::t('mall/user_center', '图文显示');?></app-radio>
                        <app-radio turn v-model="data.mode" :label="2"><?= \Yii::t('mall/user_center', '纯文字显示');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '样式选择');?>">
                        <app-radio turn v-model="data.style" :label="1"><?= \Yii::t('mall/user_center', '上下排布');?></app-radio>
                        <app-radio turn v-model="data.style" :label="2"><?= \Yii::t('mall/user_center', '左右排布');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '我的收藏图标');?>" prop="favorite_icon" v-if="data.style == 1">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="favoritePicUrl">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('favorite_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.favorite_icon" :show-delete="true" @deleted="data.favorite_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '我的足迹图标');?>" prop="foot_icon" v-if="data.style == 1">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="footPicUrl">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('foot_icon')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.foot_icon" :show-delete="true" @deleted="data.foot_icon = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片开关');?>">
                        <el-switch v-model="data.card" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片颜色');?>" v-if="data.card == 1">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片上下间距');?>" v-if="data.card == 1">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '标题文字颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.title_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.title_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.title_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '标题文字字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.title_size"
                                       :max="60" :min="12"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.title_size" :min="24"
                                             :max="60" label="<?= \Yii::t('mall/user_center', '标题文字字号');?>"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '数值文字颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.number_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.number_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.number_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '数值文字字号');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.number_size"
                                       :max="60" :min="12"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.number_size" :min="24"
                                             :max="60" label="<?= \Yii::t('mall/user_center', '数值文字字号');?>"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '分割线颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.line_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.line_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.line_color"></el-input>
                            </div>
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
    Vue.component('u-foot', {
        template: '#u-foot',
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
        computed: {
            barStyle() {
                this.height = this.data.card == 0 ? this.data.style == 2 ? 112 : 136 : this.data.style == 2 ? 144 : 184;
                let style = `height: ${this.height}px;margin-top: ${this.data.margin}px;`;
                if(this.data.card == 1) {
                    style += `padding: ${this.data.card_margin}px 20px;`
                }
                if(this.data.bg == 1) {
                    if(this.data.bg_style == 1) {
                        style += `background-color: ${this.data.bg_color};`
                    }else {
                        style += `background-image: url("${this.data.bg_pic}");background-size: 100% 100%;`
                    }
                }
                return style;
            },
            numberStyle() {
                let style = `color: ${this.data.number_color};font-size: ${this.data.number_size}px;`;
                return style;
            },
            titleStyle() {
                let style = `color: ${this.data.title_color};font-size: ${this.data.title_size}px;`;
                return style;
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
            toggle() {
                let has = (this.data.bg == 0 && this.bg.backgroundColor == '#FFFFFF' && !this.showImg) || (this.data.bg_style == 1 && this.data.bg_color == '#FFFFFF') ? false : true
                let hasValue = {
                    title_color: this.default.title_color,
                    number_color: this.default.number_color,
                    line_color: this.default.line_color,
                    foot_icon: this.default.foot_icon,
                    favorite_icon: this.default.favorite_icon
                }
                let noValue = {
                    title_color: '#9C9C9D',
                    number_color: '#9C9C9D',
                    line_color: '#9C9C9D',
                    foot_icon: this.default.gery_foot_icon,
                    favorite_icon: this.default.gery_favorite_icon
                }
                if(!has) {
                    this.data.title_color = this.data.title_color == hasValue.title_color ? noValue.title_color : this.data.title_color;
                    this.data.foot_icon = this.data.foot_icon == hasValue.foot_icon ? noValue.foot_icon : this.data.foot_icon;
                    this.data.number_color = this.data.number_color == hasValue.number_color ? noValue.number_color : this.data.number_color;
                    this.data.line_color = this.data.line_color == '' || this.data.line_color == hasValue.line_color ? noValue.line_color : this.data.line_color;
                    this.data.favorite_icon = this.data.favorite_icon == '' || this.data.favorite_icon == hasValue.favorite_icon ? noValue.favorite_icon : this.data.favorite_icon;
                }else {
                    this.data.title_color = this.data.title_color == noValue.title_color ? hasValue.title_color : this.data.title_color;
                    this.data.foot_icon = this.data.foot_icon == noValue.foot_icon ? hasValue.foot_icon : this.data.foot_icon;
                    this.data.number_color = this.data.number_color == noValue.number_color ? hasValue.number_color : this.data.number_color;
                    this.data.line_color = this.data.line_color == '' || this.data.line_color == noValue.line_color ? hasValue.line_color : this.data.line_color;
                    this.data.favorite_icon = this.data.favorite_icon == '' || this.data.favorite_icon == noValue.favorite_icon ? hasValue.favorite_icon : this.data.favorite_icon;
                }
            },
            resetImg(obj) {
                let has = (this.data.bg == 0 && this.bg.backgroundColor == '#FFFFFF' && !this.showImg) || (this.data.bg_style == 1 && this.data.bg_color == '#FFFFFF') ? false : true
                let text = obj != 'bg_pic' && !has ? 'gery_' + obj : obj
                this.data[obj] = this.default[text]
            },
            bgPicUrl(e) {
                if (e.length) {
                    this.data.bg_pic = e[0].url;
                    // this.$refs.data.validateField('member_bg_pic_url');
                }
            },
            footPicUrl(e){
                if(e.length){
                    this.data.foot_icon = e[0].url;
                }
            },
            favoritePicUrl(e){
                if(e.length){
                    this.data.favorite_icon = e[0].url;
                }
            },
        },
    });
</script>
