<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: chenzs
 */
?>

<style>

</style>

<template id="app-refuse-remark">
    <div class="app-refuse-remark">
        <!-- 备注 -->
        <el-dialog :title="title" :visible.sync="dialogVisible" width="30%" @close="closeDialog">
            <el-form>
                <el-form-item :label="content">
                    <el-input type="textarea" v-model="seller_remark" autocomplete="off"></el-input>
                </el-form-item>
                <el-form-item style="text-align: right">
                    <el-button size="small" @click="dialogVisible = false"><?= \Yii::t('plugins/invoice', '取消');?></el-button>
                    <el-button size="small" type="primary" :loading="submitLoading" @click="toSumbit"><?= \Yii::t('plugins/invoice', '确定');?>
                    </el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    Vue.component('app-refuse-remark', {
        template: '#app-refuse-remark',
        props: {
            isShow: {
                type: Boolean,
                default: false,
            },
            order: {
                type: Object,
                default: function () {
                    return {}
                }
            },
            url: {
                type: String,
                default: 'plugin/invoice/mall/applyOrder/refuse'
            }
        },
        watch: {
            isShow: function (newVal) {
                if (newVal) {
                    this.openDialog()
                }
            }
        },
        data() {
            return {
                dialogVisible: false,
                seller_remark: '',
                title: '',
                content: '<?= \Yii::t('plugins/invoice', '拒绝原因');?>',
                submitLoading: false,
            }
        },
        methods: {
            // 打开备注
            openDialog() {
                this.title = '<?= \Yii::t('plugins/invoice', '拒绝开票');?>';
                this.seller_remark = this.order.seller_remark;
                if (this.seller_remark) {
                    this.content = '<?= \Yii::t('plugins/invoice', '修改备注');?>'
                }
                this.dialogVisible = true;
            },
            closeDialog() {
                this.$emit('close')
            },
            toSumbit() {
                this.submitLoading = true;
                request({
                    params: {
                        r: this.url
                    },
                    data: {
                        id: this.order.id,
                        refusal: this.seller_remark,
                    },
                    method: 'post'
                }).then(e => {
                    this.submitLoading = false;
                    if (e.data.code === 0) {
                        this.dialogVisible = false;
                        this.$message.success(e.data.msg);
                        this.$emit('submit')
                    } else {
                        this.$message.error(e.data.msg);
                    }

                }).catch(e => {
                });
            }
        }
    })
</script>