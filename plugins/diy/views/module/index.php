<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/4/15
 * Time: 18:55
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
Yii::$app->loadViewComponent("app-market", __DIR__ . '/../template');
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" flex="dir:left" style="justify-content:space-between;">
            <span><?= \Yii::t('plugins/diy', '自定义模块');?></span>
            <app-market list-url="plugin/diy/mall/module/market-search"
                        edit-url="plugin/diy/mall/module/edit" type="">
                <el-button style="margin: -5px 0" type="primary" size="small"><?= \Yii::t('plugins/diy', '新建自定义模块');?>
                </el-button>
            </app-market>
        </div>
        <div class="table-body">
            <el-table v-loading="listLoading" :data="list" border>
                <el-table-column prop="name" label="<?= \Yii::t('plugins/diy', '名称');?>"></el-table-column>
                <el-table-column prop="useCount" label="<?= \Yii::t('plugins/diy', '被应用次数');?>"></el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('plugins/diy', '创建时间');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/diy', '操作');?>" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="text" @click="edit(scope.row.id)"
                                   size="small" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/diy', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button type="text" @click="destroy(scope.row,scope.$index)" size="small" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/diy', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>

        </div>

        <!--工具条 批量操作和分页-->
        <el-col :span="24"   flex="dir:right">
            <el-pagination
                    background
                    layout="prev, pager, next, jumper"
                    @current-change="pageChange"
                    :page-size="pagination.pageSize"
                    :total="pagination.total_count"
                    style="float:right;"
                    v-if="pagination">
            </el-pagination>
        </el-col>
    </el-card>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                listLoading: false,
                list: [],
                pagination: null,
                params: {
                    r: 'plugin/diy/mall/module/index',
                    page: 1
                }
            };
        },
        created() {
            this.getList();
        },
        methods: {
            getList() {
                this.listLoading = true;
                request({
                    params: this.params
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    }
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            edit(id) {
                navigateTo({
                    r: 'plugin/diy/mall/module/edit',
                    id: id
                })
            },
            destroy(param,index) {
                this.$confirm("<?= \Yii::t('plugins/diy', '此操作将删除该模板');?>", "<?= \Yii::t('plugins/diy', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/diy', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/diy', '取消');?>",
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    request({
                        params: {
                            r: 'plugin/diy/mall/module/destroy',
                            id: param.id
                        },
                        method: 'get'
                    }).then(e => {
                        this.listLoading = false;
                        if (e.data.code === 0) {
                            this.$message.success(e.data.msg);
                            this.list.splice(index, 1);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.listLoading = false;
                    });
                }).catch((e) => {
                    this.$message({type: 'info', message: "<?= \Yii::t('plugins/diy', '已取消删除');?>"});
                });
            },
            pageChange(page) {
                this.params.page = page;
                this.getList();
            }
        }
    });
</script>
