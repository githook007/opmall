<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * author: opmall
 * Date: 2019/7/16
 * Time: 16:22
 */

namespace app\plugins\bonus\forms\common;

use app\models\Model;
use app\plugins\bonus\models\BonusCaptainLog;

class CommonCaptainLog extends Model
{
    public static function create($event, $user_id, array $content)
    {
        try {
            $mallId = \Yii::$app->mall->id;
        } catch (\Exception $e) {
            $mallId = 0;
        }

        try {
            $handler = !\Yii::$app->user->isGuest ? \Yii::$app->user->id : 0;
        } catch (\Exception $e) {
            $handler = 0;
        }

        try {
            $log = new BonusCaptainLog();
            $log->mall_id = $mallId;
            $log->handler = $handler;
            $log->user_id = $user_id;
            $log->event = $event;
            $log->content = \Yii::$app->serializer->encode($content);
            $log->create_at = mysql_timestamp();
            $res = $log->save();
            return $res;
        } catch (\Exception $e) {
            \Yii::error($e->getMessage());
            return false;
        }
    }
}