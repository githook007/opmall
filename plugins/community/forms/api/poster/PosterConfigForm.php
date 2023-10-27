<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/23
 * Time: 9:58
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\forms\api\poster;


use app\forms\common\poster\PosterConfigTrait;
use app\plugins\community\forms\Model;
use app\plugins\community\Plugin;

class PosterConfigForm extends Model
{
    use PosterConfigTrait;
    public $goods_id;

    public function rules()
    {
        return [
            [['goods_id'], 'required'],
            [['goods_id'], 'integer'],
        ];
    }

    public function getDetail()
    {
        if (!$this->validate()) {
            return $this->getErrorResponse();
        }

        try {
            return $this->success([
                'config' => $this->getConfig(),
                'info' => $this->getAll(),
            ]);
        } catch (\Exception $e) {
            return $this->fail(['msg' => $e->getMessage()]);
        }
    }

    public function getPlugin(): array
    {
        return [
            'sign' => (new Plugin())->getName()
        ];
    }
}
