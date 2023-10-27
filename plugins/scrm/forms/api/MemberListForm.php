<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/25
 * Time: 4:11 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\forms\common\CommonMallMember;
use app\plugins\scrm\forms\Model;

class MemberListForm extends Model
{
    public $level;

    public function rules()
    {
        return [
            [['level'], 'required'],
            [['level'], 'integer'],
        ];
    }

    public function getList()
    {
        $list = CommonMallMember::getList();
        return $this->success($list['list']);
    }

    public function getOne()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        return $this->success([
            'member' => CommonMallMember::getMemberOne($this->level)
        ]);
    }
}
