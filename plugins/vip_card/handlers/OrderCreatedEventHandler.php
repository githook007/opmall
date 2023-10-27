<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\plugins\vip_card\handlers;

use app\forms\common\message\MessageService;
use app\forms\common\platform\PlatformConfig;
use app\forms\common\template\order_pay_template\AccountChangeInfo;
use app\forms\common\template\TemplateList;
use app\handlers\orderHandler\BaseOrderCreatedHandler;
use app\models\User;
use app\plugins\vip_card\forms\common\AddShareOrder;

class OrderCreatedEventHandler extends BaseOrderCreatedHandler
{
    public function handle()
    {
        $this->user = $this->event->order->user;

        $this->setShareUser()->setShareMoney();
    }

    protected function saveShareMoney()
    {
        try {
            (new AddShareOrder())->save($this->event->order);
        } catch (\Exception $exception) {
            \Yii::error('超级会员卡分销佣金记录失败：' . $exception->getMessage());
            \Yii::error($exception);
        }
    }

    public function sendTemplate($user, $desc)
    {
        try {
            TemplateList::getInstance()->getTemplateClass(AccountChangeInfo::TPL_NAME)->send([
                'remark' => '分销佣金',
                'desc' => $desc,
                'user' => $user,
                'page' => 'pages/user-center/user-center'
            ]);
        } catch (\Exception $exception) {
            \Yii::error('模板消息发送: ' . $exception->getMessage());
        }
    }

    /**
     * @param User $user
     * @param $money
     * @return $this
     * 向用户发送短信提醒
     */
    protected function sendSmsToUser($user, $nickname, $money)
    {
        try {
            \Yii::warning('----消息发送提醒----');
            if (!$user->mobile) {
                \Yii::warning('----用户未绑定手机号无法发送----');
                return $this;
            }
            $messageService = new MessageService();
            $messageService->user = $user;
            $messageService->content = [
                'mch_id' => 0,
                'args' => [$nickname, $money]
            ];
            $messageService->platform = PlatformConfig::getInstance()->getPlatform($user);
            $messageService->tplKey = 'brokerage';
            $res = $messageService->templateSend();
        } catch (\Exception $exception) {
            \Yii::error('向用户发送短信消息失败');
            \Yii::error($exception);
        }
        return $this;
    }
}
