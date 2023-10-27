<?php
/**
 * @copyright ©2018 hook007
 * author: chenzs
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/12/7 14:03
 */


namespace app\plugins\app\controllers\api;

use app\forms\common\CommonDistrict;
use app\plugins\app\forms\api\TemplateForm;

class IndexController extends ApiController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
        ]);
    }

    // 新的首页接口
    public function actionTplIndex()
    {
        $form = new TemplateForm();
        $form->attributes = \Yii::$app->request->get();
        return $this->asJson($form->getData());
    }

    public function actionAddressInfo(){
        $commonDistrict = new CommonDistrict();
        $district = $commonDistrict->search();
        return $this->asJson([
            'code' => 0,
            'data' => $district
        ]);
    }
}
