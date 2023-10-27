<?php
/**
 * @copyright hook007
 * @author 开发团队
 * @link https://www.opmall.com/
 *
 */

$_GET['r'] = 'system/pay-notify/alipay-native';

error_reporting(E_ALL);

// 注册 Composer 自动加载器
require(__DIR__ . '/../../vendor/autoload.php');

// 创建、运行一个应用
$application = new \app\bootstrap\WebApplication();
$application->run();
