<style>
    .c-input-big {
        width: 64px;
        margin-right: 25px;
        margin-left: 5px;
    }
</style>
<template id="app-padding">
    <div>
        <el-form-item label="<?= \Yii::t('components/diy', '组件边距');?>">
            <div style="border: 1px solid #e2e2e2;border-radius:6px;padding-top: 15px;min-width:500px">
                <el-form-item label="<?= \Yii::t('components/diy', '上边距');?>">
                    <div flex="dir:left">
                        <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                   v-model="value.c_padding_top"
                                   :max="50" :min="0"
                                   :show-tooltip="false"></el-slider>
                        <el-input-number size="small" v-model="value.c_padding_top" :min="0"
                                         :max="50" label="<?= \Yii::t('components/diy', '按钮圆角');?>"></el-input-number>
                        <div style="margin-left: 10px">px</div>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '下边距');?>">
                    <div flex="dir:left">
                        <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                   v-model="value.c_padding_bottom"
                                   :max="50" :min="0"
                                   :show-tooltip="false"></el-slider>
                        <el-input-number size="small" v-model="value.c_padding_bottom" :min="0"
                                         :max="50" label="<?= \Yii::t('components/diy', '按钮圆角');?>"></el-input-number>
                        <div style="margin-left: 10px">px</div>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '左右边距');?>">
                    <div flex="dir:left">
                        <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                   v-model="value.c_padding_lr"
                                   :max="50" :min="0"
                                   :show-tooltip="false"></el-slider>
                        <el-input-number size="small" v-model="value.c_padding_lr" :min="0"
                                         :max="50" label="<?= \Yii::t('components/diy', '按钮圆角');?>"></el-input-number>
                        <div style="margin-left: 10px">px</div>
                    </div>
                </el-form-item>
            </div>
        </el-form-item>
        <el-form-item label="<?= \Yii::t('components/diy', '圆角设置');?>">
            <div style="border: 1px solid #e2e2e2;border-radius:6px;padding-top: 15px;min-width:500px">
                <el-form-item label="<?= \Yii::t('components/diy', '上圆角');?>">
                    <div flex="dir:left">
                        <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                   v-model="value.c_border_top"
                                   :max="50" :min="0"
                                   :show-tooltip="false"></el-slider>
                        <el-input-number size="small" v-model="value.c_border_top" :min="0"
                                         :max="50" label="<?= \Yii::t('components/diy', '按钮圆角');?>"></el-input-number>
                        <div style="margin-left: 10px">px</div>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '下圆角');?>">
                    <div flex="dir:left">
                        <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                   v-model="value.c_border_bottom"
                                   :max="50" :min="0"
                                   :show-tooltip="false"></el-slider>
                        <el-input-number size="small" v-model="value.c_border_bottom" :min="0"
                                         :max="50" label="<?= \Yii::t('components/diy', '按钮圆角');?>"></el-input-number>
                        <div style="margin-left: 10px">px</div>
                    </div>
                </el-form-item>
            </div>
        </el-form-item>

        <slot name="c-bg">
            <el-form-item label="<?= \Yii::t('components/diy', '商品背景颜色');?>" v-if="false && value.goodsStyle != 1 && value.goodsStyle != 2">
                <el-color-picker @change="(row) => {row == null ? value.bg = '#FFFFFF' : ''}" size="small"
                                 v-model="value.bg"></el-color-picker>
                <el-input size="small" class="c-input-big"
                          v-model="value.bg"></el-input>
            </el-form-item>
        </slot>
        <slot name="bg">
            <el-form-item label="<?= \Yii::t('components/diy', '底部背景颜色');?>">
                <div flex="dir:left cross:center">
                    <el-color-picker @change="(row) => {row == null ? value.bg_padding = '#F7F7F7' : ''}" size="small"
                                     v-model="value.bg_padding"></el-color-picker>
                    <el-input size="small" class="c-input-big"
                              v-model="value.bg_padding"></el-input>
                </div>
            </el-form-item>
        </slot>
    </div>
</template>
<script>
    Vue.component('app-padding', {
        template: '#app-padding',
        props: {
            value: Object,
        },
        data() {
            return {};
        },
        mounted() {
            this.calcStyle(this.value);
        },
        watch: {
            value: {
                deep: true,
                handler(newVal, oldVal) {
                    this.calcStyle(newVal);
                },
            },
        },
        computed: {},
        methods: {
            calcStyle(v) {
                let {
                    bg_padding,
                    c_padding_top,
                    c_padding_lr,
                    c_padding_bottom,
                    bg,
                    c_border_top,
                    c_border_bottom
                } = v;

                let styleA = {
                    background: bg_padding,
                    padding: `${c_padding_top}px ${c_padding_lr}px ${c_padding_bottom}px`,
                };
                let styleB = {
                    background: `${bg}`,
                    borderTopLeftRadius: `${c_border_top}px`,
                    borderTopRightRadius: `${c_border_top}px`,
                    borderBottomLeftRadius: `${c_border_bottom}px`,
                    borderBottomRightRadius: `${c_border_bottom}px`,
                }
                this.$emit('ss', styleA, styleB);
            }
        }
    });
</script>
