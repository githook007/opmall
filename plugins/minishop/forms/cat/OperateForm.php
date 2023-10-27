<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/25
 * Time: 7:34 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\cat;

use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopCat;

class OperateForm extends Model
{
    public $operate;
    public $id;

    public function rules()
    {
        return [
            [['operate'], 'in', 'range' => ['delete']],
            [['id'], 'integer']
        ];
    }
    public function execute()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            switch ($this->operate) {
                case 'delete':
                    $this->delete();
                    break;
                default:
            }
            return $this->success([
                'msg' => '操作成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }

    protected function delete()
    {
        $minishopCat = MinishopCat::findOne([
            'is_delete' => 0,
            'id' => $this->id,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if (!$minishopCat) {
            throw new \Exception('类目不存在或已被删除');
        }
        $minishopCat->is_delete = 1;
        if (!$minishopCat->save()) {
            throw new \Exception($this->getErrorMsg($minishopCat));
        }
        return true;
    }
}
