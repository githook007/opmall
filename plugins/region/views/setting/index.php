<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
Yii::$app->loadViewComponent('app-rich-text');
?>
<style>
    .el-tabs__header {
        padding: 0 20px;
        height: 56px;
        line-height: 56px;
        background-color: #fff;
    }

    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 40%;
        min-width: 900px;
    }

    .form-button {
        margin: 0!important;
    }

    .form-button .el-form-item__content {
        margin-left: 0!important;
    }

    .button-item {
        padding: 9px 25px;
        margin-bottom: 25px;
    }

    .before {
        height: 100px;
        line-height: 100px;
        width: 100px;
        background-color: #f7f7f7;
        color: #bbbbbb;
        text-align: center;
    }
    .red {
        color: #ff4544;
    }

    .title {
        padding: 18px 20px;
        border-top: 1px solid #F3F3F3;
        border-bottom: 1px solid #F3F3F3;
        background-color: #fff;
    }

    .mobile {
        width: 400px;
        height: 740px;
        border: 1px solid #cccccc;
        padding: 25px 10px;
        border-radius: 30px;
        margin: 0 20px;
        position: relative;
        flex-shrink: 0;
    }

    .mobile img {
        width: 375px;
        height: 667px;
    }

    .mobile .bg-img {
        height: 180px;
        width: 375px;
        position: absolute;
        left: 0;
        top: 65px;
    }

    .mobile .bottom-bg-img {
        width: 375px;
        height: auto;
        position: absolute;
        left: 10px;
        top: 486px;
    }

    .left-setting-menu {
        width: 140px;
    }

    .left-setting-menu .el-form-item {
        height: 60px;
        display: flex;
        align-items: center;
        margin-bottom: 0;
        cursor: pointer;
    }

    .left-setting-menu .el-form-item .el-form-item__label {
        cursor: pointer;
    }

    .left-setting-menu .el-form-item.active {
        background-color: #F3F5F6;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .no-radius {
        border-top-left-radius: 0!important;
    }

    .left-setting-menu .el-form-item .el-form-item__content {
        margin-left: 0!important
    }

    .item-img {
        height: 700px;
        padding: 25px 10px;
        border-radius: 30px;
        border: 1px solid #CCCCCC;
        background-color: #fff;
        position: relative;
    }

    .item-img .status-txt div {
        width: 33.3%;
    }

    .item-img .price-text {
        position: absolute;
        right: 32px;
        top: 168px;
        text-align: right;
        width: 200px;
        font-size: 11px;
        color: #999;
    }

    .item-img .price-text div {
        height: 101px;
    }

    .item-img .price-text span {
        height: 18px;
        line-height: 18px;
        margin-left: 3px;
    }
    .item-img .offer-text {
        position: absolute;
        left: 85px;
        top: 215px;
        width: 200px;
        font-size: 11px;
        color: #999;
    }

    .item-img .offer-text div {
        height: 72px;
    }

    .item-img .become-txt {
        position: absolute;
        top: 440px;
        left: 10px;
        text-align: center;
        width: 375px;
        font-size: 14px;
        color: #353535;
    }

    .item-img .become-about {
        position: absolute;
        top: 510px;
        left: 46px;
        text-align: left;
        width: 305px;
        font-size: 12px;
        color: #999999;
    }

    .item-img .enter {
        position: absolute;
        top: 340px;
        left: 20px;
        width: 351px;
        flex-wrap: wrap;
    }

    .item-img .enter .enter-item {
        width: 117px;
        text-align: center;
        padding-top: 23.5px;
        color: #666;
    }

    .item-img .enter.menu-list .enter-item {
        padding-top: 18px;
        margin: 0 19px;
    }

    .item-img .enter .enter-item img {
        width: 30.5px;
        width: 30.5px;
        margin-bottom: 5px;
    }

    .item-img .enter .enter-item span {
        color: #ff4544;
    }

    .default {
        width: 10%;
        min-width: 120px;
    }

    .left-setting-menu .el-form-item__label {
        text-align: left;
        padding: 0 0 0 30px;
    }

    .reset {
        position: absolute;
        top: 7px;
        left: 90px;
    }

    .del-btn.el-button--mini.is-circle {
        position: absolute;
        top: -11px;
        right: -11px;
        padding: 4px;
    }

    .rate {
        font-size: 12px;
        height: 40px;
    }

    .rate img {
        height: 14px;
        width: 14px;
        margin-left: 5px;
    }

    .el-textarea textarea {
        padding: 6px 10px;
    }

    .el-textarea .el-textarea__inner {
        resize: none;
    }

    .item-img .balance-list {
        position: absolute;
        top: 90px;
        left: 22px;
        width: 351px;
    }

    .item-img .balance-list .balance-item {
        padding: 18px 18px;
        font-size: 13px;
        color: #A0A0A0;
        margin-top: 55px;
        position: relative;
    }

    .item-img .balance-list .balance-item .bonus-info {
        position: absolute;
        right: 18px;
        bottom: 12px;
        height: 48px;
        text-align: right;
        color: #353535;
    }

    .item-img .balance-list .balance-item .bonus-info .bonus-price {
        font-size: 16px;
        color: #ff4544;
    }

    .item-img .balance-list .balance-item .balance-name {
        color: #353535;
        margin-right: 10px;
        font-size: 16px;
    }

    .item-img .page_title {
        position: absolute;
        top: 55px;
        left: 10px;
        text-align: center;
        width: 375px;
        font-size: 16px;
        font-weight: 600;
        color: #303133;
    }

    .item-img .rate_text {
        position: absolute;
        top: 146px;
        left: 156px;
        color: #fff;
    }
    .item-img .bouns_text {
        position: absolute;
        top: 112px;
        left: 0;
        width: 100%;
        text-align: center;
        color: #fff;
    }
    .item-img .total_bonus_text {
        position: absolute;
        top: 230px;
        left: 38px;
        color: #fff;
        font-size: 12px;
    }
    .item-img .bonus_total_bonus_text {
        position: absolute;
        top: 264px;
        left: 38px;
        color: #353535;
        font-size: 13px;
    }

    .item-img .bonus_total_bonus_text.bonus_cash_bonus_text {
        top: 323px;
    }
    .item-img .bonus_total_bonus_text.bonus_loading_bonus_text {
        top: 370px;
    }
    .item-img .to_cash {
        position: absolute;
        width: 100%;
        text-align: center;
        color: #fff;
        font-size: 16px;
        top: 490px;
        left: 0;
    }
    .item-img .page_title {
        position: absolute;
        top: 55px;
        left: 10px;
        text-align: center;
        width: 375px;
        font-size: 16px;
        font-weight: 600;
        color: #303133;
    }
    .item-img .apply_text {
        position: absolute;
        top: 472px;
        left: 22px;
        width: 351px;
        text-align: center;
        color: #fff;
        font-size: 16px;
    }
    .item-img .level_rate__list_text {
        position: absolute;
        top: 265px;
        right: 40px;
        color: #353535;
        font-size: 12px;
    }
    .item-img .level_rate__list_text div {
        height: 104px;
        line-height: 104px;
    }
    .item-img .level_rate__list_text div span {
        font-weight: 600;
    }
    .item-img .bonus_title_text {
        position: absolute;
        top: 115px;
        left: 155px;
        color: #fff;
        font-size: 13px;
    }
    .item-img .bonus_total_text {
        position: absolute;
        top: 250px;
        left: 38px;
        color: #353535;
        font-size: 13px;
        width: 312px;
        height: 50px;
        line-height: 50px;
    }
    .item-img .bonus_cash_text {
        position: absolute;
        top: 306px;
        left: 38px;
        color: #353535;
        font-size: 13px;
        width: 312px;
        height: 50px;
        line-height: 50px;
    }
    .item-img .bonus_loading_text {
        position: absolute;
        top: 356px;
        left: 38px;
        color: #353535;
        font-size: 13px;
        width: 312px;
        height: 50px;
        line-height: 50px;
    }
    .item-img .cash_bonus {
        font-size: 12px;
        position: absolute;
        left: 38px;
        top: 280px;
        color: #999999;
    }

    .item-img .cash_bonus.all_bonus {
        left: 216px;
    }
    .item-img .enter.menu-list {
        top: 356px;
    }
    .item-img .enter.menu-list img {
        width: 60px;
        height: 60px;
    }

    .input-rate .el-form-item__content {
        display: flex;
        align-items: center;
        height: 40px;
    }
    .required-icon .el-form-item__label:before {
        content: '*';
        color: #F56C6C;
        margin-right: 4px;
    }

</style>
<div id="app" v-cloak>
    <el-card v-loading="loading" style="border:0" shadow="never" body-style="background-color: #f3f3f3;padding: 0 0;">
        <el-form :model="form" :rules="rules" label-width="200px" ref="form">
            <el-tabs v-model="activeName">
                <el-tab-pane label="<?= \Yii::t('plugins/region', '基本设置');?>" name="first">
                    <div class="form-body">
                        <el-form-item class="switch" label="<?= \Yii::t('plugins/region', '区域代理');?>" prop="is_region">
                            <el-switch v-model="is_region" :active-value="1" :inactive-value="0"></el-switch>
                        </el-form-item>
                        <el-form-item class="input-rate" v-if="is_region == 1" prop="region_rate">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/region', '订单最大分红比例');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/region', '订单分红比例订单实付金额');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <el-input @blur="changeBonus" type="text" size="small" style="width: 590px;" v-model="form.region_rate" autocomplete="off">
                                <template slot="append">%</template>
                            </el-input>
                        </el-form-item>
                        <el-form-item v-if="is_region == 1" class="switch" label="<?= \Yii::t('plugins/region', '申请成为代理');?>" prop="apply_type">
                            <el-radio-group @change="tabType" v-model="form.apply_type">
                                <el-radio label="1"><?= \Yii::t('plugins/region', '申请填信息');?></el-radio>
                                <el-radio label="2"><?= \Yii::t('plugins/region', '申请不填信息');?></el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item v-if="is_region == 1" class="switch" label="<?= \Yii::t('plugins/region', '显示申请协议');?>" prop="showTreaty">
                            <el-switch v-model="showTreaty" :active-value="1" :inactive-value="0"></el-switch>
                        </el-form-item>
                        <el-form-item v-if="is_region == 1 && showTreaty == 1" class="switch" label="<?= \Yii::t('plugins/region', '协议名称');?>" prop="agreement_title">
                            <el-input size="small" style="width: 590px;" v-model="form.agreement_title" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item v-show="is_region == 1 && showTreaty == 1" class="switch" label="<?= \Yii::t('plugins/region', '协议内容');?>" prop="agreement_content">
                            <app-rich-text style="width: 590px;" v-model="form.agreement_content"></app-rich-text>
                        </el-form-item>
                        <el-form-item v-show="is_region == 1" class="switch" label="<?= \Yii::t('plugins/region', '用户须知');?>" prop="user_instructions">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/region', '用户须知');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/region', '用户须知展示在代理分红页面');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <el-input type="textarea" style="width: 590px;" :rows="10" v-model="form.user_instructions">
                            </el-input>
                        </el-form-item>
                        <el-form-item v-show="is_region == 1" class="switch" label="<?= \Yii::t('plugins/region', '升级说明');?>" prop="level_up_content">
                            <el-input type="textarea" style="width: 590px;" :rows="10" v-model="form.level_up_content">
                            </el-input>
                        </el-form-item>
                    </div>
                </el-tab-pane>
                <el-tab-pane v-if="is_region == 1" label="<?= \Yii::t('plugins/region', '结算设置');?>" name="second">
                    <div class="form-body">
                        <el-form-item label-width="220px" class="switch" label="<?= \Yii::t('plugins/region', '提现方式');?>" prop="is_share">
                            <el-checkbox-group v-model="pay_type">
                                <el-checkbox label="auto"><?= \Yii::t('plugins/region', '自动打款');?></el-checkbox>
                                <el-checkbox label="wechat"><?= \Yii::t('plugins/region', '微信线下提现');?></el-checkbox>
                                <el-checkbox label="alipay"><?= \Yii::t('plugins/region', '支付宝线下提现');?></el-checkbox>
                                <el-checkbox label="bank"><?= \Yii::t('plugins/region', '银行卡线下提现');?></el-checkbox>
                                <el-checkbox label="balance"><?= \Yii::t('plugins/region', '余额提现');?></el-checkbox>
                            </el-checkbox-group>
                        </el-form-item>
                        <el-form-item label-width="220px" prop="min_money">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/region', '提现门槛金额');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/region', '可提现金额到达此金额时');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <el-input min="0" type="number" size="small" style="width: 590px;" v-model="form.min_money" autocomplete="off">
                                <template slot="append"><?= \Yii::t('plugins/region', '元');?></template>
                            </el-input>
                        </el-form-item>
                        <el-form-item class="required-icon" label-width="220px" prop="cash_service_charge">
                            <template slot='label'>
                                <span><?= \Yii::t('plugins/region', '分红提现手续费');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('plugins/region', '申请提现金额');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <el-input min="0" type="number" size="small" style="width: 590px;" v-model="form.cash_service_charge" autocomplete="off">
                                <template slot="append">%</template>
                            </el-input>
                        </el-form-item>
                        <el-form-item label-width="220px" label="<?= \Yii::t('plugins/region', '免手续费提现门槛金额');?>">
                            <el-input min="0" type="number" size="small" style="width: 590px;" v-model="form.free_cash_min" autocomplete="off">
                                <template slot="append"><?= \Yii::t('plugins/region', '元');?></template>
                            </el-input>
                        </el-form-item>
                    </div>
                </el-tab-pane>
                <el-tab-pane v-if="is_region == 1" label="<?= \Yii::t('plugins/region', '页面自定义');?>" name="three">
                    <div class="title">
                        <span><?= \Yii::t('plugins/region', '头部banner图片');?></span>
                    </div>
                    <div class="form-body">
                        <div flex="dir:left">
                            <div class="mobile">
                                <div style="height: 690px;position: absolute;overflow-x: hidden;overflow-y: auto;">
                                    <img src="statics/img/plugins/region/head.png" alt="">
                                    <img class="bg-img" :src="bg_url" alt="">
                                </div>
                            </div>
                            <div>
                                <el-form-item class="required-icon" label="<?= \Yii::t('plugins/region', '头部banner图片');?>" prop="bg_url">
                                    <div style="position: relative">
                                        <app-attachment :multiple="false" :max="1" @selected="topPicUrl">
                                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/region', '建议尺寸');?>:750*360" placement="top">
                                                <el-button size="mini"><?= \Yii::t('plugins/region', '选择文件');?></el-button>
                                            </el-tooltip>
                                        </app-attachment>
                                        <div style="margin-top: 10px;position: relative">
                                            <app-image width="100px"
                                                       height="100px"
                                                       mode="aspectFill"
                                                       :src="bg_url">
                                            </app-image>
                                            <el-button v-if="bg_url != ''" class="del-btn" @click="resetImg(1,'top')" size="mini" type="danger" icon="el-icon-close" circle @click="delPic(index)"></el-button>
                                        </div>
                                        <el-button size="mini" @click="resetImg(2,'top')" class="reset" type="primary"><?= \Yii::t('plugins/region', '恢复默认');?></el-button>
                                    </div>
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                    <div class="title" style="margin-top: 10px">
                        <span><?= \Yii::t('plugins/region', '自定义文字');?></span>
                    </div>
                    <div class="form-body" style="padding: 40px;display: flex;">
                        <div class='left-setting-menu'>
                            <el-form-item label-width="140px" :class='active_setting == "1" ? "active":""' @click.native='chooseSetting("1")' label="<?= \Yii::t('plugins/region', '入口位置');?>">
                            </el-form-item>
                            <el-form-item label-width="140px" :class='active_setting == "2" ? "active":""' @click.native='chooseSetting("2")' label="<?= \Yii::t('plugins/region', '申请成为代理');?>">
                            </el-form-item>
                            <el-form-item label-width="140px" :class='active_setting == "3" ? "active":""' @click.native='chooseSetting("3")' label="<?= \Yii::t('plugins/region', '主页');?>">
                            </el-form-item>
                            <el-form-item label-width="140px" :class='active_setting == "4" ? "active":""' @click.native='chooseSetting("4")' label="<?= \Yii::t('plugins/region', '代理分红');?>">
                            </el-form-item>
                        </div>
                        <div style='background-color: #F3F5F6;padding: 30px;border-radius: 10px;overflow-y: auto;' :class='active_setting == "1" ? "no-radius":""' flex="dir:left">
                            <div v-if='active_setting == "1"' class="item-img">
                                <app-image mode="aspectFill" src="statics/img/plugins/index.png" style="margin-bottom: 20px" height="667" width="375"></app-image>
                                <div class="enter" flex="dir:left">
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-price.png" alt="">
                                        <div><?= \Yii::t('plugins/region', '分销佣金');?></div>
                                        <div><span>3500</span><?= \Yii::t('plugins/region', '元');?></div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-order.png" alt="">
                                        <div><?= \Yii::t('plugins/region', '分销订单');?></div>
                                        <div><span>26</span><?= \Yii::t('plugins/region', '元');?></div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-cash.png" alt="">
                                        <div><?= \Yii::t('plugins/region', '提现明细');?></div>
                                        <div><span>10</span><?= \Yii::t('plugins/region', '元');?></div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-team.png" alt="">
                                        <div><?= \Yii::t('plugins/region', '我的团队');?></div>
                                        <div><span>4</span>人</div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-qrcode.png" alt="">
                                        <div><?= \Yii::t('plugins/region', '分销佣金');?></div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/mall/share-custom/img-share-region.png" alt="">
                                        <div>{{form.form.entry_bonus?form.form.entry_bonus:'<?= \Yii::t('plugins/region', '区域代理');?>'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div v-if='active_setting == "2"' class="item-img">
                                <app-image mode="aspectFill" src="statics/img/plugins/region/apply.png" style="margin-bottom: 20px" height="667" width="375"></app-image>
                                <div class="page_title">{{form.form.apply_title_text ? form.form.apply_title_text : '<?= \Yii::t('plugins/region', '申请成为代理');?>'}}</div>
                                <div class="apply_text">{{form.form.apply_text ? form.form.apply_text : '<?= \Yii::t('plugins/region', '申请成为区域代理');?>'}}</div>
                            </div>
                            <div v-if='active_setting == "3"' class="item-img">
                                <app-image mode="aspectFill" src="statics/img/plugins/region/index.png" style="margin-bottom: 20px" height="667" width="375"></app-image>
                                <div class="rate_text">{{form.form.rate ? form.form.rate : '<?= \Yii::t('plugins/region', '分红比例');?>'}}10%</div>
                                <div class="total_bonus_text">{{form.form.total_bonus ? form.form.total_bonus : '<?= \Yii::t('plugins/region', '可提现分红');?>'}}(<?= \Yii::t('plugins/region', '元');?>)</div>
                                <div class="cash_bonus">{{form.form.cash_bonus ? form.form.cash_bonus : '<?= \Yii::t('plugins/region', '已提现分红');?>'}}(<?= \Yii::t('plugins/region', '元');?>)</div>
                                <div class="cash_bonus all_bonus">{{form.form.all_bonus ? form.form.all_bonus : '<?= \Yii::t('plugins/region', '累计分红');?>'}}(<?= \Yii::t('plugins/region', '元');?>)</div>
                                <div class="menu-list enter" flex="main:center">
                                    <div class="enter-item">
                                        <img src="statics/img/plugins/region/bonus.png" alt="">
                                        <div>{{form.form.region?form.form.region:'<?= \Yii::t('plugins/region', '代理分红');?>'}}</div>
                                    </div>
                                    <div class="enter-item">
                                        <img src="statics/img/plugins/stock/cash.png" alt="">
                                        <div>{{form.form.cash_detail?form.form.cash_detail:'<?= \Yii::t('plugins/region', '提现明细');?>'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div v-if='active_setting == "4"' class="item-img">
                                <app-image mode="aspectFill" src="statics/img/plugins/region/page-bonus.png" style="margin-bottom: 20px" height="667" width="375"></app-image>
                                <div class="bouns_text">{{form.form.bouns ? form.form.bouns : '<?= \Yii::t('plugins/region', '代理分红');?>'}}(<?= \Yii::t('plugins/region', '元');?>)</div>
                                <div class="bonus_total_bonus_text">{{form.form.total_bonus_text ? form.form.total_bonus_text : '<?= \Yii::t('plugins/region', '可提现分红');?>'}}</div>
                                <div class="bonus_total_bonus_text bonus_cash_bonus_text">{{form.form.cash_bonus_text ? form.form.cash_bonus_text : '<?= \Yii::t('plugins/region', '已提现分红');?>'}}</div>
                                <div class="bonus_total_bonus_text bonus_loading_bonus_text">{{form.form.loading_bonus_text ? form.form.loading_bonus_text : '<?= \Yii::t('plugins/region', '待打款分红');?>'}}</div>
                                <div class="to_cash">{{form.form.to_cash ? form.form.to_cash : '<?= \Yii::t('plugins/region', '我要提现');?>'}}</div>
                            </div>
                        </div>
                        <div class="item-img" style="border: 0;width: 570px;margin-left: 20px;">
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 1">
                                <div class="default"><?= \Yii::t('plugins/region', '区域代理');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.entry_bonus" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 2">
                                <div class="default"><?= \Yii::t('plugins/region', '申请成为代理');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="8" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.apply_title_text" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 2">
                                <div class="default"><?= \Yii::t('plugins/region', '申请成为区域代理');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="8" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.apply_text" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '分红比例');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.rate" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '可提现分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="8" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.total_bonus" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '已提现分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="8" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.cash_bonus" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '累计分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="8" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.all_bonus" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '代理分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.region" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 3">
                                <div class="default"><?= \Yii::t('plugins/region', '提现明细');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.cash_detail" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 4">
                                <div class="default"><?= \Yii::t('plugins/region', '代理分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.bouns" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 4">
                                <div class="default"><?= \Yii::t('plugins/region', '可提现分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.total_bonus_text" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 4">
                                <div class="default"><?= \Yii::t('plugins/region', '已提现分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.cash_bonus_text" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 4">
                                <div class="default"><?= \Yii::t('plugins/region', '待打款分红');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.loading_bonus_text" class="text-input"></el-input>
                            </div>
                            <div style="height: 32px;margin-left: 40px;margin-bottom: 20px" flex="dir:left cross:center" v-if="active_setting == 4">
                                <div class="default"><?= \Yii::t('plugins/region', '我要提现');?></div>
                                <app-image width="12px" height="12px" mode="aspectFill" :src="customize_pic"></app-image>
                                <el-input maxlength="6" style="width: 310px;margin-left: 25px;" size="small" v-model="form.form.to_cash" class="text-input"></el-input>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <el-button class="button-item" type="primary" size="small" :loading="submitLoading" @click="submit('form')"><?= \Yii::t('plugins/region', '保存');?></el-button>
        </el-form>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            var validateRate = (rule, value, callback) => {
                if (value === '' || value === undefined) {
                    callback(new Error('<?= \Yii::t('plugins/region', '请填写订单最大分红比例');?>'));
                } else if (value > 100) {
                    callback(new Error('<?= \Yii::t('plugins/region', '分红比例不得大于100');?>'));
                } else {
                    callback();
                }
            };
            var validateRate4 = (rule, value, callback) => {
                if (this.form.cash_service_charge === '' || this.form.cash_service_charge === undefined) {
                    callback(new Error('<?= \Yii::t('plugins/region', '请填写分红提现手续费');?>'));
                } else if (this.form.cash_service_charge > 100) {
                    callback(new Error('<?= \Yii::t('plugins/region', '分红提现手续费不得大于100');?>'));
                } else {
                    callback();
                }
            };
            return {
                is_region: 0,
                showTreaty: 0,
                pay_type: ['auto'],
                form: {
                    is_region: 0,
                    is_agreement: 0,
                    agreement_content: '',
                    base_rate: 0,
                    region_rate: '',
                    apply_type: '1',
                    become_type: 1,
                    condition: 0,
                    min_money: 0,
                    free_cash_min: '',
                    free_cash_max: '',
                    cash_service_charge: 0,
                    pay_type: [],
                    user_instructions: '',
                    form: {
                        bg_url: 'statics/img/app/region/banner.png',
                        entry_bonus: '',
                        title: '',
                        total_bonus: '',
                        expect_bonus: '',
                        cashd_bonus: '',
                        become_name: '',
                        statics_text: '',
                        price_text: '',
                        can_cash_bonus: '',
                        orders: '',
                        statistic_bonus: '',
                        members: '',
                        offer_text: ''
                    }
                },
                rules: {
                    is_region: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请选择是否开启区域代理');?>', trigger: 'change' }
                    ],
                    region_rate: [
                        { required: true, validator: validateRate, trigger: 'blur' }
                    ],
                    base_rate: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请填写基础等级分红比例');?>', trigger: 'blur' }
                    ],
                    apply_type: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请选择申请成为代理的条件');?>', trigger: 'change' }
                    ],
                    become_type: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请选择申请成为代理的条件');?>', trigger: 'change' }
                    ],
                    user_instructions: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请填写用户须知');?>', trigger: 'blur' }
                    ],
                    level_up_content: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请填写升级说明');?>', trigger: 'blur' }
                    ],
                    condition: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '请填写升级条件');?>', trigger: 'blur' }
                    ],
                    min_money: [
                        { required: true, message: '<?= \Yii::t('plugins/region', '提现门槛金额不能为空');?>', trigger: 'blur' }
                    ],
                    cash_service_charge: [
                        { validator: validateRate4, trigger: 'blur' }
                    ]
                },
                active_setting: '1',
                customize_pic: _baseUrl + '/statics/img/mall/customize_jp.png',
                bg_url: 'statics/img/app/region/banner.png',
                loading: false,
                activeName: 'first',
                submitLoading: false,
            };
        },
        methods: {
            topPicUrl(e) {
                this.form.form.bg_url = e[0].url;
                this.bg_url = e[0].url;
            },

            changeBonus() {
                if(this.form.region_rate > -1 && this.form.region_rate != '') {
                    this.form.region_rate = parseFloat(this.form.region_rate).toFixed(2);
                }
            },

            changeBase() {
                if(this.form.base_rate > -1 && this.form.base_rate != '') {
                    this.form.base_rate = parseFloat(this.form.base_rate).toFixed(2);
                }
            },

            tabType() {
                this.form.form.become_name = '';
                if(this.form.become_type == 0) {
                    this.active_setting = 1;
                }
            },

            bottomPicUrl(e) {
                this.form.form.bottom_bg_url = e[0].url;
                this.bottom_bg_url = e[0].url;
            },

            chooseSetting(e) {
                this.active_setting = e;
            },

            getList() {
                this.loading = true;
                let para = {
                    r: 'plugin/region/mall/setting',
                }
                request({
                    params: para,
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        if(e.data.data) {
                            if(e.data.data.is_region == 1) {
                                this.form = e.data.data;
                                this.is_region = this.form.is_region;
                                this.showTreaty = this.form.is_agreement;
                                this.pay_type = this.form.pay_type;
                                this.bg_url = this.form.form.bg_url;
                                this.bottom_bg_url = this.form.form.bottom_bg_url;
                                if(this.form.pay_type.length == 0) {
                                    this.pay_type = ['auto']
                                }
                            }
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },

            resetImg(res,position) {
                if(position == 'top') {
                    if(res == 2) {
                        this.bg_url = 'statics/img/app/region/banner.png';
                        this.form.form.bg_url = 'statics/img/app/region/banner.png';
                    }else {
                        this.bg_url = '';
                        this.form.form.bg_url = '';
                    }
                }else if(position == 'bottom') {
                    if(res == 2) {
                        this.bottom_bg_url = 'statics/img/app/region/foot.png';
                        this.form.form.bottom_bg_url = 'statics/img/app/region/foot.png';
                    }else {
                        this.bottom_bg_url = '';
                        this.form.form.bottom_bg_url = '';
                    }
                }
            },

            submit(formName) {
                let that = this;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        that.submitLoading = true;
                        let para = that.form;
                        para.is_region = that.is_region;
                        para.is_agreement = that.showTreaty;
                        para.pay_type = [];
                        if(that.pay_type.indexOf('balance') > -1) {
                            para.pay_type.push('balance')
                        }
                        if(that.pay_type.indexOf('bank') > -1) {
                            para.pay_type.push('bank')
                        }
                        if(that.pay_type.indexOf('alipay') > -1) {
                            para.pay_type.push('alipay')
                        }
                        if(that.pay_type.indexOf('wechat') > -1) {
                            para.pay_type.push('wechat')
                        }
                        if(that.pay_type.indexOf('auto') > -1 || that.pay_type.length == 0) {
                            para.pay_type.push('auto')
                        }
                        if(para.cash_service_charge == 0) {
                            para.free_cash_min = '';
                            para.free_cash_max = '';
                        }
                        request({
                            params: {
                                r: 'plugin/region/mall/setting'
                            },
                            data: para,
                            method: 'post'
                        }).then(e => {
                            that.submitLoading = false;
                            if (e.data.code === 0) {
                                that.$message({
                                    message: e.data.msg,
                                    type: 'success'
                                });
                                setTimeout(function(){
                                    that.getList();
                                },500);
                            } else {
                                that.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            that.loading = false;
                        });
                    }
                })
            },
        },
        created() {
            this.loading = true;
            this.getList();
        }
    });
</script>
