<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/9
 * Time: 5:18 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms;

use app\plugins\minishop\forms\common\CheckException;
use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\models\MinishopBrand;
use app\plugins\minishop\models\MinishopGoods;
use yii\helpers\Json;

class IndexForm extends Model
{
    public $page;
    public $sort_prop;
    public $sort_type;
    public $keyword;

    public function rules()
    {
        return [
            [['page', 'sort_type'], 'integer'],
            ['page', 'default', 'value' => 1],
            [['sort_prop', 'keyword'], 'trim'],
            [['sort_prop', 'keyword'], 'string'],
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            // 校验是否可用交易组件
            CommonRegister::getInstance()->check();

            $sort = ['id' => SORT_DESC];
            if ($this->sort_prop) {
                switch ($this->sort_prop) {
                    case 'id':
                        $sort['id'] = $this->sort_type == 0 ? SORT_DESC : SORT_ASC;
                        break;
                    case 'price':
                        $sort['price'] = $this->sort_type == 0 ? SORT_DESC : SORT_ASC;
                        break;
                    case 'stock':
                        $sort['stock'] = $this->sort_type == 0 ? SORT_DESC : SORT_ASC;
                        break;
                    default:
                }
            }

            /* @var MinishopGoods[] $list */
            $list = MinishopGoods::find()->where([
                'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0,
            ])->keyword($this->keyword, [
                'or',
                ['like', 'title', $this->keyword],
                ['goods_id' => $this->keyword]
            ])->orderBy($sort)->page($pagination)->all();
            $newList = [];
            foreach ($list as $item) {
                $goodsInfo = Json::decode($item->goods_info, true);
                $newList[] = [
                    'id' => $item->id,
                    'goods_id' => $item->goods_id,
                    'title' => $item->title,
                    'cat' => $item->third_cat,
                    'brand' => $item->brand,
                    'brand_id' => intval($item->brand_id),
                    'cover_pic' => $goodsInfo['head_img'][0],
                    'price' => $item->price,
                    'stock' => $item->stock,
                    'created_at' => $item->created_at,
                    'apply_status' => $item->apply_status,
                    'status' => $item->status,
                    'third_cat_id' => $goodsInfo['third_cat_id'],
                    'qualification_pics' => Json::decode($item->qualification_pics, true),
                    'audit_info' => Json::decode($item->audit_info, true)
                ];
            }
            /* @var MinishopBrand[] $brandList */
            $brandList = MinishopBrand::find()->where([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
                'status' => 1
            ])->all();
            $newBrandList = [
                [
                    'brand_id' => 2100000000,
                    'brand_wording' => '无品牌'
                ]
            ];
            foreach ($brandList as $item) {
                $newBrandList[] = [
                    'brand_id' => $item->brand_id,
                    'brand_wording' => $item->brand_wording
                ];
            }
            return $this->success([
                'can_use' => true,
                'list' => $newList,
                'pagination' => $pagination,
                'brand_list' => $newBrandList
            ]);
        } catch (CheckException $exception) {
            return $this->success([
                'can_use' => false,
                'content' => $exception->getMessage()
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
                if ($value['first_cat_name'] == '') {
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
            return $this->fail([
                'msg' => $exception->getMessage()
            ]);
        }
    }
}
