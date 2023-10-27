<?php
/**
 * @copyright ©2021 hook007
 * Created by PhpStorm.
 * User: Andy - fjt
 * Date: 2020/03/09
 * Time: 14:34
 */
?>

<style>

</style>

<div id="app-invoice-print" v-cloak>
    <div class="print" v-for="(it, key) in list" :key="key"
         :style="{
                padding: `0 ${mmConversionPx(setting.left_right_margins) + 'px'}`,
                width: `${mmConversionPx(Number(setting.left_right_margins) + Number(setting.left_right_margins) + Number(setting.stencil_width) + Number(setting.border_width)+ Number(setting.border_width)) + 'px'}`,
                pageBreakBefore: 'always'
            }"
    >
        <div id="invoice"
             :style="{
                width: mmConversionPx(setting.stencil_width) + 'px',
                 marginLeft: setting.offset.left + 'px',
                 marginRight: setting.offset.right + 'px',
                 minHeight:mmConversionPx(setting.stencil_high) + 'px',
                 cursor: 'pointer',
                 border: `${mmConversionPx(setting.border_width)}px solid #000000`,
                 boxSizing: 'content-box',
                 margin: 0
             }">
            <div :style="{
                    textAlign: setting.headline.align === 0 ? 'center' : setting.headline.align === 1 ? 'left' : 'right',
                    fontFamily: setting.headline.font,
                    textDecoration: setting.headline.underline ? 'underline' : 'none',
                    fontWeight: setting.headline.bold ? 'bold' : 'normal',
                    fontStyle: setting.headline.italic ? 'italic' : 'normal',
                    fontSize: setting.headline.size / (4/3) + 'px',height: '50px',lineHeight: '50px', letterSpacing: setting.headline.space / (4/3)+'px', borderBottom: `${!setting.order.date && !setting.order.time && !setting.order.orderNumber ? '1px solid #000000' : 'none'}`}"
                 class="title"
            >{{setting.headline.name}}
            </div>
            <div
                    v-if="setting.order.date || setting.order.time || setting.order.orderNumber"
                    :style="{display: 'flex',flexWrap:'wrap',borderTop: '1px solid #000000',borderBottom: '1px solid #000000',padding:'10px 10px 10px 0.5%', boxSizing: 'border-box'}"
            >
                <div style="width: 50%;font-size:10px;line-height:1;margin-bottom: 6px"
                     v-if="setting.order.date"><?= \Yii::t('components/other', '打印日期');?>{{printTime}}
                </div>
                <div style="width: 50%;font-size:10px;line-height:1;" v-if="setting.order.time">
                    <?= \Yii::t('components/other', '订单时间');?>{{it.pay_time}}
                </div>
                <div style="width: 50%;font-size:10px;line-height:1;" v-if="setting.order.orderNumber">
                    <?= \Yii::t('components/other', '订单号');?>{{it.order_no}}
                </div>
            </div>
            <div :style="{display: 'flex', boxSizing: 'border-box'}"
                 v-if="setting.personalInf.name || setting.personalInf.nickname || setting.personalInf.phone || setting.personalInf.address || setting.personalInf.leaveComments || setting.personalInf.payMethod || setting.personalInf.shipMethod">
                <div v-if="setting.personalInf.name || setting.personalInf.nickname || setting.personalInf.phone || setting.personalInf.address  || setting.personalInf.payMethod || setting.personalInf.shipMethod"
                     :style="{width: `${setting.personalInf.leaveComments ? '62%' : '100%'}`, boxSizing: 'border-box', borderBottom:'1px solid #000000',borderRight: `${ setting.personalInf.leaveComments ? '1px solid #000000': 'none'}`, padding:'10px 10px 10px 0.5%'}"
                >
                    <div style="font-size:10px;line-height:1.5;" v-if="setting.personalInf.name">
                        <?= \Yii::t('components/other', '收货人信息');?>{{item.name}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;" v-if="setting.personalInf.nickname">
                        <?= \Yii::t('components/other', '收货人昵称');?>{{item.nickname}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;" v-if="setting.personalInf.phone">
                        <?= \Yii::t('components/other', '联系方式');?>{{item.mobile}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;" v-if="setting.personalInf.payMethod">
                        <?= \Yii::t('components/other', '支付方式');?>{{item.pay_type == 1 ? '<?= \Yii::t('components/other', '在线支付');?>' : item.pay_type == 2 ? '<?= \Yii::t('components/other', '货到付款');?>' : item.pay_type == 3 ?
                        '<?= \Yii::t('components/other', '余额支付');?>' : ''}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;"
                         v-if="setting.personalInf.shipMethod && item.send_type != 1"><?= \Yii::t('components/other', '发货方式');?>{{item.send_type ==
                        0 ? '<?= \Yii::t('components/other', '快递配送');?>' : item.send_type == 1 ? '<?= \Yii::t('components/other', '到店自提');?>' : item.send_type == 2 ? '<?= \Yii::t('components/other', '同城配送');?>' : ''}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;"
                         v-if="setting.personalInf.address && item.send_type != 1"><?= \Yii::t('components/other', '收货地址');?>{{item.address}}
                    </div>
                    <div style="font-size:10px;line-height:1.5;"
                         v-if="setting.personalInf.mention_address && item.send_type == 1">
                        <?= \Yii::t('components/other', '自提门店地址');?>{{item.store_address}}
                    </div>
                </div>
                <div :style="{width: `${setting.personalInf.name || setting.personalInf.nickname || setting.personalInf.phone || setting.personalInf.address  || setting.personalInf.payMethod || setting.personalInf.shipMethod ? '38%' : '100%'}`,borderBottom:'1px solid #000000',padding: '10px 10px 10px 0.5%', fontSize:'10px', boxSizing: 'border-box', lineHeight:'1.2', boxSizing: 'border-box'}"
                     v-if="setting.personalInf.leaveComments">
                    <?= \Yii::t('components/other', '买家留言');?>{{item.remark}}
                </div>
            </div>
            <div style="width: 100%;box-sizing:border-box;">
                <div style="display: flex;border-bottom:1px solid #000000;width: 100%;"
                     v-if="setting.goodsInf.serial || setting.goodsInf.name || setting.goodsInf.attr || setting.goodsInf.number || setting.goodsInf.univalent || setting.goodsInf.article_number || setting.goodsInf.unit">
                    <div style="width: 6%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: .5%;font-size:10px;"
                         v-if="setting.goodsInf.serial"><?= \Yii::t('components/other', '序号');?>
                    </div>
                    <div style="width: 26%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: 10px;font-size:10px;"
                         v-if="setting.goodsInf.name"
                    ><?= \Yii::t('components/other', '商品名称');?>
                    </div>
                    <div style="width: 18%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: .5%;font-size:10px;"
                         v-if="setting.goodsInf.attr"><?= \Yii::t('components/other', '规格');?>
                    </div>
                    <div style="width: 12%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: .5%;font-size:10px;"
                         v-if="setting.goodsInf.number"><?= \Yii::t('components/other', '数量');?>
                    </div>
                    <div style="width: 12%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: .5%;font-size:10px;"
                         v-if="setting.goodsInf.univalent"><?= \Yii::t('components/other', '小计');?>
                    </div>
                    <div style="width: 13%;border-right: 1px solid #000000;box-sizing:border-box;height: 30px;line-height: 30px;padding-left: .5%;font-size:10px;"
                         v-if="setting.goodsInf.article_number"><?= \Yii::t('components/other', '货号');?>
                    </div>
                    <div style="width: 13%;height: 30px;line-height: 30px;box-sizing:border-box;padding-left: 10px;font-size:10px;"
                         v-if="setting.goodsInf.unit"><?= \Yii::t('components/other', '单位');?>
                    </div>
                </div>
                <div v-for="good in item.detail"
                     style="display: flex;border-bottom: 1px solid #000000;width: 100%;"
                     v-if="setting.goodsInf.serial || setting.goodsInf.name || setting.goodsInf.attr || setting.goodsInf.number || setting.goodsInf.univalent || setting.goodsInf.article_number || setting.goodsInf.unit">
                    <div style="word-wrap:break-word;width: 6%;box-sizing:border-box;word-wrap: break-word;border-right: 1px solid #000000;padding: 10px 10px 10px .5%;font-size:10px;position: relative"
                         v-if="setting.goodsInf.serial">
                        {{good.id}}
                    </div>
                    <div style="word-wrap:break-word;width: 26%;box-sizing:border-box;word-wrap: break-word;border-right: 1px solid #000000;padding: 10px 10px 10px .5%;font-size:10px;position: relative"
                         v-if="setting.goodsInf.name">
                        {{good.name}}
                    </div>
                    <div style="word-wrap:break-word;width: 18%;box-sizing:border-box;word-wrap: break-word;border-right: 1px solid #000000;font-size:10px ;padding: 10px 10px 10px .5%;position: relative"
                         v-if="setting.goodsInf.attr">
                        {{good.attr}}
                    </div>
                    <div style="word-wrap:break-word;width: 12%;box-sizing:border-box;border-right: 1px solid #000000;font-size:10px;padding: 10px 0 10px .5%;position: relative"
                         v-if="setting.goodsInf.number">
                        {{good.num}}
                    </div>
                    <div style="word-wrap:break-word;width: 12%;box-sizing:border-box;border-right: 1px solid #000000;font-size:10px;padding: 10px 0 10px .5%;position: relative"
                         v-if="setting.goodsInf.univalent">
                        ￥{{good.price}}
                    </div>
                    <div style="word-wrap:break-word;width: 13%;box-sizing:border-box;border-right: 1px solid #000000;font-size:10px;padding: 10px 0 10px .5%;position: relative"
                         v-if="setting.goodsInf.article_number">
                        {{good.goods_no}}
                    </div>
                    <div style="word-wrap:break-word;width: 13%;box-sizing:border-box;word-wrap: break-word;font-size:10px;padding: 10px 0 10px .5%;position: relative"
                         v-if="setting.goodsInf.unit">
                        {{good.unit}}
                    </div>
                </div>

                <div style="display: flex;height: 30px;padding-left: 0.5%;border-bottom:1px solid #000000;font-size: 10px"
                     v-if="setting.goodsInf.amount || setting.goodsInf.fare || setting.goodsInf.discount || setting.goodsInf.actually_paid">
                    <div style="width: 27%;height: 30px;line-height:30px;" v-if="setting.goodsInf.amount">
                        <?= \Yii::t('components/other', '订单金额');?>：￥{{item.total_goods_price}}
                    </div>
                    <div style="width: 24%;height: 30px;line-height:30px;" v-if="setting.goodsInf.fare">
                        <?= \Yii::t('components/other', '运费');?>：￥{{item.express_price}}
                    </div>
                    <div style="width: 25%;height: 30px;line-height:30px;" v-if="setting.goodsInf.discount">
                        <?= \Yii::t('components/other', '优惠');?>：￥{{item.discount_price}}
                    </div>
                    <div style="width: 24%;height: 30px;line-height:30px;"
                         v-if="setting.goodsInf.actually_paid"><?= \Yii::t('components/other', '实付');?>：￥{{item.total_pay_price}}
                    </div>
                </div>
            </div>
            <div :style="{display:'flex',borderBottom:'1px solid #000000', boxSizing: 'border-box'}"
                 v-if="setting.sellerInf.branch || setting.sellerInf.name || setting.sellerInf.phone || setting.sellerInf.postcode || setting.sellerInf.address || setting.sellerInf.remark">
                <div v-if="address_list.length>0"
                     :style="{width:`${!setting.sellerInf.remark ? '100%': '62%'}`,padding: ' 10px 10px 10px .5%', fontSize: '10px',borderRight: `${!setting.sellerInf.remark ? 'none' : '1px solid #000000'}`, boxSizing: 'border-box'}"
                     v-if="setting.sellerInf.branch || setting.sellerInf.name || setting.sellerInf.phone || setting.sellerInf.postcode || setting.sellerInf.address">
                    <div v-if="setting.sellerInf.branch"><?= \Yii::t('components/other', '网点名称');?>{{address_list[0].name}}</div>
                    <div v-if="setting.sellerInf.name"><?= \Yii::t('components/other', '联系人');?>{{address_list[0].username}}</div>
                    <div v-if="setting.sellerInf.phone"><?= \Yii::t('components/other', '联系方式');?>{{address_list[0].mobile}}</div>
                    <div v-if="setting.sellerInf.postcode"><?= \Yii::t('components/other', '网点邮编');?>{{address_list[0].code}}</div>
                    <div v-if="setting.sellerInf.address">
                        <?= \Yii::t('components/other', '网点地址');?>{{address_list[0].province}}{{address_list[0].city}}{{address_list[0].district}}{{address_list[0].address}}
                    </div>
                </div>
                <div :style="{boxSizing: 'border-box',width: `${!setting.sellerInf.branch && !setting.sellerInf.name && !setting.sellerInf.phone && !setting.sellerInf.postcode && !setting.sellerInf.address ? '100%' : '38%'}`,padding: ' 10px 10px 10px .5%', fontSize: '10px'}"
                     v-if="setting.sellerInf.remark">
                    <?= \Yii::t('components/other', '卖家备注');?>{{item.seller_remark}}
                </div>
            </div>
            <div flex="" :style="{padding: '10px 10px 10px 0.5%', fontSize: '10px', boxSizing: 'border-box'}">
                <div style="width: 100%;" flex="">
                    <div v-html="setting.customize" style="width: 100%;word-wrap:break-word;">
                        {{setting.customize}}
                    </div>
                </div>
                <div v-html="setting.customize_image"
                     style="width: 100%;margin-top: 10px;word-wrap:break-word;">{{setting.customize_image}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    Vue.component('app-invoice-print', {
        template: '#app-invoice-print',
        data() {
            return {
                setting: {
                    order: {
                        orderNumber: true,
                        time: true,
                        date: true
                    },
                    personalInf: {
                        name: true,
                        shipMethod: true,
                        nickname: true,
                        payMethod: true,
                        mention_address: true,
                        phone: true,
                        address: true,
                        leaveComments: true
                    },
                    goodsInf: {
                        serial: true,
                        name: true,
                        attr: true,
                        number: true,
                        unit: true,
                        univalent: true,
                        article_number: true,
                        amount: true,
                        fare: true,
                        discount: true,
                        actually_paid: true
                    },
                    sellerInf: {
                        branch: true,
                        name: true,
                        phone: true,
                        postcode: true,
                        address: true,
                        remark: true
                    },
                    headline: {
                        name: "<?= \Yii::t('components/other', '发货单');?>",
                        font: "<?= \Yii::t('components/other', '微软雅黑');?>",
                        size: 16,
                        align: 0,
                        line: 48,
                        space: -100
                    },
                    stencil_width: 204,
                    stencil_high: 142,
                    left_right_margins: 0,
                    border_width: 1,
                    customize_image: '',
                    offset: {
                        left: 0,
                        right: 0,
                    },
                },
                list: []
            };
        },
        created() {
        },
        methods: {
            mmConversionPx(value) {
                return value * 2.834;
            },
        },
    });
</script>