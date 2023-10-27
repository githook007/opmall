<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/24
 * Time: 9:08 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
?>
<style>
    .app-show-link .show-name {
        height: 16px;
        line-height: 16px;
    }

    .app-show-link .img {
        width: 7px;
        height: 12px;
        margin: 0 5px;
    }
</style>
<template id="app-show-link">
    <div class="app-show-link" flex="dir:left cross:center" v-if="link">
        <template v-if="link.name">
            <el-tooltip effect="dark" placement="top" :disabled="tooltip ? false : true">
                <div slot="content">{{tooltip}}
                </div>
                <div flex="dir:left cross:center" :style="border"
                 style="padding-top: 6px; padding-bottom: 6px; flex-grow: 1">
                <div class="show-name" :style="nameStyle">
                    <app-ellipsis :line="1">{{link.name}}</app-ellipsis>
                </div>
                <template v-if="link.button_text">
                    <template v-if="link.button_text.show_name">
                        <img class="img" src="statics/img/mall/customer-right.png" alt="">
                        <div class="show-name" :style="showNameStyle">
                            <app-ellipsis :line="1">{{link.button_text.show_name}}</app-ellipsis>
                        </div>
                    </template>
                    <template v-if="link.button_text.second_name">
                        <img class="img" src="statics/img/mall/customer-right.png" alt="">
                        <div class="show-name" :style="secondNameStyle">
                            <app-ellipsis :line="1">{{link.button_text.second_name}}</app-ellipsis>
                        </div>
                    </template>
                    <template v-if="link.button_text.third_name">
                        <img class="img" src="statics/img/mall/customer-right.png" alt="">
                        <div class="show-name" style="flex-grow: 1">
                            <app-ellipsis :line="1">{{link.button_text.third_name}}</app-ellipsis>
                        </div>
                    </template>
                </template>
            </div>
            </el-tooltip>
        </template>
        <template v-else>
            <el-tooltip effect="dark" placement="top" :disabled="link.url ? false : true">
                <div slot="content">{{link.url}}
                </div>
            <div style="padding-top: 6px; padding-bottom: 6px; flex-grow: 1" :style="border">
                <div style="height: 16px;line-height: 16px;">
                    <app-ellipsis :line="1">{{link.url}}</app-ellipsis>
                </div>
            </div>
            </el-tooltip>
        </template>
        <slot></slot>
    </div>
</template>
<script>
    Vue.component('app-show-link', {
        template: '#app-show-link',
        props: {
            link: {
                type: Object | null,
                default: null
            },
            isBorder: {
                type: Boolean,
                default: true
            },
            isPadding: {
                type: Boolean,
                default: true
            },
        },
        computed: {
            nameStyle() {
                if (this.link.button_text) {
                    return `max-width: 70px; flex-grow: 0; flex-shrink: 0`;
                } else {
                    return `width: 100%`;
                }
            },
            showNameStyle() {
                if (this.link.button_text.second_name) {
                    return `max-width: 70px; flex-grow: 0; flex-shrink: 0`;
                } else {
                    return `flex-grow: 1`;
                }
            },
            secondNameStyle() {
                if (this.link.button_text.third_name) {
                    return `max-width: 70px; flex-grow: 0; flex-shrink: 0`;
                } else {
                    return `flex-grow: 1`;
                }
            },
            border() {
                let style = '';
                if (this.isBorder) {
                    style += `border: 1px solid #DCDFE6; border-radius: 3px;`;
                }
                if (this.isPadding) {
                    style += `padding-left: 16px; padding-right: 16px;`
                }
                return style;
            },
            tooltip() {
                let tooltip = this.link.name;
                if (!this.link.button_text) {
                    return tooltip;
                }
                if (this.link.button_text.show_name) {
                    tooltip += '/' + this.link.button_text.show_name
                }
                if (this.link.button_text.second_name) {
                    tooltip += '/' + this.link.button_text.second_name
                }
                if (this.link.button_text.third_name) {
                    tooltip += '/' + this.link.button_text.third_name
                }
                return tooltip;
            }
        }
    });
</script>



