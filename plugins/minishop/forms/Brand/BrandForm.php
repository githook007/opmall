<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/25
 * Time: 8:19 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\Brand;

use app\plugins\minishop\forms\common\CheckException;
use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopBrand;
use yii\helpers\Json;

class BrandForm extends Model
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

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $common = CommonRegister::getInstance();
            $common->check();
            $pagination = null;

            /* @var MinishopBrand[] $list */
            $list = MinishopBrand::find()->where([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ])->page($pagination, $this->limit, $this->page)->all();

            $newList = [];
            foreach ($list as $item) {
                if ($item->status == 0) {
                    try {
                        $item = $common->listChangeBrandStatue($item);
                    } catch (\Exception $exception) {
                        \Yii::warning($exception);
                    }
                }
                $item->sale_authorization = Json::decode($item->sale_authorization, true);
                if (!$item->sale_authorization) {
                    $item->sale_authorization = [];
                }
                $newList[] = $item;
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
}
