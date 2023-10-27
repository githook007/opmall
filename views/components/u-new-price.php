<style>
    .u-new-price {
        display: inline-block;
        width: 100%;
    }

    .u-new-price > div {
        line-height: 1;
    }

    .u-new-price .label, .u-new-price .float {
        font-size: 28px;
    }

    .u-new-price .int {
        font-size: 40px;
    }

    .u-new-price .free {
        font-size: 28px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .u-new-price .free-1 {
        font-size: 36px;
    }
    .u-new-price .free-0,.u-new-price .free-3 {
        font-size: 20px;
    }
    .u-new-price .free-0.text,.u-new-price .free-3.text {
        font-size: 28px;
    }
    .u-new-price .free-0 span,.u-new-price .free-3 span {
        font-size: 28px;
    }


    .u-new-price .label.s-0, .u-new-price .label.s-3, .u-new-price .float.s-0, .u-new-price .float.s-3 {
        font-size: 24px;
        padding-bottom: 2px;
    }

    .u-new-price .int.s-0, .u-new-price .int.s-3 {
        font-size: 32px;
    }
</style>
<template id="u-new-price">
    <div class="u-new-price">
        <div :class="[`free free-${listStyle}`]" v-if="sign == 'step' || sign == 'integral_mall'">
            <span>{{extra}}</span>{{pluginText}}<span>{{priceContent}}</span>{{priceContent ? ' <?= \Yii::t('components/other', '元');?>' : ''}}
        </div>
        <div :class="[`free free-${listStyle} ${priceContent == '<?= \Yii::t('components/other', '免费');?>' || priceContent == '<?= \Yii::t('components/other', '价格面议');?>' ? 'text' : ''}`]" v-else-if="priceFree">
            {{priceContent}}
        </div>
        <div v-else flex="dir:left cross:bottom">
            <div class="label" :class="[`s-${listStyle}`]">{{priceLabel}}</div>
            <div class="int" :class="[`s-${listStyle}`]">{{priceInt}}</div>
            <div class="float" :class="[`s-${listStyle}`]">{{priceFloat}}</div>
        </div>
    </div>
</template>
<script>
    Vue.component('u-new-price', {
        template: '#u-new-price',
        props: {
            priceContent: String,
            sign: String,
            extra: Number,
            listStyle: [Number, String],
        },
        data() {
            return {
                bigger: false,
                price: null
            }
        },
        created() {
            if(this.priceContent.indexOf('￥') == 0) {
                this.price = this.priceContent.toString().replace('￥', '');
            }else {
                this.price = this.priceContent
            }
            if(this.price > 10000) {
                this.bigger = true;
                this.price = (+this.price / 10000).toFixed(2);
            }
        },
        computed: {
            priceFree() {
                return this.priceContent === '<?= \Yii::t('components/other', '免费');?>' || this.priceContent === '<?= \Yii::t('components/other', '价格面议');?>';
            },
            pluginText() {
                let text = this.sign == 'step' ? '<?= \Yii::t('components/other', '活力币');?>' : '<?= \Yii::t('components/other', '积分');?>';
                if(this.priceContent) {
                    text = text + '+ '
                }
                return text
            },
            priceLabel() {
                return /^￥\d/.test(this.priceContent) ? '￥' : '';
            },
            priceInt() {
                let m = this.price.match(/(\d+)(.\d*)?$/);
                return m ? m[1] : '';
            },
            priceFloat() {
                let m = this.price.match(/(\d+)(.\d*)?$/);
                if (m && m[2] && m[2] !== '.') {
                    let price = m[2];
                    if(this.bigger) {
                        price = price + 'w'
                    }
                    return price
                } else {
                    return ''
                }
            },
        }
    });
</script>
