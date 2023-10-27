<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/28
 * Time: 10:53 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\common\CommonMallMember;
use app\models\User;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;

class MemberForm extends Model
{
    public $user_id;
    public $level;

    public function rules()
    {
        return [
            [['user_id', 'level'], 'required'],
            [['user_id', 'level'], 'integer'],
        ];
    }

    public function setMember()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $user = User::findOne([
                'id' => $this->user_id,
                'is_delete' => 0,
                'mall_id' => \Yii::$app->mall->id
            ]);
            if (!$user) {
                throw new ScrmException('无效的user_id');
            }
            if ($user->identity->member_level == $this->level) {
                throw new ScrmException('用户已经是这个等级的会员，无需变更');
            }
            $memberName = '普通会员';
            $up = $user->identity->member_level < $this->level;
            if ($this->level != 0) {
                $member = CommonMallMember::getMemberOne($this->level);
                if (!$member) {
                    throw new ScrmException('无效的level');
                }
                $memberName = $member->name;
            }
            $user->identity->member_level = $this->level;
            if (!$user->identity->save()) {
                throw new ScrmException($this->getErrorMsg($user->identity));
            }
            return $this->success([
                'msg' => ($up ? '用户会员等级提升到' : '用户会员等级降级到') . $memberName,
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
