<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/9/29
 * Time: 4:16 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */

namespace app\plugins\mobile\forms;

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
