<?php
/**
 * 获取微信第三方访问量
 */
namespace app\forms\open3rd;

use app\helpers\Json;
use app\models\Mall;
use app\models\UserVisit;
use Curl\Curl;

/**
 * Class ExtAppForm
 * @package app\forms\open3rd
 */
class Visits
{
    private $dailyUrl = "https://api.weixin.qq.com/datacube/getweanalysisappiddailyretaininfo"; // 获取用户访问小程序日留存
    private $monthlyUrl = "https://api.weixin.qq.com/datacube/getweanalysisappidmonthlyretaininfo";  // 获取用户访问小程序月留存
    private $tokenList;

    // 获取时间范围
    public function getDateRange($dateType, $startTime, $endTime, $sortType = "asc"){
        $dateList = [];
        $endTime = $endTime ?: mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        if($dateType == 1) {
            do {
                $value = [date("Ym01", $endTime), date("Ymt", $endTime)];
                $endTime = $endTime - (strtotime($value[1]) - strtotime($value[0])) - 86400;
                $dateList[] = $value;
            } while ($endTime >= $startTime);
        }else {
            $endTime = $endTime ?: mktime(23, 59, 59, date("m"), date("d"), date("y")); // 结束时间戳
            do {
                $dateList[] = [date("Ymd", $endTime), date("Ymd", $endTime)];
                $endTime = $endTime - 86400;
            } while ($endTime >= $startTime);
        }
        return $sortType == "asc" ? array_reverse($dateList) : $dateList;
    }

    // 获取访问量
    public function getVisits(){
        \Yii::warning("开始小程序活跃量记录：");

        try {
            $mallList = Mall::find()->where([
                'and', [
                    'is_delete' => 0, 'is_recycle' => 0, 'is_disable' => 0,
                ], [
                    'or', ['>', "expired_at", mysql_timestamp()], ['=', "expired_at", '0000-00-00 00:00:00']
                ]
            ])->all();
            $dateList = $this->getDateRange(1, strtotime("2023-01"), time());
            array_pop($dateList);

            foreach ($dateList as $date) {
                /** @var Mall $mall */
                foreach ($mallList as $mall) {
                    \Yii::$app->setMall($mall);
                    if (!isset($this->tokenList[$mall->id])) {
                        try {
                            $accToken = \Yii::$app->plugin->getPlugin("wxapp")->getWechat(true)->getAccessToken();
                        } catch (\Exception $e) {
                            $accToken = '';
                        }
                        $this->tokenList[$mall->id] = $accToken;
                        if (!$accToken) {
                            continue;
                        }
                    }
                    if (empty($this->tokenList[$mall->id])) {
                        continue;
                    }
                    if (date('Ymd', strtotime($mall->created_at)) > $date[1]) {
                        continue;
                    }

                    $exist = UserVisit::find()->where([
                        'mall_id' => $mall->id, 'date' => substr($date[0], 0, 6)
                    ])->exists();
                    if ($exist) {
                        continue;
                    }
                    if (!$ret = $this->request($date)) {
                        continue;
                    }
                    $userVisit = new UserVisit();
                    $userVisit->mall_id = $mall->id;
                    $userVisit->visit_uv_new = $ret['visit_uv_new'][0]['value'] ?? 0;
                    $userVisit->visit_uv = $ret['visit_uv'][0]['value'] ?? 0;
                    $userVisit->time = intval($ret['ref_date']);
                    $userVisit->date = $userVisit->time;
                    $userVisit->save();
                }
            }

            $dateList = $this->getDateRange(2, strtotime(date("Y-m-01")), time());
            array_pop($dateList);
            foreach ($mallList as $mall) {
                if (empty($this->tokenList[$mall->id])) {
                    continue;
                }
                \Yii::$app->setMall($mall);

                /** @var UserVisit $visit */
                $visit = UserVisit::find()->where([
                    'mall_id' => $mall->id, 'date' => substr($dateList[0][0], 0, 6)
                ])->one();
                $time = null;
                if (!$visit) {
                    $visit = new UserVisit();
                    $visit->mall_id = $mall->id;

                    $visit_uv_new = $visit_uv = 0;
                    foreach ($dateList as $date) {
                        if (!$ret = $this->request($date, 2)) {
                            continue;
                        }
                        $visit_uv_new = max($visit_uv_new, $ret['visit_uv_new'][0]['value']);
                        $visit_uv = max($visit_uv, $ret['visit_uv'][0]['value']);
                        if ($visit_uv == $ret['visit_uv'][0]['value']) {
                            $time = $ret['ref_date'];
                        }
                    }
                } else {
                    $date = $dateList[count($dateList) - 1];
                    if (!$ret = $this->request($date, 2)) {
                        continue;
                    }
                    $visit_uv_new = max($visit->visit_uv_new, ($ret['visit_uv_new'][0]['value'] ?? 0));
                    $visit_uv = max($visit->visit_uv, ($ret['visit_uv'][0]['value'] ?? 0));
                    if ($visit_uv == $ret['visit_uv'][0]['value']) {
                        $time = $ret['ref_date'];
                    }
                }
                $visit->time = $time ?: ($ret['ref_date'] ?? null);
                $visit->visit_uv_new = $visit_uv_new;
                $visit->visit_uv = $visit_uv;
                $visit->date = substr($dateList[0][0], 0, 6);
                $visit->save();
            }
        }catch (\Exception $e){
            \Yii::error($e);
        }
    }

    public function request($date, $type = 1){
        $param = ['begin_date' => $date[0], 'end_date' => $date[1]];
        $accToken = $this->tokenList[\Yii::$app->mall->id];
        $url = $type == 1 ? $this->monthlyUrl : $this->dailyUrl;
        $ret = Json::decode($this->getCurl()->post("{$url}?access_token={$accToken}", Json::encode($param, JSON_FORCE_OBJECT))->response);
        if(!empty($ret['errcode'])){
            return false;
        }
        if($type == 2 && !isset($ret['visit_uv_new'][0])){
            return false;
        }
        return $ret;
    }

    /**
     * @return Curl
     */
    public function getCurl()
    {
        $curl = new Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);
        return $curl;
    }
}