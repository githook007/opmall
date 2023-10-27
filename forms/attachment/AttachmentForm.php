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
use app\forms\AttachmentUploadForm;
use app\models\Attachment;
use app\models\AttachmentEffect;
use app\models\Mall;
use app\models\Model;

class AttachmentForm extends Model
{
    public $attachment_group_id;
    public $is_recycle;
    public $type;
    public $is_effect;
    public $keyword;
    public $tag;
    public $limit;

    public $id;
    public $name;

    public $ids;

    /** @var Mall */
    public $mall;
    public $mchId = 0;

    public function rules()
    {
        return [
            [['mall'], 'required'],
            [['attachment_group_id', 'is_recycle', 'is_effect', 'limit', 'id', 'mchId'], 'integer'],
            [['type', 'tag', 'keyword', 'name'], 'string'],
            [['ids'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mall' => 'Mall',
        ];
    }

    public function getList(){
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $typeMap = [
            'other' => 0,
            'image' => 1,
            'video' => 2,
            'doc' => 3,
        ];

        if($this->is_effect){ // 超管上传的效果图 。默认 -1
            $this->attachment_group_id = -1;
        }

        $query = Attachment::find()->where([
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
            'type' => $typeMap[$this->type],
            'mch_id' => $this->mchId,
        ]);

        !is_null($this->is_recycle) && $query->andWhere(['is_recycle' => $this->is_recycle]);
        $query->keyword($this->keyword, ['like', 'name', $this->keyword]);
        $this->attachment_group_id && $query->andWhere(['attachment_group_id' => $this->attachment_group_id]);
        !$this->attachment_group_id && $query->andWhere(['>=', 'attachment_group_id', 0]);

        // 超管的素材库默认的mall_id 为 -1。 小程序用户上传图的mall_id 为 0
        if($this->mall->id == -1 && $this->attachment_group_id >= 0){
            if($this->tag){
                $effectQuery = AttachmentEffect::find()->where(['tag' => $this->tag, "is_delete" => 0])->select("pic_id");
                $query->andWhere(['id' => $effectQuery]);
            }
            $query->with(["effect.attachment"]);
        }
        if(!\Yii::$app->user->isGuest && $this->mall->id == 0){
            $query->andWhere(['user_id' => \Yii::$app->user->id]);
        }

        $list = $query
            ->orderBy('id DESC')
            ->page($pagination, intval($this->limit ?: '20'))
            ->asArray()
            ->all();

        foreach ($list as &$item) {
            $item['thumb_url'] = $item['thumb_url'] ?: $item['url'];
        }
        $space = [];
        if($this->mall->id > 0){
            $extend = $this->mall->extendObj();
            $space['memory'] = $extend->memory == -1 ? '无限制' : space_unit($extend->memory);
            $space['used_memory'] = space_unit($extend->used_memory);
        }
        $tags = (new AttachmentUploadForm())->getDefaultTag();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
                'space' => $space,
                'tags' => array_merge(['' => '全部'], $tags)
            ],
        ];
    }

    public function rename()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        $attachment = Attachment::findOne([
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
            'id' => $this->id,
            'mch_id' => $this->mchId,
        ]);
        if (!$attachment) {
            throw new \Exception('数据为空');
        }
        $attachment->name = $this->name;
        $attachment->save();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功'
        ];
    }

    public function delete()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        if (!is_array($this->ids)) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '提交数据格式错误。',
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
                $edit = [];
                break;
        }
        Attachment::updateAll($edit, [
            'id' => $this->ids,
            'mall_id' => $this->mall->id,
            'mch_id' => $this->mchId,
        ]);
        if($this->mall->id > 0 && $this->type == 3 && $this->mall->extend){
            $attachmentSize = Attachment::find()->where([
                'id' => $this->ids,
                'mall_id' => $this->mall->id,
                'mch_id' => $this->mchId,
                'is_delete' => 1
            ])->sum('size');
            $used_memory = $this->mall->extend->used_memory - round($attachmentSize / 1024 / 1024, 8);
            $this->mall->extendObj(['used_memory' => max($used_memory, 0)]);
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功。',
        ];
    }
}
