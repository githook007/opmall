<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/17
 * Time: 2:30 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('pick-link/app-pick-link-demo');
?>
<template id="app-coupon">
    <div style="display: inline-block">
        <app-pick-link-demo title="<?= \Yii::t('components/other', '优惠券');?>" placeholder="<?= \Yii::t('components/other', '根据ID、优惠券名称搜索');?>"
                            list-url="mall/coupon/index" :other="other"
                            :select="select"
                            @confirm="confirm" v-model="isSelect" :id="id">
            <template #table="slotProps">
                <el-table
                    ref="multipleTable"
                    :data="slotProps.table.list"
                    border
                    max-height="400"
                    style="width: 100%;margin-top: 20px"
                    v-loading="slotProps.table.listLoading">
                    <el-table-column label="ID" prop="id">
                        <template slot-scope="scope">
                            <div flex="dir:left cross:center">
                                <el-radio :label="scope.$index" v-model="id">
                                    {{scope.row.id}}
                                </el-radio>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('components/other', '优惠券名称');?>" prop="name"></el-table-column>
                    <el-table-column width='150' prop="sub_price" label="<?= \Yii::t('components/other', '优惠方式');?>">
                        <template slot-scope="scope">
                            <div v-if="scope.row.type == 2"><?= \Yii::t('components/other', '优惠');?>:{{scope.row.sub_price}}<?= \Yii::t('components/other', '元');?></div>
                            <div v-if="scope.row.type == 1">{{scope.row.discount}}<?= \Yii::t('components/other', '折');?></div>
                            <div v-if="scope.row.discount_limit && scope.row.type == 1"><?= \Yii::t('components/other', '优惠上限');?>:{{scope.row.discount_limit}}</div>
                        </template>
                    </el-table-column>
                    <el-table-column width='120' prop="sub_price" label="<?= \Yii::t('components/other', '使用范围');?>">
                        <template slot-scope="scope">
                            <span v-if="scope.row.appoint_type == 1"><?= \Yii::t('components/other', '指定商品类目');?></span>
                            <span v-if="scope.row.appoint_type == 2"><?= \Yii::t('components/other', '指定商品');?></span>
                            <span v-if="scope.row.appoint_type == 3"><?= \Yii::t('components/other', '全场通用');?></span>
                            <span v-if="scope.row.appoint_type == 4"><?= \Yii::t('components/other', '当面付');?></span>
                            <span v-if="scope.row.appoint_type == 5"><?= \Yii::t('components/other', '礼品卡');?></span>
                        </template>
                    </el-table-column>
                    <el-table-column width='170' prop="expire_type" label="<?= \Yii::t('components/other', '有效时间');?>">
                        <template slot-scope="scope">
                            <span v-if="scope.row.expire_type == 1">
                                <?= \Yii::t('components/other', '领取');?>{{scope.row.expire_day}}<?= \Yii::t('components/other', '天后过期');?>
                            </span>
                            <span v-else-if="scope.row.expire_type == 2">
                                {{scope.row.begin_time}} - {{scope.row.end_time}}
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column width='150' prop="total_count" label="<?= \Yii::t('components/other', '数量');?>">
                        <template slot-scope="scope">
                            <div v-if="scope.row.total_count == -1">
                                <div><?= \Yii::t('components/other', '总数量：无限制');?></div>
                                <div><?= \Yii::t('components/other', '剩余发放数：无限制');?></div>
                            </div>
                            <div v-else>
                                <div><?= \Yii::t('components/other', '总数量');?>：{{scope.row.count}}</div>
                                <div><?= \Yii::t('components/other', '剩余发放数');?>：{{scope.row.total_count}}</div>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </template>
        </app-pick-link-demo>
    </div>
</template>
<script>
    Vue.component('app-coupon', {
        template: '#app-coupon',
        props: {
            value: {
                type: Object | null,
                default: null
            }
        },
        data() {
            return {
                id: -1,
                select: '',
                other: {
                    is_expired: 1,
                }
            };
        },
        created() {
            this.select = this.value;
        },
        methods: {
            confirm(select) {
                this.select = {
                    show_name: select.name,
                    id: select.id
                };
                this.$emit('confirm', this.select);
            },
        },
        computed: {
            isSelect() {
                return !this.select || this.select.length === 0
            }
        }
    });
</script>

