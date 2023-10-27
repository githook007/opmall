<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>

<style>
    .form-body {
        display: flex;
        justify-content: center;
    }

    .form-body .el-form {
        width: 750px;
        margin-top: 10px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" v-loading="loading">
        <div style="margin-bottom: 20px"><?= \Yii::t('admin/setting', '域名设置');?></div>
        <div class='form-body' ref="body">
            <el-form @submit.native.prevent label-position="left" label-width="180px" :model="form" ref="form">
                <el-form-item label="<?= \Yii::t('admin/setting', '小程序业务域名校验文件');?>">
                    <app-upload @complete="updateSuccess" :accept="'text/plain'" :params="params"
                                v-model="form.file" :simple="true">
                        <el-button size="small"><?= \Yii::t('admin/setting', '上传文件');?></el-button>
                    </app-upload>
                    <div class="preview"><?= \Yii::t('admin/setting', '仅支持上传txt');?></div>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                form: {
                    file: '',
                },
                submitLoading: false,
                params: {
                    r: 'admin/setting/upload-file'
                }
            };
        },
        created() {
        },
        methods: {
            updateSuccess(e) {
                this.$message.success("<?= \Yii::t('admin/setting', '上传成功');?>")
            }
        }
    });
</script>
