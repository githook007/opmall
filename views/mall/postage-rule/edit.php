<?php
/**
 * @link:https://www.opmall.com/
 * @copyright: Copyright (c) 2018 hook007
 *
 * Created by PhpStorm.
 * User: opmall
 * Date: 2018/11/30
 * Time: 16:16
 */
?>
<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-button {
        margin: 0;
    }

    .form-button .el-form-item__content {
        margin-left: 0!important;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" v-loading="listLoading" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'mall/index/rule',tab: 'second'})"><?= \Yii::t('mall/postage_rule', '运费规则');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/postage_rule', '添加规则');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px"
                 class="demo-ruleForm" position-label="right">
            <div class="form-body">
                <el-form-item label="<?= \Yii::t('mall/postage_rule', '规则名称');?>" prop="name">
                    <el-input size="small" style="width: 650px" v-model="ruleForm.name"></el-input>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/postage_rule', '计费方式');?>" prop="type">
                    <el-radio name="type" :label="1" v-model="ruleForm.type"><?= \Yii::t('mall/postage_rule', '按重计费');?></el-radio>
                    <el-radio name="type" :label="2" v-model="ruleForm.type"><?= \Yii::t('mall/postage_rule', '按件计费');?></el-radio>
                </el-form-item>
                <el-form-item label="<?= \Yii::t('mall/postage_rule', '运费规则');?>" prop="detail" required>
                    <el-card shadow="never" style="margin-bottom: 12px;width: 650px" v-if="ruleForm.detail"
                             v-for="(item, index) in ruleForm.detail" :key="item.id">
                        <div flex="dir:left box:last">
                            <div>
                                <div>
                                    <el-breadcrumb separator="/">
                                        <el-breadcrumb-item><?= \Yii::t('mall/postage_rule', '首重');?>:{{item.first}}</el-breadcrumb-item>
                                        <el-breadcrumb-item><?= \Yii::t('mall/postage_rule', '首费');?>:{{item.firstPrice}}</el-breadcrumb-item>
                                        <el-breadcrumb-item><?= \Yii::t('mall/postage_rule', '续重');?>:{{item.second}}</el-breadcrumb-item>
                                        <el-breadcrumb-item><?= \Yii::t('mall/postage_rule', '续费');?>:{{item.secondPrice}}</el-breadcrumb-item>
                                    </el-breadcrumb>
                                </div>
                                <div flex="dir:left" style="flex-wrap: wrap">
                                    <div><?= \Yii::t('mall/postage_rule', '区域');?></div>
                                    <el-tag type="info" style="margin: 5px;border:0" v-for="(value, key) in item.list">{{value.name}}</el-tag>
                                </div>
                            </div>
                            <div style="text-align: right">
                                <el-button type="text" @click="editDistrict(item)" size="small" circle>
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/postage_rule', '编辑');?>" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button style="margin-left: 0" type="text" @click="deleteDetail(index)" size="small" circle>
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/postage_rule', '删除');?>" placement="top">
                                        <img src="statics/img/mall/del.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </div>
                        </div>
                    </el-card>
                    <el-button type="text" @click="openDistrict"><i class="el-icon-plus"><?= \Yii::t('mall/postage_rule', '新增规则');?></i></el-button>
                </el-form-item>
            </div>
            <el-form-item class="form-button">
                <el-button sizi="mini" class="button-item" :loading="submitLoading" type="primary" @click="onSubmit('ruleForm')">
                    <?= \Yii::t('mall/postage_rule', '保存');?>
                </el-button>
                <el-button sizi="mini" class="button-item" @click="Cancel">
                    <?= \Yii::t('mall/postage_rule', '取消');?>
                </el-button>
            </el-form-item>
        </el-form>
    </el-card>
    <el-dialog :title="title" :visible.sync="dialogVisible" width="50%">
        <div style="margin-bottom: 1rem;">
            <el-form :model="detail" ref="detail" label-width="100px" :rules="detailRules">
                <div flex="dir:left box:mean">
                    <div style="padding: 0 1rem;">
                        <el-form-item prop="first" :label="ruleForm.type == 1 ? '<?= \Yii::t('mall/postage_rule', '首重克');?>' : '<?= \Yii::t('mall/postage_rule', '首件个');?>'">
                            <el-input v-model.number="detail.first" min="0" type="number"></el-input>
                        </el-form-item>
                    </div>
                    <div style="padding: 0 1rem;">
                        <el-form-item prop="firstPrice" label="<?= \Yii::t('mall/postage_rule', '首费元');?>">
                            <el-input v-model.number="detail.firstPrice" min="0" type="number"></el-input>
                        </el-form-item>
                    </div>
                </div>
                <div flex="dir:left box:mean">
                    <div style="padding: 0 1rem;">
                        <el-form-item prop="second" :label="ruleForm.type == 1 ? '<?= \Yii::t('mall/postage_rule', '续重克');?>' : '<?= \Yii::t('mall/postage_rule', '续件个');?>'">
                            <el-input v-model.number="detail.second" min="0" type="number"></el-input>
                        </el-form-item>
                    </div>
                    <div style="padding: 0 1rem;">
                        <el-form-item prop="secondPrice" label="<?= \Yii::t('mall/postage_rule', '续费元');?>">
                            <el-input v-model.number="detail.secondPrice" min="0" type="number"></el-input>
                        </el-form-item>
                    </div>
                </div>
                <el-form-item prop="list" label="<?= \Yii::t('mall/postage_rule', '地区选择');?>">
                    <app-district :detail="ruleForm.detail" :edit="detail.list" @selected="selectDistrict" :level="3"></app-district>
                </el-form-item>
                <div style="text-align: right;margin-top: 1rem;">
                    <el-button type="primary" @click="districtConfirm('detail')">
                        <?= \Yii::t('mall/postage_rule', '确定选择');?>
                    </el-button>
                </div>
            </el-form>
        </div>
    </el-dialog>
</div>
<style>
    .el-dialog {
        min-width: 900px;
    }
</style>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            let min_number = (rule, value, callback) => {
                if (value < 0) {
                    callback(new Error('<?= \Yii::t('mall/postage_rule', '输入值必须大于0');?>'));
                } else {
                    callback();
                }
            };
            return {
                ruleForm: {
                    name: '',
                    type: -1,
                    detail: []
                },
                submitLoading: false,
                rules: {
                    name: [
                        {
                            required: true,
                            message: '<?= \Yii::t('mall/postage_rule', '请输入规则名称');?>',
                            trigger: 'blur'
                        }
                    ],
                    detail: [
                        {
                            required: true,
                            message: '<?= \Yii::t('mall/postage_rule', '请输入运费规则');?>'
                        }
                    ]
                },
                listLoading: false,
                dialogVisible: false,
                is_add: false,
                title: '<?= \Yii::t('mall/postage_rule', '新增规则');?>',
                detail: {
                    first: 0,
                    firstPrice: 0,
                    second: 0,
                    secondPrice: 0,
                    list: []
                },
                detailRules: {
                    first: [
                        {
                            validator: min_number,
                            trigger: 'blur'
                        },
                        {
                            type: 'number',
                            message: '<?= \Yii::t('mall/postage_rule', '请输入数字');?>',
                            trigger: 'blur',
                            required: true
                        }
                    ],
                    firstPrice: [
                        {
                            validator: min_number,
                            trigger: 'blur'
                        },
                        {
                            type: 'number',
                            message: '<?= \Yii::t('mall/postage_rule', '请输入数字');?>',
                            trigger: 'blur',
                            required: true
                        }
                    ],
                    second: [
                        {
                            validator: min_number,
                            trigger: 'blur'
                        },
                        {
                            type: 'number',
                            message: '<?= \Yii::t('mall/postage_rule', '请输入数字');?>',
                            trigger: 'blur',
                            required: true
                        }
                    ],
                    secondPrice: [
                        {
                            validator: min_number,
                            trigger: 'blur'
                        },
                        {
                            type: 'number',
                            message: '<?= \Yii::t('mall/postage_rule', '请输入数字');?>',
                            trigger: 'blur',
                            required: true
                        }
                    ],
                    list: [
                        {
                            type: 'array',
                            message: '<?= \Yii::t('mall/postage_rule', '请选择地区');?>',
                            trigger: 'blur',
                            required: true
                        }
                    ]
                }
            }
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getDetail(getQuery('id'));
            } else {
                this.ruleForm.type = 1;
            }
        },
        watch: {
            newType(val, oldVal) {
                if (oldVal == -1) {

                } else {
                    this.ruleForm.detail.splice(0, this.ruleForm.detail.length);
                }
            }
        },
        computed: {
            newType() {
                return this.ruleForm.type;
            }
        },
        methods: {
            Cancel(){
                window.history.go(-1)
            },

            onSubmit(formName) {
                this.submitLoading = true;
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        request({
                            params: {
                                r: 'mall/postage-rule/edit',
                                id: getQuery('id')
                            },
                            method: 'post',
                            data: {
                                form: this.ruleForm
                            }
                        }).then(e => {
                            this.submitLoading = false;
                            if (e.data.code == 0) {
                                this.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'mall/index/rule',
                                    tab: 'second'
                                })
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.submitLoading = false;
                            this.$message.error(e.data.msg);
                        });
                    } else {
                        console.log('error submit!!');
                        this.submitLoading = false;
                        return false;
                    }
                });
            },
            openDistrict() {
                this.title = '<?= \Yii::t('mall/postage_rule', '新增规则');?>';
                this.dialogVisible = true;
                this.detail = {
                    first: 0,
                    firstPrice: 0,
                    second: 0,
                    secondPrice: 0,
                    list: []
                };
                this.is_add = true;
            },
            editDistrict(row) {
                this.title = '<?= \Yii::t('mall/postage_rule', '编辑规则');?>';
                this.dialogVisible = true;
                this.detail = row;
                this.is_add = false;
            },
            deleteDetail(index) {
                this.ruleForm.detail.splice(index, 1);
            },
            selectDistrict(e) {
                let list = [];
                for (let i in e) {
                    let obj = {
                        id: e[i].id,
                        name: e[i].name
                    };
                    list.push(obj);
                }
                this.detail.list = list;
            },
            districtConfirm(formName) {
                this.$refs[formName].validate((valid) => {
                    if (valid) {
                        if (this.is_add) {
                            this.ruleForm.detail.push(this.detail);
                        }
                        this.dialogVisible = false;
                        this.$refs['ruleForm'].validate((valid) => {
                            return false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail(id = 0) {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'mall/postage-rule/edit',
                        id: id
                    },
                    method: 'get'
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = Object.assign(self.ruleForm, e.data.data.model);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.listLoading = false;
                });
            }
        }
    });
</script>
