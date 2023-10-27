<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/22
 * Time: 10:24 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\common\config\UserCenterConfig;
use app\models\MallMembers;
use app\models\UserPlatform;
use app\plugins\scrm\exception\ScrmException;
use app\plugins\scrm\forms\Model;

class UserInfoForm extends Model
{
    public $unionid;

    public function rules()
    {
        return [
            [['unionid'], 'required'],
            [['unionid'], 'trim'],
            [['unionid'], 'string'],
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $userPlatform = UserPlatform::findOne([
                'mall_id' => \Yii::$app->mall->id,
                'unionid' => $this->unionid,
            ]);
            if (!$userPlatform) {
                throw new ScrmException('unionid对应的用户未在商城中登陆，商城id：' . \Yii::$app->mall->id);
            }
            $userCenterConfig = UserCenterConfig::getInstance()->memberData();
            $levelName = $userCenterConfig['general_user_text'];
            if ($userPlatform->user->identity->member_level != 0) {
                $level = MallMembers::findOne([
                    'mall_id' => \Yii::$app->mall->id,
                    'level' => $userPlatform->user->identity->member_level,
                    'status' => 1, 'is_delete' => 0
                ]);
                if ($level) {
                    $levelName = $level->name;
                }
            }
            return $this->success([
                'id' => $userPlatform->user->id,
                'nickname' => $userPlatform->user->nickname,
                'mobile' => $userPlatform->user->mobile,
                'avatar' => $userPlatform->user->userInfo->avatar,
                'integral' => $userPlatform->user->userInfo->integral,
                'balance' => $userPlatform->user->userInfo->balance,
                'identity' => [
                    'parent_id' => $userPlatform->user->userInfo->parent_id,
                    'member_level' => $userPlatform->user->identity->member_level,
                    'level_name' => $levelName
                ],
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
