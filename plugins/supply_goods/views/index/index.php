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
        padding-right: 50%;
    }

    .button-item {
        padding: 9px 25px;
    }
</style>

<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;" class="box-card" v-loading="cardLoading">
        <div slot="header">
            <div>
                <span><?= \Yii::t('plugins/supply_goods', '请选择角色');?></span>
            </div>
        </div>
        <el-form class="form-body" :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" size="small">
            <el-form-item label="<?= \Yii::t('plugins/supply_goods', '角色');?>" prop="ruleForm.is_open">
                <el-radio v-model="ruleForm.is_open" :label="1"><?= \Yii::t('plugins/supply_goods', '普通商户');?>
                </el-radio>
                <el-radio v-model="ruleForm.is_open" :label="2"><?= \Yii::t('plugins/supply_goods', '批发商');?>
                </el-radio>
            </el-form-item>
        </el-form>


        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('plugins/supply_goods', '确定');?></el-button>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    is_open: 1,
                },
                rules: {},
                btnLoading: false,
                cardLoading: false,
                is_show: false,
            };
        },
        methods: {
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/supply_goods/mall/index/index'
                            },
                            method: 'post',
                            data: {
                                is_open: self.ruleForm.is_open,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                window.location.href = e.data.url
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
        },
        mounted: function () {
            this.getDetail();
        }
    });
</script>
