<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/11/18
 * Time: 14:58
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\admin\template;


use app\bootstrap\response\ApiCode;
use app\models\Model;

class ListForm extends Model
{
    public $page;
    public $keyword;
    public $type;
    public $is_buy;
    public $status; // 0--本地已安装 1--云市场

    public function rules()
    {
        return [
            [['page', 'is_buy', 'status'], 'integer'],
            [['keyword', 'type'], 'trim'],
            [['keyword', 'type'], 'string'],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $list = [];
            try {
                $plugin = \Yii::$app->plugin->getPlugin('diy');
                if ($this->status == 1) {
                    $list = $plugin->getMarketList($this->attributes);
                } else {
                    $list = $plugin->getLocalList($this->attributes);
                }
            } catch (\Exception $exception) {
                throw $exception;
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => [
                    'list' => $list
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }
}
