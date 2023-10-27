<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/11/29
 * Time: 14:58
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\diy\forms\mall\market;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\diy\models\CoreTemplateEdit;

class EditForm extends Model
{
    public $template_id;
    public $name;
    public $price;

    public function rules()
    {
        return [
            [['template_id', 'name', 'price'], 'required'],
            [['template_id'], 'integer'],
            [['name'], 'trim'],
            [['name'], 'string'],
            [['price'], 'number', 'min' => 0]
        ];
    }

    public function attributeLabels()
    {
        return [
            'template_id' => '选择模板',
            'name' => '修改后模板名称',
            'price' => '修改后价格',
        ];
    }

    public function update()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $model = CoreTemplateEdit::findOne(['template_id' => $this->template_id]);
        if (!$model) {
            $model = new CoreTemplateEdit();
            $model->template_id = $this->template_id;
        }
        $model->name = $this->name;
        $model->price = $this->price;
        if (!$model->save()) {
            return $this->getErrorResponse($model);
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '设置成功'
        ];
    }
}
