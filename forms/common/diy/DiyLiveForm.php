<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\diy;

use app\forms\mall\live\LiveForm;
use app\models\Model;

class DiyLiveForm extends Model
{
    public function getLiveList()
    {
        $form = new LiveForm();
        $list = $form->getList();

        return $list;
    }

    public function getNewList($data, $res)
    {
        $liveList = isset($res['data']['list']) ? $res['data']['list'] : [];
        $data['live_list'] = [];
        if ($data['number'] >= count($liveList)) {
            $data['live_list'] = $liveList;
        } else {
            $data['live_list'] = array_slice($liveList, 0, $data['number']);
        }
        return $data;
    }
}
