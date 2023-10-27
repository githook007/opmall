<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

$urlManager = Yii::$app->urlManager->createUrl('mall/index/index');
?>

<style>
    .main {
        width: 100%;
        text-align: center;
    }

    .error-box {
        margin-top: 200px;
    }

    .img-box {
        float: left;
        margin-left: 20%;
        width: 330px;
        height: 260px;
    }

    .error-text-box {
        float: left;
        margin-left: 72px;
        height: 260px;
    }

    .text1 {
        margin-top: 50px;
        font-size: 30px;
        font-weight: bold;
        color: #005cdb;
        text-align: left;
    }

    .text2 {
        margin-top: 24px;
        font-size: 20px;
        color: #202020;
        text-align: left;
    }

    .text3 {
        margin-top: 40px;
        font-size: 20px;
        color: #006cdb;
        text-align: left;
    }
</style>

<div class="main">

    <div class="error-box">
        <div class="img-box">
            <img src="<?= Yii::$app->request->baseUrl . '/statics/img/permission-error.png' ?>">
        </div>
        <div class="error-text-box">
            <p class="text1"><?= \Yii::t('error/error', '对不起');?></p>
            <p class="text2"><?= \Yii::t('error/error', '正在为您跳转管理页面');?><label class="second">3</label><?= \Yii::t('error/error', '秒');?>...</p>
            <p class="text3"><a href="<?= $urlManager ?>"><?= \Yii::t('error/error', '返回首页');?></a></p>
        </div>
    </div>
</div>

<script src="<?= Yii::$app->request->baseUrl ?>/statics/unpkg/jquery@3.3.1/dist/jquery.min.js"></script>
<script>
    $(function () {
        var second = 3;
        $('.second').text(second);
        var interval = setInterval(function () {
            second--;
            $('.second').text(second);
            if (second == 0) {
                window.clearInterval(interval);
                window.location.href = '<?= $urlManager ?>';
            }

        }, 1000)

    })
</script>
