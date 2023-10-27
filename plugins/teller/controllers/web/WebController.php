<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/11/7 18:17
 */


namespace app\plugins\teller\controllers\web;

use app\controllers\Controller;
use app\models\Mall;
use app\plugins\teller\controllers\web\filter\LoginFilter;

class WebController extends Controller
{
    public $layout = '/main';

    public function init()
    {
        parent::init();
        $this->loadMall();
    }

    private function loadMall()
    {
        $url = \Yii::$app->urlManager->createUrl('admin/index/index');
        $id = \Yii::$app->getSessionMallId();
        if (!$id) {
            $id = \Yii::$app->request->get('mall_id');
            $id = base64_decode($id);
        }
        if (empty($id)) {
            return $this->redirect($url);
        }
        /** @var Mall $mall */
        $mall = Mall::find()->where(['id' => $id, 'is_delete' => 0])->one();
        if (!$mall) {
            return $this->redirect($url);
        }
        if ($mall->is_delete !== 0 || $mall->is_recycle !== 0) {
            return $this->redirect($url);
        }

//        $newOptions = [];
//        foreach ($mall['option'] as $item) {
//            $newOptions[$item['key']] = $item['value'];
//        }
//        $mall->options = (object)$newOptions;

        \Yii::$app->mall = $mall;
        return $this;
    }

    public function behaviors()
    {
        return [
            'loginFilter' => [
                'class' => LoginFilter::class,
                'safeRoutes' => [
                    'plugin/teller/web/passport/login',
                    'plugin/teller/web/passport/setting',
                ],
            ]
        ];
    }

    public function render($view, $params = [])
    {
        if (mb_stripos($view, '@') !== 0 && mb_stripos($view, '/') !== 0) {
            $view = '@app/plugins/' . $this->module->id . '/views/web/' . mb_strtolower($this->id) . '/' . $view;
        }
        return parent::render($view, $params);
    }
}
