<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/25
 * Time: 10:19
 */

$mchId = Yii::$app->user->identity->mch_id;
$is = 0;
if(!empty($userId = \Yii::$app->session->get('__fast_login_user_id')) && $userId == Yii::$app->user->id){
    $is = 1;
}
?>
<style>
    .mall-header .aside-logo {
        /*width: 60px;*/
        height: 60px;
        display: flex;
        flex-direction: row;
        align-items: center;
        /*justify-content: center;*/
        background: #1e222d;
        color: #f2f2f2;
        cursor: pointer;
        font-weight: bold;
        /*text-align: center;*/
        padding: 0 10px;
    }

    .mall-header .aside-logo:hover {
        color: #fff;
    }

    .mall-header .aside-logo div {
        background: #262f3e;
        /*padding: 6px 6px;*/
        width: 100%;
        border-radius: 3px;
        /*margin: 10px 0;*/
    }

    .mall-header .aside-logo img {
        height: calc(40px + 2px);
        width: calc(40px + 2px);
        border-radius: 50%;
        border: 2px solid #ffffff;
        margin-right: 20px;
        /*display: block;*/
        /*margin-top: 10px;*/
    }
    .mall-header {
        /*margin-left: 30px;*/
    }

    .mall-header .mall-name {
        max-width: 120px;
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .mall-header .mall-name-icon {
        background: #2670E8;
        width: 36px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        border-radius: 36px;
        font-size: 12px;
    }

    .mall-header .el-menu.el-menu--horizontal {
        border-bottom: 0;
    }

    .mall-header-menu .el-menu--popup {
        min-width: 1px;
        width: 200px;
        text-align: center;
    }

    .mall-header-menu .el-menu--popup .is-disabled {
        opacity: .65;
        cursor: default;
        background-color: white;
    }
    .mall-header .el-menu-item {
        padding: 0 15px;
    }
    .el-submenu .el-submenu__title {
        display: flex;
        align-items: center;
    }
    .flex-row{
        display: flex;
    }
    .header-menu-item{
        padding: 0 15px;
        color: white;
        height: 60px;
        line-height: 60px;
        cursor: pointer;
    }
</style>
<template id="mall-header">
    <div style="background: #1e222d;">
        <header class="mall-header" flex="box:last">
            <el-menu class="el-menu-demo flex-row" mode="horizontal" menu-trigger="click"
                     background-color="#1e222d"
                     active-text-colo="#006eff"
                     text-color="#fff">
                <el-menu-item v-if="showIcon" style="padding: 0; margin-right: 40px;">
                    <div class="aside-logo" @click="indexClick" flex="dir:top main:center cross:center">
                        <template v-if="mall">
                            <img v-if="mall.mall_logo_pic" :src="mall.mall_logo_pic" alt=""/>
                        </template>
                        <span>{{mall.name}}</span>
                    </div>
                </el-menu-item>

                <div v-for="(nav, index) in navs"
                     :key="index"
                     :index="'' + index"
                     class="header-menu-item"
                     @click="$navigate(nav.url)">{{nav.name}}
                </div>
            </el-menu>
            <el-menu class="menu-box" mode="horizontal" menu-trigger="hover"
                     background-color="#1e222d"
                     text-color="#fff">
                <el-menu-item @click="fastLoginBack" index="5" v-if="is > 0">
                    <el-button type="primary" size="small">
                        <?= \Yii::t('layouts/layouts', '返回店铺列表');?>
                    </el-button>
                </el-menu-item>
                <el-menu-item @click="navigateClick({r: 'mall/file/index'})" index="5">
                    <img ref="system_download_icon" id="system-download-icon" src="statics/img/mall/download-1.png">
                </el-menu-item>
                <el-menu-item v-if="mch_id == 0" @click="navigateClick({r: 'admin/cache/clean', '_layout': 'mall'})" index="4"><?= \Yii::t('layouts/layouts', '缓存');?>
                </el-menu-item>
                <el-submenu v-if="mch_id == 0 && courseMenu.children && courseMenu.children.length > 0" index="3" v-if="courseMenu" popper-class="mall-header-menu">
                    <template slot="title">
                        <span :title="courseMenu.name" class="mall-name">{{courseMenu.name}}</span>
                    </template>
                    <el-menu-item @click="navigateClick({r: item.route})" v-for="(item, index) in courseMenu.children"
                                  :key="index" :index="'1-' + index">
                        {{item.name}}
                    </el-menu-item>
                </el-submenu>
                <el-submenu index="4" v-if="courseMenu" popper-class="mall-header-menu">
                    <template slot="title">
                        <span :title="courseMenu.name" class="mall-name"><?= \Yii::t('common', '语言');?></span>
                    </template>
                    <el-menu-item @click="switchIng('cn')"><?= \Yii::t('common', '中文');?></el-menu-item>
                    <el-menu-item @click="switchIng('en')"><?= \Yii::t('common', '英语');?></el-menu-item>
                </el-submenu>
                <el-submenu index="2" v-if="mall && user" popper-class="mall-header-menu">
                    <template slot="title">
                        <span :title="mall.name" class="mall-name mall-name-icon">{{mall.name.substr(0,2)}}</span>
                    </template>
                    <el-menu-item index="2-1" :disabled="true">{{mall.name}}</el-menu-item>
                    <el-menu-item index="2-2" :disabled="true">{{user.nickname}}({{user.username}})</el-menu-item>
                    <el-menu-item v-if="user.identity && user.identity.is_operator == 0 && user.mch_id == 0"
                                  index="2-3" @click="goBackToSystem"><?= \Yii::t('layouts/layouts', '返回系统');?>
                    </el-menu-item>
                    <el-menu-item
                            v-if="user.identity && user.identity.is_operator == 1 && user.mch_id == 0 && updatePasswordStatus"
                            index="2-4" @click="updatePassword"><?= \Yii::t('layouts/layouts', '修改密码');?>
                    </el-menu-item>
                    <el-menu-item v-if="user.identity && user.identity.is_operator == 1 || user.mch_id"
                                  index="2-5" @click="logout"><?= \Yii::t('layouts/layouts', '注销');?>
                    </el-menu-item>
                </el-submenu>
            </el-menu>
        </header>
        <el-dialog title="<?= \Yii::t('layouts/layouts', '修改密码');?>" :visible.sync="dialogFormVisible" width="30%">
            <el-form :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px">
                <el-form-item label="<?= \Yii::t('layouts/layouts', '密码');?>" prop="pass">
                    <el-input type="password" v-model="ruleForm.pass" auto-complete="off"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('layouts/layouts', '确认密码');?>" prop="checkPass">
                    <el-input type="password" v-model="ruleForm.checkPass" auto-complete="off"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogFormVisible = false"><?= \Yii::t('layouts/layouts', '取消');?></el-button>
                <el-button :loading="btnLoading" type="primary" @click="updatePasswordSubmit('ruleForm')"><?= \Yii::t('layouts/layouts', '确定');?>
                </el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
    Vue.component('mall-header', {
        template: '#mall-header',
        props: {
            showIcon: {
                type: Boolean,
                default: true
            }
        },
        data() {
            var validatePass = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('<?= \Yii::t('layouts/layouts', '请输入密码');?>'));
                } else {
                    if (this.ruleForm.checkPass !== '') {
                        this.$refs.ruleForm.validateField('checkPass');
                    }
                    callback();
                }
            };
            var validatePass2 = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('<?= \Yii::t('layouts/layouts', '请再次输入密码');?>'));
                } else if (value !== this.ruleForm.pass) {
                    callback(new Error('<?= \Yii::t('layouts/layouts', '两次输入密码不一致');?>!'));
                } else {
                    callback();
                }
            };
            return {
                user: null,
                navs: [],
                mall: null,
                dialogFormVisible: false,
                ruleForm: {
                    pass: '',
                    checkPass: '',
                },
                btnLoading: false,
                rules: {
                    pass: [
                        {required: true, message: '<?= \Yii::t('layouts/layouts', '请输入密码');?>', trigger: 'blur'},
                        {validator: validatePass, trigger: 'blur'},
                    ],
                    checkPass: [
                        {required: true, message: '<?= \Yii::t('layouts/layouts', '请输入确认密码');?>', trigger: 'blur'},
                        {validator: validatePass2, trigger: 'blur'}
                    ]
                },
                updatePasswordStatus: 0,
                courseMenu: {
                    children: [],
                },
                mch_id: '<?=$mchId ?>',
                is: '<?=$is ?>',
            };
        },
        created() {
            const cacheKey = '_MALL_MENUS';
            let data = localStorage.getItem(cacheKey);
            if (data) {
                try {
                    data = JSON.parse(data);
                    this.courseMenu = data.courseMenu;
                } catch (e) {
                    data = false;
                }
            }
            console.log(_aside)
            this.loadData();
            let headerData = localStorage.getItem('_MALL_HEADER_DATA');
            if (headerData) {
                try {
                    headerData = JSON.parse(headerData);
                    this.user = headerData.user;
                    this.navs = headerData.navs;
                    this.mall = headerData.mall;
                    this.updatePasswordStatus = headerData.update_password_status;
                    _aside.mall = headerData.mall;
                } catch (e) {
                    headerData = false;
                }
            }
        },
        methods: {
            indexClick() {
                navigateTo({r: 'mall/index/index'})
                this.clearMenuStorage();
            },
            handleOpen() {
            },
            handleClose() {
            },
            loadData() {
                this.$request({
                    params: {
                        r: 'mall/index/header-bar',
                    },
                    method: 'get',
                }).then(e => {
                    localStorage.setItem('_MALL_HEADER_DATA', JSON.stringify(e.data.data));
                    this.user = e.data.data.user;
                    this.navs = e.data.data.navs;
                    this.mall = e.data.data.mall;
                    this.updatePasswordStatus = e.data.data.update_password_status;
                    _aside.mall = e.data.data.mall;
                }).catch(e => {
                    console.log(e);
                });
            },
            logout() {
                let self = this;
                this.$request({
                    params: {
                        r: 'mall/user/logout'
                    },
                    method: 'get',
                    data: {}
                }).then(e => {
                    // 在当前页面打开
                    self.$navigate({
                        r: 'admin/user/me',
                    });
                }).catch(e => {
                    console.log(e);
                });
            },
            goBackToSystem() {
                if (_isWe7) {
                    this.$navigate({r: 'mall/we7-entry/logout'});
                } else {
                    this.$navigate({r: 'admin/index/back-index'});
                }
            },
            updatePassword() {
                this.dialogFormVisible = true;
            },
            updatePasswordSubmit(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        this.$request({
                            params: {
                                r: 'mall/role-user/update-password'
                            },
                            method: 'post',
                            data: {
                                password: this.ruleForm.checkPass
                            }
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code == 0) {
                                this.dialogFormVisible = false;
                                this.$message.success(e.data.msg);
                                window.location.reload();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            console.log(e);
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            navigateClick(params) {
                this.clearMenuStorage();
                this.$navigate(params);
            },
            clearMenuStorage() {
                localStorage.removeItem('_OPENED_MENU_1_ID');
                localStorage.removeItem('_OPENED_MENU_2_ID');
                localStorage.removeItem('_OPENED_MENU_3_ID');
                localStorage.removeItem('_UNFOLD_ID_1');
                localStorage.removeItem('_UNFOLD_ID_2');
            },
            switchIng(res){
                if(res === 'cn'){
                    window.location.href = this.changeURLArg(window.location.href, 'lang', 'cn');
                }
                if(res === 'en'){
                    window.location.href = this.changeURLArg(window.location.href, 'lang', 'en');
                }
            },
            fastLoginBack(){
                this.$navigate({r: 'mall/mch/fast-login-back'});
            },
            changeURLArg(url, arg, arg_val){
                var pattern=arg+'=([^&]*)';
                var replaceText=arg+'='+arg_val;
                if(url.match(pattern)){
                    var tmp='/('+ arg+'=)([^&]*)/gi';
                    tmp=url.replace(eval(tmp),replaceText);
                    return tmp;
                }else{
                    if(url.match('[\?]')){
                        return url+'&'+replaceText;
                    }else{
                        return url+'?'+replaceText;
                    }
                }
            },
        },
        mounted() {
            let html = document.getElementById('system-download-icon')
            localStorage.setItem('_SYSTEM_DOWNLOAD_PARAMS', JSON.stringify(html.getBoundingClientRect()));
        }
    });
</script>