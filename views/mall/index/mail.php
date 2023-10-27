<?php
/**
 * @link:https://www.opmall.com/
 * @copyright: Copyright (c) 2018 hook007
 *
 * Created by PhpStorm.
 * User: opmall
 * Date: 2018/12/8
 * Time: 14:01
 */
Yii::$app->loadViewComponent('app-mail-setting');
?>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('mall/index', '邮件管理（QQ邮箱）');?></span>
        </div>
        <app-mail-setting></app-mail-setting>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
    });
</script>
