<?php
/**
 * @copyright ©2018 .hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/14 11:33
 */

namespace app\controllers\pc\web;

use app\controllers\pc\web\filters\LoginFilter;
use app\bootstrap\Pagination;
use app\bootstrap\response\ApiCode;
use app\forms\AttachmentUploadForm;
use app\models\Attachment;
use yii\web\UploadedFile;

class UploadController extends CommonController
{
    public function behaviors(){
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginFilter::class,
            ],
        ]);
    }

    public function actionList()
    {
        $page = $this->getParams("page", 1);
        $limit = $this->getParams("pageSize", 20);
        $type = "image";

        $typeMap = [
            'other' => 0,
            'image' => 1,
            'video' => 2,
            'doc' => 3,
        ];
        /** @var Pagination  $pagination */
        $list = Attachment::find()->where([
            'mall_id' => 0,
            'is_delete' => 0,
            "attachment_group_id" => 0,
            'type' => $typeMap[$type],
            'user_id' => \Yii::$app->user->id,
        ])->orderBy('id DESC')
            ->page($pagination, $limit, $page)
            ->asArray()
            ->all();
        $newList = ["count" => $pagination->total_count, "currentPage" => $pagination->current_page, "totalPage" => $pagination->page_count, "list" => []];
        foreach ($list as &$item) {
            $item['thumb_url'] = $item['thumb_url'] ?: $item['url'];
            $newList['list'][] = [
                "id" => $item['thumb_url'], "isDelete" => 0, "location" => "pc", "name" => $item['thumb_url'], "url" => $item['thumb_url']
            ];
        }
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $newList,
        ]);
    }

    public function actionDelete()
    {
        $ids = $this->getParams("ids");
        $ids = explode(",", $ids);
        foreach ($ids as $id){
            Attachment::updateAll([
                "is_delete" => 1,
            ], [
                'AND',
                ["mall_id" => \Yii::$app->mall->id],
                ['user_id' => \Yii::$app->user->id],
                [
                    'or',
                    ['thumb_url' => $id],
                    ['url' => $id]
                ]
            ]);
        }
        return $this->asJson([
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ]);
    }

    public function actionUpload($name = 'file')
    {
        try{
            $form = new AttachmentUploadForm();
            $form->file = UploadedFile::getInstanceByName($name);
            return $this->asJson($form->save());
        }catch (\Exception $e){
            return $this->asJson([
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ]);
        }
    }
}
