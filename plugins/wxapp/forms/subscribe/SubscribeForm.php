<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/12/20
 * Time: 16:54
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\wxapp\forms\subscribe;


use app\bootstrap\response\ApiCode;
use app\forms\common\template\TemplateList;
use app\models\Model;
use app\plugins\wxapp\models\WxappSubscribe;
use app\plugins\wxapp\Plugin;

/**
 * Class SubscribeForm
 * @package app\plugins\wxapp\forms\subscribe
 * @property Plugin $plugin
 */
class SubscribeForm extends Model
{
    protected $plugin;
    public $mall;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->plugin = new Plugin();
    }

    /**
     * @param $templateList
     * @return array
     * @throws \Exception
     * 微信小程序后台添加订阅消息
     */
    public function addTemplate($templateList)
    {
        try {
            $wechatTemplate = $this->plugin->getSubscribe();
            $list = $wechatTemplate->getTemplateList();
            $newList = $list['data'];
            $templateIdList = [];
            foreach ($templateList as $index => $item) {
                $flag = true;
                foreach ($newList as $value) {
                    if (trim($item['title']) == trim($value['title'])) {
                        $templateIdList[] = [
                            'tpl_name' => $index,
                            'tpl_id' => $value['priTmplId']
                        ];
                        $flag = false;
                        break;
                    }
                }
                if ($flag) {
                    try {
                        $res = $wechatTemplate->addTemplate($item['id'], $item['keyword_id_list'], '添加订阅模板');
                        $templateIdList[] = [
                            'tpl_name' => $index,
                            'tpl_id' => $res['priTmplId']
                        ];
                    } catch (\Exception $exception) {
                        \Yii::error("一键添加模板失败：{$item['id']} - ".$exception->getMessage());
                        continue;
                    }
                }
            }
            return $templateIdList;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return array
     * 类目307--服装/鞋/箱包 774--广告/设计 订阅消息配置
     */
    public function getTemplateInfo()
    {
        $config = SubscribeConfig::config();
        return array_column($config, 'config', 'tpl_name');
    }

    public function getDefault()
    {
        $config = SubscribeConfig::config();
        $list = [];
        foreach ($config as $item) {
            if (!isset($list[$item['key']])) {
                $list[$item['key']] = [
                    'name' => $item['key_name'],
                    'key' => $item['key'],
                    'list' => [],
                ];
            }
            $list[$item['key']]['list'][] = [
                'name' => $item['local']['name'],
                'img_url' => $item['local']['img_url'],
                $item['tpl_name'] => '',
                'tpl_name' => $item['tpl_name']
            ];
        }
        return array_values($list);
    }

    /**
     * @param $list
     * @return array
     * 重新调整数据结构
     */
    public function getList($list)
    {
        $default = $this->getDefault();

        if (!$list) {
            return $default;
        }

        $newList = [];
        foreach ($list as $item) {
            $newList[$item['tpl_name']] = $item['tpl_id'];
        }

        foreach ($default as $k => $item) {
            foreach ($item['list'] as $k2 => $item2) {
                if (isset($newList[$item2['tpl_name']])) {
                    $default[$k]['list'][$k2][$item2['tpl_name']] = $newList[$item2['tpl_name']];
                }
            }
        }

        return $default;
    }

    /**
     * @return array
     * 后台获取订阅消息列表
     */
    public function getDetail()
    {
        $list = $this->getTemplateList();
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $this->getList($list)
            ]
        ];
    }

    /**
     * @return array
     * 一键获取订阅消息
     */
    public function saveAll()
    {
        try {
            $templateList = $this->getTemplateInfo();
            $templateIdList = $this->addTemplate($templateList);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '',
                'data' => [
                    'list' => $this->getList($templateIdList),
                ]
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }

    /**
     * @param $templateTpl
     * @return string
     * @throws \Exception
     * 获取单个订阅消息template_id
     */
    public function addTemplateOne($templateTpl)
    {
        $params = TemplateList::getInstance()->getTemplateClass($templateTpl);
        $templateList = $this->addTemplate([$params->config('wxapp')]);
        if (empty($templateList)) {
            throw new \Exception('获取模板出错');
        }
        return $templateList[0]['tpl_id'];
    }

    /**
     * @param string $param
     * @return array
     * 获取所有的订阅消息
     */
    public function getTemplateList($param = '*')
    {
        $list = WxappSubscribe::find()->where(['mall_id' => \Yii::$app->mall->id])->select($param)->all();
        return $list;
    }

    /**
     * @param $attributes
     * @return bool
     * @throws \Exception
     * 保存订阅消息到数据库
     */
    public function addTemplateList($attributes)
    {
        foreach ($attributes as $item) {
            if (!isset($item['tpl_name'])) {
                throw new \Exception('缺少必要的参数tpl_name');
            }
            if (!isset($item[$item['tpl_name']])) {
                throw new \Exception("缺少必要的参数{$item['tpl_name']}");
            }
            $tpl = WxappSubscribe::findOne(['mall_id' => \Yii::$app->mall->id, 'tpl_name' => $item['tpl_name']]);
            $tplId = $item[$item['tpl_name']];
            if (!$tpl) {
                $tpl = new WxappSubscribe();
                $tpl->mall_id = \Yii::$app->mall->id;
                $tpl->tpl_name = $item['tpl_name'];
            }
            if ($tpl->tpl_id != $tplId) {
                $tpl->tpl_id = $tplId;
                if (!$tpl->save()) {
                    throw new \Exception((new Model())->getErrorMsg($tpl));
                } else {
                    continue;
                }
            } else {
                continue;
            }
        }
        return true;
    }

    public $data;
    public function save()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (!$this->data || !is_array($this->data)) {
                throw new \Exception('数据异常');
            }
            $newData = [];
            foreach ($this->data as $item) {
                foreach ($item['list'] as $item2) {
                    if (!isset($item2[$item2['tpl_name']])) {
                        throw new \Exception('默认数据有误、请排查<' . $item2['name'] . '>字段信息');
                    }
                    $newData[] = [
                        'tpl_name' => $item2['tpl_name'],
                        $item2['tpl_name'] => $item2[$item2['tpl_name']]
                    ];
                }
            }
            $this->addTemplateList($newData);

            $transaction->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
