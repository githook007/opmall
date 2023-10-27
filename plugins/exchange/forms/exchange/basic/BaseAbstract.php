<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\exchange\basic;


use app\models\User;

class BaseAbstract
{
    protected $config;
    protected $user;
    protected $codeModel;
    protected $extra_info;

    public function __construct(array $config, User $user, $codeModel, $extra_info)
    {
        $this->config = $config;
        $this->user = $user;
        $this->codeModel = $codeModel;
        $this->extra_info = $extra_info;
    }
}