<style>
    .diy-preview.diy-foot .foot-line {
        width: 2px;
        height: 56px;
        opacity: 0.5;
    }
    .diy-preview.diy-foot .foot-bar {
        border-radius: 16px;
        box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.1);
    }
    .diy-preview.diy-foot .foot-bar-item {
        display: flex;
        flex-shrink: 1;
        flex-grow: 1;
    }
    .diy-preview.diy-foot .foot-box-info {
        height: 36px;
        margin-top: 10px;
    }
    .diy-preview.diy-foot .foot-box-info.style-2 {
        margin-top: 0;
        margin-right: 24px;
    }
    .diy-preview.diy-foot .foot-box-info img {
        margin-right: 10px;
        display: block;
    }
</style>
<template id="p-foot">
    <div ref="component" class="diy-preview diy-foot">
        <div :style="barStyle">
            <div :class="data.card == 1 ? 'foot-bar' : ''" flex="main:center cross:center" style="height: 100%;" :style="{'background-color': data.card == 1 ? data.card_color : ''}">
                <div class="foot-bar-item" :flex="data.style == 1 ? 'dir:top main:center cross:center' : 'dir:right main:center cross:center'">
                    <div :style="numberStyle">8</div>
                    <div class="foot-box-info" :class="data.style == 2 ? 'style-2':''" flex="main:center cross:center">
                        <img v-if="data.mode == 1" width="36" height="36" :src="data.favorite_icon">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '我的收藏');?></div>
                    </div>
                </div>
                <div class="foot-line" :style="{'background-color': data.line_color}"></div>
                <div class="foot-bar-item" :flex="data.style == 1 ? 'dir:top main:center cross:center' : 'dir:right main:center cross:center'">
                    <div :style="numberStyle">56</div>
                    <div class="foot-box-info" :class="data.style == 2 ? 'style-2':''" flex="main:center cross:center">
                        <img v-if="data.mode == 1" width="36" height="36" :src="data.foot_icon">
                        <div :style="titleStyle"><?= \Yii::t('mall/user_center', '我的足迹');?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-foot', {
        template: '#p-foot',
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
                this.height = this.data.card == 0 ? this.data.style == 2 ? 112 : 136 : this.data.style == 2 ? 144 : 184;
                let style = `height: ${this.height}px;margin-top: ${this.data.margin}px;`;
                if(this.data.card == 1) {
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
