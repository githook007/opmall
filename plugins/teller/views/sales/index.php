<style>
    .input-item {
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

    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header" flex="dir:left" style="justify-content:space-between;">
            <span><?= \Yii::t('plugins/teller', '导购员');?></span>
            <el-button @click="$navigate({r:'plugin/teller/mall/sales/detail'})" style="margin: -5px 0" type="primary"
                       size="small"><?= \Yii::t('plugins/teller', '添加导购员');?>
            </el-button>
        </div>
        <div class="table-body">
            <!--工具条 过滤表单和新增按钮-->
            <el-col :span="24" class="toolbar" style="padding-bottom: 0px">
                <el-select size="small" @change="searchList" v-model="search.store_id" placeholder="<?= \Yii::t('plugins/teller', '请选择门店');?>">
                    <el-option value="" label="<?= \Yii::t('plugins/teller', '全部门店');?>"></el-option>
                    <el-option
                            v-for="(item, index) in storeList"
                            :key="index"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
                <div class="input-item" style="display:inline-block;margin-left: 12px">
                    <el-input @keyup.enter.native="searchList"
                              size="small"
                              placeholder="<?= \Yii::t('plugins/teller', '请输入导购员编号');?>"
                              v-model="search.keyword"
                              clearable
                              @clear="searchList">
                        <el-button slot="append" icon="el-icon-search" @click="searchList"></el-button>
                    </el-input>
                </div>
            </el-col>
            <!-- 列表 -->
            <el-table v-loading="listLoading" :data="list" border>
                <el-table-column prop="number" label="<?= \Yii::t('plugins/teller', '导购员编号');?>" width="100"></el-table-column>
                <el-table-column prop="name" label="<?= \Yii::t('plugins/teller', '姓名');?>"></el-table-column>
                <el-table-column prop="head_url" label="<?= \Yii::t('plugins/teller', '头像');?>" width="100">
                    <template slot-scope="scope">
                        <app-image :src="scope.row.head_url"></app-image>
                    </template>
                </el-table-column>
                <el-table-column prop="mobile" label="<?= \Yii::t('plugins/teller', '电话');?>" width="180"></el-table-column>
                <el-table-column prop="creator_name" label="<?= \Yii::t('plugins/teller', '创建人');?>"></el-table-column>
                <el-table-column prop="store_name" label="<?= \Yii::t('plugins/teller', '所属门店');?>"></el-table-column>
                <el-table-column prop="sale_money" label="<?= \Yii::t('plugins/teller', '业绩');?>">
                    <template slot="header" slot-scope="scope">
                        <span><?= \Yii::t('plugins/teller', '业绩');?></span>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/teller', '销售额');?>" placement="top">
                            <i class="el-icon-info"></i>
                        </el-tooltip>
                    </template>
                    <template slot-scope="scope">
                        <span>{{scope.row.sale_money}}/{{scope.row.push_money}}</span>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('plugins/teller', '添加日期');?>" width="110"></el-table-column>
                <el-table-column prop="status" label="<?= \Yii::t('plugins/teller', '状态');?>" width="100">
                    <template slot-scope="scope">
                        <el-switch
                                @change="changeStatus(scope.row.id,scope.row.status)"
                                v-model="scope.row.status"
                                :active-value="1"
                                :inactive-value="0">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('plugins/teller', '操作');?>" width="280" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="text" @click="edit(scope.row)"
                                   size="small" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/teller', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button size="mini" type="text"
                                   @click="$navigate({r:'plugin/teller/mall/push/index',user_type:'sales',keyword_name: 'number', keyword_value: scope.row.number})"
                                   circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/teller', '业绩明细');?>" placement="top">
                                <img src="statics/img/mall/detail.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button class="set-el-button" size="mini" type="text" circle
                                   @click="destroy(scope.row,scope.$index)">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/teller', '删除');?>" placement="top">
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
                    store_id: '',
                    keyword: '',
                },
                storeList: [],
                btnLoading: false,
                listLoading: false,
                list: [],
                pagination: null,
                page: 1,
            };
        },
        mounted() {
            this.getList();
            this.getStore();
        },
        methods: {
            getStore() {
                request({
                    params: {
                        r: 'mall/store/index',
                        page_size: 999,
                    },
                    method: 'get',
                }).then(e => {
                    this.storeList = e.data.data.list;
                });
            },
            getList() {
                this.listLoading = true;
                let params = Object.assign({}, {
                    r: 'plugin/teller/mall/sales/index',
                    page: this.page,
                }, this.search);
                request({
                    params,
                }).then(e => {
                    this.listLoading = false;
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination
                    }
                });
            },
            navTest(column) {
                this.pForm.id = column.id;
                this.pFormVisible = true;
            },
            pSubmit() {
                this.$refs.pForm.validate((valid) => {
                    if (valid) {
                        let para = Object.assign({}, this.pForm);
                        this.btnLoading = true;
                        request({
                            params: {
                                r: 'plugin/teller/mall/sales/update-password',
                            },
                            data: para,
                            method: 'POST',
                        }).then(e => {
                            this.btnLoading = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                this.pFormVisible = false;
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        })
                    }
                });
            },
            pageChange(page) {
                this.page = page;
                this.getList()
            },
            searchList() {
                this.page = 1;
                this.getList();
            },
            edit(column) {
                navigateTo({
                    r: 'plugin/teller/mall/sales/detail',
                    id: column.id,
                })
            },
            changeStatus(id, status) {
                let para = Object.assign({}, {id: id, status: status});
                request({
                    params: {
                        r: 'plugin/teller/mall/sales/update-status',
                    },
                    data: para,
                    method: 'POST',
                }).then(e => {
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            destroy(params, index) {
                this.$confirm('<?= \Yii::t('plugins/teller', '是否删除该导购员');?>', '<?= \Yii::t('plugins/teller', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/teller', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/teller', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    this.listLoading = true;
                    request({
                        params: {
                            r: 'plugin/teller/mall/sales/delete',
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
                    this.$message({type: 'info', message: '<?= \Yii::t('plugins/teller', '已取消删除');?>'});
                });
            },
        }
    });
</script>
