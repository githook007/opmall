<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/23
 * Time: 3:07 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\cat;

use app\plugins\minishop\forms\common\CommonRegister;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopCat;
use yii\helpers\Json;

class EditCatForm extends Model
{
    public $id;
    public $third_cat_id;
    public $license;
    public $certificate;

    public function rules()
    {
        return [
            [['third_cat_id', 'license', 'certificate'], 'required'],
            [['id', 'third_cat_id'], 'integer'],
            [['license'], 'trim'],
            [['license'], 'string'],
            [['certificate'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'license' => '营业执照',
            'certificate' => '类目资质',
            'third_cat_id' => '商品分类'
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }
        try {
            $model = MinishopCat::findOne([
                'id' => $this->id,
                'mall_id' => \Yii::$app->mall->id,
                'is_delete' => 0
            ]);
            if (!$model) {
                $model = new MinishopCat();
                $model->mall_id = \Yii::$app->mall->id;
                $model->is_delete = 0;
                $model->status = 0;
            }
            if ($model->status == 1) {
                throw new \Exception('类目资质已通过审核，无需更改');
            }
            $common = CommonRegister::getInstance();
            $res = $common->shopService->register->imgUpload($this->license); // @czs 先获取临时图片地址
            $this->license = $res['img_info']['temp_img_url'];
            foreach ($this->certificate as &$cert) {
                $res = $common->shopService->register->imgUpload($cert);
                $cert = $res['img_info']['temp_img_url'];
            }
            unset($cert);
            $model->certificate = Json::encode($this->certificate, JSON_UNESCAPED_UNICODE);
            $model->license = $this->license;
            $model->third_cat_id = $this->third_cat_id;
            $model->status = 0;
            $catList = $common->getCat();
            $thirdCatList = array_column($catList['third_cat_list'], null, 'third_cat_id');
            $res = $common->shopService->register->auditCategory([
                'license' => $this->license,
                'level1' => $thirdCatList[$this->third_cat_id]['first_cat_id'],
                'level2' => $thirdCatList[$this->third_cat_id]['second_cat_id'],
                'level3' => $thirdCatList[$this->third_cat_id]['third_cat_id'],
                'certificate' => $this->certificate
            ]);
            $model->third_cat_name = $thirdCatList[$this->third_cat_id]['third_cat_name'];
            $model->audit_id = $res['audit_id'];
            if (!$model->save()) {
                throw new \Exception($this->getErrorMsg($model));
            }
            return $this->success([
                'msg' => '上传成功'
            ]);
        } catch (\Exception $exception) {
            return $this->failByException($exception);
        }
    }
}
