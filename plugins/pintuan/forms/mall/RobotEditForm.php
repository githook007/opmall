<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\pintuan\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\pintuan\models\PintuanRobots;

class RobotEditForm extends Model
{
    public $id;
    public $avatar;
    public $nickname;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nickname', 'avatar'], 'string']
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $robot = PintuanRobots::findOne($this->id);
            if (!$robot) {
                $robot = new PintuanRobots();
                $robot->mall_id = \Yii::$app->mall->id;
            }

            $robot->nickname = $this->nickname;
            $robot->avatar = $this->avatar;
            $res = $robot->save();

            if (!$res) {
                throw new \Exception($this->getErrorMsg($robot));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功'
            ];

        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}