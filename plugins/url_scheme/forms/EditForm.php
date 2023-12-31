<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/20
 * Time: 3:57 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\url_scheme\forms;

use app\plugins\url_scheme\models\UrlScheme;
use app\plugins\wxapp\Plugin;
use yii\helpers\Json;

class EditForm extends Model
{
    public $name;
    public $user_id;
    public $is_expire;
    public $expire_time;
    public $link;

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'trim'],
            [['name'], 'string', 'max' => 14],
            ['is_expire', 'in', 'range' => [0, 1]],
            ['expire_time', 'integer', 'min' => 0, 'max' => 365],
            [['expire_time', 'user_id'], 'default', 'value' => 0],
            ['link', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '链接名称',
            'expire_time' => '失效时间'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            if ($this->is_expire == 1 && $this->expire_time == 0) {
                throw new \Exception('失效时间必填');
            }
            $query = [];
            if ($this->user_id != 0) {
                $query[] = 'user_id=' . $this->user_id;
            }
            if ($this->link) {
                if (isset($this->link['params']) && $this->link['params']) {
                    foreach ($this->link['params'] as $param) {
                        $query[] = $param['key'] . '=' . $param['value'];
                    }
                }
                $link = explode('?', $this->link['value']);
                $path = $link[0];
                if (count($link) > 1) {
                    $query[] = $link[1];
                }
            } else {
                $path = '/pages/index/index';
            }
            $params = [
                'jump_wxa' => [
                    'path' => $path,
                    'query' => !empty($query) ? implode('&', $query) : ''
                ],
                'expire_type' => $this->is_expire
            ];
            if($this->is_expire == 1){
                $params['expire_interval'] = $this->expire_time;
            }
            /** @var Plugin $plugin */
            $plugin = \Yii::$app->plugin->getPlugin('wxapp');
            $res = $plugin->getSubscribe()->getScheme($params);
            $model = new UrlScheme();
            $model->mall_id = \Yii::$app->mall->id;
            $model->is_delete = 0;
            $model->name = $this->name;
            $model->is_expire = $this->is_expire;
            $model->expire_time = $this->expire_time;
            $model->url_scheme = $res['openlink'];
            $model->link = Json::encode($params['jump_wxa'], JSON_UNESCAPED_UNICODE);
            if (!$model->save()) {
                throw new \Exception($this->getErrorMsg($model));
            }
            return $this->success([
                'msg' => '成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
