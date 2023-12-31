<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/8
 * Time: 9:20
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;


use app\forms\common\prints\PrintOrder;
use app\models\Mall;
use app\models\Order;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class PrintJob
 * @package app\jobs
 * @property Order $order
 * @property Mall $mall
 */
class PrintJob extends BaseJob implements JobInterface
{
    public $order;
    public $mall;
    public $orderType;

    public function execute($queue)
    {
        try {
            $this->setRequest();
            \Yii::$app->setMall($this->mall);

            $printer = new PrintOrder();
            $printer->print($this->order, $this->order->id, $this->orderType);
        } catch (\Exception $exception) {
            \Yii::error('小票打印机打印:' . $exception->getMessage());
            \Yii::warning($exception);
        }
    }
}
