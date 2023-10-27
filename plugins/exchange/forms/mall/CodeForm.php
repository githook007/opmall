<?php

/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2020 hook007
 * author: opmall
 */

namespace app\plugins\exchange\forms\mall;

use app\bootstrap\response\ApiCode;
use app\forms\common\platform\PlatformConfig;
use app\forms\mall\export\CommonExport;
use app\helpers\ArrayHelper;
use app\models\Model;
use app\plugins\exchange\forms\common\CommonModel;
use app\plugins\exchange\models\ExchangeCode;
use app\plugins\exchange\models\ExchangeLibrary;

class CodeForm extends Model
{
    public $library_id;
    public $status; //-1 过期 0禁用 1 启用 2兑换
    public $code;
    public $created_at;
    public $type; //0 后台 礼品
    public $flag;
    public $page;

    public function rules()
    {
        return [
            [['library_id'], 'required'],
            [['library_id', 'type', 'status', 'page'], 'integer'],
            [['code', 'flag'], 'string', 'max' => 100],
            [['created_at'], 'trim'],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            /** @var ExchangeLibrary $library */
            $library = CommonModel::getLibrary($this->library_id);
            if (!$library) {
                throw new \Exception('兑换库错误');
            }

            $where = [
                'AND',
                ['library_id' => $this->library_id],
                ['mall_id' => \Yii::$app->mall->id],
            ];
            switch ($this->status) {
                case '-1':
                    in_array($library->expire_type, ['fixed', 'relatively'], true)
                        ? array_push($where, ['<', 'valid_end_time', date('Y-m-d H:i:s')])
                        : array_push($where, ['is', 'id', null]);
                    break;
                case '1':
                    array_push($where, ['status' => $this->status]);
                    if ($library->expire_type !== 'all') {
                        array_push($where, ['>', 'valid_end_time', date('Y-m-d H:i:s')]);
                    };
                    break;
                case '0':
                    array_push($where, ['status' => $this->status]);
                    break;
                case '2':
                    array_push($where, ['in', 'status', [2, 3]]);
                    break;
                default:
                    break;
            }

            if (!is_null($this->type) && $this->type !== '' && $this->type != -1) {
                array_push($where, ['type' => $this->type]);
            }
            empty($this->code) || array_push($where, ['like', 'code', $this->code]);
            empty($this->created_at) || array_push(
                $where,
                ['>=', 'created_at', current($this->created_at)],
                ['<=', 'created_at', next($this->created_at)]
            );

            $query = ExchangeCode::find()->where($where)->orderBy(['id' => SORT_DESC]);

            if ($this->flag == "EXPORT") {
                $queueId = CommonExport::handle([
                    'export_class' => 'app\\plugins\\exchange\\forms\\mall\\export\\ExchangeExport',
                    'params' => [
                        'library_id' => $this->library_id,
                        'query' => $query,
                    ]
                ]);

                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'msg' => '请求成功',
                    'data' => [
                        'queue_id' => $queueId
                    ]
                ];
            }

            $list = $query
                ->with(["user.userInfo", "user.userPlatform"])
                ->page($pagination)
                ->all();

            $qrcode = new QrcodeForm();
            $qrcode->setTempList();
            /** @var ExchangeCode[] $list */
            $list = array_map(function ($item) use ($library, $qrcode) {
                $newItem = ArrayHelper::toArray($item);
                $qrcode->code = $item->code;

                $newItem['qrcode_url'] = $qrcode->generate();
                $newItem['validity_type'] = $library->expire_type;
                $newItem['status'] = (new CommonModel())->getStatus($library, $newItem);
                if (in_array($item->status, [2, 3])) {
                    $newItem['r_rewards'] = (new CommonModel())->getFormatRewards($item->r_rewards);
                    $newItem['nickname'] = $item->user->nickname;
                    $newItem['avatar'] = $item->user->userInfo->avatar;
                    $newItem['platform'] = PlatformConfig::getInstance()->getPlatform($item->user);
                    $newItem['platform_text'] = PlatformConfig::getInstance()->getPlatformText($item->user);
                    $newItem['platform_icon'] = PlatformConfig::getInstance()->getPlatformIcon($item->user);
                } else {
                    $newItem['platform'] = '';
                    $newItem['platform_text'] = '';
                    $newItem['platform_icon'] = '';
                    $newItem['r_rewards'] = [];
                    $newItem['nickname'] = '';
                    $newItem['avatar'] = '';
                }
                return $newItem;
            }, $list);
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '获取成功',
                'data' => [
                    'list' => $list,
                    'pagination' => $pagination,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
