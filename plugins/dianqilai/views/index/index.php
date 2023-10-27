<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/3
 * Time: 11:44
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
?>
<style>
    .form-body {
        padding: 20px;
        background-color: #fff;
        margin-bottom: 20px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" v-loading="loading"
             body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('plugins/dianqilai', '客服回调链接');?></span>
        </div>
        <div class="form-body">
            <div style="color: rgb(2, 117, 216)">
                <div><?= \Yii::t('plugins/dianqilai', '温馨提示');?></div>
                <div><?= \Yii::t('plugins/dianqilai', '该设置用于客服发送给离线访客消息后');?></div>
            </div>
            <el-input id="url" v-model="url" :readonly="true" size="mini" style="max-width: 600px;"></el-input>
            <div style="margin-top: 24px">
                <el-button size="mini" class="copy-btn" data-clipboard-action="copy" type="primary"
                           data-clipboard-target="#url"><?= \Yii::t('plugins/dianqilai', '复制链接');?>
                </el-button>
                <el-button size="mini" @click="reset"><?= \Yii::t('plugins/dianqilai', '重置链接');?></el-button>
            </div>
        </div>
    </el-card>
</div>
<script src="<?= Yii::$app->request->baseUrl ?>/statics/js/clipboard.min.js"></script>
<script>
    var clipboard = new Clipboard('.copy-btn');

    var self = this;
    clipboard.on('success', function (e) {
        self.ELEMENT.Message.success("<?= \Yii::t('plugins/dianqilai', '复制成功');?>");
        e.clearSelection();
    });
    clipboard.on('error', function (e) {
        self.ELEMENT.Message.success("<?= \Yii::t('plugins/dianqilai', '复制失败');?>");
    });
    const app = new Vue({
        el: '#app',
        data() {
            return {
                url: 'lllll',
                loading: false
            };
        },
        created() {
            this.load();
        },
        methods: {
            reset() {
                this.load(1);
            },
            load(isNew = 0) {
                this.loading = true;
                request({
                    params: {
                        r: 'plugin/dianqilai/mall/index/index',
                        is_new: isNew
                    },
                    method: 'get'
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.url = e.data.data.url
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            }
        }
    });
</script>
