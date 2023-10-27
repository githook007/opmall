<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 12:00
 */


namespace app\plugins;

use app\controllers\mall\MallController;

class Controller extends MallController
{
    public $layout = '/plugin';

    public function init()
    {
        parent::init();
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->headers
                ->set('Cache-Control', 'no-store, no-cache, must-revalidate')
                ->set('Expires', 'Thu, 19 Nov 1981 08:00:00 GMT')
                ->set('Pragma', 'no-cache');
        }
    }

    public function render($view, $params = [])
    {
        if (mb_stripos($view, '@') !== 0 && mb_stripos($view, '/') !== 0) {
            $view = '@app/plugins/' . $this->module->id . '/views/' . mb_strtolower($this->id) . '/' . $view;
        }
        return parent::render($view, $params);
    }
}
