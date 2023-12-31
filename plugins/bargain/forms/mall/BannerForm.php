<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/9
 * Time: 16:12
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\bargain\forms\mall;


use app\bootstrap\response\ApiCode;
use app\models\Mall;
use app\models\Model;
use app\plugins\bargain\models\BargainBanner;
use yii\db\Exception;

/**
 * @property Mall $mall
 */
class BannerForm extends Model
{
    public $mall;
    public $ids;

    public function rules()
    {
        return [
            [['ids'], 'safe'],
            [['ids'], 'default', "value" => []]
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        if (!$this->mall) {
            $this->mall = \Yii::$app->mall;
        }
        $t = \Yii::$app->db->beginTransaction();
        try {
            BargainBanner::updateAll(['is_delete' => 1, 'deleted_at' => mysql_timestamp()], ['is_delete' => 0, 'mall_id' => $this->mall->id]);
            // 循环添加新的数据
            foreach ($this->ids as $item) {
                $form = new BargainBanner();
                $form->banner_id = $item;
                $form->mall_id = $this->mall->id;
                $form->is_delete = 0;
                $form->created_at = mysql_timestamp();
                if (!$form->save()) {
                    throw new Exception($this->getErrorMsg($form));
                }
            }
            $t->commit();
            return [
                'code' => ApiCode::CODE_SUCCESS,
                'msg' => '保存成功',
            ];
        } catch (Exception $exception) {
            $t->rollBack();
            return [
                'code' => ApiCode::CODE_ERROR,
                'msg' => $exception->getMessage()
            ];
        }
    }
}
