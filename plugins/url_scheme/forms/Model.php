<?php
/**
 * Created by PhpStorm.
 * Date: 2019/3/26
 * Time: 17:06
 * @copyright: ©2019 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\url_scheme\forms;

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

    /**
     * @param \Exception $exception
     * @return array
     */
    public function failByException($exception)
    {
        return $this->fail([
            'msg' => $exception->getMessage(),
            'errors' => $exception
        ]);
    }
}
