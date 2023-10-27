<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\exchange\basic;

class Balance extends BaseAbstract implements Base
{
    public function exchange(&$message, &$reward)
    {
        try {
            $balance = floatval($this->config['balance']);
            $desc = sprintf('兑换码%s兑换%s余额', $this->codeModel->code, $balance);
            return \Yii::$app->currency->setUser($this->user)->balance->add($balance, $desc) === true;
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return false;
        }
    }
}
