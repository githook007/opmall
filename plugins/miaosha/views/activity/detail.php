<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/3/7
 * Time: 11:46
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent('goods/app-batch');

?>
<style>
    .el-card__header {
        background-color: #ffffff;
    }
    .shelves {
        background-color: #f5f7fa;
        height: 50px;
        line-height: 50px;
        padding-left: 20px;
        margin-top: 20px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" v-loading="detailLoading" style="border:0;background-color: #f3f3f3;" body-style="background-color: #f3f3f3;padding: 0 0 0 0;">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer" @click="$navigate({r:'plugin/miaosha/mall/activity/index'})"><?= \Yii::t('plugins/miaosha', '秒杀活动');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('plugins/miaosha', '活动数据');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-card style="margin-top: 10px">
            <el-form size="small" :inline="true">
                <el-form-item>
                    <div flex="dir:left cross: center">
                        <span style="line-height: 76px;margin-right: 16px"><?= \Yii::t('plugins/miaosha', '商品信息');?>:</span>
                        <div flex="box:first" style="width:420px;border: 1px solid #ebeef5;height: 76px;padding:12px;line-height:14px">
                            <app-image mode="aspectFill" :src="detail.goods_cover_pic"></app-image>
                            <div style="margin-left:16px" flex="dir:top main:justify">
                                <div style="font-size: 14px;color:#606266;margin-top: 4px;-webkit-line-clamp: 2;" class="vue-line-clamp">{{detail.goods_name}}</div>
                            </div>
                        </div>
                    </div>
                </el-form-item>

                <el-form-item label="<?= \Yii::t('plugins/miaosha', '场次时间');?>" style="margin-top: 20px;">
                    <el-date-picker
                            type="datetimerange"
                            @change="toSearch"
                            prefix-icon="el-icon-date"
                            v-model="search.date_picker"
                            range-separator="<?= \Yii::t('plugins/miaosha', '至');?>"
                            value-format="yyyy-MM-dd HH:mm:ss"
                            start-placeholder="<?= \Yii::t('plugins/miaosha', '开始日期');?>"
                            end-placeholder="<?= \Yii::t('plugins/miaosha', '结束日期');?>">
                    </el-date-picker>
                </el-form-item>
            </el-form>

            <div class="shelves">
                <el-button size="mini" @click="operatingActivity(0)"><?= \Yii::t('plugins/miaosha', '上架');?></el-button>
                <el-button size="mini" @click="operatingActivity(1)"><?= \Yii::t('plugins/miaosha', '下架');?></el-button>
                <el-button size="mini" @click="operatingActivity(2)"><?= \Yii::t('plugins/miaosha', '删除');?></el-button>
            </div>
            <el-table
                    ref="multipleTable"
                    :data="list"
                    tooltip-effect="dark"
                    style="width: 100%"
                    border
                    v-loading="listLoading"
                    @selection-change="selectionChange">

                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '场次ID');?>"
                        show-overflow-tooltip
                        prop="goods_id"
                        width="80">
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '场次');?>"
                        show-overflow-tooltip
                        :render-header="render"
                        prop="date_time"
                        width="150">
                </el-table-column>

                <el-table-column
                        prop="payment_num"
                        label="<?= \Yii::t('plugins/miaosha', '秒杀件数');?>"
                        width="150"
                        show-overflow-tooltip
                >
                </el-table-column>

                <el-table-column
                        prop="payment_amount"
                        label="<?= \Yii::t('plugins/miaosha', '实付金额');?>"
                        show-overflow-tooltip>
                </el-table-column>

                <el-table-column
                        prop="activity_status"
                        label="<?= \Yii::t('plugins/miaosha', '活动状态');?>"
                        width="100"
                        show-overflow-tooltip>
                    <template slot-scope="scope">
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '未开始');?>'" type="info"><?= \Yii::t('plugins/miaosha', '未开始');?></el-tag>
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '进行中');?>'"><?= \Yii::t('plugins/miaosha', '进行中');?></el-tag>
                        <el-tag  v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '下架中');?>'" type="warning"><?= \Yii::t('plugins/miaosha', '下架中');?></el-tag>
                        <el-tag v-if="scope.row.activity_status === '<?= \Yii::t('plugins/miaosha', '已结束');?>'" type="danger"><?= \Yii::t('plugins/miaosha', '已结束');?></el-tag>
                    </template>
                </el-table-column>

                <el-table-column
                        label="<?= \Yii::t('plugins/miaosha', '操作');?>"
                        fixed="right"
                        show-overflow-tooltip>
                    <template slot-scope="scope">
                        <el-button  type="text" v-if="scope.row.is_show_edit == 1" circle size="mini" @click="edit(scope.row)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/miaosha', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="box:last cross:center" style="margin-top: 20px;">
                <div style="visibility: hidden;">
                    <el-button plain type="primary" size="small"><?= \Yii::t('plugins/miaosha', '批量操作1');?></el-button>
                    <el-button plain type="primary" size="small"><?= \Yii::t('plugins/miaosha', '批量操作2');?></el-button>
                </div>
                <div>
                    <el-pagination
                            v-if="page_count > 0"
                            @current-change="pagination"
                            background
                            layout="prev, pager, next, jumper"
                            :page-count="page_count">
                    </el-pagination>
                </div>
        </el-card>
</div>
</el-card>
</div>


<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                form: {
                },
                activity_id: -1,
                detail: {},
                page_count: 0,
                current_page: 0,
                list: [],
                page: 1,
                search: {
                    date_start: '',
                    date_end: '',
                    date_picker: []
                },
                detailLoading: false,
                listLoading: false,
                selection_list: [],
                goods_warehouse_id: 0,
            };
        },
        created() {
            this.activity_id = getQuery('id');
            this.detailLoading = true;
            this.getList().then(() => {
                this.detailLoading = false;
            });
        },
        methods: {
            toSearch() {
                this.page = 1;
                this.getList();
            },
            async getList() {
                try {
                    this.listLoading = true;
                    if (this.search.date_picker) {
                        this.search.date_start = this.search.date_picker[0];
                        this.search.date_end = this.search.date_picker[1];
                    } else {
                        this.search.date_start = null;
                        this.search.date_end = null;
                    }
                    const res = await request({
                        params: {
                            r: `plugin/miaosha/mall/activity/activity-goods`,
                            activity_id: this.activity_id,
                            page: this.page,
                            search: this.search
                        },
                        method: 'get'
                    });
                    this.listLoading = false;
                    if (res.data.code === 0) {
                        let {list, pagination, activity} = res.data.data;
                        this.detail = activity;
                        let {page_count, current_page} = pagination;
                        this.list = list;
                        this.page_count = page_count;
                        this.current_page = current_page;
                    }
                } catch (e) {
                    throw new Error(e);
                }
            },

            async operatingActivity(status) {
                if (this.selection_list.length === 0) {
                    this.$message.warning('<?= \Yii::t('plugins/miaosha', '请先勾选要设置的场次');?>');
                    return;
                }
                if (status === 0) {
                    const res = await request({
                        params: {
                            r: `plugin/miaosha/mall/goods/batch-update-status`,
                            page: this.page,
                            search: this.search
                        },
                        method: 'post',
                        data: {
                            is_all:  0,
                            batch_ids: this.selection_list,
                            status: 1,
                            plugin_sign: 'miaosha',
                            goods_warehouse_id: this.goods_warehouse_id,
                        }
                    });
                    if (res.data.code === 0) {
                        this.getList();
                    }
                } else if (status === 1) {
                    const res = await request({
                        params: {
                            r: 'plugin/miaosha/mall/goods/batch-update-status',
                            page: this.page,
                            search: this.search
                        },
                        method: 'post',
                        data: {
                            is_all:  0,
                            batch_ids: this.selection_list,
                            status: 0,
                            plugin_sign: 'miaosha',
                            goods_warehouse_id: this.goods_warehouse_id,
                        }
                    });
                    if (res.data.code === 0) {
                        this.getList();
                    }
                } else if (status === 2) {
                    const res = await request({
                        params: {
                            r: `plugin/miaosha/mall/goods/batch-miaosha-destroy`,
                            page: this.page,
                            search: this.search
                        },
                        method: 'post',
                        data: {
                            is_all:  0,
                            batch_ids: this.selection_list,
                            goods_warehouse_id: this.goods_warehouse_id,
                            plugin_sign: 'miaosha',
                        }
                    });
                    if (res.data.code === 0) {
                        this.getList();
                    }
                }
            },

            selectionChange(list) {
                this.selection_list = [];
                list.map((item) => {
                    this.selection_list.push(item.id);
                })
            },

            pagination(currentPage) {
                this.page = currentPage;
                this.getList();
            },

            edit(row) {
                navigateTo({
                    r: `plugin/miaosha/mall/activity/edit`,
                    id: row.miaosha_goods_id,
                    page: this.page,
                    activity_id: this.activity_id
                });
            },

            render(h, { column, $index },index) {
                return h('span', {}, `<?= \Yii::t('plugins/miaosha', '场次共');?>${this.detail.miaosha_count}<?= \Yii::t('plugins/miaosha', '场');?>`)
            },

        }
    });
</script>
