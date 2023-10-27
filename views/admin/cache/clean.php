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
    <el-card shadow="never">
        <el-form>
            <el-form-item>
                <el-checkbox v-model="form.data" label="<?= \Yii::t('admin/cache', '程序处理过程产生的缓存数据');?>"></el-checkbox>
                <div style="line-height: 1.4;color: #909399"><?= \Yii::t('admin/cache', '数据缓存');?></div>
            </el-form-item>
            <el-form-item>
                <el-checkbox v-model="form.file" label="<?= \Yii::t('admin/cache', '临时文件');?>"></el-checkbox>
                <div style="line-height: 1.4;color: #909399"><?= \Yii::t('admin/cache', '二维码海报等生成的临时文件');?></div>
            </el-form-item>
            <el-form-item>
                <el-checkbox v-model="form.update" label="<?= \Yii::t('admin/cache', '更新文件');?>"></el-checkbox>
                <div style="line-height: 1.4;color: #909399"><?= \Yii::t('admin/cache', '系统');?></div>
            </el-form-item>
        </el-form>
    </el-card>
    <el-button class="button-item" type="primary" :loading="loading" @click="clean"><?= \Yii::t('admin/cache', '确认清理');?></el-button>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                form: {
                    data: false,
                    file: false,
                    update: false,
                }
            };
        },
        created() {
        },
        methods: {
            clean() {
                this.loading = true;
                this.$request({
                    params: {
                        r: 'admin/cache/clean',
                    },
                    method: 'post',
                    data: this.form,
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
        },
    });
</script>
