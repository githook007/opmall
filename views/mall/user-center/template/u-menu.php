<style>
    .diy-component-edit .diy-menu .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-menu .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
    .diy-component-edit .diy-menu .mode-choose .classic .text {
        line-height: 1;
    }
    .diy-component-edit .diy-menu .menus-box {
        border: 1px solid #eeeeee;
        background: #F6F8F9;
    }
    .diy-component-edit .diy-menu .menu-add {
        text-align: right;
        background: #ffffff;
        height: 50px;
        line-height: 50px;
        padding-right: 10px;
    }
    .diy-component-edit .diy-menu .menu-list {
        max-height: 564px;
        overflow-y: auto;
    }
    .diy-component-edit .diy-menu .menus-box .menu-item {
        cursor: move;
        background-color: #fff;
        height: 70.5px;
        border-top: 1px solid #DCDFE6;
    }
</style>
<template id="u-menu">
    <div>
        <div ref="component" class="diy-component-preview diy-menu">
            <p-menu v-model="data"></p-menu>
        </div>
        <div class="diy-component-edit">
            <div class="diy-menu">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '菜单栏');?></div>
                </div>
                <el-form ref="data" :model="data" label-width="118px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '菜单标题栏');?>">
                        <el-switch v-model="data.show_title" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '标题名称');?>" v-if="data.show_title == 1">
                        <el-input size="small" v-model="data.title" class="c-input"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '菜单栏样式');?>">
                        <app-radio turn v-model="data.title_style" :label="1"><?= \Yii::t('mall/user_center', '列表样式');?></app-radio>
                        <app-radio turn v-model="data.title_style" :label="2"><?= \Yii::t('mall/user_center', '宫格样式');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '菜单栏文字颜色');?>" v-if="data.show_title == 1">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.title_color = '#333333' : ''}"
                                                 size="small"
                                                 v-model="data.title_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.title_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '菜单栏文字字号');?>">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '列表文字颜色');?>">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.text_color = '#666666' : ''}"
                                                 size="small"
                                                 v-model="data.text_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.text_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片开关');?>">
                        <el-switch v-model="data.card" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '底部卡片颜色');?>" v-if="data.card == 1">
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片包含标题');?>" v-if="data.card == 1 && data.show_title == 1">
                        <el-switch v-model="data.titleInCard" :active-value="1"
                                           :inactive-value="0"></el-switch>
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
                    <el-form-item label="<?= \Yii::t('mall/user_center', '菜单栏');?>">
                        <div class="menus-box">
                            <div class="menu-add">
                                <app-pick-link type="multiple" @selected="selectLinkUrl">
                                    <el-button type="primary" size="small"><?= \Yii::t('mall/user_center', '添加');?></el-button>
                                </app-pick-link>
                            </div>
                            <draggable v-model="data.menus" class="menu-list">
                                <div v-for="(item, index) in data.menus"
                                     flex="main:justify cross:center box:justify"
                                     class="menu-item">
                                    <div style="margin: 0 15px 0 20px;height: 40px;">
                                        <app-image-upload :tooltip="true" size="40" width="50" height="50" v-model="item.icon_url">
                                        </app-image-upload>
                                    </div>
                                    <el-input size="small" v-model="item.name" class="c-input"></el-input>
                                    <div flex="dir-left" style="width: 36px;margin: 5px 28px 5px 20px;">
                                        <el-button style="padding: 0" @click="menuDestroy(index)" type="text"
                                                   circle
                                                   size="mini">
                                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/user_center', '删除');?>" placement="top">
                                                <img src="statics/img/mall/del.png" alt="">
                                            </el-tooltip>
                                        </el-button>
                                    </div>
                                </div>
                            </draggable>
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
    Vue.component('u-menu', {
        template: '#u-menu',
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
                this.data.text_color = this.data.text_color ? this.data.text_color : '#666666';
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
            // 添加链接
            selectLinkUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    let obj = {
                        icon_url: item.icon,
                        name: item.name,
                        link_url: item.new_link_url,
                        open_type: item.open_type,
                        params: item.params ? item.params : []
                    }
                    if (item.key) {
                        obj.key = item.key
                    }
                    self.data.menus.push(obj);
                })
            },
            // 删除链接
            menuDestroy(index) {
                this.data.menus.splice(index, 1);
            },
        },
    });
</script>
