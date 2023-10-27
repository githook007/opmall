<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/2/19 11:21
 */

namespace app\forms\attachment;

use app\bootstrap\response\ApiCode;
use app\models\Attachment;
use app\models\AttachmentGroup;
use app\models\Mall;
use app\models\Model;

class GroupForm extends Model
{
    public $attachment_group_id;
    public $is_recycle;
    public $type;

    public $id;

    public $ids;

    /** @var Mall */
    public $mall;
    public $mchId = 0;

    public function rules()
    {
        return [
            [['mall'], 'required'],
            [['attachment_group_id', 'is_recycle', 'id', 'mchId'], 'integer'],
            [['type'], 'string'],
            [['ids'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mall' => 'Mall',
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $query = AttachmentGroup::find()->where([
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
            'mch_id' => $this->mchId,
        ]);

        is_null($this->type) || $query->andWhere(['type' => $this->type === 'video' ? 1 : 0]);
        is_null($this->is_recycle) || $query->andWhere(['is_recycle' => $this->is_recycle]);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $query->all(),
            ],
        ];
    }

    public function move()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $attachmentGroup = AttachmentGroup::findOne([
            'id' => $this->attachment_group_id,
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
            'mch_id' => $this->mchId,
        ]);
        if (!$attachmentGroup) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'data' => '分组不存在，请刷新页面后重试。',
            ];
        }
        Attachment::updateAll(['attachment_group_id' => $attachmentGroup->id,], [
            'id' => $this->ids,
            'mall_id' => $this->mall->id,
        ]);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ];
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $model = AttachmentGroup::findOne([
            'id' => $this->id,
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
            'mch_id' => $this->mchId,
        ]);
        if (!$model) {
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '分组已删除。',
            ];
        }

        switch ($this->type) {
            case '1':
                $edit = ['is_recycle' => 1];
                break;
            case '2':
                $edit = ['is_recycle' => 0];
                break;
            case '3':
                $edit = ['is_delete' => 1];
                break;
            default:
                throw new \Exception('TYPE 错误');
        }
        $model->attributes = $edit;
        if (!$model->save()) {
            return $this->getErrorResponse($model);
        }

        Attachment::updateAll($edit, [
            'attachment_group_id' => $model->id,
            'mall_id' => $this->mall->id,
            'mch_id' => $this->mchId,
        ]);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ];
    }
}
