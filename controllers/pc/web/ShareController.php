<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/14
 * Time: 11:47
 */

namespace app\controllers\pc\web;

use app\controllers\api\filters\LoginFilter;
use app\forms\pc\share\ShareApplyForm;
use app\forms\pc\share\ShareForm;

class ShareController extends CommonController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionIndex()
    {
        $form = new ShareApplyForm();
        $form->mall = \Yii::$app->mall;
        $data = $form->getShareStatus();
        $form = new ShareForm();
        $form->attributes = \Yii::$app->request->get();
        $temp = $form->search();
        if(!empty($temp['data'])){
            $data['data']['share'] = $temp['data'];
        }
        $temp = $form->customize();
        if(!empty($temp['data']['list']['data'])){
            $data['data']['agree_title'] = $temp['data']['list']['data']['apply']['share_apply_pact']['default'];
        }
        $data['data']['menu'] = $temp['data']['list']['data']['menus'];
        return $this->asJson($data);
    }

    // 申请成为分销商
    public function actionApply()
    {
        $form = new ShareApplyForm();
        $form->attributes = $this->getParams();
        return $this->asJson($form->save());
    }
}
