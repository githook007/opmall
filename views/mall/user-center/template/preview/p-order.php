<style>
    .diy-preview.diy-order .order-line {
        width: 2px;
        height: 56px;
        opacity: 0.5;
    }
    .diy-preview.diy-order .order-bar {
        border-radius: 16px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
    }
    .diy-preview.diy-order .order-bar-item {
        display: flex;
        flex-shrink: 1;
        flex-grow: 1;
    }
    .diy-preview.diy-order .order-box-info {
        height: 36px;
        margin-top: 10px;
    }.diy-preview.diy-order .order-box-info.style-2 {
        margin-top: 0;
        margin-right: 24px;
    }
    .diy-preview.diy-order .order-box-info img {
        margin-right: 10px;
        display: block;
    }
    .diy-preview.diy-order .order-title {
        width: 100%;
        padding: 0 24px;
        height: 76px;
        color: #999;
        font-size: 24px;
    }
    .diy-preview.diy-order .order-title img {
        width: 16px;
        height: 24px;
        margin-left: 8px;
    }
    .diy-preview.diy-order .order-menu {
        width: 100%;
        padding: 0 20px;
        height: 120px;
    }
    .diy-preview.diy-order .order-menu .order-menu-item {
        width: 25%;
        height: 100%;
    }
    .diy-preview.diy-order .order-menu .order-menu-item img {
        width: 52px;
        height: 52px;
        margin-bottom: 5px;
    }
</style>
<template id="p-order">
    <div ref="component" class="diy-preview diy-order">
        <div :style="barStyle">
            <div :class="data.mode == 1 ? 'order-bar' : ''" flex="dir:top main:center cross:center" style="height: 100%;" :style="{'background-color': data.mode == 1 ? data.card_color : ''}">
                <div class="order-title" flex="main:justify cross:center">
                    <div :style="titleStyle"><?= \Yii::t('mall/user_center', '我的订单');?></div>
                    <div flex="dir:left cross:center">
                        <div><?= \Yii::t('mall/user_center', '查看更多');?></div>
                        <img src="statics/img/app/user-center/arrow.png" alt="">
                    </div>
                </div>
                <div :class="data.mode == 2 ? 'order-bar' : ''"  class="order-menu" flex="main:center cross:center" :style="{'background-color': data.mode == 2 ? data.card_color : '','margin': data.mode == 2 ? data.card_margin + 'px' : '','height': data.mode == 2 ? '140px' : '120px'}">
                    <div class="order-menu-item" flex="dir:top main:center cross:center">
                        <img :src="data.pay_icon" alt="">
                        <div :style="iconStyle"><?= \Yii::t('mall/user_center', '待付款');?></div>
                    </div>
                    <div class="order-menu-item" flex="dir:top main:center cross:center">
                        <img :src="data.send_icon" alt="">
                        <div :style="iconStyle"><?= \Yii::t('mall/user_center', '待发货');?></div>
                    </div>
                    <div class="order-menu-item" flex="dir:top main:center cross:center">
                        <img :src="data.confirm_icon" alt="">
                        <div :style="iconStyle"><?= \Yii::t('mall/user_center', '待收货');?></div>
                    </div>
                    <div class="order-menu-item" flex="dir:top main:center cross:center">
                        <img :src="data.sale_icon" alt="">
                        <div :style="iconStyle"><?= \Yii::t('mall/user_center', '已完成');?></div>
                    </div>
                    <div class="order-menu-item" flex="dir:top main:center cross:center">
                        <img :src="data.refund_icon" alt="">
                        <div :style="iconStyle"><?= \Yii::t('mall/user_center', '售后');?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-order', {
        template: '#p-order',
        props: {
            value: Object
        },
        data() {
            return {
                height: 136,
                data: {}
            }
        },
        computed: {
            barStyle() {
                this.height = this.data.mode == 1 ? 200 : this.data.mode == 2 ? 198 : 158;
                this.height += this.data.card_margin*2;
                let style = `margin-top: ${this.data.margin}px;`;
                if(this.data.mode != 3) {
                    if(this.data.mode == 2) {
                        style += `padding: 0 20px;`
                    }else {
                        style += `padding: ${this.data.card_margin}px 20px;`
                    }
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
            iconStyle() {
                let style = `color: ${this.data.icon_color};font-size: ${this.data.icon_size}px;`;
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
