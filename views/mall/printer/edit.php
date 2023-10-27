<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-button {
        margin: 0;
    }

    .form-button .el-form-item__content {
        margin-left: 0!important;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<section id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" class="clearfix">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'mall/printer/index'})"><?= \Yii::t('mall/printer', '打印机管理');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/printer', '打印机编辑');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="text item">
            <el-form :model="setting" v-loading="loading" label-width="10rem" :rules="FormRules" ref="setting">
                <div class="form-body">
                    <el-form-item label="<?= \Yii::t('mall/printer', '打印机名称');?>" prop="print_name">
                        <el-input size="small" v-model="setting.print_name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="type">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '打印机类型');?></span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '目前支持365');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-select size="small" v-model="form.type" placeholder="<?= \Yii::t('mall/printer', '请选择打印机类型');?>" @change="toggle">
                            <el-option :label="item.label" :value="item.value" v-for="item in select" :key="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item prop="apiKey" v-if="form.type == 'gainscha-gp'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', 'API密钥');?></span>
                            <el-tooltip effect="dark" content="(<?= \Yii::t('mall/printer', '云平台系统集成里获取');?>)"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.apiKey" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="user" v-if="form.type == 'feie'">
                        <template slot='label'>
                            <span>USER</span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '飞鹅云后台注册用户名');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.user" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="ukey" v-if="form.type == 'feie'">
                        <template slot='label'>
                            <span>UKEY</span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '飞鹅云后台登录生成的UKEY');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.ukey" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="sn" v-if="form.type == 'feie'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '打印机编号');?></span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '打印机编号9位');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.sn" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="machine_code" v-if="form.type == 'yilianyun-k4'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '终端号');?></span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '打印机终端号');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.machine_code" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="key" v-if="form.type == 'feie' || form.type == 'yilianyun-k4'">
                        <template slot='label'>
                            <span>{{form.type == 'feie'?'<?= \Yii::t('mall/printer', '打印机key');?>':'<?= \Yii::t('mall/printer', '密钥');?>'}}</span>
                            <el-tooltip effect="dark" :content="form.type == 'feie'?'<?= \Yii::t('mall/printer', '查看飞鹅打印机底部贴纸上面的打印机key');?>':'<?= \Yii::t('mall/printer', '打印机终端密钥');?>'"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.key" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/printer', '打印机编号');?>" prop="name" v-if="form.type == '360-kdt2'">
                        <el-input size="small" v-model="setting.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/printer', '打印机密钥');?>" prop="key" v-if="form.type == '360-kdt2'">
                        <el-input size="small" v-model="setting.key" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="client_id" v-if="form.type == 'yilianyun-k4'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '用户ID');?></span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '用户id');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.client_id" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item label="" prop="client_key" v-if="form.type == 'yilianyun-k4'">
                        <template slot='label'>
                            <span>apiKey</span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', 'apiKey');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.client_key" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="memberCode" v-if="form.type == 'gainscha-gp'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '商户编号');?></span>
                            <el-tooltip effect="dark" content="(<?= \Yii::t('mall/printer', '云平台系统集成里获取');?>)"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.memberCode" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="deviceNo" v-if="form.type == 'gainscha-gp'">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '终端编号');?></span>
                            <el-tooltip effect="dark" content="(<?= \Yii::t('mall/printer', '云平台系统集成里获取');?>)"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.deviceNo" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item prop="time">
                        <template slot='label'>
                            <span><?= \Yii::t('mall/printer', '打印联数');?></span>
                            <el-tooltip effect="dark" content="<?= \Yii::t('mall/printer', '同一订单');?>"
                                        placement="top">
                                <i class="el-icon-info"></i>
                            </el-tooltip>
                        </template>
                        <el-input size="small" v-model="setting.time" autocomplete="off"></el-input>
                    </el-form-item>
                </div>
                <el-form-item class="form-button">
                    <el-button sizi="mini" class="button-item" :loading="submitLoading" type="primary" @click="onSubmit">
                        <?= \Yii::t('mall/printer', '保存');?>
                    </el-button>
                    <el-button sizi="mini" class="button-item" @click="Cancel">
                        <?= \Yii::t('mall/printer', '取消');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</section>

<style>
    .el-input__inner {
        width: 500px;
    }
</style>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {},
                select: [],
                setting: {},
                submitLoading: false,
                FormRules: {
                    print_name: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '打印机名称不能为空');?>', trigger: 'change' },
                        { min: 1, max: 30, message: "<?= \Yii::t('mall/printer', '打印机名称长度在1');?>" },
                    ],
                    name: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '打印机编号不能为空');?>', trigger: 'change' }
                    ],
                    user: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '用户名不能为空');?>', trigger: 'change' }
                    ],
                    ukey: [
                        { required: true, message: '<?= \Yii::t('mall/printer', 'UKEY不能为空');?>', trigger: 'change' }
                    ],
                    sn: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '打印机编号不能为空');?>', trigger: 'change' }
                    ],
                    machine_code: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '终端号不能为空');?>', trigger: 'change' }
                    ],
                    key: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '密钥不能为空');?>', trigger: 'change' }
                    ],
                    client_key: [
                        { required: true, message: '<?= \Yii::t('mall/printer', 'API密钥不能为空');?>', trigger: 'change' }
                    ],
                    apiKey: [
                        { required: true, message: '<?= \Yii::t('mall/printer', 'API密钥不能为空');?>', trigger: 'change' }
                    ],
                    memberCode: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '商户编号不能为空');?>', trigger: 'change' }
                    ],
                    deviceNo: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '终端编号不能为空');?>', trigger: 'change' }
                    ],
                    client_id: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '用户ID不能为空');?>', trigger: 'change' }
                    ],
                    time: [
                        { required: true, message: '<?= \Yii::t('mall/printer', '打印联数不能为空');?>', trigger: 'change' }
                    ],
                },
            };
        },
        methods: {
            // 返回上一页
            Cancel(){
                window.history.go(-1)
            },
            toggle() {
                this.$refs.setting.clearValidate()
            },
            // 提交数据
            onSubmit() {
                this.$refs.setting.validate((valid) => {
                    if (valid) {
                        this.submitLoading =true;
                        this.form.setting = this.setting;
                        this.form.name = this.setting.print_name;
                        let para = Object.assign(this.form);
                        request({
                            params: {
                                r: 'mall/printer/edit',
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            this.submitLoading = false;
                            if (e.data.code === 0) {
                                this.$message({
                                    message: e.data.msg,
                                    type: 'success'
                                });
                                setTimeout(function(){
                                    navigateTo({ r: 'mall/printer/index' });
                                },300);
                            } else {
                                this.$alert(e.data.msg, '<?= \Yii::t('mall/printer', '提示');?>', {
                                    confirmButtonText: '<?= \Yii::t('mall/printer', '确定');?>'
                                })
                            }
                        }).catch(e => {
                            this.submitLoading = false;
                            this.$alert(e.data.msg, '<?= \Yii::t('mall/printer', '提示');?>', {
                                confirmButtonText: '<?= \Yii::t('mall/printer', '确定');?>'
                            })
                        });
                    }else{
                        this.loading = false;
                    }
                });
            },

            //获取列表
            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'mall/printer/edit',
                        id: getQuery('id'),
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.select = e.data.data.select;
                        if(e.data.data.list != null){
                            this.form = e.data.data.list;
                            this.setting = e.data.data.list.setting;
                        }else {
                            this.form = { type: this.select[0].value }
                        }
                    }
                }).catch(e => {
                });
            },
        },

        created() {
            this.getList();
        }
    })
</script>