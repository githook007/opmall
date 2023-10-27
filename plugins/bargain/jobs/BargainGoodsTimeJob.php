<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/13
 * Time: 18:22
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\jobs;


use app\jobs\BaseJob;
use app\models\Mall;
use app\plugins\bargain\forms\common\goods\CommonBargainGoods;
use app\plugins\bargain\models\BargainGoods;
use yii\queue\JobInterface;

/**
 * @property BargainGoods $bargainGoods
 */
class BargainGoodsTimeJob extends BaseJob implements JobInterface
{
    public $bargainGoods;

    public function execute($queue)
    {
        $this->setRequest();
        \Yii::$app->setMall(Mall::findOne($this->bargainGoods->mall_id));
        $this->bargainGoods = CommonBargainGoods::getCommonGoods()->getGoods($this->bargainGoods->goods_id);
        if ($this->bargainGoods->end_time > date('Y-m-d H:i:s')) {
            return false;
        }
        $this->bargainGoods->goods->status = 0;
        $this->bargainGoods->goods->save();
    }
}
