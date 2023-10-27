<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/30
 * Time: 11:40 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\mall;

use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;
use app\plugins\scrm\models\ScrmConfig;
use yii\base\BaseObject;

class ConfigForm extends Model
{
    public $appid;
    public $secret;
    public $domain;

    public function rules()
    {
        return [
            [['appid', 'secret', 'domain'], 'required'],
            [['appid', 'secret', 'domain'], 'trim'],
            [['appid', 'secret', 'domain'], 'string'],
            [['domain'], 'url', 'message' => '域名不是正确，检查下是否填写http://或者https://']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $model = ScrmConfig::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ]);
            if (!$model) {
                $model = new ScrmConfig();
                $model->mall_id = \Yii::$app->mall->id;
                $model->is_delete = 0;
            }
            $model->attributes = $this->attributes;
            if (!$model->save()) {
                throw new ScrmException($this->getErrorMsg($model));
            }
            return $this->success([
                'msg' => '保存成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
