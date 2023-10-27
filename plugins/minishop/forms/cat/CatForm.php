<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 2:29 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\cat;

use app\plugins\minishop\forms\common\CheckException;
use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopCat;
use yii\helpers\Json;

class CatForm extends Model
{
    public $limit;
    public $page;

    public function rules()
    {
        return [
            [['limit', 'page'], 'integer'],
            ['limit', 'default', 'value' => 20],
            ['page', 'default', 'value' => 1]
        ];
    }

    public function get()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $common = CommonRegister::getInstance();
            $common->check();
            /* @var MinishopCat[] $list*/
            $list = MinishopCat::find()->where([
                'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0
            ])->page($pagination, $this->limit, $this->page)->all();
            $catList = $common->getCat();
            $thirdCatList = array_column($catList['third_cat_list'], null, 'third_cat_id');
            $newList = [];
            foreach ($list as $item) {
                if ($item->status == 0) {
                    $item = $common->listChangeCatStatue($item);
                }
                $newList[] = [
                    'id' => $item->id,
                    'third_cat_id' => $item->third_cat_id,
                    'third_cat_name' => $item->third_cat_name,
                    'status' => $item->status,
                    'reject_reason' => $item->reject_reason,
                    'license' => $item->license,
                    'certificate' => Json::decode($item->certificate, true),
                    'qualification' => $thirdCatList[$item->third_cat_id]['qualification']
                ];
            }
            return $this->success([
                'can_use' => true,
                'content' => '',
                'list' => $newList,
                'pagination' => $pagination
            ]);
        } catch (CheckException $exception) {
            return $this->success([
                'can_use' => false,
                'content' => $exception->getMessage(),
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }

    public function getCat()
    {
        try {
            $res = CommonRegister::getInstance()->getCat();
            $newList = [];
            foreach ($res['third_cat_list'] as $value) {
                // 需要资质的分类去掉不显示、一级分类名称为空的不显示
                if ($value['first_cat_name'] == '' || $value['qualification_type'] == 0) {
                    continue;
                }
                if (!isset($newList[$value['first_cat_id']])) {
                    $newList[$value['first_cat_id']] = [
                        'label' => $value['first_cat_name'],
                        'value' => $value['first_cat_id'],
                        'children' => []
                    ];
                }
                if (!isset($newList[$value['first_cat_id']]['children'][$value['second_cat_id']])) {
                    $newList[$value['first_cat_id']]['children'][$value['second_cat_id']] = [
                        'label' => $value['second_cat_name'],
                        'value' => $value['second_cat_id'],
                        'children' => []
                    ];
                }
                $newList[$value['first_cat_id']]['children'][$value['second_cat_id']]['children'][] = [
                    'label' => $value['third_cat_name'],
                    'value' => $value['third_cat_id'],
                    'qualification' => $value['qualification'],
                    'qualification_type' => $value['qualification_type'],
                    'product_qualification' => $value['product_qualification'],
                    'product_qualification_type' => $value['product_qualification_type'],
                ];
            }
            foreach ($newList as &$item) {
                $item['children'] = array_values($item['children']);
            }
            unset($item);
            $newList = array_values($newList);
            return $this->success([
                'list' => $newList
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
