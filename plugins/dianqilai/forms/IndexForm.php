<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/7/3
 * Time: 15:48
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\dianqilai\forms;


use app\bootstrap\response\ApiCode;
use app\models\Model;
use app\plugins\dianqilai\forms\common\CommonSetting;

class IndexForm extends Model
{
    public $is_new;

    public function rules()
    {
        return [
            [['is_new'], 'integer'],
            [['is_new'], 'in', 'range' => [0, 1]]
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        if ($this->is_new) {
            $token = CommonSetting::getCommon(\Yii::$app->mall)->setToken();
        } else {
            $token = CommonSetting::getCommon(\Yii::$app->mall)->getToken();
        }
        $token = base64_encode(json_encode([
            'token' => $token,
            'mall_id' => \Yii::$app->mall->id
        ]));
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'url' => \Yii::$app->request->hostInfo .
                    \Yii::$app->urlManager->createUrl(['plugin/dianqilai/api/index/index', 'token' => $token])
            ]
        ];
    }
}
