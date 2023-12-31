<?php
/**
 * Created by op_mall
 * User: jack_guo
 * Date: 2019/7/5
 * Email: <657268722@qq.com>
 */

namespace app\plugins\region\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\region\models\RegionBonusLog;

class BalanceForm extends Model
{
    public $start_date;
    public $end_date;


    public function rules()
    {
        return [
            [['start_date', 'end_date'], 'string'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = RegionBonusLog::find()->where(['mall_id' => \Yii::$app->mall->id]);
        if ($this->start_date) {
            $model->andWhere(['>=', 'end_time', $this->start_date]);
        }
        if ($this->end_date) {
            $model->andWhere(['<=', 'start_time', $this->end_date]);
        }
        $list = $model->orderBy('created_at desc')
            ->page($pagination)
            ->asArray()
            ->all();

        foreach ($list as &$item) {
            $item['start_time'] = substr($item['start_time'], 0, 10);
            $item['end_time'] = substr($item['end_time'], 0, 10);
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => $list,
            'pagination' => $pagination
        ];
    }
}