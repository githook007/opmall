<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/11/15
 * Time: 17:23
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\diy\forms\mall\market;


use app\bootstrap\response\ApiCode;
use app\forms\common\diy\CommonFormat;
use app\models\Model;
use app\plugins\diy\models\CoreTemplate;
use app\plugins\diy\models\DiyPage;
use app\plugins\diy\models\DiyPageNav;
use app\plugins\diy\models\DiyTemplate;

class LocalForm extends Model
{
    public $keyword;
    public $page;
    public $template_id;
    public $type;

    public function rules()
    {
        return [
            [['keyword', 'type'], 'trim'],
            [['keyword', 'type'], 'string'],
            [['page', 'template_id'], 'integer'],
            [['page'], 'default', 'value' => 1],
            ['type', 'in', 'range' => [DiyTemplate::TYPE_MODULE, DiyTemplate::TYPE_PAGE]]
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $list = CoreTemplate::find()->where([
            'is_delete' => 0,
        ])->keyword($this->keyword !== '', [
            'or',
            ['like', 'name', $this->keyword],
            ['like', 'author', $this->keyword],
        ])->page($pagination)->select('id,name,author,price,pics,type')->all();
        $permission = \Yii::$app->branch->childPermission(\Yii::$app->mall->user->adminInfo);
        $hasDiy = false;
        if (in_array('diy', $permission)) {
            $hasDiy = true;
        }
        $common = CommonTemplateCenter::getInstance();
        $templatePermission = $common->getTemplatePermission();
        array_walk($list, function (&$item) use ($hasDiy, $templatePermission) {
            $item = $item->toArray();
            $pics = json_decode($item['pics'], true);
            $item['img'] = $pics[0];
            $item['is_use'] = $item['type'] == 'diy' ? $hasDiy : true;
            if (!$templatePermission['is_all'] && !in_array($item['id'], $templatePermission['list'])) {
                $item['is_use'] = false;
            }
        });
        unset($item);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
            ]
        ];
    }

    /**
     * 兼容DIY新加功能
     * @param $data
     * @return mixed
     */
    private function formatData($data)
    {
        foreach ($data as $key => $diy) {
            $default = [];
            $diy['id'] === 'nav' && $default = [
                'swiperType' => 'circle',
                'swiperColor' => '#409EFF',
                'swiperNoColor' => '#a9a9a9',
                'lineNum' => 2,
                'aloneNum' => 3,
                'navType' => 'fixed',
                'modeType' => 'img',
                'columns' => 4,
            ];
            $diy['id'] === 'coupon' && $default = [
                'addType' => '',
                'has_hide' => false,
                'coupons' => [],
                'couponBg' => '#D9BC8B',
                'couponBgType' => 'pure',
                'has_limit' => '',
                'limit_num' => '',
            ];
            $data[$key]['data'] = array_merge($data[$key]['data'], $default);
        }
        return $data;
    }

    public function loadTemplate()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            $coreTemplate = CoreTemplate::findOne(['template_id' => $this->template_id, 'is_delete' => 0]);
            if (!$coreTemplate) {
                throw new \Exception('未知模板');
            }

            switch ($coreTemplate->type) {
                case 'diy':
                    $coreData = \yii\helpers\BaseJson::decode($coreTemplate->data);
                    $data = (new CommonFormat())->handleAll($coreData);
                    foreach ($data as &$item) {
                        if ($item['id'] == 'module' && isset($item['data']['list']) && is_array($item['data']['list'])) {
                            foreach ($item['data']['list'] as &$datum) {
                                if (!$datum['id']) {
                                    continue;
                                }
                                $template = new DiyTemplate();
                                $template->mall_id = \Yii::$app->mall->id;
                                $template->name = $datum['name'];
                                $template->type = DiyTemplate::TYPE_MODULE;
                                $issue = new Issue();
                                $template->data = json_encode($issue->unsetList($datum['data']), JSON_UNESCAPED_UNICODE);
                                $template->is_delete = 0;
                                if (!$template->save()) {
                                    throw new \Exception((new Model())->getErrorMsg($template));
                                }
                                $datum['id'] = $template->id;
                            }
                            unset($datum);
                        }
                    }
                    unset($item);

                    $template = new DiyTemplate();
                    $template->mall_id = \Yii::$app->mall->id;
                    $template->name = $coreTemplate->name;
                    $template->type = $this->type;
                    $issue = new Issue();
                    $template->data = json_encode($issue->unsetList($data), JSON_UNESCAPED_UNICODE);
                    $template->is_delete = 0;
                    if (!$template->save()) {
                        throw new \Exception((new Model())->getErrorMsg($template));
                    }
                    $id = $template->id;
                    if ($this->type == DiyTemplate::TYPE_PAGE) {
                        $diyPage = new DiyPage();
                        $diyPage->mall_id = \Yii::$app->mall->id;
                        $diyPage->show_navs = 0;
                        $diyPage->is_disable = 0;
                        $diyPage->title = $coreTemplate->name;
                        $diyPage->is_home_page = 0;
                        if (!$diyPage->save()) throw new \Exception('diyPage错误');

                        $diyPageNav = new DiyPageNav();
                        $diyPageNav->mall_id = \Yii::$app->mall->id;
                        $diyPageNav->name = $coreTemplate->name;
                        $diyPageNav->template_id = $template->id;
                        $diyPageNav->page_id = $diyPage->id;
                        if (!$diyPageNav->save()) throw new \Exception('diyPageNav错误');
                        $id = $diyPage->id;
                    }

                    $t->commit();
                    $data = [
                        'id' => $id,
                    ];
                    break;
//                case 'home':
//                    $data = $this->saveHome($coreTemplate);
//                    break;
                default:
                    throw new \Exception('错误的模板信息，请刷新重试');
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => $data
            ];
        } catch (\Exception $exception) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
            ];
        }
    }

    /**
     * @param CoreTemplate $coreTemplate
     * @return mixed
     * @throws \Exception
     * 保存到diy
     */
    private function saveByDiy($coreTemplate)
    {
        try {
            $plugin = \Yii::$app->plugin->getPlugin('diy');
            if (!method_exists($plugin, 'loadTemplate')) {
                throw new \Exception('请更新diy插件');
            }
            return $plugin->loadTemplate($coreTemplate);
        } catch (\Exception $exception) {
            throw new \Exception('未安装diy插件，无法使用diy模板，请联系管理员');
        }
    }
}
