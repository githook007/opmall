<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/1/29
 * Time: 11:14
 * @copyright: ©2021 .hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\pc\user;

use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\models\User;

class UserEditForm extends Model
{
    /** @var User $user */
    public $user;
    public $nickname;

    public function rules()
    {
        return [
            [['nickname'], 'string', 'max' => 50],
        ];
    }

    public function edit(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $user = $this->user;
        $user->nickname = $this->nickname;
        if(!$user->save()){
            return $this->getErrorResponse($user);
        }
        return ["code" => ApiCode::CODE_SUCCESS, "msg" => "更新成功"];
    }
}
