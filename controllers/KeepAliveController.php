<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/29 16:46
 */


namespace app\controllers;


use app\controllers\behaviors\LoginFilter;

class KeepAliveController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'loginFilter' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionIndex()
    {
    }
}
