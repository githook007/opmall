<style>
    .diy-component-edit .diy-user .c-input-big {
        width: 100%;
        margin-right: 25px;
        margin-left: 5px;
    }
    .diy-component-edit .diy-user .label {
        font-size: 18px;
        color: #242424;
        font-weight: bold;
        margin: 55px 35px 40px;
    }
    .diy-component-edit .diy-user .mode-choose.bg-mode {
        padding: 20px 0;
        border-radius: 5px;
        border: 1px solid #DCDFE6;
        margin-top: 10px;
    }
    .diy-component-edit .diy-user .mode-choose .classic {
        width: 98px;
        height: 70px;
        border-radius: 3px;
        border: 2px solid #DCDFE6;
        color: #606266;
        font-size: 14px;
        margin-right: 20px;
    }
    .diy-component-edit .diy-user .mode-choose .classic.active {
        color: #409EFF;
        border-color: #409EFF;
    }
    .diy-component-edit .diy-user .mode-choose .classic img {
        margin-bottom: 6px;
        display: block;
    }
    .diy-component-edit .diy-user .mode-choose .classic .text {
        line-height: 1;
    }
    .diy-component-edit .diy-user .mode-choose .classic .login-style {
        height: 24px;
        line-height: 22px;
        text-align: center;
        width: 70px;
        border-radius: 12px;
        border: 1px solid #B5DAFF;
        color: #B5DAFF;
        font-size: 12px;
    }
    .diy-component-edit .diy-user .mode-choose .classic .login-style.style-two {
        border-color: transparent;
    }
    .diy-component-edit .diy-user .mode-choose .classic .login-style.style-three {
        background-color: #B5DAFF;
        color: #fff;
    }
</style>
<template id="u-user">
    <div>
        <div ref="component" class="diy-component-preview diy-user">
            <p-user v-model="data" @update="getUser" :bg="bg"></p-user>
        </div>
        <div class="diy-component-edit">
            <div class="diy-user">
                <div class="app-form-title">
                    <div><?= \Yii::t('mall/user_center', '用户中心');?></div>
                </div>
                <el-form ref="form" :model="form" label-width="132px" size="small" style="padding: 20px 0">
                    <el-form-item label="<?= \Yii::t('mall/user_center', '用户信息背景');?>">
                        <app-radio turn v-model="data.bg_style" :label="1"><?= \Yii::t('mall/user_center', '背景颜色');?></app-radio>
                        <app-radio turn v-model="data.bg_style" :label="2"><?= \Yii::t('mall/user_center', '背景图片');?></app-radio>
                        <div v-if="data.bg_style == 1" class="mode-choose bg-mode">
                            <el-form-item label="<?= \Yii::t('mall/user_center', '背景颜色');?>" label-width="101px">
                                <div flex="dir:left cross:center">
                                    <div flex="dir:left cross:center" style="width: 100%;">
                                        <el-color-picker @change="(row) => {row == null ? data.bg_color = '#FF4544' : ''}"
                                                         size="small"
                                                         v-model="data.bg_color"></el-color-picker>
                                        <el-input size="small" class="c-input-big"
                                                  v-model="data.bg_color"></el-input>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('mall/user_center', '色块样式');?>" label-width="101px">
                                <div flex="dir:left cross:center">
                                    <div flex="dir:top main:center cross:center" @click="data.mode = 1" class="classic" :class="{'active' : data.mode == 1}">
                                        <img src="statics/img/mall/user-center/user-style-1.png" width="32" height="22" alt="">
                                        <div class="text"><?= \Yii::t('mall/user_center', '样式一');?></div>
                                    </div>
                                    <div flex="dir:top main:center cross:center" @click="data.mode = 2" class="classic" :class="{'active' : data.mode == 2}">
                                        <img src="statics/img/mall/user-center/user-style-2.png" width="32" height="22" alt="">
                                        <div class="text"><?= \Yii::t('mall/user_center', '样式二');?></div>
                                    </div>
                                    <div flex="dir:top main:center cross:center" @click="data.mode = 3" class="classic" :class="{'active' : data.mode == 3}">
                                        <img src="statics/img/mall/user-center/user-style-3.png" width="32" height="22" alt="">
                                        <div class="text"><?= \Yii::t('mall/user_center', '样式三');?></div>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('mall/user_center', '色块高度');?>" prop="bg_height" label-width="101px">
                                <div flex="dir:left">
                                    <el-slider style="width: 50%;margin-right: 20px" input-size="mini"
                                               v-model="data.bg_height"
                                               :max="1440" :min="user_height"
                                               :show-tooltip="false"></el-slider>
                                    <el-input-number size="small" v-model="data.bg_height" :min="user_height"
                                                     :max="1440"></el-input-number>
                                    <div style="margin-left: 10px">px</div>
                                </div>
                            </el-form-item>
                        </div>
                        <div v-if="data.bg_style == 2">
                            <div flex style="margin-bottom: 8px;">
                                <app-attachment :multiple="false" :max="1" @selected="topPicUrl">
                                    <el-tooltip effect="dark"
                                                :content="'<?= \Yii::t('mall/user_center', '建议尺寸');?>:750 * ' + bg_height"
                                                placement="top">
                                        <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                    </el-tooltip>
                                </app-attachment>
                                <div style="margin-left: 10px;">
                                    <el-button type="primary" size="mini" @click="resetImg('top_pic_url')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                                </div>
                            </div>
                            <app-gallery :url="data.top_pic_url" :show-delete="true" @deleted="data.top_pic_url = ''"
                                         width="80px" height="80px">
                            </app-gallery>
                            <div style="color: #c9c9c9;"><?= \Yii::t('mall/user_center', '注：请在最上面预留48px的图片高度用于适配全面屏手机，其他手机不显示这部分图片');?></div>
                            <el-button @click="screenDialog = true;"
                                       type="text"><?= \Yii::t('mall/user_center', '查看图例');?>
                            </el-button>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '普通用户图标');?>" prop="member_pic_url">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="memberPicUrl">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('member_pic_url')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.member_pic_url" :show-delete="true" @deleted="data.member_pic_url = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '普通用户文字');?>">
                        <el-input v-model="data.general_user_text"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '会员中心背景图');?>" prop="member_bg_pic_url">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="memberBgPicUrl">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:660 * 320"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('member_bg_pic_url')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.member_bg_pic_url" :show-delete="true" @deleted="data.member_bg_pic_url = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '头像大小');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.top_size"
                                       :max="150" :min="80"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.top_size" :min="80"
                                             :max="150"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '头像样式');?>">
                        <app-radio @change="changeStyle" turn v-model="data.top_style" :label="1"><?= \Yii::t('mall/user_center', '头像靠左');?></app-radio>
                        <app-radio @change="changeStyle" turn v-model="data.top_style" :label="2"><?= \Yii::t('mall/user_center', '头像居中');?></app-radio>
                        <app-radio @change="changeStyle" turn v-model="data.top_style" :label="3"><?= \Yii::t('mall/user_center', '卡片式内嵌');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '卡片颜色');?>" v-if="data.top_style == 3">
                        <div flex="dir:left cross:center">
                            <div flex="dir:left cross:center" style="width: 100%;">
                                <el-color-picker @change="(row) => {row == null ? data.card_color = '#FFFFFF' : ''}"
                                                 size="small"
                                                 v-model="data.card_color"></el-color-picker>
                                <el-input size="small" class="c-input-big"
                                          v-model="data.card_color"></el-input>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '用户昵称大小');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.nickname_size"
                                       :max="50" :min="24"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.nickname_size" :min="24"
                                             :max="80"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '未登录按钮样式');?>">
                        <div flex="dir:left cross:center" class="mode-choose">
                            <div flex="dir:top main:center cross:center" @click="data.login_style = 1" class="classic" :class="{'active' : data.login_style == 1}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <div class="login-style"><?= \Yii::t('mall/user_center', '立即登录');?></div>
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式一');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="data.login_style = 2" class="classic" :class="{'active' : data.login_style == 2}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <div class="login-style style-two"><?= \Yii::t('mall/user_center', '立即登录');?></div>
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式二');?></div>
                            </div>
                            <div flex="dir:top main:center cross:center" @click="data.login_style = 3" class="classic" :class="{'active' : data.login_style == 3}">
                                <div flex="main:center cross:center" style="height: 37px;">
                                    <div class="login-style style-three"><?= \Yii::t('mall/user_center', '立即登录');?></div>
                                </div>
                                <div class="text"><?= \Yii::t('mall/user_center', '样式三');?></div>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item v-if="data.login_style == 3" label="<?= \Yii::t('mall/user_center', '立即登录文字颜色');?>">
                        <div>
                            <div flex="dir:left cross:center">
                                <div flex="dir:left cross:center" style="width: 100%;">
                                    <el-color-picker @change="(row) => {row == null ? data.login_color = '#FFFFFF' : ''}"
                                                     size="small"
                                                     v-model="data.login_color"></el-color-picker>
                                    <el-input size="small" class="c-input-big"
                                              v-model="data.login_color"></el-input>
                                </div>
                            </div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '用户昵称文字颜色');?>">
                        <div>
                            <div flex="dir:left cross:center">
                                <div flex="dir:left cross:center" style="width: 100%;">
                                    <el-color-picker @change="(row) => {row == null ? data.nickname_color = '#FFFFFF' : ''}"
                                                     size="small"
                                                     v-model="data.nickname_color"></el-color-picker>
                                    <el-input size="small" class="c-input-big"
                                              v-model="data.nickname_color"></el-input>
                                </div>
                            </div>
                            <div style="color: #c9c9c9;"><?= \Yii::t('mall/user_center', '注：未登录按钮字体/边框或是填充颜色同用户昵称文字颜色');?></div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '用户信息组上边距');?>">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.top_margin"
                                       :max="228" :min="0"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.top_margin" :min="0"
                                             :max="228"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>

                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址开关');?>">
                        <el-switch v-model="data.address.status" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址摆放样式');?>" v-if="data.address.status == 1">
                        <app-radio turn v-model="data.address.mode" :label="1"><?= \Yii::t('mall/user_center', '贴边');?></app-radio>
                        <app-radio turn v-model="data.address.mode" :label="2"><?= \Yii::t('mall/user_center', '跟随昵称');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址上边距');?>" v-if="data.address.status == 1 && data.address.mode == 1">
                        <div flex="dir:left">
                            <el-slider style="width: 60%;margin-right: 20px" input-size="mini"
                                       v-model="data.address.top_margin"
                                       :max="1440" :min="0"
                                       :show-tooltip="false"></el-slider>
                            <el-input-number size="small" v-model="data.address.top_margin" :min="0"
                                             :max="1440"></el-input-number>
                            <div style="margin-left: 10px">px</div>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址样式');?>" v-if="data.address.status == 1 && data.address.mode == 1">
                        <app-radio turn v-model="data.address.style" :label="1"><?= \Yii::t('mall/user_center', '图文按钮');?></app-radio>
                        <app-radio turn v-model="data.address.style" :label="2"><?= \Yii::t('mall/user_center', '纯文字按钮');?></app-radio>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址图标');?>" v-if="data.address.status == 1">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="addressPic">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:18 * 18"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('address','pic_url')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.address.pic_url" :show-delete="true" @deleted="data.address.pic_url = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址文字颜色');?>" v-if="data.address.status == 1 && data.address.mode == 1">
                        <div flex="cross:center">
                            <el-color-picker
                                    @change="(row) => {row == null ? value.text_color = '#ffffff' : ''}"
                                    size="small"
                                    v-model="data.address.text_color"></el-color-picker>
                            <el-input size="small" style="width:20%;margin-left: 2%"
                                      v-model="data.address.text_color"></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址背景颜色');?>" v-if="data.address.status == 1 && data.address.mode == 1">
                        <div flex="cross:center">
                            <el-color-picker @change="(row) => {row == null ? value.bg_color = '#ff4544' : ''}"
                                             size="small"
                                             v-model="data.address.bg_color"></el-color-picker>
                            <el-input size="small" style="width:20%;margin-left: 2%"
                                      v-model="data.address.bg_color"></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '收货地址背景描边');?>" v-if="data.address.status == 1 && data.address.mode == 1">
                        <el-switch v-model="data.address.border" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/user_center', '背景描边颜色');?>" v-if="data.address.status == 1&& data.address.mode == 1 && data.address.border == 1">
                        <div flex="cross:center">
                            <el-color-picker @change="(row) => {row == null ? value.border_color = '#ffffff' : ''}"
                                             size="small"
                                             v-model="data.address.border_color"></el-color-picker>
                            <el-input size="small" style="width:20%;margin-left: 2%"
                                      v-model="data.address.border_color"></el-input>
                        </div>
                    </el-form-item>
                    <el-form-item v-if="data.show_code == 1" label="<?= \Yii::t('mall/user_center', '付款码开关');?>">
                        <el-switch v-model="data.code" :active-value="1"
                                           :inactive-value="0"></el-switch>
                    </el-form-item>
                    <el-form-item v-if="data.show_code == 1" label="<?= \Yii::t('mall/user_center', '付款码图标');?>" v-if="data.code == 1">
                        <div flex style="margin-bottom: 8px;">
                            <app-attachment :multiple="false" :max="1" @selected="codePic">
                                <el-tooltip effect="dark"
                                            content="<?= \Yii::t('mall/user_center', '建议尺寸');?>:44 * 44"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/user_center', '选择图标');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-left: 10px;">
                                <el-button type="primary" size="mini" @click="resetImg('code_pic')"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
                            </div>
                        </div>
                        <app-gallery :url="data.code_pic" :show-delete="true" @deleted="data.code_pic = ''"
                                     width="80px" height="80px">
                        </app-gallery>
                    </el-form-item>
                </el-form>
                <el-dialog title="<?= \Yii::t('mall/user_center', '大小屏背景图差别图例');?>"
                           :visible.sync="screenDialog" width="870px">
                    <div flex="dir:left main:center" class="app-share">
                        <div style="width: 375px;margin: 0 20px;" flex="dir:top main:center">
                            <div style="height: 180px;width: 375px;" flex="main:center cross:center">
                                <img src="statics/img/mall/user-center/small-screen.png" alt="">
                            </div>
                            <div style="text-align: center;margin: 10px 0;"><?= \Yii::t('mall/user_center', '小屏（非刘海屏）背景图图例');?></div>
                        </div>
                        <div style="width: 375px;margin: 0 20px;" flex="dir:top main:center">
                            <div style="height: 180px;width: 375px;" flex="main:center cross:center">
                                <img src="statics/img/mall/user-center/all-screen.png" alt="">
                            </div>
                            <div style="text-align: center;margin: 10px 0;"><?= \Yii::t('mall/user_center', '大屏（刘海屏）背景图图例');?></div>
                        </div>
                    </div>
                    <div slot="footer" class="dialog-footer">
                        <el-button size="small" @click="screenDialog = false" type="primary"><?= \Yii::t('mall/user_center', '我知道了');?></el-button>
                    </div>
                </el-dialog>
            </div>
        </div>
    </div>
</template>
<script>
    Vue.component('u-user', {
        template: '#u-user',
        props: {
            value: Object,
            bg: Object,
            default: Object
        },
        data() {
            return {
                screenDialog: false,
                bg_height: 408,
                form: {},
                user_height: 0,
                img_height: 0,
                data: {},
                styleList: [
                {},
                {
                    address: {
                        top_margin: 124,
                        style: 1,
                        bg_color: '#101010',
                        text_color: '#FFFFFF'
                    },
                    nickname_color: '#FFFFFF',
                    nickname_size: 40,
                },
                {
                    address: {
                        top_margin: 142,
                        style: 1,
                        bg_color: '#101010',
                        text_color: '#FFFFFF'
                    },
                    nickname_color: '#FFFFFF',
                    nickname_size: 40,
                },
                {
                    address: {
                        top_margin: 64,
                        style: 2,
                        bg_color: '#FFECEC',
                        text_color: '#FF4544'
                    },
                    nickname_color: '#333333',
                    nickname_size: 40,
                }]
            }
        },
        created() {
            this.data = JSON.parse(JSON.stringify(this.default));
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.value.login_style = this.value.login_style ? this.value.login_style : 1;
                this.value.login_color = this.value.login_color ? this.value.login_color : 1;
                this.data = JSON.parse(JSON.stringify(this.value));
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
        methods: {
            getUser(e) {
                this.user_height = e;
            },
            changeStyle() {
                this.bg_height = this.data.top_style == 1 ? 360 : this.data.top_style == 2 ? 438 : 300
                this.data.nickname_color = this.styleList[this.data.top_style].nickname_color;
                this.data.nickname_size = this.styleList[this.data.top_style].nickname_size;
                this.data.address.top_margin = this.styleList[this.data.top_style].address.top_margin;
                this.data.address.style = this.styleList[this.data.top_style].address.style;
                this.data.address.bg_color = this.styleList[this.data.top_style].address.bg_color;
                this.data.address.text_color = this.styleList[this.data.top_style].address.text_color;
                if(this.data.top_style == 3) {
                    this.data.code_pic = this.data.code_pic == this.default.code_pic ? this.default.black_code_pic : this.data.code_pic
                    this.data.top_pic_url = this.data.top_pic_url == this.default.top_pic_url || this.data.top_pic_url == this.default.center_pic_url ? this.default.card_pic_url : this.data.top_pic_url
                }else {
                    if(this.data.top_style == 2) {
                        this.data.top_pic_url = this.data.top_pic_url == this.default.top_pic_url || this.data.top_pic_url == this.default.card_pic_url ? this.default.center_pic_url : this.data.top_pic_url
                    }else {
                        this.data.top_pic_url = this.data.top_pic_url == this.default.center_pic_url || this.data.top_pic_url == this.default.card_pic_url ? this.default.top_pic_url : this.data.top_pic_url
                    }
                    this.data.code_pic = this.data.code_pic == this.default.black_code_pic ? this.default.code_pic : this.data.code_pic
                }
            },
            resetImg(obj,int) {
                if(int) {
                    this.data[obj][int] = this.default[obj][int]
                }else {
                    this.data[obj] = this.default[obj]
                }
            },
            topPicUrl(e) {
                if (e.length) {
                    this.data.top_pic_url = e[0].url;
                }
            },
            memberPicUrl(e) {
                if (e.length) {
                    this.data.member_pic_url = e[0].url;
                }
            },
            memberBgPicUrl(e) {
                if (e.length) {
                    this.data.member_bg_pic_url = e[0].url;
                }
            },
            addressPic(e){
                if(e.length){
                    this.data.address.pic_url = e[0].url;
                }
            },
            codePic(e){
                if(e.length){
                    this.data.code_pic = e[0].url;
                }
            },
        },
    });
</script>
