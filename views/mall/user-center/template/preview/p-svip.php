<style>
    .diy-preview.diy-svip .buy-user-info {
        margin: 0 24px;
        position: relative;
    }
    .diy-preview.diy-svip .buy-user-info .buy-bg {
        height: 120px;
        width: 702px;
        border-radius: 16px;
        display: block;
    }
    .diy-preview.diy-svip .buy-user-info .buy-logo {
        width: 60px;
        height: 60px;
        position: absolute;
        z-index: 5;
        left: 25px;
        top: 30px;
        display: block;
    }
    .diy-preview.diy-svip .buy-user-info .buy-big {
        position: absolute;
        z-index: 5;
        left: 120px;
        top: 26px;
        font-size: 26px;
        color: #D0B8A5;
    }
    .diy-preview.diy-svip .buy-user-info .buy-small {
        position: absolute;
        z-index: 5;
        left: 120px;
        font-size: 24px;
        bottom: 28px;
        color: #C09878;
    }
    .diy-preview.diy-svip .buy-user-info .buy-btn {
        position: absolute;
        right: 30px;
        top: 34px;
        width: 140px;
        height: 52px;
        line-height: 52px;
        border-radius: 26px;
        text-align: center;
        z-index: 5;
        font-size: 24px;
        color: #5A4D40;
    }
    .diy-preview.diy-svip .buy-user-info .buy-btn.default {
        background: linear-gradient(to right,#fbdec7,#f3bf95);
    }
</style>
<template id="p-svip">
    <div ref="component" class="diy-preview diy-svip">
        <div :style="areaStyle">
            <div class="buy-user-info">
                <img class="buy-bg" :src="data.buy_bg" alt="">
                <img class="buy-logo" src="statics/img/app/vip_card/logo.png" alt="">
                <div class="buy-big" :style="{'color':data.buy_big_color}">{{data.buy_big}}</div>
                <div class="buy-small" :style="{'color':data.buy_small_color}">{{data.buy_small}}</div>
                <div :class="data.buy_btn_bg_color ? 'buy-btn' : 'buy-btn default'" :style="{'background-color': data.buy_btn_bg_color ? data.buy_btn_bg_color : '','color':data.buy_btn_color}">{{data.buy_btn_text}}</div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-svip', {
        template: '#p-svip',
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
                let style = `margin-top: ${this.data.margin}px;padding: ${this.data.card_margin}px 0;`;
                if(this.data.bg_style == 1) {
                    style += `background-color: ${this.data.bg_color};`
                }else {
                    style += `background-image: url("${this.data.bg_pic}");background-size: 100% 100%;`
                }
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
