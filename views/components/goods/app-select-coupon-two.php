<style>
    .app-select-coupon-two .el-table__header-wrapper .has-gutter tr th:first-of-type  .cell {
        display: none !important;
    }
</style>
<template id="app-select-coupon-two">
    <div class="app-select-coupon-two">
        <el-dialog title="<?= \Yii::t('components/goods', '优惠券名称');?>" :visible.sync="couponDialog" top="10vh" width="45%">
            <div class="input-item">
                <el-input @keyup.enter.native="searchCoupon" size="small" placeholder="<?= \Yii::t('components/goods', '根据名称搜索');?>"
                          v-model="search.keyword" clearable
                          @clear="searchCoupon">
                    <el-button slot="append" icon="el-icon-search" @click="searchCoupon"></el-button>
                </el-input>
            </div>
            <el-table ref="singleTable" v-loading="listLoading" :data="couponList" height="544" @selection-change="handleSelectionChange">
                <el-table-column type="selection" :selectable="selectable" width="55"></el-table-column>
                <el-table-column prop="name" label="<?= \Yii::t('components/goods', '优惠券名称');?>"></el-table-column>
                <el-table-column prop="type" label="<?= \Yii::t('components/goods', '类型');?>">
                    <template slot-scope="scope">
                        <div v-if="scope.row.type == 1"><?= \Yii::t('components/goods', '打折券');?></div>
                        <div v-if="scope.row.type == 2"><?= \Yii::t('components/goods', '满减券');?></div>
                    </template>
                </el-table-column>
                <el-table-column prop="appoint_type" label="<?= \Yii::t('components/goods', '适用范围');?>">
                    <template slot-scope="scope">
                        <span v-if="scope.row.appoint_type == 1"><?= \Yii::t('components/goods', '指定商品类目');?></span>
                        <span v-if="scope.row.appoint_type == 2"><?= \Yii::t('components/goods', '指定商品');?></span>
                        <span v-if="scope.row.appoint_type == 3"><?= \Yii::t('components/goods', '全场通用');?></span>
                        <span v-if="scope.row.appoint_type == 4"><?= \Yii::t('components/goods', '当面付');?></span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('components/goods', '优惠内容');?>">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 2 && scope.row.min_price > 0"><?= \Yii::t('components/goods', '满');?>{{scope.row.min_price}},<?= \Yii::t('components/goods', '减');?>{{scope.row.sub_price}}</span>
                        <span v-else-if="scope.row.type == 2"><?= \Yii::t('components/goods', '满');?>{{scope.row.sub_price}}</span>
                        <span v-else-if="scope.row.type == 1 && scope.row.min_price > 0"><?= \Yii::t('components/goods', '满');?>{{scope.row.min_price}},<?= \Yii::t('components/goods', '打');?>{{scope.row.discount}}<?= \Yii::t('components/goods', '折');?></span>
                        <span v-else-if="scope.row.type == 1">{{scope.row.discount}}<?= \Yii::t('components/goods', '折');?></span>
                    </template>
                </el-table-column>
                <el-table-column prop="total_count" label="<?= \Yii::t('components/goods', '剩余可领取数量');?>">
                    <template slot-scope="scope">
                        <div v-if="scope.row.total_count == -1"><?= \Yii::t('components/goods', '无限制');?></div>
                        <div v-else>{{scope.row.total_count}}</div>
                    </template>
                </el-table-column>
                <el-table-column prop="has_expire" label="<?= \Yii::t('components/goods', '状态');?>">
                    <template slot-scope="scope">
                        <div v-if="scope.row.has_expire"><?= \Yii::t('components/goods', '过期失效');?></div>
                        <div v-else><?= \Yii::t('components/goods', '有效');?></div>
                    </template>
                </el-table-column>
            </el-table>
            <!--工具条 批量操作和分页-->
            <el-col :span="24" class="toolbar">
                <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="pageChange"
                        :page-size="pagination.pageSize"
                        :total="pagination.total_count"
                        style="text-align:center;margin: 15px 0"
                        v-if="pagination">
                </el-pagination>
            </el-col>
            <div slot="footer" class="dialog-footer">
                <el-button size="small" @click="couponDialog = false"><?= \Yii::t('components/goods', '取消');?></el-button>
                <el-button size="small" type="primary" @click="couponConfirm"><?= \Yii::t('components/goods', '确定');?></el-button>
            </div>
        </el-dialog>

        <span @click="openCouponDialog">
            <slot></slot>
        </span>
    </div>
</template>

<script>
    Vue.component('app-select-coupon-two', {
        template: '#app-select-coupon-two',
        props: {
            value: {
                type: Array,
                default: () => {
                    return []
                }
            },
            hasNum: {
                type: Boolean,
                default: true,
            },
            maxNum: {
                type: Number,
                default: 999,
            },
            isJoin: Number,
        },
        data() {
            return {
                couponList: null,
                couponDialog: false,
                page: 1,
                pagination: null,
                listLoading: false,
                couponSelect: [],
                search: {
                    keyword: '',
                }
            }
        },
        methods: {
            searchCoupon() {
                this.page = 1;
                this.getList();
            },

            selectable(column) {
                let sentinel = false;
                let newCount = 0;
                let oldCount = this.value.length;
                this.couponSelect.forEach(item => {
                    newCount++;
                    if (item.id === column.id) {
                        sentinel = true;
                    }
                });
                return sentinel || this.maxNum > oldCount + newCount;
            },
            handleSelectionChange(row) {
                this.couponSelect = row;
            },

            couponConfirm() {
                this.$emit('change', this.couponSelect);
                this.closeCouponDialog();
            },

            closeCouponDialog() {
                this.couponDialog = false;
            },
            openCouponDialog() {
                if (!this.couponList) {
                    this.getList();
                }
                this.couponDialog = true;
                setTimeout(() => {
                    this.couponSelect = [];
                    this.$refs.singleTable.clearSelection();
                });
            },
            pageChange(currentPage) {
                this.page = currentPage;
                this.getList();
            },
            getList() {
                const self = this;
                self.listLoading = true;

                let extra = {}
                if (this.isJoin !== undefined) {
                    extra = Object.assign(extra, {is_join: this.isJoin})
                }

                request({
                    params: Object.assign({}, {
                        r: 'mall/coupon/index',
                        keyword: self.search.keyword,
                        page: self.page,
                    }, extra),
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.pagination = e.data.data.pagination;
                        self.couponList = e.data.data.list;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                });
            },
        },
        mounted() {
        }
    })
</script>