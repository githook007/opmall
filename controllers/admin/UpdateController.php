<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/3/1
 * Time: 15:58
 */

namespace app\controllers\admin;

use app\controllers\behaviors\SuperAdminFilter;
use app\bootstrap\response\ApiCode;
use app\forms\admin\UpdateForm;

class UpdateController extends AdminController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'superAdminFilter' => [
                'class' => SuperAdminFilter::class,
            ],
        ]);
    }

    public function actionIndex()
    {
        if (\Yii::$app->request->isAjax) {
            try {
                $versions = UpdateForm::getInstance()->getVersionData();
            } catch (\Exception $exception) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $exception->getMessage(),
                ];
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $versions,
            ];
        } else {
            return $this->render('index');
        }
    }

    public function actionUpdate()
    {
        if (\Yii::$app->request->isPost) {
            try {
                $result = UpdateForm::getInstance()->update();
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '更新成功。',
                    'data' => $result === 2 ? ["reply" => 1] : $result,
                ];
            } catch (\Exception $exception) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => $exception->getMessage(),
                ];
            }
        }
    }
}