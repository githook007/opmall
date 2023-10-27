<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/9/9
 * Time: 11:06
 */

$_GET['r'] = 'system/pay-notify/baidu-refund-verify';

error_reporting(E_ALL);

// 注册 Composer 自动加载器
require(__DIR__ . '/../../vendor/autoload.php');

// 创建、运行一个应用
$application = new \app\bootstrap\WebApplication();
$application->run();
