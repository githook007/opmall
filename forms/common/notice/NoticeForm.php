<?php


namespace app\forms\common\notice;

use app\bootstrap\Pagination;
use app\bootstrap\response\ApiCode;
use app\models\AdminNotice;
use app\models\MallNoMoreNotice;
use app\models\Model;

class NoticeForm extends Model
{
    public $id;
    public $type;

    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
        ];
    }

    public function getList()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            $model = AdminNotice::find()->andWhere(['is_delete' => 0])->orderBy('created_at desc');
            $first = [];
            $mall_notice = '';
            //3条统计页面公告，顺带到期通知
            switch ($this->type) {
                case 3:
                    $pagination = null;
                    $list = $model->limit($this->type)->asArray()->all();
//                $mall = Mall::findOne(\Yii::$app->mall->id);
//                if ($mall->expired_at != '0000-00-00 00:00:00' && strtotime($mall->expired_at) + (86400 * 30) < time()) {
//                    $mall_notice = '商城到期提醒：' . $mall->expired_at . '到期';
//                }
                    break;
                case 2:
                    $list = $model->page($pagination, 5)->asArray()->all();
                    break;
                default:
                    $model_1 = clone $model;
                    $first = strip_tags($model_1->asArray()->one()['content']);
                    /* @var Pagination $pagination */
                    $list = $model->page($pagination, 5)->offset($pagination->offset + 1)->asArray()->all();
                    break;
            }
            foreach ($list as &$item) {
                $item['content_text'] = strip_tags($item['content']);
            }
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => [
                    'first' => $first,
                    'list' => $list,
                    'mall_notice' => $mall_notice,
                    'pagination' => $pagination
                ]
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            if (!$this->id) {
                throw new \Exception('ID不能为空');
            }
            $data = AdminNotice::find()->andWhere(['is_delete' => 0, 'id' => $this->id])->asArray()->one();

            return [
                'code' => ApiCode::CODE_SUCCESS,
                'data' => $data,
            ];
        } catch (\Exception $e) {
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $e->getMessage(),
                'error' => [
                    'line' => $e->getLine()
                ]
            ];
        }
    }

    public function noticeEject(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $setting = \app\forms\common\CommonOption::get(\app\models\Option::NAME_IND_SETTING);
        if($setting->announcement_eject == 1){  // 弹出
            $notice = AdminNotice::find()->andWhere(['is_delete' => 0])->orderBy('id desc')->asArray()->one();
            $is_no_more = MallNoMoreNotice::findOne(['mall_id' => \Yii::$app->mall->id, 'notice_id' => $notice['id']]);
            if (!$is_no_more && $notice){
                switch ($notice['type']) {
                    case 'update':
                        $notice['type_text'] = '更新公告';
                        break;
                    case 'urgent':
                        $notice['type_text'] = '紧急公告';
                        break;
                    case 'important':
                        $notice['type_text'] = '重要公告';
                        break;
                    default:
                        $notice['type_text'] = '未知公告';
                        break;
                }
                return [
                    'code' => ApiCode::CODE_SUCCESS,
                    'data' => $notice,
                ];
            }
        }
        return [
            'code' => ApiCode::CODE_ERROR,
            'data' => [],
        ];
    }

    public function noticeIDo(){
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        $model = new MallNoMoreNotice();
        $model->mall_id = \Yii::$app->mall->id;
        $model->notice_id = $this->id;
        $model->add_time = mysql_timestamp();
        if (!$model->save()){
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $this->getErrors($model),
            ];
        }
        return [
            'code' => ApiCode::CODE_SUCCESS,
            'msg' => '成功',
        ];
    }
}
