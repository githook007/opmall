<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/2
 * Time: 1:48 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\jobs;

use app\models\Mall;
use yii\queue\JobInterface;

class WechatTransferJob extends BaseJob implements JobInterface
{
    /**
     * @var Mall $mall
     */
    public $mall;

    public $post;

    public function execute($queue)
    {
        $this->setRequest();
        \Yii::$app->setMall($this->mall);

        $operate = $this->post['model'] ?? '';

        $pre = '';
        if ($operate == 'share') {
            $class = 'app\\models\\ShareCash';
        } elseif ($operate == 'mch') {
            $class = "app\\plugins\\mch\\models\MchCash";
            $pre = 'MC';
        } elseif ($operate == 'bonus') {
            $class = "app\\plugins\\bonus\\models\BonusCash";
        } elseif ($operate == 'region') {
            $class = "app\\plugins\\region\\models\RegionCash";
        } elseif ($operate == 'stock') {
            $class = "app\\plugins\\stock\\models\StockCash";
        } else {
            $class = "";
        }
        if (!class_exists($class)) {
            return;
        }

        $cash = $class::findOne([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
            'id' => $this->post['id'],
        ]);
        if (!$cash) {
            return;
        }
        $cash->order_no = $pre . date('YmdHis') . rand(1000, 9999);
        $cash->save();
    }
}
