<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2019/1/23 16:32
 */


namespace app\plugins\fxhb\handle;


use yii\base\BaseObject;

class HandlerRegister extends BaseObject
{
    const FXHB_JOIN_ACTIVITY = 'fxhb_join_activity';
    public function getHandlers()
    {
        return [
            JoinActivityHandle::class
        ];
    }
}
