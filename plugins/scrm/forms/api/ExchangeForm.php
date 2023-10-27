<?php
/**
 * Created By PhpStorm
 * Date: 2021/6/28
 * Time: 11:23 上午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\scrm\forms\api;

use app\bootstrap\exceptions\ClassNotFoundException;
use app\plugins\exchange\forms\common\Code;
use app\plugins\exchange\forms\common\CommonModel;
use app\plugins\exchange\forms\common\CreateCode;
use app\plugins\exchange\models\ExchangeCode;
use app\plugins\exchange\models\ExchangeLibrary;
use app\plugins\scrm\forms\Model;

class ExchangeForm extends Model
{
    public $page;

    private $is_recycle = 0;
    public $name;
    public $created_at;
    public $rewards_s;
    public $library_id;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name'], 'string'],
            [['page', 'library_id'], 'integer'],
            [['rewards_s', 'created_at'], 'trim'],
        ]);
    }


    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            \Yii::$app->plugin->getPlugin('exchange');
            $where = [
                'AND',
                ['is_delete' => 0],
                ['mall_id' => \Yii::$app->mall->id],
                ['is_recycle' => $this->is_recycle],
            ];
            empty($this->name) || array_push($where, ['like', 'name', $this->name]);
            empty($this->created_at) || array_push($where, ['>=', 'created_at', current($this->created_at)], ['<=', 'created_at', next($this->created_at)]);
            $query = ExchangeLibrary::find()->where($where);
            if (!empty($this->rewards_s) && is_array($this->rewards_s)) {
                $andWhere = ['OR'];
                for ($i = 0; $i < count($this->rewards_s); $i++) {
                    array_push($andWhere, ['like', 'rewards_s', $this->rewards_s[$i]]);
                }
                $query->andWhere($andWhere);
            }
            $list = $query
                ->orderBy(['id' => SORT_DESC])
                ->page($pagination)
                ->asArray()
                ->all();

            $list = array_map(function ($item) {
                $rewards_text_arr = array_map(function ($r) {
                    return ExchangeLibrary::defaultType()[$r]['name'] ?? $r;
                }, explode(',', $item['rewards_s']));

                $item['rewards_text'] = implode(',', $rewards_text_arr);
                return $item;
            }, $list);

            return $this->success([
                'list' => $list,
                'pagination' => $pagination,
                'msg' => '获取成功'
            ]);
        } catch (ClassNotFoundException $exception) {
            return $this->fail([
                'msg' => '兑换中心插件不可用'
            ]);
        } catch (\Exception $e) {
            return $this->failByException($e);
        }
    }

    public function add()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            \Yii::$app->plugin->getPlugin('exchange');
            $libraryModel = CommonModel::getLibrary($this->library_id);
            if (!$libraryModel) {
                throw new \Exception('兑换库不合法');
            }
            $create = new class ($libraryModel, \Yii::$app->mall->id) extends CreateCode {
                public function copy_getTime()
                {
                    $library = $this->libraryModel;
                    switch ($library->expire_type) {
                        case 'fixed':
                            $valid_start_time = $library->expire_start_time;
                            $valid_end_time = $library->expire_end_time;
                            break;
                        case 'relatively':
                            $real_start_day = new \DateInterval(sprintf('P%dD', intval($library->expire_start_day) - 1));
                            $real_end_day = new \DateInterval(sprintf('P%dD', intval($library->expire_end_day) - 1));
                            $valid_start_time = (new \DateTime())->add($real_start_day)->format('Y-m-d H:i:s');
                            $valid_end_time = (new \DateTime())->add($real_end_day)->format('Y-m-d H:i:s');
                            break;
                        default:
                            $valid_start_time = "0000-00-00 00:00:00";
                            $valid_end_time = "0000-00-00 00:00:00";
                            break;
                    }
                    return [$valid_start_time, $valid_end_time];
                }

                private function copy_testDatabase($num, &$code)
                {
                    while (count($code) < $num) {
                        while (count($code) < $num) {
                            array_push($code, (new Code($this->libraryModel->code_format))->generate());
                        }
                        $ids = ExchangeCode::find()->select('code')->where([
                            'AND',
                            //['library_id' => $this->libraryModel->id],
                            ['mall_id' => \Yii::$app->mall->id],
                            ['in', 'code', $code],
                        ])->column();
                        $code = array_diff(array_unique($code), $ids);
                    }
                }

                public function createScrm()
                {
                    $libraryModel = $this->libraryModel;
                    list($valid_start_time, $valid_end_time) = $this->copy_getTime($libraryModel);

                    $code = [];
                    $this->copy_testDatabase(1, $code);
                    //兑换码
                    $codeModel = new ExchangeCode();
                    $codeModel->mall_id = $this->mall_id;
                    $codeModel->library_id = $libraryModel->id;
                    $codeModel->type = 2;/////////////////CONST 问题重写  新加类型
                    $codeModel->code = current($code);
                    $codeModel->status = 1;
                    $codeModel->validity_type = $libraryModel->expire_type;
                    $codeModel->valid_start_time = $valid_start_time;
                    $codeModel->valid_end_time = $valid_end_time;
                    $codeModel->r_user_id = 0;
                    $codeModel->r_raffled_at = '0000-00-00 00:00:00';
                    $codeModel->r_origin = \Yii::$app->appPlatform;
                    $codeModel->name = '';
                    $codeModel->mobile = '';
                    $codeModel->save();
                    return $codeModel;
                }
            };

            $code = $create->createScrm();
            return $this->success([
                'code' => $code,
                'msg' => '生成兑换码成功'
            ]);
        } catch (ClassNotFoundException $exception) {
            return $this->fail([
                'msg' => '兑换中心插件不可用'
            ]);
        } catch (\Exception $e) {
            return $this->failByException($e);
        }
    }
}
