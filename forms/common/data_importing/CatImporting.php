<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

namespace app\forms\common\data_importing;


use app\forms\PickLinkForm;
use app\models\GoodsCats;
use app\models\HomeNav;

class CatImporting extends BaseImporting
{
    public static $catIds = [];

    public function import()
    {
        try {
            foreach ($this->v3Data as $datum) {
                $cat = $this->saveCat($datum);
                self::$catIds[$datum['id']] = $cat->id;
                if (isset($datum['childrenList'])) {
                    foreach ($datum['childrenList'] as $item) {
                        $cat2 = $this->saveCat($item, $cat->id);
                        self::$catIds[$item['id']] = $cat2->id;
                    }
                }

            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function saveCat($datum, $parentId = 0)
    {
        $pickLink = PickLink::getNewLink($datum['advert_url']);
        $cats = new GoodsCats();
        $cats->mall_id = $this->mall->id;
        $cats->parent_id = $parentId;
        $cats->name = $datum['name'];
        $cats->pic_url = $datum['pic_url'];
        $cats->sort = $datum['sort'];
        $cats->big_pic_url = $datum['big_pic_url'];
        $cats->advert_pic = $datum['advert_pic'];
        $cats->advert_url = $pickLink['url'];
        $cats->status = $datum['is_show'];
        $cats->is_show = 1;
        $cats->advert_params = '';
        $cats->created_at = date('Y-m-d H:i:s', $datum['addtime']);
        $cats->updated_at = date('Y-m-d H:i:s', $datum['addtime']);
        $res = $cats->save();

        if (!$res) {
            throw new \Exception($this->getErrorMsg($cats));
        }

        return $cats;
    }
}