<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/10/31
 * Time: 15:10
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\forms\common\form;


use app\models\Form;
use app\models\Mall;
use app\models\Model;

/**
 * Class CommonForm
 * @package app\forms\common\form
 * @property Mall $mall
 */
class CommonForm extends Model
{
    private static $instance;
    public $mall;

    const FORM_DEFAULT = 1; // 默认
    const FORM_NOT_DEFAULT = 0; // 不默认
    const FORM_OPEN = 1; // 状态开启
    const FORM_CLOSE = 0; // 状态关闭

    public static function getInstance($mall = null)
    {
        if (!self::$instance) {
            self::$instance = new self();
            if (!$mall) {
                $mall = \Yii::$app->mall;
            }
            self::$instance->mall = $mall;
        }
        return self::$instance;
    }

    /**
     * @param $id
     * @return Form|null
     * @throws \Exception
     */
    public function getDetail($id)
    {
        $form = Form::findOne([
            'mall_id' => $this->mall->id, 'is_delete' => 0, 'id' => $id
        ]);
        if (!$form) {
            throw new \Exception('内容不存在');
        }
        $form->value = json_decode($form->value, true);
        return $form;
    }
}
