<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/3/23
 * Time: 10:42
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\jobs;


use app\forms\common\ecard\CheckGoods;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Class EcardLogJob
 * @package app\jobs
 * @property CheckGoods $checkGoods
 */
class EcardLogJob extends BaseJob implements JobInterface
{
    public $checkGoods;

    public function execute($queue)
    {
        $this->setRequest();
        $this->checkGoods->save();
    }
}
