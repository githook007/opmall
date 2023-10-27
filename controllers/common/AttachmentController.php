<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/14 11:33
 */

namespace app\controllers\common;

use app\controllers\Controller;
use app\bootstrap\response\ApiCode;
use app\forms\attachment\AttachmentForm;
use app\forms\attachment\EffectForm;
use app\forms\attachment\GroupForm;
use app\forms\attachment\GroupUpdateForm;
use app\forms\AttachmentUploadForm;
use app\models\Mall;
use yii\web\UploadedFile;

class AttachmentController extends Controller
{
    private $xMall;
    private $mchId;

    /**
     * @return null|Mall
     */
    protected function getMall()
    {
        if ($this->xMall) {
            return $this->xMall;
        }
        if(\Yii::$app->request->isGet){
            $mall_id = \Yii::$app->request->get('mall_id');
        }elseif(\Yii::$app->request->isPost){
            $mall_id = \Yii::$app->request->post('mall_id') ?: \Yii::$app->request->get('mall_id');
        }
        if(isset($mall_id) && $mall_id == -1){
            $this->xMall = new Mall();
            $this->xMall->id = -1;
            return $this->xMall;
        }
        $id = \Yii::$app->getSessionMallId();
        if (!$id || !$mall = Mall::findOne(['id' => $id])) {
            $mall = new Mall();
            $mall->id = 0;
        }
        $this->xMall = $mall;
        return $this->xMall;
    }

    protected function getMchId()
    {
        if ($this->mchId) {
            return $this->mchId;
        }
        $mallId = $this->getMall();
        if($mallId->id == -1){
            $this->mchId = 0;
        }else {
            $this->mchId = !\Yii::$app->user->isGuest ? \Yii::$app->user->identity->mch_id : 0;
        }
        return $this->mchId;
    }

    public function actionList()
    {
        $form = new AttachmentForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->getList());
    }

    public function actionRename()
    {
        $form = new AttachmentForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->rename());
    }

    public function actionDelete()
    {
        $form = new AttachmentForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->delete());
    }

    public function actionUpload($name = 'file', $attachment_group_id = null)
    {
        $mall = $this->getMall();
        if ($mall) {
            \Yii::$app->setMall($mall);
        }
        \Yii::$app->setMchId($this->getMchId());

        $form = new AttachmentUploadForm();
        $form->file = UploadedFile::getInstanceByName($name);
        $form->attachment_group_id = $attachment_group_id;
        return $this->asJson($form->save());
    }

    public function actionMove()
    {
        $form = new GroupForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->move());
    }

    public function actionGroupList()
    {
        $form = new GroupForm();
        $form->attributes = \Yii::$app->request->get();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->getList());
    }

    public function actionGroupUpdate()
    {
        $mall = $this->getMall();
        if (!$mall) {
            return $this->asJson([
                'code' => ApiCode::CODE_ERROR,
                'data' => 'Mall为空，请刷新页面后重试。'
            ]);
        }

        $form = new GroupUpdateForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall_id = $mall->id;
        $form->type = \Yii::$app->request->post('type') == 'video' ? 1 : 0;
        $form->mch_id = $this->getMchId();
        return $this->asJson($form->save());
    }

    public function actionGroupDelete()
    {
        $form = new GroupForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall = $this->getMall();
        $form->mchId = $this->getMchId();
        return $this->asJson($form->delete());
    }

    public function actionEffect()
    {
        $mall = $this->getMall();
        if (!$mall) {
            return $this->asJson([
                'code' => ApiCode::CODE_ERROR,
                'data' => 'Mall为空，请刷新页面后重试。'
            ]);
        }

        $form = new EffectForm();
        $form->attributes = \Yii::$app->request->post();
        $form->mall_id = $mall->id;
        return $this->asJson($form->save());
    }
}
