<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/7
 * Time: 11:46
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('app-goods');
?>
<style>
    .bargain-time {
        color: #666666;
        padding: 5px 10px;
    }

    .bargain-time:before {
        content: '●';
        margin-right: 10px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'plugin/bargain/mall/goods/index'})"><?= \Yii::t('plugins/bargain', '砍价活动');?></span></el-breadcrumb-item>
                <el-breadcrumb-item v-if="form.goods_id > 0"><?= \Yii::t('plugins/bargain', '详情');?></el-breadcrumb-item>
                <el-breadcrumb-item v-else><?= \Yii::t('plugins/bargain', '新建活动');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <app-goods ref="appGoods" :is_attr="0" :is_show="0" url="plugin/bargain/mall/goods/edit"
                   get_goods_url="plugin/bargain/mall/goods/edit"
                   @goods-success="goodsSuccess"
                   :preview-info="previewInfo"
                   @handle-preview="handlePreview"
                   sign="bargain"
                   :is_cats="0"
                   :is_edit="is_edit"
                   :no_price="1"
                   :rules="rule"
                   :is_member="0"
                   :is_min_number="false"
                   :is_display_setting="0"
                   :form="form" :rule="rule" referrer="plugin/bargain/mall/goods/index">

            <template slot="before_info">
                <el-card shadow="never" style="margin-bottom: 24px">
                    <div slot="header"><?= \Yii::t('plugins/bargain', '活动设置');?></div>
                    <el-col :xl="12" :lg="16">
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '开始时间');?>" width="120" prop="begin_time">
                            <el-date-picker
                                    :disabled="form.disabledBegin"
                                    v-model="form.begin_time"
                                    type="datetime"
                                    value-format="yyyy-MM-dd HH:mm:ss"
                                    placeholder="<?= \Yii::t('plugins/bargain', '开始时间');?>">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '结束时间');?>" prop="end_time">
                            <el-date-picker
                                    :disabled="form.disabledEnd"
                                    v-model="form.end_time"
                                    type="datetime"
                                    value-format="yyyy-MM-dd HH:mm:ss"
                                    placeholder="<?= \Yii::t('plugins/bargain', '选择结束时间');?>">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '砍价时间');?>" width="120" prop="bargain_time">
                            <label slot="label">
                                <?= \Yii::t('plugins/bargain', '砍价时间');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('plugins/bargain', '若到时间没有购买，则视为砍价失败');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input required type="number" v-model="form.bargain_time" placeholder="<?= \Yii::t('plugins/bargain', '砍价时间');?>">
                                <template slot="append"><?= \Yii::t('plugins/bargain', '小时');?></template>
                            </el-input>
                        </el-form-item>
                    </el-col>
                </el-card>
            </template>

            <template slot="before_price">
                <!-- 最低价 -->
                <el-form-item label="<?= \Yii::t('plugins/bargain', '最低价');?>" prop="min_price">
                    <el-input type="number"
                              oninput="this.value = this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');" min="0"
                              v-model="form.min_price" placeholder="<?= \Yii::t('plugins/bargain', '请输入商品最低价');?>">
                        <template slot="append"><?= \Yii::t('plugins/bargain', '元');?></template>
                    </el-input>
                </el-form-item>

<!--                <el-form-item label="售价" prop="price">-->
<!--                    <el-input type="number"-->
<!--                              disabled-->
<!--                              v-model="form.price">-->
<!--                        <template slot="append">元</template>-->
<!--                    </el-input>-->
<!--                </el-form-item>-->
            </template>

            <template slot="after_original_price">
                <el-form-item label="<?= \Yii::t('plugins/bargain', '活动库存');?>" width="120" prop="stock">
                    <label slot="label">
                        <?= \Yii::t('plugins/bargain', '活动库存');?>
                        <el-tooltip class="item" effect="dark"
                                    content="<?= \Yii::t('plugins/bargain', '活动库存会从商城对应商品的总库存中扣除');?>"
                                    placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </label>
                    <el-input required type="number" v-model.number="form.stock" placeholder="<?= \Yii::t('plugins/bargain', '请输入活动库存');?>">
                    </el-input>
                </el-form-item>
            </template>

<!--            <template slot="before_select_attr_groups">-->
<!--                <el-form-item label="商品货号">-->
<!--                    <el-input :disabled="form.use_attr == 1 || is_edit === 1 ? true : false" v-model="form.goods_no">-->
<!--                    </el-input>-->
<!--                </el-form-item>-->
<!---->
<!--                <el-form-item label="商品重量">-->
<!--                    <el-input oninput="this.value = this.value.replace(/[^0-9]/g, '');"-->
<!--                              :disabled="form.use_attr == 1 || is_edit === 1 ? true : false"-->
<!--                              v-model="form.goods_weight">-->
<!--                        <template slot="append">克</template>-->
<!--                    </el-input>-->
<!--                </el-form-item>-->
<!--            </template>-->

            <template slot="before_goods">
                <el-card shadow="never" style="margin-top: 24px;margin-bottom: 24px;">
                    <div slot="header"><?= \Yii::t('plugins/bargain', '砍价设置');?></div>
                    <el-col :xl="12" :lg="16">
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '减库存方式');?>" prop="stock_type" required>
                            <el-radio-group v-model.number="form.stock_type">
                                <el-radio :label="1"><?= \Yii::t('plugins/bargain', '参与减库存');?></el-radio>
                                <el-radio :label="2"><?= \Yii::t('plugins/bargain', '拍下减库存');?></el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '是否支持中途下单');?>" prop="type">
                            <el-radio-group v-model.number="form.type">
                                <el-radio :label="1"><?= \Yii::t('plugins/bargain', '允许中途下单');?></el-radio>
                                <el-radio :label="2"><?= \Yii::t('plugins/bargain', '必须砍到最低价下单');?></el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '指定参与人数');?>" width="120" prop="bargain_people">
                            <label slot="label">
                                <?= \Yii::t('plugins/bargain', '指定参与人数');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('plugins/bargain', '若填0，则表示不限参与人数，砍完为止');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <el-input required type="text" v-model="form.bargain_people"
                                      maxlength="9"
                                      oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                      placeholder="<?= \Yii::t('plugins/bargain', '请输入参与人数');?>"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('plugins/bargain', '砍价方式');?>" width="120">
                            <label slot="label">
                                <?= \Yii::t('plugins/bargain', '砍价方式');?>
                                <el-tooltip class="item" effect="dark"
                                            content="<?= \Yii::t('plugins/bargain', '前N个人砍价波动值，剩余价格波动值');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </label>
                            <div>
                                <el-col :span="12">
                                    <el-form-item prop="bargain_human">
                                        <el-input required type="number"

                                                  v-model.number="form.bargain_human"
                                                  placeholder="<?= \Yii::t('plugins/bargain', '砍价人数');?>">
                                            <template slot="prepend"><?= \Yii::t('plugins/bargain', '前');?></template>
                                            <template slot="append"><?= \Yii::t('plugins/bargain', '人每人砍价');?></template>
                                        </el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="6">
                                    <el-form-item prop="bargain_first_min_price">
                                        <el-input required type="number" v-model="form.bargain_first_min_price" @input="changeSalary('bargain_first_min_price')">
                                            <template slot="append">~</template>
                                        </el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="6">
                                    <el-form-item prop="bargain_first_max_price">
                                        <el-input required type="number" v-model="form.bargain_first_max_price" @input="changeSalary('bargain_first_max_price')">
                                            <template slot="append"><?= \Yii::t('plugins/bargain', '元');?></template>
                                        </el-input>
                                    </el-form-item>
                                </el-col>
                            </div>
                            <div style="clear: both;"></div>
                            <div style="margin-top: 18px;">
                                <el-col :span="12">
                                    <el-form-item prop="bargain_second_min_price">
                                        <el-input required type="number" @input="changeSalary('bargain_second_min_price')" v-model="form.bargain_second_min_price">
                                            <template slot="prepend"><?= \Yii::t('plugins/bargain', '剩余每人砍价');?></template>
                                        </el-input>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="12">
                                    <el-form-item prop="bargain_second_max_price">
                                        <el-input required type="number" @input="changeSalary('bargain_second_max_price')" v-model="form.bargain_second_max_price">
                                            <template slot="prepend">~</template>
                                            <template slot="append"><?= \Yii::t('plugins/bargain', '元');?></template>
                                        </el-input>
                                    </el-form-item>
                                </el-col>
                            </div>
                            <div style="clear: both;"></div>
                        </el-form-item>
                    </el-col>
                </el-card>
            </template>

            <template slot="preview">
                <div v-if="previewData" flex="dir:top">
                    <div class="goods" style="padding-top:12px">
                        <el-image style="margin-bottom:12px;height:40px"
                                  src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/img/4534.png"></el-image>
                        <div class="goods-name">{{previewData.name}}</div>
                        <div flex="dir:left" style="font-size:14px">
                            <div flex="dir:top" style="font-size: 10px;color:#999999">
                                <div flex="dir:left" style="margin-top:16px">
                                    <div flex="dir:left">
                                        <div><?= \Yii::t('plugins/bargain', '最低');?></div>
                                        <div style="color:#ff4544">￥</div>
                                        <div style="color:#ff4544;font-size:18px;line-height:10px">{{form.min_price}}</div>
                                    </div>
                                    <div style="margin-left: 6px;text-decoration: line-through;"><?= \Yii::t('plugins/bargain', '原价');?>￥{{previewData.original_price}}</div>
                                </div>
                                <div flex="dir:left" style="margin-top:10px">
                                    <div><?= \Yii::t('plugins/bargain', '库存');?>:{{form.stock}}</div>
                                    <div style="margin-left: 20px"><?= \Yii::t('plugins/bargain', '已有100人参与砍价');?></div>
                                </div>
                            </div>
                            <div class="share" flex="dir:top main:center cross:center">
                                <el-image src="statics/img/mall/goods/icon-share.png"></el-image>
                                <div>分享</div>
                            </div>
                        </div>
                    </div>
                    <el-image style="margin-top:12px;height:188px"
                              src="<?= \app\helpers\PluginHelper::getPluginBaseAssetsUrl() ?>/img/845.png"></el-image>
                    <div style="background: #FFFFFF">
                        <div class="bargain-time"><?= \Yii::t('plugins/bargain', '本活动开始时间');?>{{form.active_date[0]}}</div>
                        <div class="bargain-time"><?= \Yii::t('plugins/bargain', '本活动结束时间');?>{{form.active_date[1]}}</div>
                    </div>
                </div>
            </template>

        </app-goods>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                previewData: null,
                previewInfo: {
                    is_head: false,
                    is_cart: false,
                    is_attr: false,
                    is_content: false,
                },
                form: {
                    min_price: '',
                    begin_time: '',
                    end_time: '',
                    bargain_time: '',
                    bargain_people: '',
                    bargain_human: '',
                    bargain_first_min_price: '',
                    bargain_first_max_price: '',
                    bargain_second_min_price: '',
                    bargain_second_max_price: '',
                    bargain_status: 0,
                    type: 1,
                    stock: 0,
                    use_attr: 0,
                    stock_type: 1,
                    attr_setting_type: 0,
                },
                cats: [],
                attrGroups: [],
                rule: {
                    min_price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入商品最低价');?>'},
                    ],
                    bargain_time: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入砍价时间');?>'},
                    ],
                    bargain_people: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入砍价人数');?>'},
                    ],
                    bargain_human: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入前面人数');?>'},
                    ],
                    bargain_first_min_price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入前面砍价最低值');?>'},
                    ],
                    bargain_first_max_price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入前面砍价最高值');?>'},
                    ],
                    bargain_second_min_price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入后面砍价最低值');?>'},
                    ],
                    bargain_second_max_price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入后面砍价最高值');?>'},
                    ],
                    stock: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入活动库存');?>'},
                    ],
                    active_date: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入活动时间');?>'}
                    ],
                    price: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入活动售价');?>'}
                    ],
                    begin_time: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入开始时间');?>'},
                    ],
                    end_time: [
                        {required: true, message: '<?= \Yii::t('plugins/bargain', '请输入结束时间');?>'},
                    ],
                },
                is_edit: 0,
            };
        },
        created() {
            if (getQuery('id')) {
                this.is_edit = 1;
            }
        },
        methods: {
            handlePreview(e) {
                this.previewData = e;
            },
            goodsSuccess(detail) {
                this.form = Object.assign(this.form, detail.plugin);
            },
            changeSalary(key) {
                this.$nextTick(() => {
                    // 先把非数字的都替换掉(空)，除了数字和.
                    this.form[key] = this.form[key].replace(/[^\d.]/g, "");
                    // 必须保证第一个为数字而不是.
                    this.form[key] = this.form[key].replace(/^\./g, "");
                    // 保证只有出现一个.而没有多个.
                    this.form[key] =this.form[key].replace(/\.{3,}/g, "");
                    // 保证.只出现一次，而不能出现两次以上
                    this.form[key]= this.form[key]
                        .replace(".", "$#$")
                        .replace(/\./g, "")
                        .replace("$#$", ".");
                    // 限制几位小数
                    let subscript = -1;
                    for (let i in this.form[key]) {
                        if (this.form[key][i] === ".") {
                            subscript = i;
                        }
                        if (subscript !== -1) {
                            if (i - subscript > 2) {
                                this.form[key] =this.form[key].substring(0, this.form[key].length - 1);
                            }
                        }
                    }
                });
            },
        }
    });
</script>
