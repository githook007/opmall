<?php
$userCenterPath = \Yii::$app->viewPath . '/mall/user-center/';

$currentDir = scandir($userCenterPath . 'template');
$components = [];
foreach ($currentDir as $fileName) {
    if (preg_match("/^u-(\S)+\.php$/", $fileName)) {
        $m = substr($fileName, 0, strripos($fileName, ".php"));
        array_push($components, $m);
        Yii::$app->loadViewComponent($m, $userCenterPath . '/template');
    };
    if (preg_match("/^p-(\S)+\.php$/", $fileName)) {
        $n = substr($fileName, 0, strripos($fileName, ".php"));
        array_push($components, $n);
        Yii::$app->loadViewComponent($n, $userCenterPath . '/template/preview');
    };
}

Yii::$app->loadViewComponent('app-hotspot');
Yii::$app->loadViewComponent('app-radio');
Yii::$app->loadViewComponent('color', $userCenterPath . '');
Yii::$app->loadViewComponent("app-padding", \Yii::$app->viewPath . '/components/diy');
//////////other
$other = ['goods', 'banner', 'notice', 'rubik', 'link', 'empty', 'customer'];
Yii::$app->loadViewComponent("diy-goods", \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("diy-banner", \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("diy-notice", \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("diy-rubik", \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("diy-link",  \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent('app-rich-text');
Yii::$app->loadViewComponent('diy/diy-bg');
Yii::$app->loadViewComponent("diy-empty",  \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("diy-customer",  \Yii::$app->viewPath . '/components/diy');
Yii::$app->loadViewComponent("p-account",  $userCenterPath . 'template/preview');
Yii::$app->loadViewComponent("p-foot",  $userCenterPath . 'template/preview');
Yii::$app->loadViewComponent("p-order",  $userCenterPath . 'template/preview');
Yii::$app->loadViewComponent("p-menu",  $userCenterPath . 'template/preview');
Yii::$app->loadViewComponent("p-svip",  $userCenterPath . 'template/preview');
Yii::$app->loadViewComponent("p-user",  $userCenterPath . 'template/preview');
?>