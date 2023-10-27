<style>
    .diy-preview.diy-user .user-area {
        background-color: transparent;
        position: relative;
        z-index: 3;
    }
    .diy-preview.diy-user .user-area .mode-2 .mode-2-top {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .diy-preview.diy-user .user-area .mode-2 .mode-2-radius {
        position: absolute;
        width: 32px;
        height: 32px;
    }
    .diy-preview.diy-user .user-area .mode-3 {
        position: absolute;
        left: 0;
        width: 100%;
        top: 0;
        overflow: hidden;
    }
    .diy-preview.diy-user .user-area .mode-3 div {
        position: absolute;
        z-index: 2;
        bottom: 0;
        width: 4000px;
        height: 4000px;
        border-radius: 50%;
        left: 50%;
        margin-left: -2000px;
    }
    .diy-preview.diy-user .user-area .user-info {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        z-index: 3;
    }
    .diy-preview.diy-user .user-area .user-info .user-avatar {
        overflow: hidden;
        position: relative;
    }
    .diy-preview.diy-user .user-area .user-info .user-avatar img {
        width: 100%;
        height: 100%;
    }
    .diy-preview.diy-user .user-area .user-info .user-avatar .update-btn {
        width: 100%;
        position: absolute;
        bottom: 0;
        left: 0;
        text-align: center;
        color: #FFF;
        background-color: rgba(0, 0, 0, 0.35);
    }
    .diy-preview.diy-user .user-area .user-info .user-name {
        max-width: 320px;
        font-size: 40px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .diy-preview.diy-user .user-area .user-code-icon {
        width: 36px;
        height: 36px;
        margin-left: 8px;
    }
    .diy-preview.diy-user .user-area .user-info .member-info {
        height: 32px;
        border-radius: 16px;
        background-color: rgba(0,0,0,.25);
        color: #fff;
        font-size: 24px;
        width: auto;
        display: inline-block;
        position: relative;
        margin-top: 8px;
    }
    .diy-preview.diy-user .user-area .user-info .member-info div {
        padding: 0 12px 0 36px;
    }
    .diy-preview.diy-user .user-area .user-info .member-info img {
        position: absolute;
        left: 0;
        top: 0;
        border-radius: 50%;
        z-index: 3;
    }
    .diy-preview.diy-user .user-area .address-icon {
        position: absolute;
        right: 0;
        top: 0;
        height: 56px;
        font-size: 28px;
        border-radius: 28px 0 0 28px;
        padding: 0 10px 0 20px;
        z-index: 3;
    }
    .diy-preview.diy-user .user-area .address-icon .user-code-icon {
        margin-right: 8px;
        max-width: 28px;
        height: 28px;
    }
</style>
<template id="p-user">
    <div ref="component" class="diy-preview diy-user">
        <div class="user-area" :style="{'height': all_height + 'px'}">
            <div :style="areaStyle">
                <div class="mode-2" v-if="data.bg_style == 1 && data.mode == 2" >
                    <div class="mode-2-top" :style="{'background-color': data.bg_color,'height': data.bg_height - 32 + 'px'}"></div>
                    <div class="mode-2-radius" :style="{'background': 'radial-gradient(64px at right bottom,transparent 50%,' + data.bg_color +' 50%)','top': data.bg_height - 32 + 'px','left': '0'}"></div>
                    <div class="mode-2-radius" :style="{'background': 'radial-gradient(64px at left bottom,transparent 50%,' + data.bg_color +' 50%)','top': data.bg_height - 32 + 'px', 'right': '0'}"></div>
                </div>

                <div class="mode-3" v-if="data.bg_style == 1 && data.mode == 3" :style="{'height': data.bg_height + 'px'}">
                    <div :style="{'background-color': data.bg_color}"></div>
                </div>
                <div :flex="data.top_style == 2 ? 'dir:top cross:center':'dir:left cross:center'" class="user-info" :style="userStyle">
                    <div :style="avatarStyle" class="user-avatar">
                        <img src="statics/img/common/default-avatar.png" alt="">
                        <div class="update-btn" :style="updateText"><?= \Yii::t('mall/user_center', '刷新');?></div>
                    </div>
                    <div class="user-card">
                        <div flex="dir:left cross:center">
                            <div class="user-name" :style="{'color': data.nickname_color,fontSize: `${data.nickname_size}px`}"><?= \Yii::t('mall/user_center', '用户昵称');?></div>
                            <img class="user-code-icon" v-if="data.code == 1" :src="data.code_pic">
                            <img v-if="data.address.status == 1 && data.address.mode == 2" class="user-code-icon" :src="data.address.pic_url">
                        </div>
                        <div :flex="data.top_style == 2 ? 'dir:top main:center cross:center':'dir:left'">
                            <div class="member-info">
                                <img :src="data.member_pic_url" width="32" height="32">
                                <div>{{data.general_user_text}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="address-icon" flex="main:center cross:center" v-if="data.address.status == 1 && data.address.mode == 1 && data.top_style == 3" :style="addressStyle">
                        <img v-if="data.address.style == 1" class="user-code-icon" :src="data.address.pic_url">
                        <div :style="{'color': data.address.text_color}"><?= \Yii::t('mall/user_center', '收货地址');?></div>
                    </div>
                </div>
                <div class="address-icon" flex="main:center cross:center" v-if="data.address.status == 1 && data.address.mode == 1 && data.top_style != 3" :style="addressStyle">
                    <img v-if="data.address.style == 1" class="user-code-icon" :src="data.address.pic_url">
                    <div :style="{'color': data.address.text_color}"><?= \Yii::t('mall/user_center', '收货地址');?></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('p-user', {
        template: '#p-user',
        props: {
            value: Object,
            bg: Object
        },
        data() {
            return {
                user_height: 0,
                data: {}
            }
        },
        computed: {
            all_height() {
                let height =  this.data.bg_style == '1' ? this.data.bg_height : this.data.img_height;
                let domHeight = +this.user_height + 176;
                let avatar = this.data.top_size;
                if(this.data.top_margin > 0) {
                    domHeight = +this.user_height + +this.data.top_margin + 176
                }
                if(this.data.top_style == 3) {
                    domHeight += 30;
                }
                if(+domHeight > height) {
                    height = domHeight
                }
                if(this.data.address.status == 1 && this.data.address.mode == 1) {
                    let addressHeight = +this.data.address.top_margin + 232;
                    if(+addressHeight > height) {
                        height = addressHeight
                    }
                }
                return height
            },
            areaStyle() {
                let style = ''  
                if(this.data.bg_style == 1) {
                    style += `height: ${this.data.bg_height}px;width:100%;`;
                    if(this.data.mode == 1) {
                        style += `background-color: ${this.data.bg_color};`
                    }
                }else if(this.data.bg_style == 2) {
                    this.getImageInfo();
                    style += `background-image: url("${this.data.top_pic_url}");height: ${this.data.img_height}px;background-size: 100% 100%;`;
                }
                return style;
            },
            avatarStyle() {
                let style = `border-radius: 50%;height: ${this.data.top_size}px;width: ${this.data.top_size}px;`
                if(this.data.top_style != 2) {
                    style += `margin-right: 25px;`
                }
                if(this.data.top_style != 3) {
                    style += `border: 4px solid #ffffff;`
                }
                return style;
            },
            updateText() {
                let style = `height: ${this.data.top_size *27.5/67.5}px;`;
                let fontSize = 32;
                if(this.data.top_size < 100) {
                    fontSize = 24;
                }else if(this.data.top_size > 140) {
                    fontSize = 40;
                }
                style += `font-size: ${fontSize}px;`
                return style;
            },
            userStyle() {
                let cardHeight = this.data.top_size + 40 > 176 ? this.data.top_size + 40 : 176;
                if(this.data.address.status == 1 && this.data.address.mode == 1) {
                    let addressHeight = +this.data.address.top_margin + 56;
                    if(+addressHeight > cardHeight) {
                        cardHeight = addressHeight
                    }
                }
                this.user_height = this.data.top_style == 1 ? this.data.top_size + 8 : this.data.top_style == 3 ? cardHeight : this.data.top_size + 110 > 164 ? this.data.top_size + 110 : 164;
                this.$emit('update', this.user_height);
                let style = `height: ${this.user_height}px;padding: 0 24px 10px;top: ${this.data.top_margin + 176}px;`;
                if(this.data.top_style == 3) {
                    style += `width: 702px;background-color: ${this.data.card_color};border-radius: 8px;left: 24px;box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);`
                }
                return style;
            },
            addressStyle() {
                let top = this.data.top_style == 3 ? 0 : 176;
                let style = `background-color: ${this.data.address.bg_color};top: ${this.data.address.top_margin + top}px;`
                if(this.data.address.border == 1) {
                    style += `border: 1px solid ${this.data.address.border_color};border-right-width: 0;`
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
        methods: {
            getImageInfo() {
                let that = this;
                let img = new Image();
                img.src = that.data.top_pic_url
                img.onload = function () {
                    let rate = img.width / 750;
                    that.data.img_height = img.height / rate;
                }
            }
        }
    });
</script>
