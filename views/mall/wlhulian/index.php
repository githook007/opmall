<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/5/23
 * Time: 16:15
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
?>
<style>
    .online-pay .el-radio__label {
        vertical-align: middle;
    }

    .online-pay .label {
        width: 75px;
        margin-left: 75px;
    }

    .online-pay .item-box {
        margin-bottom: 15px;
    }

    .online-pay .qrcode-bg {
        width: 140px;
        height: 140px;
        background-image:url('statics/img/mall/wlhulian/qrcode_bg.png');
        background-size: 100% 100%;
        padding: 3px;
    }

    .online-pay .header {
        font-size: 16px;
        border-bottom: 1px solid #EBEEF5;
        padding: 5px 0 20px;
    }

    .danger {
        background-color: #fce9e6;
        width: 100%;
        border-color: #edd7d4;
        color: #e55640;
        border-radius: 2px;
        padding: 15px;
        margin-top: 20px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never">
        <el-tabs v-model="activeName" @tab-click="handleClick">
            <el-tab-pane label="<?= \Yii::t('mall/index', '基本信息');?>" name="first">
                <el-alert :closable="true" type="success" style="margin-bottom: 10px;">订单商品重量需要大于0才可以使用聚合配送</el-alert>
                <el-card shadow="never">
                    <div>
                        <div flex="dir:left ">
                            <span>当前余额：</span>
                            <div style="color: #ff4544">
                                ￥{{info.balance}}
                            </div>
                        </div>
                        <div>
                            <el-input size="small" style="width: 50%;margin-top: 10px;" type="number" v-model.trim="previewOrderDialog.pay_price" placeholder="<?= \Yii::t('admin/setting', '请输入充值金额');?>"></el-input>
                            <el-button type="primary" size="small" style="margin-left: 10px;" :loading="buyLoading"
                                       @click="previewOrder"><?= \Yii::t('mall/wlhulian', '充值');?>
                            </el-button>
                        </div>
                    </div>
                </el-card>
                <el-dialog :show-close="false" class="online-pay" :visible.sync="previewOrderDialog.visible" width="725px">
                    <template slot="title">
                        <div flex="box:last"  class="header">
                            <div style="width: 200px;">余额充值</div>
                            <div><?= \Yii::t('mall/wlhulian', '账号');?>：<?php echo Yii::$app->mall->name; ?></div>
                        </div>
                    </template>
                    <template>
                        <div flex="box:first" class="item-box">
                            <div class="label"><?= \Yii::t('mall/wlhulian', '订单编号');?></div>
                            <div>{{previewOrderDialog.order_no}}</div>
                        </div>
                        <div flex="box:first" class="item-box">
                            <div class="label"><?= \Yii::t('mall/wlhulian', '支付金额');?></div>
                            <div style="color: #ff4544;font-size: 18px;">{{previewOrderDialog.pay_price}}元</div>
                        </div>

                        <div flex="box:first" class="item-box">
                            <div class="label"><?= \Yii::t('mall/wlhulian', '支付方式');?></div>
                            <div>
                                <el-radio
                                        @change="payTypeChange"
                                        v-for="(item, index) in paySetting.pay_list"
                                        :key="index"
                                        v-model="previewOrderDialog.pay_type"
                                        :label="item">
                                    <div style="display: inline-block;">
                                        <div flex="dir:left cross:center">
                                            <img style="width: 23px;height: 20px;margin-right: 3px;" v-if="item == '<?= \Yii::t('mall/wlhulian', '微信');?>'" src="statics/img/admin/app_manage/wechat_icon.png">
                                            <img style="width: 20px;height: 20px;margin-right: 3px;" v-if="item == '<?= \Yii::t('mall/wlhulian', '支付宝');?>'" src="statics/img/admin/app_manage/alipay_icon.png">
                                            <span>{{item}}</span>
                                        </div>
                                    </div>
                                </el-radio>
                            </div>
                        </div>

                        <div flex="box:first" class="item-box">
                            <div class="label"></div>
                            <div flex="dir:left cross:center">
                                <div v-loading="previewOrderDialog.loading" class="qrcode-bg">
                                    <img style="width: 100%;height: 100%;" :src="previewOrderDialog.code_url">
                                </div>
                                <div style="margin-left: 15px;font-size: 15px;" flex="dir:top">
                                    <span><?= \Yii::t('mall/wlhulian', '打开');?>{{previewOrderDialog.pay_type}},</span>
                                    <span style="margin-top: 5px;"><?= \Yii::t('mall/wlhulian', '扫描二维码支付');?></span>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <el-button
                                    @click="hintDialogVisible = true"
                                    type="primary"
                                    size="small">
                                <?= \Yii::t('mall/wlhulian', '关闭');?>
                            </el-button>
                        </div>
                    </template>
                </el-dialog>
                <el-dialog class="hint-box" :visible.sync="hintDialogVisible" :show-close="false" width="301px">
                    <template slot="title">
                        <div flex="dir:top cross:center">
                            <img class="icon" src="statics/img/admin/app_manage/hint_icon.png">
                        </div>
                    </template>
                    <div flex="dir:top cross:center">
                        <div class="title"><?= \Yii::t('mall/wlhulian', '确定要关闭支付');?></div>
                        <div class="content"><?= \Yii::t('mall/wlhulian', '你是否要关闭支付');?></div>
                        <div class="content"><?= \Yii::t('mall/wlhulian', '别手误哦');?>~</div>
                        <div style="width: 252px;margin-top: 20px;" flex="dir:left box:mean">
                            <div style="text-align: center;"><el-button @click="cancelPayment"><?= \Yii::t('mall/wlhulian', '确认取消');?></el-button></div>
                            <div style="text-align: center;"><el-button @click="hintDialogVisible = false" type="primary"><?= \Yii::t('mall/wlhulian', '继续支付');?></el-button></div>
                        </div>
                    </div>
                </el-dialog>

                <div class="danger" v-if="info.is_audit">
                    存在审核中的运力，暂时无法修改！
                </div>
                <el-col :span="16" style="margin-top: 24px;">
                    <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" size="small">
                        <el-form-item label="<?= \Yii::t('mall/index', '联系号码');?>" prop="contact_tel">
                            <el-input v-model="ruleForm.contact_tel" :disabled="info.is_audit"></el-input>
                        </el-form-item>

                        <el-form-item label="<?= \Yii::t('mall/index', '一键导航');?>">
                            <el-form-item label="<?= \Yii::t('mall/index', '详细地址');?>" label-width="80px">
                                <el-input v-model="ruleForm.quick_map_address" :disabled="info.is_audit"
                                          placeholder="<?= \Yii::t('mall/index', '请输入详细地址');?>">
                                </el-input>
                            </el-form-item>
                            <el-form-item label-width="80px">
                                <el-input v-model="ruleForm.latitude" :disabled="info.is_audit"
                                          placeholder="<?= \Yii::t('mall/index', '请输入经度');?>">
                                    <template slot="prepend"> <?= \Yii::t('mall/logistics', '经度');?></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item label-width="80px">
                                <el-input v-model="ruleForm.longitude" :disabled="info.is_audit"
                                          placeholder="<?= \Yii::t('mall/index', '请输入纬度');?>">
                                    <template slot="prepend"> <?= \Yii::t('mall/logistics', '纬度');?></template>
                                </el-input>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('mall/index', '地图');?>"  label-width="80px" v-if="!info.is_audit">
                                <div flex="dir:left">
                                    <app-map @map-submit="mapEvent"
                                             :address="ruleForm.quick_map_address"
                                             :lat="ruleForm.latitude"
                                             :long="ruleForm.longitude">
                                        <el-button size="small"><?= \Yii::t('mall/index', '展开地图');?></el-button>
                                    </app-map>
                                </div>
                            </el-form-item>
                        </el-form-item>

                        <el-form-item label="<?= \Yii::t('admin/logistics', '行业类型');?>" prop="industry_type">
                            <el-select size="small" :disabled="info.is_audit" clearable v-model="ruleForm.industry_type" placeholder="<?= \Yii::t('admin/logistics', '请选择');?>">
                                <el-option
                                        v-for="item in industry_type"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label="<?= \Yii::t('admin/logistics', '运力集合');?>" prop="delivery_supplier_list">
                            <el-checkbox-group v-model="ruleForm.delivery_supplier_list" :disabled="info.is_audit">
                                <el-checkbox v-for="item in delivery_supplier" :label="item.deliveryCode">{{item.deliveryChannelName}}</el-checkbox>
                            </el-checkbox-group>
                        </el-form-item>

                        <el-button :loading="listLoading" class="button-item" size="small" type="primary"
                                   @click="submit('ruleForm')"><?= \Yii::t('mall/index', '保存');?>
                        </el-button>
                    </el-form>
                </el-col>
            </el-tab-pane>

            <el-tab-pane label="<?= \Yii::t('mall/index', '日志数据');?>" name="second">
                <el-col :span="24" style="margin-top: 24px;">
                    <el-select size="small" v-model="search.type" @change='searchList' class="select">
                        <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                    <el-date-picker
                            size="small"
                            v-model="search.time"
                            @change="searchList"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            type="datetimerange"
                            range-separator="<?= \Yii::t('mall/wlhulian', '至');?>"
                            start-placeholder="<?= \Yii::t('mall/wlhulian', '开始日期');?>"
                            end-placeholder="<?= \Yii::t('mall/wlhulian', '结束日期');?>"
                    ></el-date-picker>
                    <el-table :data="list" highlight-current-row v-loading="listLoading" style="margin-top: 10px;" border>
                        <el-table-column prop="id" label="ID" width="100"></el-table-column>
                        <el-table-column prop="order_no" label="操作订单号"></el-table-column>
                        <el-table-column prop="nickname" label="操作人"></el-table-column>
                        <el-table-column prop="money" label="操作金额" width="120"></el-table-column>
                        <el-table-column prop="balance" label="当前金额" width="160"></el-table-column>
                        <el-table-column prop="status" label="<?= \Yii::t('mall/wlhulian', '类型');?>" width="150">
                            <template slot-scope="scope">
                                <el-tag v-if="scope.row.type == 1" type="success">充值</el-tag>
                                <el-tag v-else-if="scope.row.type == 2" type="error">扣除</el-tag>
                                <el-tag v-else-if="scope.row.type == 3" type="primary">派单</el-tag>
                                <el-tag v-else-if="scope.row.type == 4" type="warning">退单</el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="created_at" label="操作时间" width="180"></el-table-column>
                    </el-table>

                    <div flex="dir:right" style="margin-top: 20px;">
                        <el-pagination
                                hide-on-single-page
                                @current-change="pagination"
                                background
                                layout="prev, pager, next, jumper"
                                :page-count="pageCount">
                        </el-pagination>
                    </div>
                </el-col>
            </el-tab-pane>
        </el-tabs>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            let contactTel = (rule, value, callback) => {
                let reg = /(^1\d{10}$)|(^$)|(^([0-9]{3,4}-)?\d{7,8}$)|(^400[0-9]{7}$)|(^800[0-9]{7}$)|(^(400)-(\d{3})-(\d{4})(.)(\d{1,4})$)|(^(400)-(\d{3})-(\d{4}$))/;
                if (!reg.test(this.ruleForm.contact_tel)) {
                    callback(new Error('<?= \Yii::t('mall/index', '请填写有效的联系电话或手机');?>'))
                } else {
                    callback()
                }
            };
            return {
                activeName: 'first',

                info: {},
                paySetting: {},
                hintDialogVisible: false,
                buyLoading: false,
                previewOrderDialog: {
                    visible: false,
                    loading: false,
                },

                ruleForm: {},
                industry_type: [],
                delivery_supplier: [],
                rules: {
                    contact_tel: [
                        {validator: contactTel, trigger: 'change'},
                    ],
                },

                list: [],
                listLoading: false,
                pageCount: 0,
                options: [
                    {label: "充值", value: 1},
                    {label: "扣除", value: 2},
                    {label: "派单", value: 3},
                    {label: "退单", value: 4},
                ],
                search: {
                    time: '',
                    page: 1,
                },
            };
        },
        created() {
            this.getList();
        },
        methods: {
            submit(formName) {
                this.$refs[formName].validate((valid,mes) => {
                    if (valid) {
                        this.listLoading = true;
                        request({
                            params: {
                                r: 'mall/wlhulian/save-setting',
                            },
                            method: 'post',
                            data: this.ruleForm,
                        }).then(e => {
                            this.listLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                        });
                    } else {
                        //test
                        this.$message.error(Object.values(mes).shift().shift().message);
                    }
                });
            },
            //地图确定事件
            mapEvent(e) {
                let self = this;
                self.ruleForm.longitude = e.long;
                self.ruleForm.latitude = e.lat;
                self.ruleForm.quick_map_address = e.address;
            },
            handleClick(tab, event) {
                this.getList();
            },
            searchList() {
                this.getList();
            },
            pagination(currentPage) {
                let self = this;
                self.search.page = currentPage;
                self.getList();
            },
            cancelPayment() {
                this.previewOrderDialog.visible = false;
                this.hintDialogVisible = false;
                clearInterval(this.intervalTime);
            },
            payTypeChange() {
                this.previewOrderSubmit();
            },
            previewOrder() {
                let self = this;
                if(!self.previewOrderDialog.pay_price || self.previewOrderDialog.pay_price <= 0){
                    this.$message.warning('请输入充值金额');
                    return;
                }
                if (!self.paySetting.pay_list || !self.paySetting.pay_list.length) {
                    self.$message.warning('<?= \Yii::t('mall/wlhulian', '未设置支付方式');?>');
                    return false;
                }
                self.previewOrderDialog.pay_type = self.paySetting.pay_list[0];

                self.previewOrderSubmit();
            },
            previewOrderSubmit() {
                this.buyLoading = true;
                this.previewOrderDialog.loading = true;
                this.$request({
                    method: 'post',
                    params: {
                        r: 'mall/wlhulian/preview-order',
                    },
                    data: this.previewOrderDialog
                }).then(e => {
                    this.buyLoading = false;
                    if (e.data.code === 0) {
                        this.previewOrderDialog.visible = true;
                        this.previewOrderDialog.loading = false;
                        this.previewOrderDialog.code_url = e.data.data.code_url;
                        this.previewOrderDialog.order_no = e.data.data.order_no;
                        this.queryOrder(e.data.data.order_no);
                    } else {
                        this.$alert(e.data.msg, '<?= \Yii::t('mall/wlhulian', '提示');?>', {
                            type: 'error'
                        });
                    }
                }).catch(e => {
                });
            },
            queryOrder(orderNo) {
                let self = this;
                self.intervalTime = setInterval(function() {
                    self.$request({
                        params: {
                            r: 'mall/wlhulian/query-order',
                            order_no: orderNo,
                        },
                    }).then(e => {
                        if (e.data.code === 0 && e.data.data.is_pay) {
                            clearInterval(self.intervalTime);
                            self.previewOrderDialog.visible = false;
                            self.getList();
                        }
                    }).catch(e => {
                    });
                }, 1000);
            },
            getSetting(){
                request({
                    params: {r: 'admin/setting/pay-setting'},
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        this.paySetting = e.data.data.setting;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(response => {
                    console.log(response);
                });
            },
            getList(){
                if(this.activeName === 'first'){
                    this.getSetting();
                }
                let param = Object.assign({r: 'mall/wlhulian/index', 'tab_name': this.activeName}, this.search)
                request({
                    params: param,
                    method: 'get',
                }).then(e => {
                    if (e.data.code === 0) {
                        if(this.activeName === 'first') {
                            this.ruleForm = Object.assign({}, this.ruleForm, e.data.data.list);
                            this.info = e.data.data.info;
                            this.industry_type = e.data.data.industry_type;
                            this.delivery_supplier = e.data.data.delivery_supplier;
                        }else{
                            this.list = e.data.data.list;
                            this.pageCount = e.data.data.pagination.page_count;
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(response => {
                    console.log(response);
                });
            },
        }
    });
</script>
