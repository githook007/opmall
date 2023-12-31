<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .form-body {
        background-color: #fff;
        padding: 20px 50% 20px 20px;
        min-width: 1000px;
    }

    .button-item {
        margin-top: 12px;
        padding: 9px 25px;
    }
</style>
<section id="app" v-cloak>
    <el-card class="box-card" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" class="clearfix">
            <span></span>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                          @click="$navigate({r:'mall/coupon/index'})"><?= \Yii::t('mall/coupon', '优惠券管理');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/coupon', '优惠券发放');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="form" @submit.native.prevent v-loading="loading" label-width="10rem" ref="form">
                <el-form-item label="<?= \Yii::t('mall/coupon', '优惠券名称');?>" prop="name">
                    <div>{{form.name}}</div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '最低消费金额');?>" prop="min_price">
                    <div>{{form.min_price}}</div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '优惠金额');?>" prop="sub_price">
                    <div>{{form.sub_price}}</div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '剩余数量');?>" prop="total_count">
                    <div v-if="form.total_count == -1"><?= \Yii::t('mall/coupon', '无限制');?></div>
                    <div v-else>{{form.total_count}}</div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '优惠券有效期');?>" prop="expire_type">
                    <div v-if="form.expire_type == '2'"><span style="color: #FF4544">{{form.begin_time}}</span><?= \Yii::t('mall/coupon', '至');?><span style="color: #FF4544">{{form.end_time}}</span></div>
                    <div v-if="form.expire_type == '1'"><?= \Yii::t('mall/coupon', '领取后');?>{{form.expire_day}}<?= \Yii::t('mall/coupon', '天过期');?></div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '发送小程序模板消息');?>" prop="is_send" v-if="false">
                    <el-switch
                            style="margin-left: 20px;"
                            v-model="form.is_send"
                            active-value="1"
                            inactive-value="0">
                    </el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/coupon', '发送数量');?>" prop="coupon_num">
                    <el-input size="small" type="number" v-model="form.coupon_num" style="width:50%" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item prop="user_id_list">
                    <template slot='label'>
                        <span><?= \Yii::t('mall/coupon', '发放对象');?></span>
                        <el-tooltip effect="dark" content="<?= \Yii::t('mall/coupon', '请输入昵称');?>"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <el-input @keyup.enter.native="search" size="small" v-model="keyword" autocomplete="off" placeholder="<?= \Yii::t('mall/coupon', '昵称');?>" style="width: 20%"></el-input>
                    <el-button size="small" :loading=foundLoading @click="search"><?= \Yii::t('mall/coupon', '查找用户');?></el-button>
                    <el-checkbox-group  class="user-list" v-model="id" size="medium">
                        <el-checkbox-button class="user-item" v-for="item in userList" :label="item.id" :key="item.id">
                            <img class="avatar" :src="item.avatar" alt="">
                            <div class="username">{{ item.nickname }}</div>
                            <img class="platform-img" :src="item.platform_icon" alt="">
                        </el-checkbox-button>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>
        </div>
        <el-button class="button-item" type="primary" size="small" :loading=btnLoading @click="onSubmit"><?= \Yii::t('mall/coupon', '提交');?></el-button>
        <el-button class="button-item" @click="Cancel" size="small"><?= \Yii::t('mall/coupon', '取消');?></el-button>
    </el-card>
</section>

<style>
    .user-list{
        width: 150%;
    }

    .user-item .el-checkbox-button__inner{
        border: 1px solid #e2e2e2;
        /*height: 125px;*/
        width: 120px;
        padding-top: 15px;
        text-align: center;
        margin: 0 20px 20px 0;
        cursor: pointer;
        border-radius: 0!important;
    }

    .user-item.active{
        background-color: #50A0E4;
        color: #fff;
    }

    .user-list .avatar{
        height: 60px;
        width: 60px;
        border-radius: 30px;
    }

    .username{
        margin-top: 10px;
        font-size: 13px;
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }

    .platform-img {
        width: 24px;
        height: 24px;
        margin-top: 8px;
    }
</style>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {},
                count:null,
                foundLoading:false,
                btnLoading:false,
                userList:[],
                id:[],
                keyword:null,
                loading:false
            };
        },
        methods: {
            search(){
                this.foundLoading = true;
                request({
                    params: {
                        r: 'mall/coupon/search-user',
                        keyword: this.keyword,
                    },
                }).then(e => {
                    this.foundLoading = false;
                    if (e.data.code == 0) {
                        this.userList = e.data.data.list;
                    }
                }).catch(e => {
                    this.foundLoading = false;
                });
            },

            Cancel(){
                window.history.go(-1)
            },

            onSubmit() {
                this.$refs.form.validate((valid) => {
                    this.btnLoading = true;
                    if (valid) {
                        let para = {
                            id : this.form.id,
                            user_id_list : this.id,
                            is_send: this.form.is_send,
                            coupon_num: this.form.coupon_num
                        }
                        request({
                            params: {
                                r: 'mall/coupon/send',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$alert(e.data.msg, {
                                    confirmButtonText: '<?= \Yii::t('mall/coupon', '确定');?>',
                                    callback: action => {
                                        navigateTo({ r: 'mall/coupon/index' });
                                    }
                                });
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.btnLoading = false;
                        });
                    }
                });
            },

            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'mall/coupon/send',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        if(e.data.data.list.id > 0){
                            this.form = e.data.data.list;
                            this.count = e.data.data.count;
                        }
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
        },

        created() {
            this.getList();
        }
    })
</script>
