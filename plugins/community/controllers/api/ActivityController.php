<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/2
 * Time: 17:16
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\controllers\api;


use app\controllers\api\filters\LoginFilter;
use app\plugins\community\forms\api\activity\PosterConfigForm;
use app\plugins\community\forms\api\ActivityForm;
use app\plugins\community\forms\api\GoodsCatsForm;
use app\plugins\community\forms\api\UserActivityForm;

class ActivityController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionList()
    {
        $form = new ActivityForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->type = 2;
        return $this->asJson($form->getActivityList());
    }

    public function actionDetail()
    {
        $form = new ActivityForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->getActivityDetail());
    }

    public function actionLog()
    {
        $form = new ActivityForm();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->getLog());
    }


    public function actionActivityList()
    {
        $form = new ActivityForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        $form->type = 1;
        return $this->asJson($form->getActivityList());
    }

    //用户活动页面，分享页面
    public function actionUserActivity()
    {
        $form = new UserActivityForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->getActivityDetail());
    }

    public function actionPosterConfig()
    {
        $form = new PosterConfigForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getDetail());
    }

    public function actionGoodsCats()
    {
        $form = new GoodsCatsForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getCats());
    }

    public function actionGoodsList()
    {
        $form = new GoodsCatsForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->goodsList());
    }

    public function actionActivity()
    {
        $form = new UserActivityForm();
        $form->attributes = \Yii::$app->request->get();
        $form->attributes = \Yii::$app->request->post();
        return $this->asJson($form->getNewUserActivity());
    }
}
