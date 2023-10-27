<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: fjt
 */
Yii::$app->loadViewComponent('app-activity-list');
?>

<div id="app" v-cloak>
    <app-activity-list
            activity_name="<?= \Yii::t('plugins/miaosha', '秒杀');?>"
            :tabs="tabs"
            :no_edit="1"
            sign="miaosha"
            activity_url="plugin/miaosha/mall/activity/index"
            activity_detail_url="plugin/miaosha/mall/activity/detail"
            edit_activity_url='plugin/miaosha/mall/activity/edit'
            edit_activity_status_url="plugin/miaosha/mall/activity/batch-update-status"
            edit_activity_destroy_url="plugin/miaosha/mall/activity/batch-destroy"
    ></app-activity-list>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                tabs: [
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '全部');?>',
                        value: '1'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '未开始');?>',
                        value: '2'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '进行中');?>',
                        value: '3'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '已结束');?>',
                        value: '4'
                    },
                    {
                        name: '<?= \Yii::t('plugins/miaosha', '已下架');?>',
                        value: '5'
                    }
                ]
            };
        },
        created() {
        },
        methods: {
        }
    });
</script>
