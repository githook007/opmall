<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\wlhulian\api;

use app\forms\wlhulian\Base;

class WalletFlow extends Base
{
    /** @var int 第几页：默认1 */
    public $pageNum;

    /** @var int 每页条数：默认10 */
    public $pageSize;

    /** @var string 开始时间：格式yyyy-MM-dd HH:mm:  */
    public $startDate;

    /** @var string 结束时间：格式yyyy-MM-dd HH:mm:ss  */
    public $endDate;

    public function getAttribute(): array
    {
        $params = get_object_vars($this);
        foreach ($params as $k => $value){
            if(($value === '' || $value === null)){
                unset($params[$k]);
            }
        }
        return $params;
    }

    public function getMethodName()
    {
        return "/api/v1/wallet/flow";
    }
}
