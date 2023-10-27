<?php

namespace app\plugins\supply_goods\models;

use Yii;

/**
 * This is the model class for table "{{%supply_goods_wholesaler}}".
 *
 * @property int $id
 * @property int $mall_id 商城
 * @property int $user_id 用户
 * @property string $name 批发商名称
 * @property string $introduction 简介
 * @property string $phone 联系方式
 * @property string $address 地址
 * @property string $send_type 发货方式
 * @property string $send_time 发货时间
 * @property string $logo logo
 * @property string $back_image 背景图
 * @property int $status 0待审核   1审核成功   2审核失败
 * @property string $examine_remarks 审核备注
 * @property string $add_time 新增时间
 * @property string $update_time 编辑时间
 * @property string $examine_time 审核时间
 * @property int $is_delete 是否删除
 * @property string $delete_time 删除时间
 */
class SupplyGoodsWholesaler extends \app\models\ModelActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%supply_goods_wholesaler}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mall_id', 'user_id', 'name', 'introduction', 'phone', 'address', 'send_type', 'send_time', 'logo', 'back_image', 'status', 'add_time', 'update_time'], 'required'],
            [['mall_id', 'user_id', 'is_delete', 'status'], 'integer'],
            [['name', 'introduction', 'phone', 'address', 'send_type', 'logo', 'back_image', 'examine_remarks', 'add_time', 'update_time', 'examine_time', 'delete_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mall_id' => 'Mall ID',
            'user_id' => 'User ID',
            'name' => '批发商名称',
            'introduction' => '简介',
            'phone' => '联系方式',
            'address' => '地址',
            'send_type' => '发货方式',
            'send_time' => '发货时间',
            'logo' => 'logo',
            'back_image' => '背景图',
            'status' => '状态',
            'examine_remarks' => '审核备注',
            'add_time' => '新增时间',
            'update_time' => '编辑时间',
            'examine_time' => '审核时间',
            'is_delete' => '是否删除',
            'delete_time' => '删除时间',
        ];
    }
}
