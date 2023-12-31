<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\mall\card;


use app\bootstrap\response\ApiCode;
use app\forms\common\card\CommonCard;
use app\forms\common\card\CommonUserCardList;
use app\models\GoodsCards;
use app\models\Model;
use app\models\User;
use app\models\UserCard;
use yii\helpers\ArrayHelper;

class CardForm extends Model
{
    public $id;
    public $page;
    public $keyword;
    public $user_card_id;
    public $user_id_list;
    public $is_send;
    public $card_num;

    public $is_expired;
    public $type;

    public function rules()
    {
        return [
            [['id', 'user_card_id', 'is_expired', 'type'], 'integer'],
            [['page'], 'default', 'value' => 1],
            [['keyword',], 'default', 'value' => ''],
            [['keyword',], 'string'],
            [['user_id_list',], 'safe'],
            [['card_num', 'is_send'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '卡券ID',
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $query = GoodsCards::find()
            ->where([
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0,
            ]);

        if ($this->is_expired) {
            $query->andWhere([
                'or',
                [
                    'and',
                    ['expire_type' => 2],
                    ['>', 'end_time', date('Y-m-d H:i:s')]
                ],
                ['expire_type' => 1]
            ]);
        }

        $list = $query
            ->keyword($this->keyword, ['like', 'name', $this->keyword])
            ->orderBy(['created_at' => SORT_DESC])
            ->page($pagination)
            ->all();

        $newList = [];
        /** @var GoodsCards $item */
        foreach ($list as $item) {
            $newItem = ArrayHelper::toArray($item);
            $newItem['begin_time'] = new_date($item->begin_time);
            $newItem['end_time'] = new_date($item->end_time);
            $newItem['created_at'] = new_date($item->created_at);
            $newList[] = $newItem;
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $newList,
                'pagination' => $pagination
            ]
        ];
    }

    public function getOptionList()
    {
        $list = GoodsCards::find()->where([
            'mall_id' => \Yii::$app->mall->id,
            'is_delete' => 0,
        ])->all();

        $newList = [];
        /** @var GoodsCards $item */
        foreach ($list as $item) {
            $newList[$item->id]['id'] = $item->id;
            $newList[$item->id]['name'] = $item->name;
            $newList[$item->id]['num'] = 1;
            $newList[$item->id]['count'] = $item->total_count == -1 ? '无限量' : $item->total_count;
        }

        $newList = array_values($newList);

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'list' => $newList
            ]
        ];
    }

    public function getDetail()
    {
        $detail = GoodsCards::find()->where(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id])->one();
        $detail = ArrayHelper::toArray($detail);
        $sign = '0000-00-00 00:00:00';
        if ($detail['begin_time'] == $sign || $detail['end_time'] == $sign) {
            $detail['time'] = [];
        } else {
            $detail['time'] = [$detail['begin_time'], $detail['end_time']];
        }

        if (!$detail) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '请求失败',
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '请求成功',
            'data' => [
                'detail' => $detail,
            ]
        ];
    }

    public function destroy()
    {
        try {
            $card = GoodsCards::findOne(['mall_id' => \Yii::$app->mall->id, 'id' => $this->id]);
            if (!$card) {
                return [
                    'code' => ApiCode::CODE_ERROR,
                    'msg' => '数据异常,该条数据不存在'
                ];
            }
            $card->is_delete = 1;
            $res = $card->save();
            if ($res) {
                if($this->type != '1'){ // 已领取也删除
                    UserCard::updateAll([
                        'is_delete' => 1,
                        'deleted_at' => mysql_timestamp()
                    ], [
                        'mall_id' => \Yii::$app->mall->id,
                        'is_delete' => 0,
                        'is_use' => 0,
                        'card_id' => $card->id
                    ]);
                }
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '删除成功',
                ];
            }
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '删除失败',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    public function getHistoryList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $form = new CommonUserCardList();
            $form->user_card_id = $this->user_card_id;
            $form->page = $this->page;
            $res = $form->getClerkHistory();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '请求成功',
                'data' => $res
            ];
        } catch (\Exception $exception) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage(),
                'error' => [
                    'line' => $exception->getLine()
                ]
            ];
        }
    }

    public function send()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $card = GoodsCards::findOne([
                 'mall_id' => \Yii::$app->mall->id,
                 'id' => $this->id,
                 'is_delete' => 0,
             ]);

            if (!$card) {
                throw new \Exception('卡券不存在');
            }

            if ($this->card_num == 0) {
                throw new \Exception('发送数量不能为空');
            }

            if ($card->total_count < $this->card_num && $card->total_count != -1) {
                throw new \Exception('卡券数量不足');
            }

            $userList = User::find()->where([
                'id' => $this->user_id_list,
                'mall_id' => \Yii::$app->mall->id
            ])->all();

            $commonCard = new CommonCard();
            $counts = 0;
            $num = 0;
            $msg = '操作完成,';
            foreach ($userList as $u) {
                try {
                    $count = 0;
                    while ($count < $this->card_num) {
                        $commonCard->user_id = $u->id;
                        $commonCard->receive($card, 0, 0);
                        $count++;
                        $num++;
                    }
                } catch (\Exception $e) {
                    $msg = "卡券数量不够,";
                    continue;
                }
                $counts++;
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => $msg . "共发放{$counts}人次，{$num}张。",
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage()
            ];
        }
    }
}
