<?php
/**
 * @copyright ©2022 opmall
 * author: opmall
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/20 14:03
 */


namespace app\validators;


use yii\validators\Validator;

class VersionValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        $pattern = '/^\d*(\.\d*)*$/';
        if ($value && !preg_match($pattern, $value)) {
            $model->addError($attribute, "{$model->getAttributeLabel($attribute)}格式不正确。");
        }
    }
}
