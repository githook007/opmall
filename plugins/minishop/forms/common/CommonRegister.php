<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 2:57 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */

namespace app\plugins\minishop\forms\common;

use app\models\Mall;
use app\plugins\minishop\forms\Model;
use app\plugins\minishop\models\MinishopBrand;
use app\plugins\minishop\models\MinishopCat;
use app\plugins\wxapp\models\shop\ShopFactory;
use app\plugins\wxapp\Plugin;
use yii\helpers\Json;

class CommonRegister extends Model
{
    public static $instance;

    /**
     * @var $mall Mall
     */
    public $mall;

    /**
     * @var $shopService ShopFactory
     */
    public $shopService;

    public static function getInstance($mall = null)
    {
        if (self::$instance) {
            return self::$instance;
        }
        $instance = new self();
        if (!$mall) {
            $mall = \Yii::$app->mall;
        }
        $instance->mall = $mall;
        /* @var Plugin $plugin */
        $plugin = \Yii::$app->plugin->getPlugin('wxapp');
        $instance->shopService = $plugin->getShopService();
        self::$instance = $instance;
        return $instance;
    }

    public function check()
    {
        if (\Yii::$app->cache->get('minishop_status_' . $this->mall->id)) {
            return true;
        }
        $res = $this->shopService->register->check();
        switch ($res['data']['status']) {
            case 0:
                throw new CheckException('该小程序自定义版交易组件接入审核中，请开通后再使用');
                break;
            case 1:
            case 2:
                \Yii::$app->cache->set('minishop_status_' . \Yii::$app->mall->id, true, 86400);
                return true;
                break;
            case 3:
                throw new CheckException('该小程序自定义版交易组件已被封禁，请开通后再使用');
                break;
            case 4:
                throw new CheckException('该小程序自定义版交易组件审批不通过，请开通后再使用');
                break;
            default:
                throw new CheckException('该小程序未开通自定义版交易组件，请开通后再使用');
        }
    }

    public function getCat()
    {
        if ($cat = \Yii::$app->cache->get('wxapp_shop_cat_list_tow')) {
            return Json::decode($cat, true);
        }

        $res = $this->shopService->register->getCat();
        \Yii::$app->cache->set('wxapp_shop_cat_list_tow', Json::encode($res, JSON_UNESCAPED_UNICODE), 86400);
        return $res;
    }

    /**
     * @param MinishopCat $minishopCat
     * @param $array
     * @return mixed
     */
    public function changeCatStatus($minishopCat, $array)
    {
        switch ($array['status']) {
            case 1:
                $minishopCat->status = 1;
                if (!$minishopCat->save()) {
                    throw new \Exception($this->getErrorMsg($minishopCat));
                }
                break;
            case 9:
                $minishopCat->status = 9;
                $minishopCat->reject_reason = $array['reject_reason'];
                if (!$minishopCat->save()) {
                    throw new \Exception($this->getErrorMsg($minishopCat));
                }
                break;
            default:
        }
        return $minishopCat;
    }

    /**
     * @param MinishopCat $minishopCat
     * @return MinishopCat
     * @throws \Exception
     * 列表遍历
     */
    public function listChangeCatStatue($minishopCat)
    {
        $key = 'minishop_cat_' . $minishopCat->audit_id;
        if (\Yii::$app->cache->get($key)) {
            return $minishopCat;
        }
        $res = $this->shopService->register->auditResult([
            'audit_id' => $minishopCat->audit_id
        ]);
        $minishopCat = $this->changeCatStatus($minishopCat, [
            'status' => $res['data']['status'],
            'reject_reason' => $res['data']['reject_reason'] ?? ''
        ]);
        \Yii::$app->cache->set($key, true, 3600);
        return $minishopCat;
    }

    /**
     * @param $auditId
     * 分类审核事件处理
     */
    public function eventChangeCatStatus($qualificationAuditResult)
    {
        return true;
        $minishopCat = MinishopCat::findOne([
            'audit_id' => $qualificationAuditResult['audit_id'],
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id,
        ]);
        if (!$minishopCat) {
            throw new \Exception('类目不存在或已被删除' . $qualificationAuditResult['audit_id']);
        }
        if ($minishopCat->status !== 0) {
            throw new \Exception('类目已处理');
        }

        return $this->changeCatStatus($minishopCat, [
            'status' => $qualificationAuditResult['status'],
            'reject_reason' => $qualificationAuditResult['reject_reason']
        ]);
    }

    /**
     * @param MinishopBrand $minishopBrand
     * @param $array
     * @return mixed
     */
    public function changeBrandStatus($minishopBrand, $array)
    {
        switch ($array['status']) {
            case 1:
                $minishopBrand->status = 1;
                $minishopBrand->brand_id = $array['brand_id'] . '';
                if (!$minishopBrand->save()) {
                    throw new \Exception($this->getErrorMsg($minishopBrand));
                }
                break;
            case 9:
                $minishopBrand->status = 9;
                $minishopBrand->reject_reason = $array['reject_reason'];
                if (!$minishopBrand->save()) {
                    throw new \Exception($this->getErrorMsg($minishopBrand));
                }
                break;
            default:
        }
        return $minishopBrand;
    }

    /**
     * @param MinishopBrand $minishopBrand
     * @return MinishopBrand
     * @throws \Exception
     * 列表遍历
     */
    public function listChangeBrandStatue($minishopBrand)
    {
        $key = 'minishop_brand_' . $minishopBrand->audit_id;
        if (\Yii::$app->cache->get($key)) {
            return $minishopBrand;
        }
        $res = $this->shopService->register->auditResult([
            'audit_id' => $minishopBrand->audit_id
        ]);
        $minishopBrand = $this->changeBrandStatus($minishopBrand, [
            'status' => $res['data']['status'],
            'reject_reason' => $res['data']['reject_reason'] ?? '',
            'brand_id' => $res['data']['brand_id'] ?? '',
        ]);
        \Yii::$app->cache->set($key, true, 3600);
        return $minishopBrand;
    }

    /**
     * @param $auditId
     * 分类审核事件处理
     */
    public function eventChangeBrandStatus($qualificationAuditResult)
    {
        return true;
        $minishopBrand = MinishopBrand::findOne([
            'audit_id' => $qualificationAuditResult['audit_id'],
            'is_delete' => 0,
            'mall_id' => \Yii::$app->mall->id,
        ]);
        if (!$minishopBrand) {
            throw new \Exception('类目不存在或已被删除' . $qualificationAuditResult['audit_id']);
        }
        if ($minishopBrand->status !== 0) {
            throw new \Exception('类目已处理');
        }

        return $this->changeBrandStatus($minishopBrand, [
            'status' => $qualificationAuditResult['status'],
            'reject_reason' => $qualificationAuditResult['reject_reason'],
            'brand_id' => $qualificationAuditResult['brand_id'],
        ]);
    }
}
