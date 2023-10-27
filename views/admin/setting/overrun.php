<?php

/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 17:05
 */

/* @var $this \yii\web\View */
?>
<style>
    #app .el-checkbox {
        margin-bottom: 0;
    }

    .button-item {
        margin-top: 20px;
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="cardLoading">
        <el-row>
            <el-alert :closable="false" type="error"><?= \Yii::t('admin/setting', '注');?></el-alert>
            <el-col :span="12">
                <el-form @submit.native.prevent :model="ruleForm" :rules="rules" ref="ruleForm">
                    <el-form-item label="<?= \Yii::t('admin/setting', '上传图片限制');?>" prop="img_overrun">
                        <el-input :disabled="ruleForm.is_img_overrun"
                                  size="small"
                                  type="number"
                                  placeholder="<?= \Yii::t('admin/setting', '图片大小限制');?>"
                                  v-model="ruleForm.img_overrun">
                            <template slot="append">
                                <div>MB</div>
                            </template>
                        </el-input>
                        <el-checkbox v-model="ruleForm.is_img_overrun"><?= \Yii::t('admin/setting', '无限制');?></el-checkbox>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/setting', '上传视频限制');?>" prop="video_overrun">
                        <el-input :disabled="ruleForm.is_video_overrun"
                                  size="small"
                                  type="number"
                                  placeholder="<?= \Yii::t('admin/setting', '视频大小限制');?>"
                                  v-model="ruleForm.video_overrun">
                            <template slot="append">
                                <div>MB</div>
                            </template>
                        </el-input>
                        <el-checkbox v-model="ruleForm.is_video_overrun"><?= \Yii::t('admin/setting', '无限制');?></el-checkbox>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('admin/setting', 'diy组件限制');?>" prop="diy_module_overrun">
                        <el-input :disabled="ruleForm.is_diy_module_overrun"
                                  size="small"
                                  type="number"
                                  placeholder="<?= \Yii::t('admin/setting', '组件个数限制');?>"
                                  v-model="ruleForm.diy_module_overrun">
                            <template slot="append"><?= \Yii::t('admin/setting', '个');?></template>
                        </el-input>
                        <el-checkbox v-model="ruleForm.is_diy_module_overrun"><?= \Yii::t('admin/setting', '无限制');?></el-checkbox>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-card>
    <el-button class="button-item" type="primary" :loading="loading" @click="submit"><?= \Yii::t('admin/setting', '保存');?></el-button>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                ruleForm: {},
                rules: {
                    img_overrun: [
                        {required: true, message: "<?= \Yii::t('admin/setting', '请输入图片大小限制');?>",},
                    ],
                    video_overrun: [
                        {required: true, message: "<?= \Yii::t('admin/setting', '请输入视频大小限制');?>",},
                    ],
                    diy_module_overrun: [
                        {required: true, message: "<?= \Yii::t('admin/setting', '请输入diy组件个数限制');?>",},
                    ]
                }
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
                        r: 'admin/setting/overrun',
                    },
                    method: 'post',
                    data: {
                        form: this.ruleForm
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
                        r: 'admin/setting/overrun',
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
