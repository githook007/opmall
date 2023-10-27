<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */


namespace app\controllers\api;


use app\controllers\api\ApiController;
use app\forms\api\video_number\VideoNumberEditForm;

class VideoNumberController extends ApiController
{
    // 添加素材
    public function actionAddMaterial()
    {
        $form = new VideoNumberEditForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->save();

        return $this->asJson($res);
    }

    public function actionGetArticleUrl()
    {
    	$form = new VideoNumberEditForm();
        $form->attributes = \Yii::$app->request->get();
        $res = $form->getArticleUrl();

        return $this->asJson($res);
    }
}
