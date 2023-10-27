<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/6/14
 * Time: 11:22
 */
?>
<style>
    #app {
        padding: 40px 0 0;
    }

    .container {
        border: 1px solid #e2e2e2;
        max-width: 600px;
        margin: 0 auto 40px;
        color: #333;
    }

    .container .container-title {
        padding: 18px 20px;
        background: #F3F5F6;
    }

    .container .container-body {
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .code-block {
        background: #e8efee;
        border-left: 2px solid #d2d2d2;
        margin: 10px 0;
        padding: 10px 10px;
        white-space: pre-line;
    }
</style>
<div id="app">
    <div class="container">
        <div class="container-title"><?= \Yii::t('mall/we7_entry7', 'Redis配置');?></div>
        <div class="container-body">
            <el-form :model="form" :rules="rules" ref="form" label-width="120px">
                <el-form-item label="<?= \Yii::t('mall/we7_entry7', 'Redis服务器');?>" prop="host">
                    <el-input v-model="form.host"></el-input>
                    <div style="font-size: 12px;color: #909399;"><?= \Yii::t('mall/we7_entry7', '请填写Redis服务器的IP或域名');?></div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/we7_entry7', 'Redis端口');?>" prop="port">
                    <el-input v-model="form.port"></el-input>
                    <div style="font-size: 12px;color: #909399;"><?= \Yii::t('mall/we7_entry7', 'Redis的默认端口为6379');?></div>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/we7_entry7', 'Redis密码');?>" prop="password">
                    <el-input v-model="form.password"></el-input>
                    <div style="font-size: 12px;color: #909399;"><?= \Yii::t('mall/we7_entry7', 'Redis默认没有密码');?></div>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="saveConfig('form')" :loading="saveConfigLoading"
                               :disabled="step!=1"><?= \Yii::t('mall/we7_entry7', '保存');?>
                    </el-button>
                    <div v-if="step===2"><?= \Yii::t('mall/we7_entry7', 'Redis配置已保存');?></div>
                </el-form-item>
            </el-form>
        </div>
        <div class="container-title">2. <?= \Yii::t('mall/we7_entry7', '启动队列服务');?></div>
        <div class="container-body">
            <ol>
                <li>
                    <?php
                    $queueFile = Yii::$app->basePath . '/queue.sh';
                    $command = 'chmod a+x ' . $queueFile . ' && ' . $queueFile;
                    ?>
                    <h4><?= \Yii::t('mall/we7_entry7', '启动队列服务');?></h4>
                    <div><?= \Yii::t('mall/we7_entry7', 'Linux使用SSH远程登录服务器');?></div>
                    <pre class="code-block"><?= $command ?></pre>
                </li>
                <li>
                    <h4><?= \Yii::t('mall/we7_entry7', '测试队列服务');?></h4>
                    <el-button @click="createQueue" :loading="testQueueLoading" style="margin-bottom: 10px"><?= \Yii::t('mall/we7_entry7', '点击测试');?>
                    </el-button>
                    <div style="font-size: 12px;color: #909399;"><?= \Yii::t('mall/we7_entry7', '检测过程最多可能需要两分钟');?></div>
                </li>
            </ol>
        </div>
    </div>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                step: 1,
                saveConfigLoading: false,
                testQueueLoading: false,
                maxTestCount: 60,
                testCount: 0,
                form: {
                    host: '',
                    port: 6379,
                    password: '',
                },
                rules: {
                    host: [{required: true, message: '<?= \Yii::t('mall/we7_entry7', '请填写Redis服务器');?>'}],
                    port: [{required: true, message: '<?= \Yii::t('mall/we7_entry7', '请填写Redis端口');?>'}],
                },
            };
        },
        created() {
        },
        methods: {
            saveConfig(formName) {
                this.$refs[formName].validate(valid => {
                    if (valid) {
                        this.saveConfigLoading = true;
                        this.$request({
                            method: 'post',
                            params: {
                                r: 'mall/we7-entry/local-setting',
                                action: 'saveConfig',
                            },
                            data: this.form,
                        }).then(e => {
                            this.saveConfigLoading = false;
                            if (e.data.code === 0) {
                                this.step = 2;
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                        console.log('not ok');
                    }
                });
            },
            createQueue() {
                this.testQueueLoading = true;
                this.$request({
                    method: 'post',
                    params: {
                        r: 'mall/we7-entry/local-setting',
                        action: 'testQueue',
                        testQueueStep: 'create',
                    },
                    data: {},
                }).then(e => {
                    if (e.data.code === 0) {
                        this.testQueue(e.data.data.id);
                    } else {
                        this.testQueueLoading = false;
                        this.step = 1;
                        this.$alert(e.data.msg, '<?= \Yii::t('mall/we7_entry7', '提示');?>');
                    }
                }).catch(e => {
                    this.$alert(e.data.msg, '<?= \Yii::t('mall/we7_entry7', '提示');?>');
                    this.testQueueLoading = false;
                    this.step = 1;
                });
            },
            testQueue(id) {
                if (this.testCount >= this.maxTestCount) {
                    this.testCount = 0;
                    this.testQueueLoading = false;
                    this.$alert('<?= \Yii::t('mall/we7_entry7', '队列服务检测失败');?>', '<?= \Yii::t('mall/we7_entry7', '提示');?>');
                    return;
                }
                this.testCount++;
                this.$request({
                    method: 'post',
                    params: {
                        r: 'mall/we7-entry/local-setting',
                        action: 'testQueue',
                        testQueueStep: 'test',
                    },
                    data: {
                        id: id,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        if (e.data.data.done) {
                            this.$alert('<?= \Yii::t('mall/we7_entry7', '恭喜您队列服务已启动完成');?>', '<?= \Yii::t('mall/we7_entry7', '提示');?>', {
                                confirmButtonText: '<?= \Yii::t('mall/we7_entry7', '进入商城');?>',
                                callback: action => {
                                    this.$navigate({r: 'mall/index/index'});
                                },
                            });
                        } else {
                            setTimeout(() => {
                                this.testQueue(id);
                            }, 1000);
                        }
                    }
                }).catch(e => {
                });
            },
        },
    });
</script>