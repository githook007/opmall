<?php
defined('YII_ENV') or exit('Access Denied');
Yii::$app->loadViewComponent('app-select-member');

?>
<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 50%;
    }

    .form-button {
        margin: 0 !important;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }
    label {
        display: inline-block;
        width: 200px;
        text-align: center;
        font-weight: bold;
        font-size: 15px;
    }
</style>
<section id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" class="clearfix">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>申请成为批发商</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="form" abel-width="10rem" ref="form" style="padding-left: 20px;">
                <el-form-item prop="name">
                    <label for="">批发商名称:</label>
                    <span>{{form.name}}</span>
                </el-form-item>
                <el-form-item prop="introduction">
                    <label for="">批发商简介:</label>
                    <span>{{form.introduction}}</span>
                </el-form-item>
                <el-form-item label="" prop="phone">
                    <label for="">联系方式:</label>
                    <span>{{form.phone}}</span>
                </el-form-item>
                <el-form-item prop="address">
                    <label for="">地址:</label>
                    <span>{{form.address}}</span>
                </el-form-item>
                <el-form-item prop="address">
                    <label for="">店铺logo:</label>
                    <img :src="form.logo" alt="店铺logo" style="width: 120px;">
                </el-form-item>
                <el-form-item prop="address">
                    <label for="">店铺背景图:</label>
                    <img :src="form.back_image" alt="店铺背景图" style="width: 120px;">
                </el-form-item>
                <el-form-item prop="address">
                    <label for="">发货时间:</label>
                    <span v-if="form.send_type == 1">24小时内</span>
                    <span v-if="form.send_type == 2">48小时内</span>
                    <span v-if="form.send_type == 3">72小时内</span>
                    <span v-if="form.send_type == 4">7天</span>
                </el-form-item>
                <el-form-item prop="send_time">
                    <label for="">消息通知:</label>
                    <span>{{form.send_time}}</span>
                </el-form-item>
                <el-form-item prop="address">
                    <label for="">审核状态:</label>
                    <span v-if="form.status == 0" style="font-weight: bold; color: #00a2d4">待审核</span>
                    <span v-if="form.status == 1" style="font-weight: bold; color: #00c800">审核成功 &nbsp;&nbsp;&nbsp;<el-button @click="ExemptionLogin">跳转登录</el-button></span>
                    <span v-if="form.status == 2" style="font-weight: bold; color: #aa1111">审核失败</span>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</section>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {},
                ExemptionUrl: '',
            };
        },
        methods: {
            // 提交数据
            onSubmit() {
                request({
                    params: {
                        r: 'plugin/supply_goods/mall/wholesaler/index',
                    },
                    method: 'get'
                }).then(e => {
                    this.btnLoading = false;
                    if (e.data.code === 0) {
                        console.log(e.data.data);
                        this.form = e.data.data;
                        this.ExemptionUrl = e.data.ExemptionUrl
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.btnLoading = false;
                });
            },
            ExemptionLogin(){
                window.open(this.ExemptionUrl)
            }
        },

        created() {
            this.onSubmit();
        }
    })
</script>