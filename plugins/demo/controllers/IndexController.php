<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:44
 */


namespace app\plugins\demo\controllers;


use app\plugins\Controller;
use app\plugins\demo\models\DemoPost;

class IndexController extends Controller
{
    public function actionIndex()
    {
        \Yii::warning('--test-log--');
        $demoPost = new DemoPost();
        $demoPost->id = 1;
        $demoPost->title = 'Demo Title.';
        return $this->render('index', [
            'msg' => 'Demo Plugin.',
            'demoPost' => $demoPost,
        ]);
    }
}
