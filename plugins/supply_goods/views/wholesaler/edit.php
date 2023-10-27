<?php
defined('YII_ENV') or exit('Access Denied');

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
</style>
<section id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" class="clearfix">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>申请成为批发商</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="form" v-loading="loading" label-width="10rem" :rules="FormRules" ref="form">
                <el-form-item prop="name">
                    <template slot='label'>
                        <span>批发商名称</span>
                    </template>
                    <el-input size="small" v-model="form.name" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="introduction">
                    <template slot='label'>
                        <span>批发商简介</span>
                    </template>
                    <el-input size="small" v-model="form.introduction" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item label="" prop="phone">
                    <template slot='label'>
                        <span>联系方式</span>
                    </template>
                    <el-input size="small" v-model="form.phone" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="address">
                    <template slot='label'>
                        <span>地址</span>
                    </template>
                    <el-input size="small" v-model="form.address" autocomplete="off"
                    ></el-input>
                </el-form-item>
                <el-form-item label="店铺Logo" prop="logo">
                    <app-attachment :multiple="false" :max="1" v-model="form.logo">
                        <el-tooltip class="item"
                                    effect="dark"
                                    content="建议尺寸:240 * 240"
                                    placement="top">
                            <el-button size="mini">选择文件</el-button>
                        </el-tooltip>
                    </app-attachment>
                    <app-image mode="aspectFill" width='80px' height='80px' :src="form.logo">
                    </app-image>
                </el-form-item>
                <el-form-item label="店铺背景图" prop="back_image">
                    <app-attachment :multiple="false" :max="1" v-model="form.back_image">
                        <el-tooltip class="item"
                                    effect="dark"
                                    content="建议尺寸:750 * 200"
                                    placement="top">
                            <el-button size="mini">选择文件</el-button>
                        </el-tooltip>
                    </app-attachment>
                    <app-image mode="aspectFill" width='80px' height='80px' :src="form.back_image">
                    </app-image>
                </el-form-item>
                <el-form-item label="发货时间" prop="send_type">
                    <el-radio v-model="form.send_type" label="1">24小时内</el-radio>
                    <el-radio v-model="form.send_type" label="2">48小时内</el-radio>
                    <el-radio v-model="form.send_type" label="3">72小时内</el-radio>
                    <el-radio v-model="form.send_type" label="4">7天</el-radio>
                </el-form-item>
                <el-form-item prop="send_time">
                    <template slot='label'>
                        <span>消息通知</span>
                    </template>
                    <el-input size="small" v-model="form.send_time" autocomplete="off"
                    ></el-input>
                </el-form-item>

            </el-form>
        </div>
        <el-button class="button-item" type="primary" size='mini' :loading=btnLoading @click="onSubmit">保存</el-button>
    </el-card>
</section>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {
                    send_type:"1"
                },
                loading: false,
                btnLoading: false,
                FormRules: {
                    name: [
                        {required: true, message: '批发商名称不能为空', trigger: 'blur'},
                    ],
                    introduction: [
                        {required: true, message: '简介不能为空', trigger: 'blur'},
                    ],
                    phone: [
                        {required: true, message: '联系方式不能为空', trigger: 'blur'},
                    ],
                    address: [
                        {required: true, message: '地址不能为空', trigger: 'blur'},
                    ],
                    send_type: [
                        {required: true, message: '发货方式不能为空', trigger: 'blur'},
                    ],
                    send_time: [
                        {required: true, message: '消息通知不能为空', trigger: 'blur'},
                    ],
                    logo: [
                        {required: true, message: '店铺logo不能为空', trigger: 'blur'},
                    ],
                    back_image: [
                        {required: true, message: '店铺背景图不能为空', trigger: 'blur'},
                    ],

                },
                tempMemberName: '',
            };
        },
        methods: {
            closeMember() {
                this.tempMemberName = '';
                this.form.send_member_id = 0;
            },
            // 提交数据
            onSubmit() {
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.btnLoading = true;
                        let para = Object.assign(this.form);
                        request({
                            params: {
                                r: 'plugin/supply_goods/mall/wholesaler/edit',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message({
                                    message: e.data.msg,
                                    type: 'success'
                                });
                                setTimeout(function(){
                                    navigateTo({ r: 'plugin/supply_goods/mall/index/index' , is_open: 2});
                                },300);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },
        },

        created() {

        }
    })
</script>