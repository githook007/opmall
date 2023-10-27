<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\page;

use app\bootstrap\response\ApiCode;
use app\helpers\ArrayHelper;
use app\models\AdminInfo;
use app\models\Model;
use app\models\PageIntro;
use app\models\User;

class PageIntroForm extends Model
{
    public $route;
    public $is_restore;
    public $show_introduce_text;
    public $id;

    public function rules()
    {
        return [
            [['route'], 'string'],
            [['is_restore', 'show_introduce_text', 'id'], 'integer'],
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if($this->is_restore){
            $model = PageIntro::find()->where([
                'mall_id' => 0,
                'route' => $this->route
            ])->select('content')->one();
            $sql = sprintf(
                'update %s set content = %s where mall_id = %s and route = "%s"',
                PageIntro::tableName(),
                $model ? $model->content : '',
                \Yii::$app->mall->id,
                $this->route
            );
            \Yii::$app->db->createCommand($sql)->execute();
        }
        $query = PageIntro::find()->where([
            'route' => $this->route
        ]);
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        if($user->identity->is_super_admin){
            $query->andWhere(['mall_id' => 0]);
        }else{
            $query->andWhere(['mall_id' => [\Yii::$app->mall->id, 0]]);
        }
        /** @var PageIntro[] $data */
        $data = $query->all();

        $data = ArrayHelper::index($data, "mall_id");
        if(!isset($data[\Yii::$app->mall->id])){
            $object = $data[0];
        }else{
            $object = $data[\Yii::$app->mall->id];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => $object->content,
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $adminInfo = AdminInfo::findOne($this->id);
        if($adminInfo){
            $adminInfo->show_introduce_text = $this->show_introduce_text;
            if(!$adminInfo->save()){
                throw new \Exception($this->getErrorMsg($adminInfo));
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
        ];
    }
}
