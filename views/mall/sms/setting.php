<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

$mchId = Yii::$app->user->identity->mch_id;
Yii::$app->loadViewComponent('app-sms-setting');
?>
<style>
    .el-card__body {
        background-color: #F3F3F3;
        padding: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0">
        <div slot="header">
            <div>
                <span><?= \Yii::t('mall/sms', '短信配置');?></span>
            </div>
        </div>
        <app-sms-setting></app-sms-setting>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                mch_id: <?= $mchId ?>,
            };
        },
    });
</script>
