<?php
/**
 * Created by IntelliJ IDEA.
 * Date: 2019/5/9
 * Time: 11:58
 */
?>
<style>
    .app-image-upload {
        display: inline-block;
    }

    .app-image-upload .pic-box {
        border: 1px solid #ccc;
        cursor: pointer;
        background-color: #fff;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
    }

    .app-image-upload .pic-box img {
        width: 30px;
        height: 30px;
    }

    .app-image-upload .pic-box .size-tip {
        line-height: 1.35;
        text-align: center;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 12px;
        color: #fff;
        background: rgba(36, 36, 36, 0.5);
        letter-spacing: -1px;
    }

    .app-image-upload .pic-box .size-tip.hover {
        display: none;
    }

    .app-image-upload:hover .pic-box .size-tip.hover {
        display: block;
    }

    .app-image-upload .image-delete {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 5px;
        visibility: hidden;
        z-index: 1;
    }

    .app-image-upload:hover .image-delete {
        visibility: visible;
    }

    .app-image-upload .image-delete i {
        font-size: 12px;
        color: #fff;
    }
    .app-image-upload .hover-text {
        position: absolute;
        left: 50%;
        top: 15px;
        width: 34px;
        text-align: center;
        height: 18px;
        line-height: 18px;
        margin-left: -17px;
        font-size: 12px;
        color: #fff;
        border-radius: 3px;
        background: rgba(36, 36, 36, 0.5);
        display: none;
    }
    .app-image-upload .hover-text.hover-change {
        top: 50%;
        margin-top: -9px;
    }
    .app-image-upload:hover .hover-text {
        display: block;
    }
</style>
<template id="app-image-upload">
    <div class="app-image-upload">
        <app-attachment v-model="url" :tag="cTag">
            <el-tooltip :disabled="!tooltip || !cTooltip" class="item" effect="dark" :content="cTooltip" placement="top">
                <div class="pic-box" v-if="url" :style="{'background-image': 'url('+url+')','width': size ? size + 'px': '70px','height': size ? size + 'px': '70px'}">
                    <div class="hover-text hover-change"><?= \Yii::t('components/other', '修改');?></div>
                    <el-button @click.stop="imageDelete" class="image-delete" icon="el-icon-close" size="mini" circle type="danger"></el-button>
                    <div v-if="(!tooltip || !cTooltip) && cSizeTip" class="hover size-tip">{{cSizeTip}}</div>
                </div>
                <div class="pic-box" :style="{'width': size ? size + 'px': '70px','height': size ? size + 'px': '70px'}" v-else flex="main:center cross:center">
                    <div class="hover-text"><?= \Yii::t('components/other', '上传');?></div>
                    <img src="statics/img/mall/diy-form-img.png" alt="">
                    <div class="size-tip" v-if="cSizeTip">{{cSizeTip}}</div>
                </div>
            </el-tooltip>
        </app-attachment>
    </div>
</template>
<script>
    Vue.component('app-image-upload', {
        template: '#app-image-upload',
        props: ['value', 'width', 'height', 'size', 'tooltip', 'tag'],
        data() {
            return {
                url: '',
            };
        },
        created() {
            this.url = this.value;
        },
        watch: {
            value: {
                handler(newVal, oldVal) {
                    this.url = newVal;
                },
            },
            url: {
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            },
        },
        computed: {
            cSizeTip() {
                if (!this.width && !this.height) {
                    return false;
                }
                return (this.width ? this.width : '') + ' × ' + (this.height ? this.height : '');
            },
            cTooltip() {
                if (!this.tooltip || (!this.width && !this.height)) {
                    return '';
                }
                return '<?= \Yii::t('components/other', '建议尺寸');?>：' + (this.width ? this.width : '') + '*' + (this.height ? this.height + 'px' : '');
            },
            cTag() {
                if (!this.tag) {
                    return '';
                }
                return this.tag;
            }
        },
        methods: {
            imageDelete() {
                this.url = '';
            },
        },
    });
</script>
