<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/19
 * Time: 17:25
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\mall\import;


use app\bootstrap\response\ApiCode;
use app\forms\common\data_importing\V3DataImporting;
use app\models\Model;
use app\models\ModelActiveRecord;

class ImportApiForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            [['code'], 'string']
        ];
    }

    public function import()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            set_time_limit(0);
            // 关闭日志存储
            ModelActiveRecord::$log = false;
            $t1 = microtime(true);
            // v3商城数据导入
            $import = new V3DataImporting();
            $import->code = $this->code;
            $import->mall = \Yii::$app->mall;
            if (!$import->import()) {
                throw new \Exception('导入失败x01');
            }
            $t2 = microtime(true);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '导入成功，导入时间：' . round($t2 - $t1, 3) . '秒'
            ];
        } catch (\Exception $exception) {
            throw $exception;
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
