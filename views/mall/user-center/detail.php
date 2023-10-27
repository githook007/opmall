<?php
require __DIR__ . '/template/dothing/plugin.php';
?>

<style>
    body {
        --form-edit: inherit
    }

    .all-components {
        background: #fff;
        padding: 20px;
    }

    .all-components .component-group {
        border: 1px solid #eeeeee;
        width: 300px;
        color: #303133;
        margin-bottom: 20px;
    }

    .all-components .component-group:last-child {
        margin-bottom: 0;
    }

    .all-components .component-group-name {
        height: 35px;
        line-height: 35px;
        background: #f7f7f7;
        padding: 0 20px;
        border-bottom: 1px solid #eeeeee;
    }

    .all-components .component-list {
        margin-right: -2px;
        margin-top: -2px;
        flex-wrap: wrap;
    }

    .all-components .component-list .component-item {
        width: 100px;
        height: 100px;
        border: 0 solid #eeeeee;
        border-width: 0 1px 1px 0;
        text-align: center;
        padding: 15px 0 0;
        cursor: pointer;
        position: relative;
    }

    .all-components .component-list .component-item.active:before {
        content: "";
        display: block;
        position: absolute;
        top: 0;
        width: 98px;
        height: 98px;
        border: 1px solid var(--form-edit);
        left: 0;
    }

    .all-components .component-list .component-icon {
        width: 40px;
        height: 40px;
        margin: 0 auto 3px;
        /*border: 1px solid #eee;*/
    }

    .all-components .component-list .component-name {

    }

    .mobile-framework {
        width: 375px;
        height: 100%;
    }

    .mobile-framework-header {
        position: absolute;
        height: 88px;
        width: 375px;
        left: 62px;
        top: 0;
        z-index: 1000;
        /*line-height: 60px;*/
        background: #333;
        color: #fff;
        text-align: center;
        font-size: 15px;
        padding-top: 20px;
        cursor: pointer;
        background: url('statics/img/mall/head-user.png') no-repeat;
    }

    .mobile-framework-header .search div {
        position: absolute;
        top: 7px;
        line-height: 1;
        left: 12px;
        font-size: 11px;
        max-width: 110px;
        word-break: break-all;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 1;
        overflow: hidden;
    }

    .mobile-framework-body {
        min-height: 645px;
        border: 1px solid #e2e2e2;
        /* background: #f5f7f9; */
    }

    .mobile-framework .diy-component-preview {
        del-cursor: pointer;
        position: relative;
        zoom: 0.5;
        -moz-transform: scale(0.5);
        -moz-transform-origin: top left;
        font-size: 28px;
    }

    @-moz-document url-prefix() {
        .mobile-framework .diy-component-preview {
            cursor: pointer;
            position: relative;
            -moz-transform: scale(0.5);
            -moz-transform-origin: top left;
            font-size: 28px;
            width: 200% !important;
            height: 100%;
            margin-bottom: auto;
        }
        .mobile-framework .active .diy-component-preview {
            border: 2px dashed #409EFF;
            left: -2px;
            right: -2px;
            width: calc(200% + 4px) !important;
        }
    }

    .mobile-framework .diy-component-preview:hover {
        box-shadow: inset 0 0 10000px rgba(0, 0, 0, .03);
    }

    .mobile-framework .diy-component-edit {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 500px;
        right: 0;
        background: #fff;
        padding: 20px;
        display: none;
        cursor: default;
        overflow: auto;
    }

    .diy-component-options {
        position: relative;
    }

    .diy-component-options .el-button {
        height: 25px;
        line-height: 25px;
        width: 25px;
        padding: 0;
        text-align: center;
        border: none;
        border-radius: 0;
        position: absolute;
        margin-left: 0;
    }

    .mobile-framework .active .diy-component-preview {
        border: 2px dashed #409EFF;
        left: -2px;
        right: -2px;
        width: calc(100% + 4px);
    }

    .mobile-framework .active .diy-component-edit {
        display: block;
        del-padding-right: 20%;
        min-width: calc(650px - 20%);
    }

    .all-components {
        max-height: 725px;
        overflow-y: auto;
    }

    .bottom-menu {
        text-align: center;
        height: 54px;
        width: 100%;
    }

    .bottom-menu .el-card__body {
        padding-top: 10px;
    }

    .el-dialog {
        min-width: 800px;
    }

    #ggg div, span {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Chrome/Safari/Opera */
        -khtml-user-select: none; /* Konqueror */
        -moz-user-select: none; /* Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none; /* Non-prefixed version, currently */
    }
</style>
<style>
    .app-nav-bar {
        cursor: pointer;
    }

    .app-nav-bar .nav-input {
        width: 400px;
    }

    .app-nav-bar .reset {
        position: absolute;
        top: 7px;
        left: 90px;
    }
    .del-app-edit .diy-component-edit .el-switch .el-switch__core {
        background-color: #9BA1A9;
        border-color: #9BA1A9;
    }
    .del-app-edit .diy-component-edit .el-switch.is-checked .el-switch__core {
        border-color: #205E9E;
        background-color: #205E9E;
    }
    .del-app-edit .diy-component-edit .el-switch .el-switch__core {
        height: 13px;
        width: 37px;
    }
    .del-app-edit .diy-component-edit .el-switch .el-switch__core:after {
        top: -3.5px;
        left: -1px;
        width: 19px;
        height: 19px;
        background-color: #E5E7EC;
    }
    .del-app-edit .diy-component-edit .el-switch.is-checked .el-switch__core::after {
        left: 100%;
    }
    .del-app-edit .diy-component-edit .el-switch.is-checked .el-switch__core::after{
        background-color: #409EFF;
    }
    .app-edit .diy-component-edit .el-slider__button {
        background-color: #409EFF;
    }
    .app-edit .diy-component-edit .el-color-picker {
        vertical-align: middle;
    }
/*     .app-edit .diy-component-edit .el-color-picker {
        height: 30px;
    }
    .app-edit .diy-component-edit .el-color-picker .el-color-picker__trigger {
        width: 55px;
        height: 30px;
        border-radius: 3px;
        border: 1px solid #E5E7EC;
        padding: 6px 7px 5px;
    }
    .app-edit .diy-component-edit .el-color-picker .el-color-picker__color {
        width: 17px;
        height: 17px;
        border: 1px solid #E5E7EC;
        border-radius: 3px;
    }
    .app-edit .diy-component-edit .el-color-picker .el-color-picker__color .el-color-picker__color-inner {
        border-radius: 3px;
    }
    .app-edit .diy-component-edit .el-color-picker .el-color-picker__icon:before{
        content:"\e790";
    }
    .app-edit .diy-component-edit .el-color-picker .el-color-picker__icon {
        left: 70%;
        font-size: 22px;
        color: #888F9A;
    } */
    .app-edit .diy-component-edit .el-color-picker+.el-input .el-input__inner {
        padding: 0 5px 0 7px;
        height: 30px;
        line-height: 30px;
        font-size: 12px;
    }
    .del-app-edit .diy-component-edit .el-checkbox__input.is-checked .el-checkbox__inner {
        background-color: #FFF;
    }
    .del-app-edit .diy-component-edit .el-checkbox__input.is-checked .el-checkbox__inner::after {
        border-color: #409EFF;
    }
    .del-app-edit .diy-component-edit .el-checkbox__input.is-checked+.el-checkbox__label {
        color: #606266;
    }
    .app-edit .diy-component-edit .el-color-picker--small .el-color-picker__empty.el-icon-close:before {
        color: #ff4544;
    }
</style>
<style>
    .app-edit .app-form-title {
        margin: 0 -20px;
        border-bottom: 1px solid #E5E7EC;
    }

    .app-edit .app-form-title div {
        font-size: 22px;
        font-weight: 600;
        color: #242424;
        padding: 0 10px;
        height: 24px;
        margin-left: 20px;
        line-height: 1;
        margin-top: 10px;
        margin-bottom: 30px;
        border-left: 7px solid #409eff;
    }

    .app-edit .app-form-box-label {
        font-size: 18px;
        color: #242424;
        margin-left: 25px;
        font-weight: 600;
        margin-bottom: 20px
    }

    .app-edit .f-c-box {
        padding: 20px 40px 10px 0;
        border-radius: 13px;
        background: #F3F3F3;
    }

    .app-edit ._diy-form-label {
        padding-left: 20px;
        font-size: 30px;
        white-space: nowrap;
        margin-bottom: 18px;
    }

    .app-edit ._diy-form-label.required:after {
        content: '*';
        color: #FF4544;
        margin-left: -6px;
    }
</style>
<div id="app" v-cloak="">
    <div class="app-edit">
        <el-card shadow="never" style="margin-bottom: 10px">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span
                            style="color: #409EFF;cursor: pointer"
                            @click="$navigate({r:'mall/user-center/setting'})"
                    ><?= \Yii::t('mall/user_center', '用户中心设置');?></span>
                </el-breadcrumb-item>
                <el-breadcrumb-item>{{id > 0 ? '<?= \Yii::t('mall/user_center', '编辑');?>':'<?= \Yii::t('mall/user_center', '新增');?>'}}</el-breadcrumb-item>
            </el-breadcrumb>
        </el-card>

        <div v-loading="loading">
            <div flex="box:first" style="margin-bottom: 10px;min-width: 1280px;height: 725px;">
                <div class="all-components">
                    <el-form @submit.native.prevent label-width="95px">
                        <el-form-item :rules="[{ required: true, validator: validate, trigger: 'change'}]"
                                      label="<?= \Yii::t('mall/user_center', '名称设置');?>"
                                      prop="name">
                            <el-input size="small" show-word-limit v-model="templateName" maxlength="15"></el-input>
                        </el-form-item>
                    </el-form>
                    <el-form label-width="95px">
                        <el-form-item label="<?= \Yii::t('mall/user_center', '设置背景');?>">
                            <el-button size="small" @click="openBgSetting"><?= \Yii::t('mall/user_center', '设置');?></el-button>
                        </el-form-item>
                    </el-form>
                    <div class="component-group" v-for="(group,indexcolor) in allComponents">
                        <div class="component-group-name">{{group.groupName}}</div>
                        <div class="component-list" flex="">
                            <template v-for="item in group.list">
                                <div class="component-item"
                                     :class="{'active': activeId === item.id}"
                                     :style="{'--form-edit': formatColor(indexcolor)}"
                                     @click="selectComponent(item)">
                                    <div class="component-icon" flex="main:center cross:center">
                                        <img :src="item.icon" alt="">
                                    </div>
                                    <div class="component-name">{{item.name}}</div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div style="padding-left: 2px;position: relative;overflow-y: auto">
                    <div id="ggg" style="overflow-y: auto;padding: 0 60px;height: 705px;">
                        <div class="mobile-framework" style="height: 705px;">
                        <div ref="header" class="mobile-framework-header"
                             flex="dir:left main:center"
                             style="color: #ffffff;" :style="{'background-image': 'url(' + top_pic + ')'}">
                        </div>
                        <div @mousewheel="scrollEvent" id="mobile-framework-body" class="mobile-framework-body"
                                 :style="'background-color:'+ bg.backgroundColor+';background-image:url('+bg.backgroundPicUrl+');background-size:'+bg.backgroundWidth+'% '+bg.backgroundHeight+'%;background-repeat:'+repeat+';background-position:'+position">
                            <draggable v-model="components" :options="{filter:'.unmover',preventOnFilter:false}"
                                       v-if="components && components.length" id="child" :move="onMove">
                                <div v-for="(component, index) in components" :key="component.key"
                                     :style="{cursor: component.active || index == 0 ? 'pointer': 'move'}">
                                    <div ref="child" @click="showComponentEdit(component,index)"
                                         :class="[component.active?'active':'',component.active || index == 0 ? 'unmover': '']">
                                        <div class="diy-component-options" v-if="component.active && index > 0">
                                            <el-button type="primary"
                                                       icon="el-icon-delete"
                                                       @click.stop="deleteComponent(index)"
                                                       style="left: -25px;top:0;"></el-button>
                                            <el-button type="primary"
                                                       icon="el-icon-plus"
                                                       @click.stop="copyComponent(index)"
                                                       style="left: -25px;top:30px;"></el-button>
                                            <el-button v-if="index > 1 && components.length > 1"
                                                       type="primary"
                                                       icon="el-icon-arrow-up"
                                                       @click.stop="moveUpComponent(index)"
                                                       style="right: -25px;top:0;"></el-button>
                                            <el-button v-if="components.length > 1 && index < components.length-1"
                                                       type="primary"
                                                       icon="el-icon-arrow-down"
                                                       @click.stop="moveDownComponent(index)"
                                                       style="right: -25px;top:30px;"></el-button>
                                        </div>

                                        <?php foreach ($other as $component) : ?>
                                            <diy-<?= $component ?> v-if="component.id === '<?= $component ?>'"
                                                                   :active="component.active"
                                                                   v-model="component.data"
                                            ></diy-<?= $component ?>>
                                        <?php endforeach; ?>


                                        <?php foreach ($components as $component) : ?>
                                            <<?= $component ?> v-if="`u-${component.id}` === '<?= $component ?>'"
                                                                   :active="component.active"
                                                                    @close="closeComponent(index)"
                                                                    :bg="bg"
                                                                    :default="defaultComponents[`${component.id}`]"
                                                                   :all-data="components"
                                        :other-info="otherInfo"
                                                                   v-model="component.data"
                                            ></<?= $component ?>>
                                        <?php endforeach; ?>
                                </div>
                            </div>
                        </draggable>
                        <div v-else flex="main:center cross:center"
                             style="height: 200px;color: #adb1b8;text-align: center;">
                            <div>
                                <i class="el-icon-folder-opened"
                                   style="font-size: 32px;margin-bottom: 10px"></i>
                                <div><?= \Yii::t('mall/user_center', '空空如也');?></div>
                                <div><?= \Yii::t('mall/user_center', '请从左侧组件库添加组件');?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <el-dialog title="<?= \Yii::t('mall/user_center', '背景设置');?>" :visible.sync="bgVisible">
        <el-form @submit.native.prevent label-width="100px">
            <diy-bg v-if="bgVisible" :data="bgSetting" :background="bgVisible" :hr="!bgVisible" @update="updateData"
                    @toggle="toggleData" @change="changeData"></diy-bg>
        </el-form>
        <div slot="footer">
            <el-button size="small" @click="bgVisible = false"><?= \Yii::t('mall/user_center', '取消');?></el-button>
            <el-button size="small" @click="beSettingBg" type="primary"><?= \Yii::t('mall/user_center', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-card class="bottom-menu" shadow="never">
        <div>
            <el-button size="small" @click="onSubmit" type="primary" :loading="submitLoading"><?= \Yii::t('mall/user_center', '保存');?></el-button>
            <el-button size="small" @click="reset" :loading="submitLoading"><?= \Yii::t('mall/user_center', '恢复默认');?></el-button>
        </div>
    </el-card>
</div>
</div>
<script>
    let {log, warn, error, table} = console;
    const app = new Vue({
        el: '#app',
        data() {
            var validate = (rule, value, callback) => {
                if (this.templateName) {
                    callback();
                } else {
                    callback(new Error('<?= \Yii::t('mall/user_center', '用户中心名称不能为空');?>'))
                }
            };
            return {
                validate: validate,
                activeId: '',
                formVisible: false,
                defaultComponents: {},
                templateName: '',


                loading: false,
                allComponents: [],
                components: [],

                bgVisible: false,
                bg: {
                    showImg: false,
                    backgroundColor: '#FFFFFF',
                    backgroundPicUrl: '',
                    position: 5,
                    mode: 1,
                    backgroundHeight: 100,
                    backgroundWidth: 100,
                    positionText: 'center center',
                    repeatText: 'no-repeat',
                },
                bgSetting: {
                    showImg: false,
                    backgroundColor: '#f5f7f9',
                    backgroundPicUrl: '',
                    position: 5,
                    mode: 1,
                    backgroundHeight: 100,
                    backgroundWidth: 100,
                    positionText: 'center center',
                    repeatText: 'no-repeat',
                },
                position: 'center center',
                repeat: 'no-repeat',
                id: null,
                maxLimit: 20,
                showShare: -1,
                submitLoading: false,
                overrun: null,
                otherInfo: null,
                is_top: true
            };
        },
        watch: {
            components: {
                deep: true,
                handler(newVal, oldVal) {
                },
            }
        },
        computed: {
            top_pic: function(){
                let url = this.is_top ? 'statics/img/mall/head-user.png' : 'statics/img/mall/head-user-white.png';
                return url
            }
        },
        methods: {
            scrollEvent(e) {
                let header = this.$refs['header'].getBoundingClientRect().top;
                let child = this.$refs['child'][0].getBoundingClientRect().top;
                if(child - header > 0) {
                    this.is_top = true;
                }else {
                    this.is_top = false;
                }
            },
            onMove(e) {
                if (e.relatedContext.element.id == 'user') return false;
                return true
            },
            buttonTime(e){
                this.otherInfo.time.update = this.otherInfo.time.update++;
            },
            selectComponent(e) {
                if (this.overrun && !this.overrun.is_diy_module_overrun
                    && this.components.length >= this.overrun.diy_module_overrun) {
                    this.$message.error('<?= \Yii::t('mall/user_center', '最多添加');?>' + this.overrun.diy_module_overrun + '<?= \Yii::t('mall/user_center', '个组件');?>');
                    return;
                }
                if (e.single) {
                    for (let i in this.components) {
                        if (this.components[i].id === e.id) {
                            this.$message.error('<?= \Yii::t('mall/user_center', '该组件只允许添加一个');?>');
                            return;
                        }
                    }
                }
                let currentIndex = this.components.length;
                for (let i in this.components) {
                    if (this.components[i].active) {
                        currentIndex = parseInt(i) + 1;
                        break;
                    }
                }
                const component = {
                    id: e.id,
                    data: null,
                    active: false,
                    key: randomString(),
                    permission_key: e.key ? e.key : ''
                };

                this.components.splice(currentIndex, 0, component);
                this.$nextTick(function () {
                    this.activeId = e.id;
                    let document = this.$el.querySelector('#child').childNodes[currentIndex];
                    this.showComponentEdit(component, currentIndex);
                    this.$el.querySelector('#ggg').scrollTop = document.offsetTop - 200;
                });
            },
            showComponentEdit(component, index) {
                console.log(component, index)
                for (let i in this.components) {
                    this.components[i].active = i == index;
                }
                this.activeId = component.id;
            },
            closeComponent(index) {
                setTimeout(()=>{
                    this.components.splice(index, 1);
                },0)
            },
            deleteComponent(index) {
                this.components.splice(index, 1);
            },
            copyComponent(index) {
                if (this.overrun && !this.overrun.is_diy_module_overrun
                    && this.components.length >= this.overrun.diy_module_overrun) {
                    this.$message.error('<?= \Yii::t('mall/user_center', '最多添加');?>' + this.overrun.diy_module_overrun + '<?= \Yii::t('mall/user_center', '个组件');?>');
                    return;
                }
                for (let i in this.allComponents) {
                    for (let j in this.allComponents[i].list) {
                        if (this.allComponents[i].list[j].id === this.components[index].id) {
                            if (this.allComponents[i].list[j].single) {
                                this.$message({message: '<?= \Yii::t('mall/user_center', '该组件只允许添加一个');?>', type: 'error', center: true});
                                return;
                            }
                        }
                    }
                }
                let json = JSON.stringify(this.components[index]);
                console.log(json);
                let copy = JSON.parse(json);
                copy.active = false;
                copy.key = randomString();
                this.components.splice(index + 1, 0, copy);
            },
            moveUpComponent(index) {
                this.swapComponents(index, index - 1);
            },
            moveDownComponent(index) {
                this.swapComponents(index, index + 1);
            },
            swapComponents(index1, index2) {
                this.components[index2] = this.components.splice(index1, 1, this.components[index2])[0];
            },
            beSettingBg() {
                this.bg = JSON.parse(JSON.stringify(this.bgSetting));
                this.position = this.bgSetting.positionText;
                this.repeat = this.bgSetting.repeatText;
                this.bgVisible = false;
            },
            openBgSetting() {
                this.bgSetting = JSON.parse(JSON.stringify(this.bg));
                this.bgSetting.positionText = this.position;
                this.bgSetting.repeatText = this.repeat;
                this.bgVisible = true;
            },
            openPageSetting() {
                if (this.type === 'module') return
                this.hasNavShow = true;
                this.showComponentEdit('', '-1');
            },
            updateData(e) {
                this.bgSetting = e;
            },
            toggleData(e) {
                this.bgSetting.positionText = e;
            },
            changeData(e) {
                this.bgSetting.repeatText = e;
            },
            formatColor(e) {
                if (e === 0) {
                    return '#A342C0';
                } else if (e === 1) {
                    return '#078cff';
                } else if (e === 2) {
                    return '#2FD596';
                }
            },
            onSubmit() {
                if (!this.templateName) {
                    this.$message({message: '<?= \Yii::t('mall/user_center', '用户中心名称不能为空');?>', type: 'error', center: true});
                    return;
                }
                let hasBackGround = false;

                for (let i in this.components) {
                    if (this.components[i].id === 'background') {
                        hasBackGround = true;
                        this.components[i].data = this.bg;
                    }
                }
                if (!hasBackGround) this.components.push({
                    id: 'background',
                    permission_key: '',
                    data: this.bg
                })
                const postComponents = [];
                for (let i in this.components) {
                    if (this.components[i]['id'] === 'customer') {
                        let {title,wechat,select_style,sub_title} = this.components[i]['data'];
                        if (!title) {
                            this.$message({message: '<?= \Yii::t('mall/user_center', '客服标题不能为空');?>', type: 'warning', center: true});
                            return;
                        }
                        if(!wechat || !wechat.length){
                            this.$message({message: '<?= \Yii::t('mall/user_center', '客服微信不能为空');?>', type: 'warning', center: true});
                            return;
                        }
                        if(select_style === '2' && !sub_title){
                            this.$message({message: '<?= \Yii::t('mall/user_center', '副标题不能为空');?>', type: 'warning', center: true});
                            return;
                        }
                    }
                    if (this.components[i]['id'] === 'banner') {
                        let banner = this.components[i]['data']['banners'];
                        if (banner.length === 0) {
                            this.$message({message: '<?= \Yii::t('mall/user_center', '轮播图不能为空');?>', type: 'warning', center: true});
                            return;
                        }
                        if (banner.length < 2) {
                            this.$message({message: '<?= \Yii::t('mall/user_center', '轮播图图片不能少于2张');?>', type: 'warning', center: true});
                            return;
                        }
                        for (let i = 0; i < banner.length; i++) {
                            if (!banner[i].picUrl) {
                                this.$message({message: '<?= \Yii::t('mall/user_center', '轮播图图片不能为空');?>', type: 'warning', center: true});
                                return;
                            }
                        }
                    }
                    postComponents.push({
                        id: this.components[i].id,
                        permission_key: this.components[i].permission_key,
                        data: this.components[i].data,
                    });
                }
                this.submitLoading = true;
                let para = {
                    name: this.templateName,
                    data: JSON.stringify(postComponents),
                }
                if(this.id) {
                    para.id = this.id
                }
                this.$request({
                    params: {
                        r: 'mall/v2/user-center/detail'
                    },
                    method: 'post',
                    data: para,
                }).then(response => {
                    this.submitLoading = false;
                    if (response.data.code === 0) {
                        this.$message({message: response.data.msg, type: 'success', center: true});
                        if(!this.id) {
                            this.$navigate({
                                r: 'mall/user-center/setting',
                            });
                        }
                    } else {
                        this.$message({message: response.data.msg, type: 'error', center: true});
                    }
                }).catch(e => {
                });
            },
            reset() {
                let list = [{key:'user'},{key:'foot'},{key:'svip'},{key:'order'},{key:'member'},{key:'account'},{key:'menu'}];
                let components = [];
                for (let i in list) {
                    if(this.defaultComponents[list[i].key]) {
                        let para = {
                            active: false,
                            key: randomString(),
                            id: list[i].key,
                            data: this.defaultComponents[list[i].key]
                        }
                        components.push(para)
                    }
                }
                this.components = components;
            },
            getDetail() {
                let self = this;
                self.loading = true;
                request({
                    params: {
                        r: 'mall/v2/user-center/detail',
                        id: this.id
                    },
                    method: 'get',
                }).then(response => {
                    self.loading = false;
                    if (response.data.code == 0) {
                        let {allComponents, overrun, name, data, defaultComponents} = response.data.data.data
                        this.allComponents = allComponents;
                        this.overrun = overrun;
                        this.templateName = name;
                        this.defaultComponents = defaultComponents;
                        const components = JSON.parse(data);
                        if(components.length > 0) {
                            for (let i in components) {
                                components[i].active = false;
                                components[i].key = randomString();
                                if (components[i].id == 'background') {
                                    this.bg = components[i].data;
                                    this.bgSetting = this.bg;
                                    this.position = this.bg.positionText;
                                    this.repeat = this.bg.repeatText;
                                }
                            }
                            this.components = components;
                        }else {
                            this.reset();
                        }
                    } else {
                        self.$message.error(response.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            addressPic(e){
                if(e.length){
                    this.ruleForm.address.pic_url = e[0].url;
                }
            },
            removeAddressPic(){
                this.ruleForm.address.pic_url = '';
            },
            topPicUrl(e) {
                if (e.length) {
                    this.ruleForm.top_pic_url = e[0].url;
                    this.$refs.ruleForm.validateField('top_pic_url');
                }
            },
            memberPicUrl(e) {
                if (e.length) {
                    this.ruleForm.member_pic_url = e[0].url;
                    this.$refs.ruleForm.validateField('member_pic_url');
                }
            },
            memberBgPicUrl(e) {
                if (e.length) {
                    this.ruleForm.member_bg_pic_url = e[0].url;
                    this.$refs.ruleForm.validateField('member_bg_pic_url');
                }
            },
            // 订单图标
            iconUrl(e, params) {
                if (e.length) {
                    this.dialogForm.icon_url = e[0].url;
                }
            },
            // 添加链接
            selectLinkUrl(e) {
                let self = this;
                e.forEach(function (item, index) {
                    let obj = {
                        icon_url: item.icon,
                        name: item.name,
                        link_url: item.new_link_url,
                        open_type: item.open_type,
                        params: item.params ? item.params : []
                    }
                    if (item.key) {
                        obj.key = item.key
                    }
                    self.ruleForm.menus.push(obj);
                })
            },
            // 删除链接
            menuDestroy(index) {
                this.ruleForm.menus.splice(index, 1);
            },
            // 编辑
            openDialogForm(item, index, type) {
                let self = this;
                self.dialogFormVisible = true;
                self.dialogFormType = type;
                self.dialogFormIndex = index;
                let dialogForm = JSON.parse(JSON.stringify(item));
                if (type == 2) {
                    dialogForm.name = item.text;
                    dialogForm.icon_url = item.icon
                }
                this.dialogForm = dialogForm;
            },
            dialogFormConfirm() {
                if (this.dialogFormType == 3 && this.dialogForm && this.dialogForm.open_type === 'tel' && this.dialogForm.params.length) {
                    let value = this.dialogForm.params[0].value;
                    let sentinel = /(^1\d{10}$)|(^([0-9]{3,4}-)?\d{7,8}$)|(^400[0-9]{7}$)|(^800[0-9]{7}$)|(^(400)-(\d{3})-(\d{4})(.)(\d{1,4})$)|(^(400)-(\d{3})-(\d{4}$))/.test(value);
                    if (!sentinel) {
                        this.$message({
                            message: '<?= \Yii::t('mall/user_center', '请填写有效的联系电话或手机');?>',
                            type: 'error'
                        });
                        return;
                    }
                }
                this.dialogFormVisible = false;
                if (this.dialogFormType == 1) {
                    this.ruleForm.order_bar[this.dialogFormIndex] = this.dialogForm;
                }
                if (this.dialogFormType == 2) {
                    this.ruleForm.account_bar[this.dialogFormIndex].text = this.dialogForm.name;
                    this.ruleForm.account_bar[this.dialogFormIndex].icon = this.dialogForm.icon_url;
                }
                if (this.dialogFormType == 3) {
                    this.ruleForm.menus[this.dialogFormIndex] = this.dialogForm;
                }
                if (this.dialogFormType == 4) {
                    this.ruleForm.foot_bar[this.dialogFormIndex] = this.dialogForm;
                }
            },
        },
        created: function () {
            this.id = getQuery('id');
            this.getDetail();
        }
    });
</script>
