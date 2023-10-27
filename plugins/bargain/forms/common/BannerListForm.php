<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/9
 * Time: 16:47
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\forms\common;


use app\bootstrap\response\ApiCode;
use app\models\Mall;
use app\models\Model;
use app\plugins\bargain\models\BargainBanner;
use yii\helpers\Json;

/**
 * @property Mall $mall
 */
class BannerListForm extends Model
{
    public $mall;

    public function search()
    {
        if (!$this->mall) {
            $this->mall = \Yii::$app->mall;
        }
        $query = BargainBanner::find()->where([
            'mall_id' => $this->mall->id,
            'is_delete' => 0,
        ]);

        $list = $query->with('banner')
            ->orderBy('id ASC')
            ->asArray()
            ->all();

        $list = array_map(function ($item) {
            $item['banner']['params'] = Json::decode($item['banner']['params'], true);
            return $item['banner'];
        }, $list);
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
            ]
        ];
    }
}
