<?php
/**
 * @copyright hook007
 *
 */

$_GET['r'] = 'system/msg-notify/dada-city-service';

error_reporting(E_ALL);

// 注册 Composer 自动加载器
require(__DIR__ . '/../../vendor/autoload.php');

// 创建、运行一个应用
$application = new \app\bootstrap\WebApplication();
$application->run();
