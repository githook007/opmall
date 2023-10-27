<?php
/**
 * @copyright ©2018 hook007
 * author: opmall
 * @link https://www.opmall.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/30 14:45
 */


namespace app\plugins\demo\models;


use yii\db\ActiveRecord;

class DemoPost extends ActiveRecord
{
    public $id;
    public $title;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%demo_post}}';
    }
}