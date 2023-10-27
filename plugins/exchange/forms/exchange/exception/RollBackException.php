<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\exchange\exception;


class RollBackException extends \Exception
{
    public $token;

    public function __construct($message, $token)
    {
        $this->token = $token;
        parent::__construct($message);
    }

    public function getToken()
    {
        return $this->token;
    }
}