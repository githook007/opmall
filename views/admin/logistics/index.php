<?php

/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 17:05
 */
?>
<style>
    #app .el-checkbox {
        margin-bottom: 0;
    }

    .button-item {
        margin-top: 20px;
        padding: 9px 25px;
    }

    .form-body {
        justify-content: center;
        position: relative;
    }

    .form-body .el-form {
        margin-top: 10px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading">
        <div class="form-body">
            <el-form @submit.native.prevent :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px">
                <el-alert :closable="false" type="success">回调地址：<?php echo Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/msg-notify/wlhl.php' ?></el-alert>
                <el-form-item label="<?= \Yii::t('admin/logistics', '开启');?>" prop="appId">
                    <el-switch active-value="1"
                               inactive-value="0"
                               v-model="ruleForm.status"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '正式环境');?>" prop="is_prod">
                    <el-switch active-value="1"
                               inactive-value="0"
                               v-model="ruleForm.is_prod"></el-switch>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '应用id');?>" prop="appId">
                    <el-input size="small"
                              type="text"
                              placeholder="<?= \Yii::t('admin/logistics', '应用id');?>"
                              v-model="ruleForm.appId">
                    </el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('admin/logistics', '应用密钥');?>" prop="secret">
                    <el-input size="small"
                              type="text"
                              placeholder="<?= \Yii::t('admin/logistics', '应用密钥');?>"
                              v-model="ruleForm.secret">
                    </el-input>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
    <el-button class="button-item" type="primary" :loading="loading" @click="submit"><?= \Yii::t('admin/logistics', '保存');?></el-button>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                ruleForm: {},
                rules: {
                    appId: [
                        {required: true, message: "<?= \Yii::t('admin/logistics', '请输入应用id');?>",},
                    ],
                    secret: [
                        {required: true, message: "<?= \Yii::t('admin/logistics', '请输入应用密钥');?>",},
                    ],
                },
            };
        },
        created() {
            this.getSetting();
        },
        methods: {
            submit() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/logistics/index',
                    },
                    method: 'post',
                    data: {
                        form: this.ruleForm,
                    },
                }).then(e => {
                    this.loading = false;
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            getSetting() {
                this.cardLoading = true;
                this.$request({
                    params: {
                        r: 'admin/logistics/index',
                    },
                    method: 'get',
                }).then(e => {
                    this.cardLoading = false;
                    this.ruleForm = e.data.data.setting;
                }).catch(e => {
                });
            }
        },
    });
</script>
