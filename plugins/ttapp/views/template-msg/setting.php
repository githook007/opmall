<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<div id="app">
    <app-template url="plugin/ttapp/template-msg/setting" submit-url='plugin/ttapp/template-msg/setting'
                  sign="ttapp"
                  add-url="plugin/ttapp/template-msg/add-template" :one-key="isShow">
        <template slot="after_remind">
            <br/>
            <div style="margin: -10px 20px 20px;background-color: #F4F4F5;padding: 10px 15px;color: #909399;display: inline-block;font-size: 15px">
                <?= \Yii::t('plugins/ttapp', '目前只有今日头条支持');?>
            </div>
        </template>
    </app-template>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                isShow: false,
            };
        },
    });
</script>
