<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>

<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
        padding-right: 45%;
    }

    .form-button {
        margin: 0 !important;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }

    .list-box {
        width: 100%;
        border: 1px solid #e2e2e2;
    }
    .list-box .item-box {
        border: 1px solid #e2e2e2;
        padding: 10px;
        cursor: pointer;
    }
    .list-box .active {
        background-color: #e2e2e2;
    }
    .click-img {
        width: 100%;
    }
</style>
<div id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="loading">
        <div slot="header">
            <div>
                <span></span>
            </div>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>
                    <span style="color: #409EFF;cursor: pointer"
                          @click="$navigate({r:'mall/city-service/index'})"><?= \Yii::t('mall/city_service', '即时配送商家');?></span>
                </el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/city_service', '配送商家');?>{{city_service_id ? "<?= \Yii::t('mall/city_service', '编辑');?>" : "<?= \Yii::t('mall/city_service', '添加');?>"}}</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="150px">
                <el-form-item label="<?= \Yii::t('mall/city_service', '配送名称');?>" prop="name">
                    <el-input v-model="ruleForm.name" placeholder="<?= \Yii::t('mall/city_service', '请输入配送名称');?>"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/city_service', '选择配送公司');?>" prop="distribution_corporation">
                    <div class="list-box" flex="box:mean">
                        <div class="item-box"
                             @click="distributionChange(item)"
                             :class="{'active': item.value == ruleForm.distribution_corporation}"
                             v-for="(item, index) in corporation_list"
                             :key="index"
                             flex="dir:top cross:center main:center">
                            <img :src="item.icon">
                            <div>{{item.name}}</div>
                        </div>
                    </div>
                </el-form-item>
                <!-- 达达 -->
                <el-form-item v-if="ruleForm.distribution_corporation == 4" label="<?= \Yii::t('mall/city_service', '商户ID');?>" prop="shop_id">
                    <el-input v-model="ruleForm.shop_id" placeholder="<?= \Yii::t('mall/city_service', '请输入商户ID');?>"></el-input>
                </el-form-item>
                <el-form-item v-if="ruleForm.distribution_corporation != 3 && ruleForm.service_type == '第三方'" label="<?= \Yii::t('mall/city_service', '物品类目');?>" prop="product_type">
                    <el-select v-model="ruleForm.product_type" filterable placeholder="<?= \Yii::t('mall/city_service', '请选择');?>">
                        <el-option
                                v-for="item in product_list"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item v-if="ruleForm.service_type == '<?= \Yii::t('mall/city_service', '微信');?>'" label="<?= \Yii::t('mall/city_service', '物品类目');?>" prop="wx_product_type">
                    <el-cascader
                            :options="wx_product_list"
                            v-model="ruleForm.wx_product_type">
                    </el-cascader>
                </el-form-item>

                <template v-if="ruleForm.distribution_corporation == 3">
                    <el-form-item label="<?= \Yii::t('mall/city_service', '订单来源');?>" prop="outer_order_source_desc">
                        <el-select v-model="ruleForm.outer_order_source_desc" filterable placeholder="<?= \Yii::t('mall/city_service', '请选择');?>">
                            <el-option
                                    v-for="item in outer_order_source_desc_list"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="<?= \Yii::t('mall/city_service', '配送服务');?>" prop="delivery_service_code">
                        <el-select v-model="ruleForm.delivery_service_code" filterable placeholder="<?= \Yii::t('mall/city_service', '请选择');?>">
                            <el-option
                                    v-for="item in delivery_service_code_list"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </template>

                <el-form-item label="Appkey" prop="appkey">
                    <el-input v-model="ruleForm.appkey" placeholder="<?= \Yii::t('mall/city_service', '请输入');?>appkey"></el-input>
                </el-form-item>
                <el-form-item label="AppSecret" prop="appsecret">
                    <el-input v-model="ruleForm.appsecret" placeholder="<?= \Yii::t('mall/city_service', '请输入');?>appsecret"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/city_service', '商家门店编号');?>" prop="shop_no">
                    <el-input v-model="ruleForm.shop_no" placeholder="<?= \Yii::t('mall/city_service', '请输入商家门店编号');?>"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/city_service', '模拟测试');?>" prop="is_debug">
                    <el-switch
                            v-model="ruleForm.is_debug"
                            :active-value="1"
                            :inactive-value="0"
                            active-color="#13ce66"
                            inactive-color="#ff4949">
                    </el-switch>
                    <span style="color: #ff4544"><?= \Yii::t('mall/city_service', '正式环境下请关闭此开关');?></span>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/city_service', '使用第三方平台接口');?>">
                    <div flex="dir:left cross:center">
                        <div>
                            <el-radio v-model="ruleForm.service_type" label="<?= \Yii::t('mall/city_service', '第三方');?>"><?= \Yii::t('mall/city_service', '配送公司自带接口');?></el-radio>
                        </div>
                        <div flex="dir:top" style="position: relative;margin-left: 30px;">
                            <el-radio v-model="ruleForm.service_type" label="<?= \Yii::t('mall/city_service', '微信');?>"><?= \Yii::t('mall/city_service', '腾讯即时配送接口');?></el-radio>
                            <span style="color: #409EFF;cursor: pointer;position: absolute;top: 20px;left: 24px;" @click="dialogImg = true"><?= \Yii::t('mall/city_service', '查看图例');?></span>
                        </div>
                    </div>
                </el-form-item>
            </el-form>
        </div>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('mall/city_service', '保存');?>
        </el-button>
    </el-card>

    <!-- 查看图例 -->
    <el-dialog :visible.sync="dialogImg" width="65%" class="open-img">
        <div style="padding-bottom: 20px; font-size: 20px;"><?= \Yii::t('mall/city_service', '查看腾讯即时配送接口图例');?></div>
        <img src="statics/img/mall/city_service/example.png" class="click-img" alt="">
    </el-dialog>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    name: '',
                    distribution_corporation: 1,
                    appkey: '',
                    appsecret: '',
                    shop_no: '',
                    service_type: '<?= \Yii::t('mall/city_service', '第三方');?>',
                    shop_id: '',
                    product_type: '',
                    wx_product_type: '',
                    outer_order_source_desc: '',
                    delivery_service_code: '',
                    is_debug: 0
                },
                rules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请输入配送名称');?>', trigger: 'change'},
                    ],
                    distribution_corporation: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请选择配送公司');?>', trigger: 'change'},
                    ],
                    appkey: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请输入');?>Appkey', trigger: 'change'},
                    ],
                    appsecret: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请输入');?>AppSecret', trigger: 'change'},
                    ],
                    shop_no: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请输入商家门店编号');?>', trigger: 'change'},
                    ],
                    shop_id: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '请输入商户ID');?>', trigger: 'change'},
                    ],
                    product_type: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '物品类目');?>', trigger: 'change'},
                    ],
                    wx_product_type: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '物品类目');?>', trigger: 'change'},
                    ],
                    outer_order_source_desc: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '订单来源');?>', trigger: 'change'},
                    ],
                    delivery_service_code: [
                        {required: true, message: '<?= \Yii::t('mall/city_service', '配送服务');?>', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                loading: false,
                corporation_list: [],
                city_service_id: null,
                // 查看图例
                dialogImg: false,
                product_list:[],
                sf_product_list: [
                    {value: 1, label: '<?= \Yii::t('mall/city_service', '快餐');?>'},
                    {value: 2, label: '<?= \Yii::t('mall/city_service', '送药');?>'},
                    {value: 3, label: '<?= \Yii::t('mall/city_service', '百货');?>'},
                    {value: 4, label: '<?= \Yii::t('mall/city_service', '脏衣服收');?>'},
                    {value: 5, label: '<?= \Yii::t('mall/city_service', '干净衣服派');?>'},
                    {value: 6, label: '<?= \Yii::t('mall/city_service', '生鲜');?>'},
                    {value: 7, label: '<?= \Yii::t('mall/city_service', '保单');?>'},
                    {value: 8, label: '<?= \Yii::t('mall/city_service', '高端饮品');?>'},
                    {value: 9, label: '<?= \Yii::t('mall/city_service', '现场勘验');?>'},
                    {value: 10, label: '<?= \Yii::t('mall/city_service', '快递');?>'},
                    {value: 12, label: '<?= \Yii::t('mall/city_service', '文件');?>'},
                    {value: 13, label: '<?= \Yii::t('mall/city_service', '蛋糕');?>'},
                    {value: 14, label: '<?= \Yii::t('mall/city_service', '鲜花');?>'},
                    {value: 15, label: '<?= \Yii::t('mall/city_service', '电子数码');?>'},
                    {value: 16, label: '<?= \Yii::t('mall/city_service', '服装鞋帽');?>'},
                    {value: 17, label: '<?= \Yii::t('mall/city_service', '汽车配件');?>'},
                    {value: 18, label: '<?= \Yii::t('mall/city_service', '珠宝');?>'},
                    {value: 20, label: '<?= \Yii::t('mall/city_service', '披萨');?>'},
                    {value: 21, label: '<?= \Yii::t('mall/city_service', '中餐');?>'},
                    {value: 22, label: '<?= \Yii::t('mall/city_service', '水产');?>'},
                    {value: 27, label: '<?= \Yii::t('mall/city_service', '专人直送');?>'},
                    {value: 32, label: '<?= \Yii::t('mall/city_service', '中端饮品');?>'},
                    {value: 33, label: '<?= \Yii::t('mall/city_service', '便利店');?>'},
                    {value: 34, label: '<?= \Yii::t('mall/city_service', '面包糕点');?>'},
                    {value: 35, label: '<?= \Yii::t('mall/city_service', '火锅');?>'},
                    {value: 36, label: '<?= \Yii::t('mall/city_service', '证照');?>'},
                    {value: 99, label: '<?= \Yii::t('mall/city_service', '其他');?>'},
                ],
                dada_product_list: [
                    {value: 1, label: '<?= \Yii::t('mall/city_service', '食品小吃');?>'},
                    {value: 2, label: '<?= \Yii::t('mall/city_service', '饮料');?>'},
                    {value: 3, label: '<?= \Yii::t('mall/city_service', '鲜花');?>'},
                    {value: 8, label: '<?= \Yii::t('mall/city_service', '文印票务');?>'},
                    {value: 9, label: '<?= \Yii::t('mall/city_service', '便利店');?>'},
                    {value: 13, label: '<?= \Yii::t('mall/city_service', '水果生鲜');?>'},
                    {value: 19, label: '<?= \Yii::t('mall/city_service', '同城电商');?>'},
                    {value: 20, label: '<?= \Yii::t('mall/city_service', '医药');?>'},
                    {value: 21, label: '<?= \Yii::t('mall/city_service', '蛋糕');?>'},
                    {value: 24, label: '<?= \Yii::t('mall/city_service', '酒品');?>'},
                    {value: 25, label: '<?= \Yii::t('mall/city_service', '小商品市场');?>'},
                    {value: 26, label: '<?= \Yii::t('mall/city_service', '服装');?>'},
                    {value: 27, label: '<?= \Yii::t('mall/city_service', '汽修零配');?>'},
                    {value: 28, label: '<?= \Yii::t('mall/city_service', '数码');?>'},
                    {value: 29, label: '<?= \Yii::t('mall/city_service', '小龙虾');?>'},
                    {value: 51, label: '<?= \Yii::t('mall/city_service', '火锅');?>'},
                    {value: 5, label: '<?= \Yii::t('mall/city_service', '其他');?>'},
                ],
                ss_product_list: [
                    {value: 1, label: '<?= \Yii::t('mall/city_service', '文件广告');?>'},
                    {value: 3, label: '<?= \Yii::t('mall/city_service', '电子产品');?>'},
                    {value: 5, label: '<?= \Yii::t('mall/city_service', '蛋糕');?>'},
                    {value: 6, label: '<?= \Yii::t('mall/city_service', '快餐水果');?>'},
                    {value: 7, label: '<?= \Yii::t('mall/city_service', '鲜花绿植');?>'},
                    {value: 8, label: '<?= \Yii::t('mall/city_service', '海鲜水产');?>'},
                    {value: 9, label: '<?= \Yii::t('mall/city_service', '汽车配件');?>'},
                    {value: 10, label: '<?= \Yii::t('mall/city_service', '其他');?>'},
                    {value: 11, label: '<?= \Yii::t('mall/city_service', '宠物');?>'},
                    {value: 12, label: '<?= \Yii::t('mall/city_service', '母婴');?>'},
                    {value: 13, label: '<?= \Yii::t('mall/city_service', '医药健康');?>'},
                    {value: 14, label: '<?= \Yii::t('mall/city_service', '教育');?>'},
                ],
                wx_product_list: [
                    {
                        value: '<?= \Yii::t('mall/city_service', '美食夜宵');?>',
                        label: '<?= \Yii::t('mall/city_service', '美食夜宵');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '零食小吃');?>',
                                label: '<?= \Yii::t('mall/city_service', '零食小吃');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '香锅');?>',
                                label: '<?= \Yii::t('mall/city_service', '香锅');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '西餐');?>',
                                label: '<?= \Yii::t('mall/city_service', '西餐');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '日韩料理');?>',
                                label: '<?= \Yii::t('mall/city_service', '日韩料理');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '海鲜');?>',
                                label: '<?= \Yii::t('mall/city_service', '海鲜');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '快餐');?>',
                                label: '<?= \Yii::t('mall/city_service', '快餐');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '小龙虾');?>',
                                label: '<?= \Yii::t('mall/city_service', '小龙虾');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '披萨');?>',
                                label: '<?= \Yii::t('mall/city_service', '披萨');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '甜品饮料');?>',
                        label: '<?= \Yii::t('mall/city_service', '甜品饮料');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '甜品');?>',
                                label: '<?= \Yii::t('mall/city_service', '甜品');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '奶茶果汁');?>',
                                label: '<?= \Yii::t('mall/city_service', '奶茶果汁');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '咖啡');?>',
                                label: '<?= \Yii::t('mall/city_service', '咖啡');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '面包');?>',
                                label: '<?= \Yii::t('mall/city_service', '面包');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '冰淇淋');?>',
                                label: '<?= \Yii::t('mall/city_service', '冰淇淋');?>',
                            }
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '蛋糕');?>',
                        label: '<?= \Yii::t('mall/city_service', '蛋糕');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '蛋糕');?>',
                                label: '<?= \Yii::t('mall/city_service', '蛋糕');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '日用百货');?>',
                        label: '<?= \Yii::t('mall/city_service', '日用百货');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '便利店');?>',
                                label: '<?= \Yii::t('mall/city_service', '便利店');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '水站');?>',
                                label: '<?= \Yii::t('mall/city_service', '水站');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '零食');?>',
                                label: '<?= \Yii::t('mall/city_service', '零食');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '五金日用');?>',
                                label: '<?= \Yii::t('mall/city_service', '五金日用');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '粮油调味');?>',
                                label: '<?= \Yii::t('mall/city_service', '粮油调味');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '文具店');?>',
                                label: '<?= \Yii::t('mall/city_service', '文具店');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '酒水行');?>',
                                label: '<?= \Yii::t('mall/city_service', '酒水行');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '地方特产');?>',
                                label: '<?= \Yii::t('mall/city_service', '地方特产');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '进口食品');?>',
                                label: '<?= \Yii::t('mall/city_service', '进口食品');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '宠物用品');?>',
                                label: '<?= \Yii::t('mall/city_service', '宠物用品');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '超市');?>',
                                label: '<?= \Yii::t('mall/city_service', '超市');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '书店');?>',
                                label: '<?= \Yii::t('mall/city_service', '书店');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '宠物食品用品');?>',
                                label: '<?= \Yii::t('mall/city_service', '宠物食品用品');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '办公家居用品');?>',
                                label: '<?= \Yii::t('mall/city_service', '办公家居用品');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '果蔬生鲜');?>',
                        label: '<?= \Yii::t('mall/city_service', '果蔬生鲜');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '果蔬');?>',
                                label: '<?= \Yii::t('mall/city_service', '果蔬');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '海鲜水产');?>',
                                label: '<?= \Yii::t('mall/city_service', '海鲜水产');?>',
                            },{
                                value: '<?= \Yii::t('mall/city_service', '冷冻速食');?>',
                                label: '<?= \Yii::t('mall/city_service', '冷冻速食');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '鲜花');?>',
                        label: '<?= \Yii::t('mall/city_service', '鲜花');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '鲜花');?>',
                                label: '<?= \Yii::t('mall/city_service', '鲜花');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '医药健康');?>',
                        label: '<?= \Yii::t('mall/city_service', '医药健康');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '送药');?>',
                                label: '<?= \Yii::t('mall/city_service', '送药');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '器材器具');?>',
                                label: '<?= \Yii::t('mall/city_service', '器材器具');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '美妆护肤');?>',
                        label: '<?= \Yii::t('mall/city_service', '美妆护肤');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '日化美妆');?>',
                                label: '<?= \Yii::t('mall/city_service', '日化美妆');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '母婴');?>',
                        label: '<?= \Yii::t('mall/city_service', '母婴');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '孕婴用品');?>',
                                label: '<?= \Yii::t('mall/city_service', '孕婴用品');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '文件或票务');?>',
                        label: '<?= \Yii::t('mall/city_service', '文件或票务');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '保单');?>',
                                label: '<?= \Yii::t('mall/city_service', '保单');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '票务文件');?>',
                                label: '<?= \Yii::t('mall/city_service', '票务文件');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '政府文件');?>',
                                label: '<?= \Yii::t('mall/city_service', '政府文件');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '证件');?>',
                                label: '<?= \Yii::t('mall/city_service', '证件');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '服饰鞋帽');?>',
                        label: '<?= \Yii::t('mall/city_service', '服饰鞋帽');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '服饰鞋帽综合');?>',
                                label: '<?= \Yii::t('mall/city_service', '服饰鞋帽综合');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '洗涤');?>',
                        label: '<?= \Yii::t('mall/city_service', '洗涤');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '脏衣服收');?>',
                                label: '<?= \Yii::t('mall/city_service', '脏衣服收');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '干净衣服派');?>',
                                label: '<?= \Yii::t('mall/city_service', '干净衣服派');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '珠宝奢侈品');?>',
                        label: '<?= \Yii::t('mall/city_service', '珠宝奢侈品');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '珠宝饰品');?>',
                                label: '<?= \Yii::t('mall/city_service', '珠宝饰品');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '奢侈品');?>',
                                label: '<?= \Yii::t('mall/city_service', '奢侈品');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '家居家装');?>',
                        label: '<?= \Yii::t('mall/city_service', '家居家装');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '家具');?>',
                                label: '<?= \Yii::t('mall/city_service', '家具');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '装修建材');?>',
                                label: '<?= \Yii::t('mall/city_service', '装修建材');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '厨房卫浴');?>',
                                label: '<?= \Yii::t('mall/city_service', '厨房卫浴');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '数码产品');?>',
                        label: '<?= \Yii::t('mall/city_service', '数码产品');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '数码产品');?>',
                                label: '<?= \Yii::t('mall/city_service', '数码产品');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '配件器材');?>',
                        label: '<?= \Yii::t('mall/city_service', '配件器材');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '配件器材');?>',
                                label: '<?= \Yii::t('mall/city_service', '配件器材');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '电商');?>',
                        label: '<?= \Yii::t('mall/city_service', '电商');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '电视购物');?>',
                                label: '<?= \Yii::t('mall/city_service', '电视购物');?>',
                            },
                            {
                                value: '<?= \Yii::t('mall/city_service', '线上商城');?>',
                                label: '<?= \Yii::t('mall/city_service', '线上商城');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '现场勘查');?>',
                        label: '<?= \Yii::t('mall/city_service', '现场勘查');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '现场勘查');?>',
                                label: '<?= \Yii::t('mall/city_service', '现场勘查');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '快递业务');?>',
                        label: '<?= \Yii::t('mall/city_service', '快递业务');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '快递配送');?>',
                                label: '<?= \Yii::t('mall/city_service', '快递配送');?>',
                            },
                        ]
                    },
                    {
                        value: '<?= \Yii::t('mall/city_service', '其他');?>',
                        label: '<?= \Yii::t('mall/city_service', '其他');?>',
                        children: [
                            {
                                value: '<?= \Yii::t('mall/city_service', '其他');?>',
                                label: '<?= \Yii::t('mall/city_service', '其他');?>',
                            },
                        ]
                    },
                ],
                outer_order_source_desc_list: [
                    { value:101, label: '<?= \Yii::t('mall/city_service', '美团');?>' },
                    { value:102, label: '<?= \Yii::t('mall/city_service', '饿了么');?>' },
                    { value:103, label: '<?= \Yii::t('mall/city_service', '京东到家');?>' },
                    { value:201, label: '<?= \Yii::t('mall/city_service', '商家web网站');?>' },
                    { value:202, label: '<?= \Yii::t('mall/city_service', '商家小程序微信');?>' },
                    { value:203, label: '<?= \Yii::t('mall/city_service', '商家小程序');?>' },
                    { value:204, label: '<?= \Yii::t('mall/city_service', '商家APP');?>' },
                    { value:205, label: '<?= \Yii::t('mall/city_service', '商家热线');?>' },
                    { value:'其它', label: '<?= \Yii::t('mall/city_service', '其它');?>' },
                ],
                delivery_service_code_list: [
                    { value: 4002, label: '<?= \Yii::t('mall/city_service', '飞速达');?>' },
                    { value: 4011, label: '<?= \Yii::t('mall/city_service', '快速达');?>' },
                    { value: 4012, label: '<?= \Yii::t('mall/city_service', '及时达');?>' },
                    { value: 4013, label: '<?= \Yii::t('mall/city_service', '集中送');?>' },
                    { value: 4031, label: '<?= \Yii::t('mall/city_service', '跑腿');?>' },
                    { value: 100001, label: '<?= \Yii::t('mall/city_service', '光速达');?>-45' },
                    { value: 100000, label: '<?= \Yii::t('mall/city_service', '光速达');?>-40' },
                    { value: 100002, label: '<?= \Yii::t('mall/city_service', '光速达');?>-50' },
                    { value: 100003, label: '<?= \Yii::t('mall/city_service', '光速达');?>-55' },
                    { value: 100004, label: '<?= \Yii::t('mall/city_service', '快速达');?>-7590' },
                    { value: 100005, label: '<?= \Yii::t('mall/city_service', '快速达');?>-6090' },
                    { value: 100006, label: '<?= \Yii::t('mall/city_service', '及时达');?>' },
                ]
            };
        },
        methods: {
            store(formName) {
                let self = this;
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/city-service/edit'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'mall/city-service/index'
                                })
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.loading = true;
                request({
                    params: {
                        r: 'mall/city-service/edit',
                        id: getQuery('id')
                    },
                    method: 'get',
                }).then(e => {
                    self.loading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.city_service;
                        this.checkDistribution()
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            getOption() {
                let self = this;
                request({
                    params: {
                        r: 'mall/city-service/option',
                    },
                    method: 'get',
                }).then(e => {
                    if (e.data.code == 0) {
                        self.corporation_list = e.data.data.corporation_list;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            distributionChange(item) {
                if (this.ruleForm.distribution_corporation != item.value) {
                    this.ruleForm.product_type = '';
                }

                this.ruleForm.distribution_corporation = item.value
                this.checkDistribution()
            },
            checkDistribution() {
                if (this.ruleForm.distribution_corporation == 1) {
                    this.product_list = this.sf_product_list;
                }
                if (this.ruleForm.distribution_corporation == 2) {
                    this.product_list = this.ss_product_list;
                }
                if (this.ruleForm.distribution_corporation == 4) {
                    this.product_list = this.dada_product_list;
                }
            }
        },
        mounted: function () {
            this.getOption();
            this.checkDistribution()
            if (getQuery('id')) {
                this.city_service_id = getQuery('id');
                this.getDetail();
            }
        }
    });
</script>
