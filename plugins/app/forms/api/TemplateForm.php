<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: chenzs
 */

namespace app\plugins\app\forms\api;

use app\bootstrap\response\ApiCode;
use app\forms\api\index\NewIndexForm;
use app\plugins\diy\Plugin;

class TemplateForm extends NewIndexForm
{
    // 获取原始数据
    public function getData()
    {
        try {
            /* @var Plugin $plugin */
            $plugin = \Yii::$app->plugin->getPlugin('diy');
            if (!is_callable([$plugin, 'getTemplatePage'])) {
                throw new \Exception('插件未更新');
            }
            $page = $plugin->getTemplatePage($this->page_id);
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'show_navs' => $page['show_navs'],
                'title' => $page['title'],
                'template' => $page['navs'][0]['template']['data']
            ],
            'time' => date('Y-m-d H:i:s', time())
        ];
    }
}