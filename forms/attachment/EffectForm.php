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
use app\models\AttachmentEffect;
use app\models\Model;

class EffectForm extends Model
{
    public $id;
    public $mall_id;
    public $effect_id;
    public $group_id;
    public $attachments;
    public $name;
    public $is_edit;
    public $tag;

    public function rules()
    {
        return [
            [['attachments',], 'safe'],
            [['mall_id', 'attachments'], 'required'],
            [['id', 'group_id', 'effect_id', 'is_edit'], 'integer',],
            [['name', 'tag'], 'string'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }

        foreach ((array)$this->attachments as $item){
            $attachment = Attachment::findOne($item['id']);
            if(!$attachment){
                continue;
            }
            $attachment->attachment_group_id = $this->group_id ?: 0;
            if($this->is_edit){
                $attachment->name = $this->name;
            }
            if(!$attachment->save()){
                throw new \Exception($this->getErrorMsg($attachment));
            }
            $effect = AttachmentEffect::findOne(['pic_id' => $attachment->id]);
            if(!$effect) {
                $effect = new AttachmentEffect();
                $effect->pic_id = $attachment->id;
            }
            $effect->effect_id = $this->effect_id ?: 0;
            $effect->tag = $this->tag ?: null;
            $effect->is_delete = 0;
            if (!$effect->save()) {
                throw new \Exception($this->getErrorMsg($effect));
            }
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功。',
        ];
    }
}
