<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 2019/4/23
 * Time: 11:17
 */
?>
<template id="diy-empty">
    <div>
        <div class="diy-component-preview">
            <div style="padding: 20px 0">
                <div class="diy-empty" :style="cStyle"></div>
            </div>
        </div>
        <div v-show="!hidden" class="diy-component-edit">
            <div class="app-form-title">
                <div><?= \Yii::t('components/diy', '空白块');?></div>
            </div>
            <el-form label-width="100px" @submit.native.prevent style="padding: 20px 0">
                <el-form-item label="<?= \Yii::t('components/diy', '背景颜色');?>">
                    <div flex="dir:left cross:center">
                        <el-color-picker size="small" v-model="data.background"></el-color-picker>
                        <el-input size="small" style="width: 64px;margin-left: 8px;"
                                  v-model="data.background"></el-input>
                    </div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('components/diy', '高度');?>">
                    <el-input size="small" v-model.number="data.height" type="number" min="1">
                        <div slot="append">px</div>
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-empty', {
        template: '#diy-empty',
        props: {
            value: Object,
            hidden: Boolean
        },
        data() {
            return {
                data: {
                    background: '#ffffff',
                    height: 10,
                }
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', this.data)
            } else {
                this.data = this.value;
            }
        },
        computed: {
            cStyle() {
                if(this.data.background) {
                    return `background: ${this.data.background};`
                        + `height: ${this.data.height}px;`;
                }else {
                    return `height: ${this.data.height}px;`;
                }
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
        methods: {}
    });
</script>