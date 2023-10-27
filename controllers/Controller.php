<?php
/**
 * 本项目所有web端控制器的基类
 *
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 12:00
 */

namespace app\controllers;

use app\helpers\CurlHelper;
use yii\helpers\HtmlPurifier;

class Controller extends \yii\web\Controller
{
    public function init()
    {
        // 判断是否为https @czs
        if(isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) {
            $_SERVER['HTTPS'] = 1;
        }
        parent::init();
        if (\Yii::$app->request->get('_layout')) {
            $this->layout = \Yii::$app->request->get('_layout');
            $this->layout = HtmlPurifier::process($this->layout); // 过滤html @czs
        }
        if (\Yii::$app->request->get('lang')){
            \Yii::$app->session->set('lang', \Yii::$app->request->get('lang'));
        }
        $this->switching();
    }

    /**
     * 更改中英文状态配置
     */
    public function switching(){
        if (\Yii::$app->session['lang'] == 'en'){
            \Yii::$app->language = 'en-UA';
        }else{
            \Yii::$app->language = 'zh-CN';
        }
    }

}