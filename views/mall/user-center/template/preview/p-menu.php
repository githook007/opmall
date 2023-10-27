<style>
    .diy-preview.diy-menu .menu-title {
        padding: 0 24px;
        font-weight: 600;
    }
    .diy-preview.diy-menu .menu-list {
        flex-wrap: wrap;
        padding-bottom: 20px;
    }
    .diy-preview.diy-menu .menu-list .menu-item {
        width: 25%;
        flex-shrink: 0;
        margin-top: 28px;
    }
    .diy-preview.diy-menu .menu-list .menu-item .icon_url {
        width: 50px;
        height: 50px;
    }
    .diy-preview.diy-menu .menu-list .menu-item div {
        font-size: 24px;
        color: #999999;
        margin-top: 10px;
        width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        text-align: center;
    }
    .diy-preview.diy-menu .menu-list .menu-item .arrow {
        width: 16px;
        height: 24px;
    }
    .diy-preview.diy-menu .menu-list.list {
        padding: 0 24px 20px;    
    }
    .diy-preview.diy-menu .menu-list.list .menu-item {
        width: 100%;
        margin-top: 36px;
    }
    .diy-preview.diy-menu .menu-list.list .menu-item div {
        font-size: 32px;
        color: #333333;
        flex-grow: 1;
        padding: 0 24px;
        margin-top: 0;
        text-align: left;
    }
    .diy-preview.diy-menu .menu-shadow {
        border-radius: 16px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
    }
</style>
<template id="p-menu">
    <div ref="component" class="diy-preview diy-menu" :style="{'padding': data.card == 1 && data.titleInCard == 1 ? data.card_margin + 'px 20px': ''}">
        <div :style="areaStyle" :class="data.card == 1 && data.titleInCard == 1 ? 'menu-shadow' : ''">
            <div class="menu-title" :style="titleStyle" v-if="data.show_title && !(data.card == 1 && data.titleInCard == 1)">{{data.title}}</div>
            <div :style="listStyle">
                <div class="menu-title" :style="titleStyle" v-if="data.show_title && data.card == 1 && data.titleInCard == 1">{{data.title}}</div>
                <div class="menu-list" flex="dir:left cross:center" :class="[data.card == 1 && data.titleInCard == 0 ? 'menu-shadow' : '', data.title_style == 1 ? 'list' : '']" :style="{'background-color': data.card == 1 && data.titleInCard == 0 ? data.card_bg : ''}">
                    <div v-for="(item,index) in data.menus" :key="index" class="menu-item" :flex="data.title_style == 2 ? 'dir:top main:center cross:center' : 'dir:left cross:center'" :style="{'margin-top': index == 0 && !data.show_title ? data.title_style == 1 ? '24px' : '14px' : ''}">
                        <img class="icon_url" :src="item.icon_url" alt="">
                        <div :style="{'color': data.text_color ? data.text_color : '#666666'}">{{item.name}}</div>
                        <img class="arrow" v-if="data.title_style == 1" src="statics/img/app/user-center/arrow.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-menu', {
        template: '#p-menu',
        props: {
            value: Object
        },
        data() {
            return {
                data: {}
            }
        },
        computed: {
            areaStyle() {
                let style = `margin-top: ${this.data.margin}px;`;
                if(this.data.show_title && this.data.card == 1 && this.data.titleInCard == 1) {
                    style += `padding-top: 16px;`
                }
                if(this.data.card == 1 && this.data.titleInCard == 1) {
                    style += `background-color: ${this.data.card_bg};`
                }
                return style;
            },
            listStyle() {
                let style = ''
                if(this.data.card == 1 && this.data.titleInCard == 0) {
                    style += `padding: ${this.data.card_margin}px 20px;`
                }
                return style;
            },
            numberStyle() {
                let style = `color: ${this.data.number_color};font-size: ${this.data.number_size}px;`;
                if(this.data.mode != 1) {
                    style += `margin-bottom: 12px;`
                }
                return style;
            },
            titleStyle() {
                let style = `color: ${this.data.title_color};font-size: ${this.data.title_size}px;`;
                return style;
            }
        },
        created() {
            this.data = JSON.parse(JSON.stringify(this.value));
        },
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
    });
</script>
