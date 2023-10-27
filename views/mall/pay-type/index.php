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

    .input-item {
        display: inline-block;
        width: 250px;
        margin: 0 0 20px;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus {
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input-group__append {
        background-color: #fff;
        border-left: 0;
        width: 10%;
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        padding: 0;
    }

    .input-item .el-input-group__append .el-button {
        margin: 0;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" flex="dir:left" style="justify-content:space-between;">
            <span><?= \Yii::t('mall/pay_type', 'pay_type');?></span>
            <el-button @click="$navigate({r:'mall/pay-type/edit'})" style="margin: -5px 0" type="primary"
                       size="small"><?= \Yii::t('mall/pay_type', 'add_pay_type');?>
            </el-button>
        </div>
        <div class="table-body">
            <div class="input-item">
                <el-input @keyup.enter.native="Csearch"
                          size="small"
                          placeholder="<?= \Yii::t('mall/pay_type', 'a2981');?>"
                          v-model="search.keyword"
                          clearable
                          @clear="Csearch">
                    <el-button slot="append" icon="el-icon-search" @click="Csearch"></el-button>
                </el-input>
            </div>
            <el-table v-loading="listLoading" :data="list" border>
                <el-table-column prop="name" label="<?= \Yii::t('mall/pay_type', 'pay_name');?>" width="350"></el-table-column>
                <el-table-column prop="type_text" label="<?= \Yii::t('mall/pay_type', 'pay_type');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/pay_type', 'a2984');?>" width="120" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="text" @click="edit(scope.row)"
                                   size="small" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/pay_type', 'a2985');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="destroy(scope.row,scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/pay_type', 'a2986');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <!--工具条 批量操作和分页-->
        <el-col :span="24" class="toolbar">
            <el-pagination
                    background
                    layout="prev, pager, next"
                    @current-change="pageChange"
                    :page-size="pagination.pageSize"
                    :total="pagination.total_count"
                    style="float:right;margin-bottom:15px"
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
                search: {
                    keyword: '',
                },
                listLoading: false,
                list: [],
                pagination: null,
                is_sort: -1,
                temp_sort: '',
            };
        },
        mounted() {
            this.getList();
        },
        methods: {
            pageChange(page) {
                this.page = page;
                this.getList()
            },
            Csearch() {
                this.page = 1;
                this.getList();
            },
            edit(column) {
                navigateTo({
                    r: 'mall/pay-type/edit',
                    id: column.id,
                })
            },
            getList() {
                this.listLoading = true;
                request({
                    params: {
                        r: 'mall/pay-type/index',
                        page: this.page,
                        keyword: this.search.keyword,
                    }
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    }
                }).catch(e => {
                    this.listLoading = false;
                });
            },
            destroy(params, index) {
                this.$confirm('<?= \Yii::t('mall/pay_type', 'a2987');?>?', '<?= \Yii::t('mall/pay_type', 'a2988');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/pay_type', 'a2989');?>',
                    cancelButtonText: '<?= \Yii::t('mall/pay_type', 'a2990');?>',
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    request({
                        params: {
                            r: 'mall/pay-type/delete',
                        },
                        data: {
                            id: params.id,
                        },
                        method: 'POST',
                    }).then(e => {
                        this.listLoading = false;
                        if (e.data.code === 0) {
                            this.$message.success(e.data.msg);
                            this.list.splice(index, 1);
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        this.listLoading = false;
                    });
                }).catch(() => {
                    this.$message({type: 'info', message: '<?= \Yii::t('mall/pay_type', 'a2991');?>'});
                });
            },
        }
    });
</script>
