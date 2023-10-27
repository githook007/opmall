<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/2/21
 * Time: 14:43
 * @copyright: ©2021 .hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\pc\goods;

use app\bootstrap\response\ApiCode;
use app\models\Goods;
use app\models\Mall;
use app\models\OrderComments;

/**
 * @property Mall $mall
 */
class CommentsForm extends \app\forms\api\CommentsForm
{
    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $setting = $this->mall->getMallSetting(['is_comment']);
        if ($setting['is_comment'] == 0) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => '',
                'data' => [
                    'comments' => [],
                    'comment_count' => [],
                ]
            ];
        }
        $goods = Goods::findOne($this->goods_id);

        $list = OrderComments::find()
            ->where([
                'goods_warehouse_id' => $goods->goods_warehouse_id, 'mall_id' => $this->mall->id, 'is_delete' => 0,
                'is_show' => 1
            ])
            ->keyword($this->status, ['score' => $this->status])
            ->select(['*', 'time' => 'case when `is_virtual` = 1 then `virtual_time` else `created_at` end'])
            ->with('user')->apiPage($this->limit, $this->page)
            ->orderBy(['is_top' => SORT_DESC, 'created_at' => SORT_DESC])
            ->all();

        $newList = [];
        /* @var OrderComments[] $list */
        foreach ($list as $item) {
            $newItem = [
                'content' => $item->content,
                'pic_url' => \Yii::$app->serializer->decode($item->pic_url),
                'reply_content' => $item->reply_content,
                'status_text' => $item->score == '3' ? "好评" : ($item->score == '2' ? "中评" : "差评")
            ];
            if ($item->is_virtual == 1) {
                $newItem['avatar'] = $item->virtual_avatar;
                $newItem['time'] = date('Y-m-d', strtotime($item->virtual_time));
                $newItem['nickname'] = $this->substrCut($item->virtual_user);
            } else {
                $newItem['avatar'] = $item->user->userInfo->avatar;
                $newItem['time'] = date('Y-m-d', strtotime($item->created_at));
                $newItem['nickname'] = $this->substrCut($item->user->nickname);
            }
            if ($item->is_anonymous == 1) {
                $newItem['avatar'] = \Yii::$app->request->hostInfo .
                    \Yii::$app->request->baseUrl . '/statics/img/common/default-avatar.png';
                $newItem['nickname'] = '匿名用户';
            }
            $newList[] = $newItem;
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '',
            'data' => [
                'comments' => $newList,
                'comment_count' => $this->countData($goods),
            ]
        ];
    }
}
