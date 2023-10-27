<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 */

namespace app\forms\admin\copyright;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;

class CopyrightEditForm extends Model
{
    public $data;
    public $mall_id;

    public function rules()
    {
        return [
            [['data'], 'safe'],
            [['mall_id'], 'integer']
        ];
    }

    public function save()
    {
        try {
            if (!$this->data) {
                throw new \Exception('请输入form参数数据');
            }
            $res = CommonOption::set(Option::NAME_COPYRIGHT, $this->data, 0, Option::GROUP_APP);

            if (!$res) {
                throw new \Exception('保存失败');
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
