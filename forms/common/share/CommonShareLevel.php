<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/10/19
 * Time: 14:35
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\share;


use app\forms\common\CommonMallMember;
use app\models\Mall;
use app\models\Model;
use app\models\Order;
use app\models\Share;
use app\models\ShareCash;
use app\models\ShareLevel;
use app\models\ShareLevelGoods;
use app\models\ShareSetting;
use app\models\User;

/**
 * Class CommonShareLevel
 * @package app\forms\common\share
 * @property Mall $mall
 * @property User $user
 * @property Share $share
 */
class CommonShareLevel extends Model
{
    private static $instance;
    public $mall;
    public $user;
    public $userId;
    public $share;

    public const CHILDREN_COUNT = 1; // 下线用户数
    public const TOTAL_MONEY = 2; // 累计佣金
    public const TOTAL_CASH = 3; // 已提现佣金
    public const TOTAL_CONSUME = 4; // 累计消费

    public static function getInstance($mall = null)
    {
        if (!self::$instance) {
            self::$instance = new self();
            if (!$mall) {
                $mall = \Yii::$app->mall;
            }
            self::$instance->mall = $mall;
        }
        return self::$instance;
    }

    public function getOptions()
    {
        $list = ShareLevel::find()->select('level')->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0
        ])->column();

        $newList = [];
        for ($i = 1; $i <= 10; $i++) {
            $newList[] = [
                'name' => '等级' . $i,
                'level' => $i,
                'disabled' => in_array($i, $list),
            ];
        }

        return $newList;
    }

    /**
     * @param $id
     * @return ShareLevel|null
     */
    public function getDetail($id)
    {
        if (!$id) {
            return null;
        }
        /* @var ShareLevel $shareLevel */
        $shareLevel = ShareLevel::find()->where([
            'id' => $id,
            'mall_id' => $this->mall->id,
            'is_delete' => 0
        ])->one();
        if ($shareLevel && $shareLevel->condition_type == 1) {
            $shareLevel->condition = intval($shareLevel->condition);
        }
        return $shareLevel;
    }

    public function destroy($id)
    {
        $shareLevel = $this->getDetail($id);
        if (!$shareLevel) {
            throw new \Exception('所选择的分销商等级不存在或已删除，请刷新后重试');
        }
        $shareExists = Share::find()->where([
            'mall_id' => $this->mall->id, 'is_delete' => 0, 'status' => 1, 'level' => $shareLevel->level
        ])->exists();
        if ($shareExists) {
            throw new \Exception('该分销商等级下还有分销商存在，暂时不能删除');
        }
        $shareLevel->is_delete = 1;
        if (!$shareLevel->save()) {
            throw new \Exception($this->getErrorMsg($shareLevel));
        }
        return true;
    }

    public function switchStatus($id)
    {
        $shareLevel = $this->getDetail($id);
        if (!$shareLevel) {
            throw new \Exception('所选择的分销商等级不存在或已删除，请刷新后重试');
        }
        $shareExists = Share::find()->where([
            'mall_id' => $this->mall->id, 'is_delete' => 0, 'status' => 1, 'level' => $shareLevel->level
        ])->exists();
        if ($shareExists) {
            throw new \Exception('该分销商等级下还有分销商存在，暂时不能关闭');
        }
        $shareLevel->status = $shareLevel->status ? 0 : 1;
        if (!$shareLevel->save()) {
            throw new \Exception($this->getErrorMsg($shareLevel));
        }
        return true;
    }

    /**
     * @param integer $conditionType 升级方式1--下线用户数|2--累计佣金|3--已提现佣金
     * @return bool
     * @throws \Exception
     * 分销商升级等级
     */
    public function levelShare($conditionType)
    {
        $share = $this->getShare();
        if (!$share) {
            throw new \Exception('分销商不存在');
        }
        $condition = $this->getCondition($conditionType, $share);
        \Yii::error($condition);
        /* @var ShareLevel $shareLevel */
        $shareLevel = ShareLevel::find()->where([
            'mall_id' => $this->mall->id, 'is_delete' => 0, 'status' => 1, 'condition_type' => $conditionType,
            'is_auto_level' => 1
        ])->andWhere(['<=', 'condition', $condition])
            ->andWhere(['>', 'level', $share->level])
            ->orderBy(['level' => SORT_DESC])
            ->one();
        if (!$shareLevel) {
            \Yii::error('没有更高分销等级可升级');
            return false;
        }
        return $this->changeLevel($shareLevel->level);
    }

    /**
     * @param Order $order
     * @param int $scene 自动成为分销商场景 1--付款后 2--过售后
     * @return bool
     * @throws \Exception
     * 分销商升级等级
     */
    public function levelShareByBuyGoods($order, $scene)
    {
        $consumeCondition = ShareSetting::get($this->mall->id, ShareSetting::CONSUME_CONDITION, 0);
        if ($consumeCondition != $scene) {
            return true;
        }

        $share = $this->getShare();
        if (!$share) {
            throw new \Exception('分销商不存在');
        }

        $become_condition = ShareSetting::get($this->mall->id, ShareSetting::BECOME_CONDITION, 3);
        $share_goods_status = ShareSetting::get(\Yii::$app->mall->id, ShareSetting::SHARE_GOODS_STATUS, 0);

        if ($become_condition == 2 && $share_goods_status == 2) {
            $goodsId = [];
            foreach ($order->detail as $detail) {
                $goodsInfo = $detail->decodeGoodsInfo();
                if (isset($goodsInfo['goods_attr'])
                    && $goodsInfo['goods_attr']['goods_warehouse_id']) {
                    $goodsId[] = $goodsInfo['goods_attr']['goods_warehouse_id'];
                }
            }
            if(!$goodsId){
                throw new \Exception('订单里没有商品，不能自动升级等级');
            }
            $levelId = ShareLevelGoods::find()->where([
                "mall_id" => \Yii::$app->mall->id,
                "is_delete" => 0,
                "goods_warehouse_id" => $goodsId
            ])->select("level_id");

            /** @var ShareLevel $shareLevel */
            $shareLevel = ShareLevel::find()->where([
                "mall_id" => \Yii::$app->mall->id,
                "is_delete" => 0,
                "id" => $levelId,
                "status" => 1,
                "condition_type" => 5
            ])
                ->orderBy(['level' => SORT_DESC])
                ->one();
            if(!$shareLevel){
                throw new \Exception('分销等级数据不存在，不用自动升级等级');
            }
            if($shareLevel->level <= $share->level){
                throw new \Exception('商品可升级的等级低于或等于当前用户等级，不升级');
            }
            $this->changeLevel($shareLevel->level);
        }
        return true;
    }

    /**
     * @param $level
     * @return bool
     * @throws \Exception
     */
    public function changeLevel($level)
    {
        $share = $this->getShare();
        if (!$share) {
            throw new \Exception('分销商不存在');
        }
        $share->level = $level;
        $share->level_at = mysql_timestamp();
        if (!$share->save()) {
            \Yii::error('升级分销商等级出错');
            \Yii::error($this->getErrorMsg($share));
            return false;
        }
        return true;
    }

    /**
     * @return Share|null
     * @throws \Exception
     * 获取分销商
     */
    private function getShare()
    {
//        if ($this->share) {
//            return $this->share;
//        }
        $share = null;
        if ($this->user) {
            $share = $this->user->share;
        } elseif ($this->userId) {
            $share = Share::find()->with('firstChildren')->where([
                'user_id' => $this->userId, 'is_delete' => 0, 'mall_id' => $this->mall->id, 'status' => 1
            ])->one();
        } elseif ($this->share) {
            $share = $this->share;
        }
        if (!$share) {
            throw new \Exception('不存在分销商');
        }
//        $this->share = $share;
        return $share;
    }

    /**
     * @param int $conditionType
     * @param Share $share
     * @return float
     * 获取升级条件
     */
    private function getCondition($conditionType, $share)
    {
        $condition = 0;
        switch ($conditionType) {
            case self::CHILDREN_COUNT:
                $condition = $share->all_children;
                break;
            case self::TOTAL_MONEY:
                $condition = $share->total_money;
                break;
            case self::TOTAL_CASH:
                $condition = ShareCash::find()->where([
                    'mall_id' => $this->mall->id, 'user_id' => $share->user_id, 'is_delete' => 0, 'status' => 2,
                ])->select('SUM(price)')->scalar();
                break;
            case self::TOTAL_CONSUME:
                $commonMallMember = new CommonMallMember();
                $mallId = $this->mall->id;
                $userId = $share->user_id;
                $condition = $commonMallMember->getOrderMoneyCount($mallId, $userId);
                break;
            default:
                break;
        }
        return $condition;
    }

    protected $shareLevelList;

    /**
     * @param $level
     * @return ShareLevel|null
     * 通过分销商等级来获取分销等级
     */
    public function getShareLevelByLevel($level)
    {
        if (!$level) {
            return null;
        }
        if (isset($this->shareLevelList[$level]) && $this->shareLevelList[$level]) {
            return $this->shareLevelList[$level];
        }
        /* @var ShareLevel $shareLevel */
        $shareLevel = ShareLevel::find()->where([
            'level' => $level,
            'mall_id' => $this->mall->id,
            'is_delete' => 0
        ])->one();
        if ($shareLevel && $shareLevel->condition_type == 1) {
            $shareLevel->condition = intval($shareLevel->condition);
        }
        $this->shareLevelList[$level] = $shareLevel;
        return $shareLevel;
    }

    public function levelUp()
    {
        $share = $this->getShare();
        if (!$share) {
            throw new \Exception('用户不是分销商，无法升级分销商等级');
        }
        /* @var ShareLevel $temp */
        /* @var ShareLevel $shareLevel */
        $shareLevel = null;
        $temp = null;
        $type = null;
        $unit = null;
        $config = $this->config();
        foreach ($config as $item) {
            $condition = $this->getCondition($item['key'], $share);
            $temp = ShareLevel::find()->where([
                'mall_id' => $this->mall->id, 'is_delete' => 0, 'status' => 1, 'condition_type' => $item['key'],
                'is_auto_level' => 1
            ])->andWhere(['<=', 'condition', $condition])
                ->andWhere(['>', 'level', $share->level])
                ->orderBy(['level' => SORT_DESC])
                ->one();
            if ($temp && (!$shareLevel || ($shareLevel->level < $temp->level))) {
                $shareLevel = $temp;
                $type = $item['name'];
                $unit = $item['unit'];
            }
        }
        if (!$shareLevel) {
            return [
                'status' => 0,
                'level_name' => '升级失败',
                'condition_text' => '未满足升级条件'
            ];
        } else {
            $this->changeLevel($shareLevel->level);
            return [
                'status' => 1,
                'level_name' => '升级到' . $shareLevel->name,
                'condition_text' => $type . '达到' . round($shareLevel->condition, 2) . $unit,
            ];
        }
    }

    public function getList()
    {
        $shareLevelList = ShareLevel::find()->where([
            'mall_id' => \Yii::$app->mall->id, 'is_delete' => 0, 'status' => 1,
        ])->select(['id', 'level', 'name'])->orderBy(['level' => SORT_ASC])->all();
        array_unshift($shareLevelList, [
            'id' => 0,
            'level' => 0,
            'name' => ShareSetting::get(\Yii::$app->mall->id, ShareSetting::DEFAULT_LEVEL_NAME, ShareSetting::INFO[ShareSetting::DEFAULT_LEVEL_NAME])
        ]);
        return $shareLevelList;
    }

    public function config()
    {
        return [
            [
                'key' => 1,
                'name' => '下线用户数',
                'unit' => '人'
            ],
            [
                'key' => 2,
                'name' => '累计佣金',
                'unit' => '元'
            ],
            [
                'key' => 3,
                'name' => '已提现佣金',
                'unit' => '元'
            ],
            [
                'key' => 4,
                'name' => '累计消费',
                'unit' => '元'
            ],
        ];
    }
}
