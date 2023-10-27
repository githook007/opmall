<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/2
 * Time: 9:36
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\community\forms;


use app\bootstrap\response\ApiCode;

class Model extends \app\models\Model
{
    public function success($data)
    {
        $res = $this->handle($data);
        $res['code'] = ApiCode::CODE_SUCCESS;
        return $res;
    }

    public function fail($data)
    {
        $res = $this->handle($data);
        $res['code'] = ApiCode::CODE_ERROR;
        return $res;
    }

    private function handle($data)
    {
        $msg = $data['msg'] ?? '';
        unset($data['msg']);
        return [
            'msg' => $msg,
            'data' => $data
        ];
    }
}
