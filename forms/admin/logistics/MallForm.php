<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\admin\logistics;

use app\bootstrap\Pagination;
use app\bootstrap\response\ApiCode;
use app\forms\wlhulian\api\AccountRecharge;
use app\forms\wlhulian\api\QueryRechargeStatus;
use app\forms\wlhulian\api\WalletBalance;
use app\forms\wlhulian\ApiForm;
use app\models\Mall;
use app\models\Model;
use app\models\User;
use app\models\WlhulianData;
use app\models\WlhulianWalletLog;
use yii\db\Query;

class MallForm extends Model
{
    public $id;
    public $keyword;
    public $type;
    public $money;

    public function rules()
    {
        return [
            [['keyword'], 'string'],
            [['money'], 'number'],
            [['id', 'type'], 'integer'],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = WlhulianData::find()->where(['is_delete' => 0])->select("mall_id");

        $query = Mall::find()
            ->where([
                'is_recycle' => 0,
                'is_delete' => 0,
                'id' => $model
            ]);

        if($this->keyword) {
            $userIds = User::find()->where(['like', 'username', $this->keyword])->select('id');
            $query->andWhere([
                'OR',
                ['LIKE', 'name', $this->keyword,],
                ['user_id' => $userIds]
            ]);
        }

        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count,]);
        $list = $query->with(['user' => function ($query) {
                /** @var Query $query */
                $query->select('id,username,nickname,is_delete');
            }])
            ->orderBy('id DESC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()
            ->all();

        $config = (new WlForm())->getOption();
        $api = new ApiForm($config);
        $api->object = (new WalletBalance());
        $res = $api->request();
        $res['usableAmt'] = isset($res['usableAmt']) ? $res['usableAmt'] / 100 : 0;

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
                'pagination' => $pagination,
                'account' => $res
            ],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        $model = WlhulianData::find()->where(['is_delete' => 0])->select("mall_id");

        $query = Mall::find()
            ->where([
                'is_recycle' => 0,
                'is_delete' => 0,
            ])->andWhere(["not in", "id", $model]);

        if($this->keyword) {
            $userIds = User::find()->where(['like', 'username', $this->keyword])->select('id');
            $query->andWhere([
                'OR',
                ['LIKE', 'name', $this->keyword,],
                ['user_id' => $userIds],
                ['id' => $this->keyword],
            ]);
        }

        $list = $query->orderBy('id asc')
            ->select('id,name')
            ->apiPage(20)
            ->asArray()
            ->all();

        foreach ($list as &$item){
            $item['name'] = "（{$item['id']}）{$item['name']}";
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'data' => [
                'list' => $list,
            ],
        ];
    }

    public function add()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        /** @var Mall $mall */
        $mall = Mall::find()
            ->where([
                'is_recycle' => 0,
                'is_delete' => 0,
                'id' => $this->id,
            ])->one();

        $model = WlhulianData::find()->where(['mall_id' => $mall->id])->one();
        if(!$model){
            $model = new WlhulianData();
            $model->mall_id = $mall->id;
        }
        $model->is_delete = 0;
        if(!$model->save()){
            return $this->getErrorResponse($model);
        }

        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '操作成功',
        ];
    }

    /**
     * @return array
     */
    public function delete()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $mall = Mall::findOne($this->id);
            if($mall && $mall->wlHulian){
                $mall->wlHulian->is_delete = 1;
                if (!$mall->wlHulian->save()) {
                    throw new \Exception($this->getErrorMsg($mall->wlHulian));
                }
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     */
    public function money()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $mall = Mall::findOne($this->id);
            if(!$mall && !$mall->wlHulian){
                throw new \Exception('数据不存在');
            }
            if(!in_array($this->type, [1, 2])){
                throw new \Exception('金额类型错误');
            }
            $this->money = abs($this->money);
            $wlHulianModel = $mall->wlHulian;
            if($this->type == 1) {
                $wlHulianModel->balance += $this->money;
            }else{
                $wlHulianModel->balance -= $this->money;
            }
            if(!$wlHulianModel->save()){
                throw new \Exception($this->getErrorMsg($wlHulianModel));
            }

            $model = new WlhulianWalletLog();
            $model->mall_id = $mall->id;
            $model->order_no = '';
            $model->user_id = \Yii::$app->user->id;
            $model->money = $this->money;
            $model->balance = $wlHulianModel->balance;
            $model->type = $this->type;
            if(!$model->save()){
                throw new \Exception($this->getErrorMsg($model));
            }

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功',
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     */
    public function order()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $object = new AccountRecharge();
            $object->rechargePrice = $this->money * 100;
            $config = (new WlForm())->getOption();
            $api = new ApiForm($config);
            $api->object = $object;
            $response = $api->request();

            $token = $response['qrCodeUrl'];
            $imgName = md5(strtotime('now')) . '.jpg';
            // 获取图片存储的路径
            $res = file_uri('/web/temp/');
            $localUri = $res['local_uri'];
            $webUri = $res['web_uri'];
            $save_path = $localUri . $imgName;
            $size = floor(430 / 37 * 100) / 100 + 0.01;
            \QRcode::png($token, $save_path, QR_ECLEVEL_L, $size, 2);

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功',
                'data' => [
                    'code_url' => $webUri . $imgName,
                    'order_no' => $response['rechargeOrdNo']
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array
     */
    public function queryOrder()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $object = new QueryRechargeStatus();
            $object->rechargeOrdNo = $this->keyword;
            $api = new ApiForm((new WlForm())->getOption());
            $api->object = $object;
            $response = $api->request();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '操作成功',
                'data' => $response
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
            ];
        }
    }
}
