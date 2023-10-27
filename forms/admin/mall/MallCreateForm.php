<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/15 11:57
 */

namespace app\forms\admin\mall;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonUser;
use app\models\Mall;
use app\models\Model;
use app\models\User;

class MallCreateForm extends Model
{
    public $id;
    public $name;
    public $expired_at;
    public $goods_limit_num;
    public $memory;

    public function rules()
    {
        return [
            [['name'], 'trim'],
            [['name', 'expired_at'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['id', 'goods_limit_num', 'memory'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '商城名称',
            'expired_at' => '商城有效期'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse($this);
        }
        /** @var User $user */
        $user = \Yii::$app->user->identity;
        if ($this->id) {
            $model = Mall::findOne($this->id);
        } else {
            $adminInfo = CommonUser::getAdminInfo('app_max_count');
            $count = Mall::find()->where([
                'user_id' => $user->id,
                'is_delete' => 0,
            ])->count();
            if ($adminInfo->app_max_count >= 0 && $count >= $adminInfo->app_max_count) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '超出创建小程序最大数量',
                ];
            }
            $model = new Mall();
            $model->user_id = $user->id;
        }
        $model->attributes = $this->attributes;
        if (!$model->save()) {
            return $this->getErrorResponse($model);
        }
        $data = null;
        if($user->identity->is_super_admin){
            $data['goods_limit_num'] = $this->goods_limit_num;
            $data['memory'] = $this->memory == -1 ? -1 : $this->memory * 1024;
        }
        $model->extendObj($data);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '保存成功。',
            'data' => $model,
        ];
    }
}
