<style>
    .diy-preview.diy-account .style-1 {
        flex-wrap: wrap;
        width: 100%;
        padding: 0 22px;
    }
    .diy-preview.diy-account .style-1-item {
        position: relative;
        min-width: 336px;
        flex-grow: 1;
        border-radius: 16px;
        margin: 0 15px 20px 0;
        padding: 20px 24px;
    }
    .diy-preview.diy-account .style-1-item img {
        width: 100px;
        height: 100px;
        position: absolute;
        bottom: 0;
        right: 0;
    }
    .diy-preview.diy-account .style-2 {
        border-radius: 16px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
    }
    .diy-preview.diy-account .style-3 .style-list {
        padding: 16px 10px;
    }
    .diy-preview.diy-account .style-3 .style-list img {
        width: 28px;
        height: 28px;
        margin-right: 6px;
    }
    .diy-preview.diy-account .style-3 .style-list .style-3-item {
        min-width: 25%;
        flex-grow: 1;
    }
</style>
<template id="p-account">
    <div ref="component" class="diy-preview diy-account">
        <div class="style-1" flex="main:center cross:center" :style="areaStyle" v-if="data.mode == 1">
            <div class="style-1-item" :style="{'background-color': data.integral_color}" v-if="data.integral == 1">
                <div :style="titleStyle"><?= \Yii::t('mall/user_center', '积分');?></div>
                <div :style="numberStyle">3,000</div>
                <img :src="data.integral_icon" alt="">
            </div>
            <div class="style-1-item" :style="{'background-color': data.balance_color}" v-if="data.balance == 1">
                <div :style="titleStyle"><?= \Yii::t('mall/user_center', '余额');?></div>
                <div :style="numberStyle">1.2w</div>
                <img :src="data.balance_icon" alt="">
            </div>
            <div class="style-1-item" :style="{'background-color': data.coupon_color}" v-if="data.coupon == 1">
                <div :style="titleStyle"><?= \Yii::t('mall/user_center', '优惠券');?></div>
                <div :style="numberStyle">7</div>
                <img :src="data.coupon_icon" alt="">
            </div>
            <div class="style-1-item" :style="{'background-color': data.card_color}" v-if="data.card == 1">
                <div :style="titleStyle"><?= \Yii::t('mall/user_center', '卡券');?></div>
                <div :style="numberStyle">3,000</div>
                <img :src="data.card_icon" alt="">
            </div>
        </div>
        <div class="style-3" :style="areaStyle" v-else>
            <div :class="data.mode == 2 ? 'style-2 style-list':'style-list'" flex="main:center cross:center" style="height: 100%;" :style="{'background-color': data.mode == 2 ? data.card_bg : ''}">
                <div class="style-3-item" flex="dir:top mian:center cross:center" v-if="data.integral == 1">
                    <div :style="numberStyle">3,000</div>
                    <div flex="main:center cross:center">
                        <img v-if="data.show_icon == 1" :src="data.integral_icon" alt="">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '积分');?></div>
                    </div>
                </div>
                <div class="style-3-item" flex="dir:top mian:center cross:center" v-if="data.balance == 1">
                    <div :style="numberStyle">1.2w</div>
                    <div flex="main:center cross:center">
                        <img v-if="data.show_icon == 1" :src="data.balance_icon" alt="">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '余额');?></div>
                    </div>
                </div>
                <div class="style-3-item" flex="dir:top mian:center cross:center" v-if="data.coupon == 1">
                    <div :style="numberStyle">7</div>
                    <div flex="main:center cross:center">
                        <img v-if="data.show_icon == 1" :src="data.coupon_icon" alt="">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '优惠券');?></div>
                    </div>
                </div>
                <div class="style-3-item" flex="dir:top mian:center cross:center" v-if="data.card == 1">
                    <div :style="numberStyle">3,000</div>
                    <div flex="main:center cross:center">
                        <img v-if="data.show_icon == 1" :src="data.card_icon" alt="">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '卡券');?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-account', {
        template: '#p-account',
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
                if(this.data.mode == 2) {
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
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            }
        },
        created() {
            this.data = JSON.parse(JSON.stringify(this.value));
        },
        methods: {
        },
    });
</script>
