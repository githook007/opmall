<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/4/23
 * Time: 11:17
 */
?>
<style>
    .diy-search {
        padding: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .diy-search > div {
        height: 60px;
        line-height: 60px;
        padding: 0 24px;
        font-size: 28px;
    }

    .diy-component-edit .el-color-picker {
        vertical-align: middle;
    }
    .chooseLink .el-input-group__append {
        background-color: #fff;
    }
</style>
<template id="diy-search">
    <div>
        <div class="diy-component-preview">
            <div class="diy-search" :style="cBackground">
                <div :style="cSearchBlock" class="frame">{{data.placeholder}}</div>
                <div v-if="data.is_img">
                    <img :src="data.img" style="width: 60px; height: 60px; display: inline-block;"/>
                </div>
            </div>
        </div>
        <div class="diy-component-edit">
            <el-form label-width="100px">
                <el-form-item label="<?= \Yii::t('plugins/diy', '搜索框颜色');?>">
                    <el-color-picker size="small" v-model="data.color"></el-color-picker>
                    <el-input size="small" style="width: 80px;margin-right: 25px;" v-model="data.color"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '背景颜色');?>">
                    <el-color-picker size="small" v-model="data.background"></el-color-picker>
                    <el-input size="small" style="width: 80px;margin-right: 25px;" v-model="data.background"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '圆角');?>">
                    <el-input size="small" v-model.number="data.radius" type="number">
                        <template slot="append">px</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '类型');?>">
                    <app-radio v-model="data.typeStyle" label="0"><?= \Yii::t('plugins/diy', '默认');?></app-radio>
                    <app-radio v-model="data.typeStyle" label="1"><?= \Yii::t('plugins/diy', '沉浸式');?></app-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '提示文字');?>">
                    <el-input size="small" placeholder="<?= \Yii::t('plugins/diy', '最多输入20个字');?>" v-model="data.placeholder" maxlength="20"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '文字颜色');?>">
                    <el-color-picker size="small" v-model="data.textColor"></el-color-picker>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '背景图');?>">
                    <app-image-upload width="750" height="270" v-model="data.picUrl"></app-image-upload>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '右侧图片');?>">
                    <el-switch v-model="data.is_img"></el-switch>
                </el-form-item>
                <el-form-item v-if="data.is_img == 1" label="<?= \Yii::t('plugins/diy', '右侧显示图片');?>">
                    <app-image-upload width="100" height="100" v-model="data.img"></app-image-upload>
                </el-form-item>
                <el-form-item v-if="data.is_img == 1" class="chooseLink" label="<?= \Yii::t('plugins/diy', '链接');?>">
                    <el-input style="width: 300px" v-model="data.img_url" placeholder="<?= \Yii::t('plugins/diy', '点击选择链接');?>" :disabled="true"
                              size="small">
                        <app-pick-link slot="append" @selected="linkSelected">
                            <el-button size="small"><?= \Yii::t('plugins/diy', '选择链接');?></el-button>
                        </app-pick-link>
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('plugins/diy', '文字位置');?>">
                    <app-radio v-model="data.textPosition" label="left"><?= \Yii::t('plugins/diy', '居左');?></app-radio>
                    <app-radio v-model="data.textPosition" label="center"><?= \Yii::t('plugins/diy', '居中');?></app-radio>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-search', {
        template: '#diy-search',
        props: {
            value: Object
        },
        data() {
            return {
                data: {
                    picUrl: '',
                    color: '#ffffff',
                    background: '#f2f2f2',
                    radius: 4,
                    img: '',
                    placeholder: '<?= \Yii::t('plugins/diy', '搜索');?>',
                    textColor: '#555555',
                    textPosition: 'left',
                    typeStyle: '0',
                    is_img: 0,
                    img_url: '',
                    openType: '',
                }
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', this.data)
            } else {
                this.data = Object.assign(this.data, this.value);
            }
        },
        computed: {
            cBackground() {
                if (this.data.picUrl){
                    return `background-image: url('${this.data.picUrl}'); background-repeat:no-repeat; background-size: 100% auto;`;
                } else if(this.data.background) {
                    return `background: ${this.data.background};`;
                } {
                    return ``;
                }
            },
            cSearchBlock() {
                if(this.data.color) {
                    var styleStr = `background: ${this.data.color};`
                        + `border-radius: ${this.data.radius}px;`
                        + `color: ${this.data.textColor};`
                        + `text-align: ${this.data.textPosition};`;

                }else {
                    var styleStr = `border-radius: ${this.data.radius}px;`
                        + `color: ${this.data.textColor};`
                        + `text-align: ${this.data.textPosition};`;
                }
                if(this.data.is_img){
                    styleStr += 'width: 85%;display: inline-block;';
                }else{
                    styleStr += 'width: 100%;display: inline-block;';
                }
                return styleStr;
            },
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
            linkSelected(list) {
                if (!list || !list.length) {
                    return;
                }
                this.data.img_url = list[0].new_link_url;
                this.data.openType = list[0].open_type;
            },
        }
    });
</script>