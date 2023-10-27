<?php

/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/4/19
 * Time: 11:33
 */

/* @var $this \yii\web\View */
?>
<style>
    .el-step__description.is-finish {
        color: inherit;
        font-size: 16px;
        margin-bottom: 20px;
    }

    .form-body {
        background-color: #fff;
        padding: 20px;
    }
</style>
<div id="app" v-cloak>
    <el-card style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= Yii::$app->plugin->getCurrentPlugin()->getDisplayName() ?><?= \Yii::t('plugins/ttapp', '发布');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" size="small" @click="jumpAppidDialogVisible=true"><?= \Yii::t('plugins/ttapp', '可跳转小程序设置');?></el-button>
                </div>
            </div>
        </div>
        <div class="form-body">
            <el-steps direction="vertical" :active="4">
                <el-step>
                    <div slot="description">
                        <p><?= \Yii::t('plugins/ttapp', '下载并安装字节跳动开发者工具');?></p>
                        <el-button
                                @click="$navigate('https://microapp.bytedance.com/docs/zh-CN/mini-app/introduction/plug-in/register-auth/registration', true)">
                            <?= \Yii::t('plugins/ttapp', '下载字节跳动开发者工具');?>
                        </el-button>
                    </div>
                </el-step>
                <el-step>
                    <div slot="description">
                        <p><?= \Yii::t('plugins/ttapp', '下载小程序代码包并解压');?></p>
                        <el-button @click="$navigate({r:'plugin/ttapp/index/package-download'},true)"><?= \Yii::t('plugins/ttapp', '下载小程序代码包');?>
                        </el-button>
                    </div>
                </el-step>
                <el-step>
                    <div slot="description">
                        <p><?= \Yii::t('plugins/ttapp', '运行字节跳动开发者工具');?></p>
                    </div>
                </el-step>
            </el-steps>
        </div>
    </el-card>

    <el-dialog @open="loadJumpAppid" title="<?= \Yii::t('plugins/ttapp', '可跳转小程序设置');?>" :visible.sync="jumpAppidDialogVisible"
               :close-on-click-modal="false">
        <div style="margin-bottom: 20px"><?= \Yii::t('plugins/ttapp', '最多可配置10个');?></div>
        <div v-loading="loadJumpAppidLoading">
            <template v-for="(appid, index) in jumpAppIdList">
                <div flex="box:last" style="margin-bottom: 20px;width: 95%">
                    <el-input v-model="jumpAppIdList[index]" placeholder="<?= \Yii::t('plugins/ttapp', '请填写小程序APPID');?>"
                              style="margin-right: 10px;"></el-input>
                    <div class="outline">
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/ttapp', '删除');?>" placement="top">
                            <i @click="jumpAppIdList.splice(index,1)" class="el-icon-remove-outline"></i>
                        </el-tooltip>
                    </div>
                </div>
            </template>
            <el-button type="text" v-if="jumpAppIdList.length<10" @click="jumpAppIdList.push('')"
                       style="margin-bottom: 20px">
                <i class="el-icon-plus" style="font-weight: bolder;margin-left: 5px;"></i>
                <span style="color: #353535;font-size: 14px"><?= \Yii::t('plugins/ttapp', '新增');?></span>
            </el-button>
            <div slot="footer" style="text-align: right">
                <el-button size="small" type="primary" @click="saveJumpAppid" :loading="saveJumpAppidLoading"><?= \Yii::t('plugins/ttapp', '保存');?>
                </el-button>
            </div>
        </div>
    </el-dialog>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                downloadLoading: false,
                jumpAppidDialogVisible: false,
                saveJumpAppidLoading: false,
                loadJumpAppidLoading: false,
                jumpAppIdList: [],
            };
        },
        created() {
        },
        methods: {
            loadJumpAppid() {
                this.loadJumpAppidLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/ttapp/index/jump-appid',
                    },
                }).then(e => {
                    this.loadJumpAppidLoading = false;
                    if (e.data.code === 0) {
                        this.jumpAppIdList = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            saveJumpAppid() {
                this.saveJumpAppidLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/ttapp/index/jump-appid',
                    },
                    method: 'post',
                    data: {
                        appid_list: this.jumpAppIdList,
                    },
                }).then(e => {
                    this.saveJumpAppidLoading = false;
                    if (e.data.code === 0) {
                        this.jumpAppidDialogVisible = false;
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