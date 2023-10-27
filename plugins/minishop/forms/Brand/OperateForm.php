<?php
/**
 * Created By PhpStorm
 * Date: 2021/5/6
 * Time: 2:38 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\Brand;

use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopBrand;

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
        $minishopBrand = MinishopBrand::findOne([
            'is_delete' => 0,
            'id' => $this->id,
            'mall_id' => \Yii::$app->mall->id
        ]);
        if (!$minishopBrand) {
            throw new \Exception('品牌不存在或已被删除');
        }
        $minishopBrand->is_delete = 1;
        if (!$minishopBrand->save()) {
            throw new \Exception($this->getErrorMsg($minishopBrand));
        }
        return true;
    }
}
