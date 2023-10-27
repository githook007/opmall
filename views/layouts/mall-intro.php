<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/25
 * Time: 10:19
 */

use app\models\User;

Yii::$app->loadViewComponent('app-rich-text');
$route = Yii::$app->requestedRoute;
/** @var User $user */
$user = \Yii::$app->user->identity;
$is_super = $user->identity->is_super_admin;
$is_admin = $user->identity->is_admin;
if($is_super || $is_admin){
    $adminInfo = $user->adminInfo;
}else{
    $adminInfo = Yii::$app->mall->user->adminInfo;
}
$show_introduce_text = (bool)$adminInfo->show_introduce_text;
?>
<style>
    .intro-box{
        margin-left: 20px;
        position: relative;
        width: 54px;
        height: 54px;
        border-radius: 54px;
        cursor: pointer;
        background-color: white;
        transition: all 0.3s linear;
        border: 2px dashed #fff;
    }
    .intro-box:hover{
        color: #ffbf00;
    }
    .intro-box.display{
        width: 400px;
        height: 710px;
        overflow: auto;
        border: 2px dashed #e4e2e2;
        background-color: ##ffffff7a;
        border-radius: 8px;
        padding: 20px;
        color: #666;
        cursor: default;
    }
    .intro-icon{
        position: absolute;
        font-size: 25px;
        color: #a5a5a5;
        cursor: pointer;
        top: 0;
        right: 0;
        width: 50px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        background-color: white;
        border-radius: 50px;
    }
    .intro-icon:hover{
        color: #ffbf00;;
    }
    .info-box{
        padding: 20px 20px 0 0;
    }
    .edit-box{
        padding: 20px 20px 0 0;
    }
    .edit-box .btns{
        text-align: center;
        margin-top: 30px;
    }
</style>
<template id="mall-intro">
    <div :class="`intro-box ${isDisplay?'display':''}`">
        <div class="intro-icon" @click="open">
            <i class="el-icon-s-opportunity"></i>
        </div>
        <slot v-if="isDisplay">
            <slot v-if="!isEdit">
                <div>
                    <el-button v-if="is_super" size="mini" type="primary" icon="el-icon-edit" @click="isEdit=true">编辑</el-button>
<!--                    <el-button v-if="!is_super" size="mini" type="primary" @click="restore">还原默认</el-button>-->

                    <span style="margin-left: 10px;">默认显示</span>
                    <el-switch v-model="show_introduce_text" active-value="1" inactive-value="0" @change="update"></el-switch>
                </div>
                <div class="info-box">
                    <div v-html="introContent" v-loading="loading"></div>
                </div>
            </slot>
            <slot v-else>
                <div class="edit-box">
                    <div>
                        <app-rich-text v-model="introContent" style="width: 100%"></app-rich-text>
                    </div>
                    <div class="btns">
                        <el-button size="mini" type="primary" @click="save">保存</el-button>
                        <el-button size="mini" @click="isEdit=false">取消</el-button>
                    </div>
                </div>
            </slot>
        </slot>
    </div>
</template>
<script>
    Vue.component('mall-intro', {
        template: '#mall-intro',
        props: {},
        data() {
            return {
                isDisplay: false,
                isEdit: false,
                introContent: '',
                is_super: <?=$is_super?>,
                is_admin: <?=$is_admin?>,
                loading: false,
                is_restore: 0,
                show_introduce_text: '<?=$show_introduce_text?>',
            };
        },
        created() {
            this.isDisplay = this.show_introduce_text;
            if(this.isDisplay) {
                this.getData();
            }
        },
        methods: {
            update() {
                this.$request({
                    params: {
                        r: 'mall/page/update',
                        show_introduce_text: this.show_introduce_text,
                        id: <?=$adminInfo->id?>,
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            open() {
                this.isDisplay = !this.isDisplay;
                if(this.isDisplay && !this.introContent){
                    this.getData();
                }
            },
            getData() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'mall/page/intro',
                        route: '<?= $route?>',
                        is_restore: this.is_restore
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.code === 0){
                        if(e.data.data) {
                            this.introContent = e.data.data.replace(/\<img/gi, '<img style="max-width:100%;height:auto" ');
                            this.isEdit = false
                        }
                    }
                    this.loading = false;
                }).catch(e => {
                    console.log(e);
                });
            },
            save() {
                this.$request({
                    params: {
                        r: 'mall/page/intro',
                    },
                    method: 'post',
                    data: {
                        route: '<?= $route?>',
                        content: this.introContent
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                        this.getData()
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            restore() {
                this.is_restore = 1;
                this.getData();
            },
        },
        mounted() {}
    });
</script>