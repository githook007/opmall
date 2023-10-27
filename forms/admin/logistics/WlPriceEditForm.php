<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\logistics;

use app\bootstrap\response\ApiCode;
use app\forms\common\CommonOption;
use app\models\Model;
use app\models\Option;
use app\models\WlhulianData;

class WlPriceEditForm extends Model
{
    public $price_type;
    public $price_value;
    public $id;

    public function rules()
    {
        return [
            [['price_type', 'id'], 'integer'],
            [['price_value'], 'number'],
        ];
    }

    public function attributeLabels()
    {
        return [];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            if($this->price_type == 2){
                $this->price_value = max($this->price_value, 0);
                $this->price_value = min($this->price_value, 100);
            }
            if(!$this->id) {
                $data = $this->attributes;
                CommonOption::set('open_wlhulian_price_setting', $data, 0, Option::GROUP_ADMIN);
            }else{
                $data = WlhulianData::findOne(['mall_id' => $this->id, 'is_delete' => 0]);
                if(!$data){
                    $data = new WlhulianData();
                    $data->mall_id = $this->id;
                }
                $data->price_type = $this->price_type;
                $data->price_value = $this->price_value;
                if(!$data->save()){
                    throw new \Exception($this->getErrorMsg($data));
                }
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
