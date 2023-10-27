<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 .hook007
 * author: opmall
 */
?>
<style>
    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <div>
                <span><?= \Yii::t('pc/nav', '导航列表');?></span>
                <div style="float: right;margin-top: -5px">
                    <el-button type="primary" @click="edit()" size="small"><?= \Yii::t('pc/nav', '添加导航');?></el-button>
                </div>
            </div>
        </div>
        <div class="table-body">
            <el-table
                    v-loading="listLoading"
                    :data="list"
                    border
                    style="width: 100%">
                <el-table-column
                        prop="id"
                        label="ID"
                        width="100">
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('pc/nav', '名称');?>"
                        width="120">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.name}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('pc/nav', '导航链接');?>">
                    <template slot-scope="scope">
                        <app-ellipsis :line="1">{{scope.row.url}}</app-ellipsis>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('pc/nav', '排序');?>"
                        width="180">
                    <template slot-scope="scope">
                        <div v-if="id != scope.row.id">
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/nav', '排序');?>" placement="top">
                                <span>{{scope.row.sort}}</span>
                            </el-tooltip>
                            <el-button class="edit-sort" type="text" @click="editSort(scope.row)">
                                <img src="statics/img/mall/order/edit.png" alt="">
                            </el-button>
                        </div>
                        <div style="display: flex;align-items: center" v-else>
                            <el-input style="min-width: 70px" type="number" size="mini" class="change" v-model="sort"
                                      autocomplete="off"></el-input>
                            <el-button class="change-quit" type="text" style="color: #F56C6C;padding: 0 5px" icon="el-icon-error"
                                       circle @click="quit()"></el-button>
                            <el-button class="change-success" type="text" style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                       icon="el-icon-success" circle @click="change(scope.row)">
                            </el-button>
                        </div>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('pc/nav', '是否显示');?>"
                        width="120">
                    <template slot-scope="scope">
                        <el-switch
                                @change="status(scope.row)"
                                v-model="scope.row.status"
                                active-value="1"
                                inactive-value="0">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column
                        label="<?= \Yii::t('pc/nav', '操作');?>"
                        width="220">
                    <template slot-scope="scope">
                        <el-button @click="edit(scope.row)" size="small" type="text" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/nav', '编辑');?>" placement="top">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-tooltip>
                        </el-button>
                        <el-button @click="destroy(scope.row, scope.$index)" size="small" type="text" circle>
                            <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/nav', '删除');?>" placement="top">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-tooltip>
                        </el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align: right;margin: 20px 0;">
                <el-pagination
                        @current-change="pagination"
                        background
                        layout="prev, pager, next"
                        :page-count="pageCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <el-dialog title="<?= \Yii::t('pc/nav', '操作');?>" :visible.sync="dialog" width="50%">
        <el-form label-width="100px" ref="showData" :rules="showDataRules" :model="showData">
            <el-form-item label="<?= \Yii::t('pc/nav', '名称');?>" size="small" prop="name">
                <el-input v-model="showData.name"></el-input>
            </el-form-item>
            <!--            <el-form-item label="打开方式">-->
            <!--                <el-radio v-model="showData.open_type" label="1">本页</el-radio>-->
            <!--                <el-radio v-model="showData.open_type" label="2">新页</el-radio>-->
            <!--            </el-form-item>-->
            <el-form-item label="<?= \Yii::t('pc/nav', '链接地址');?>" size="small" prop="url">
                <el-input v-model="showData.url"></el-input>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('pc/nav', '排序');?>" size="small" prop="sort">
                <el-input v-model="showData.sort"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialog = false"><?= \Yii::t('pc/nav', '取消');?></el-button>
            <el-button type="primary" @click="save()"><?= \Yii::t('pc/nav', '确定');?></el-button>
        </div>
    </el-dialog>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                list: [],
                listLoading: false,
                page: 1,
                id: null,
                pageCount: 0,
                sort: 0,
                dialog: false,
                showData: {},
                showDataRules: {
                    name: [
                        {min: 1, max: 10, message: "<?= \Yii::t('pc/nav', '名称长度在1');?>"},
                    ],
                    sort: [
                        {required: false, pattern: /^[1-9]\d{0,8}$/, message: '<?= \Yii::t('pc/nav', '排序必须在9位正整数内');?>'}
                    ],
                    url: [
                        {required: true, message: '<?= \Yii::t('pc/nav', '链接地址不能为空');?>', trigger: ['blur', 'change']},
                    ]
                },
            };
        },
        methods: {
            search() {
                this.page = 1;
                this.getList();
            },
            editSort(row) {
                this.id = row.id;
                this.sort = row.sort;
            },
            quit() {
                this.id = null;
            },
            change(row) {
                row.sort = this.sort;
                this.showData = row;
                this.save();
                this.id = null;
            },
            status(row) {
                this.showData = row;
                this.save();
            },
            save: function(){
                request({
                    params: {
                        r: 'pc/nav/index'
                    },
                    method: 'post',
                    data: {
                        form: this.showData,
                    }
                }).then(e => {
                    this.btnLoading = false;
                    if (e.data.code == 0) {
                        this.getList();
                        this.$message.success(e.data.msg);
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.$message.error(e.data.msg);
                    this.btnLoading = false;
                });
                this.dialog = false;
            },
            edit: function(row) {
                this.dialog = true;
                if(row) {
                    this.showData = row;
                }else{
                    this.showData = {
                        id: "",
                        name: '',
                        url: '',
                        sort: 1,
                        open_type: '1',
                    };
                }
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            getList() {
                let self = this;
                self.listLoading = true;
                request({
                    params: {
                        r: 'pc/nav/index',
                        keyword: this.keyword,
                        page: self.page
                    },
                    method: 'get',
                }).then(e => {
                    self.listLoading = false;
                    self.list = e.data.data.list;
                    self.pageCount = e.data.data.pagination.page_count;
                }).catch(e => {
                    console.log(e);
                });
            },
            destroy(row, index) {
                let self = this;
                self.$confirm('<?= \Yii::t('pc/nav', '删除该条数据');?>?', '<?= \Yii::t('pc/nav', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('pc/nav', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('pc/nav', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.listLoading = true;
                    request({
                        params: {
                            r: 'pc/nav/destroy',
                        },
                        method: 'post',
                        data: {
                            id: row.id,
                        }
                    }).then(e => {
                        self.listLoading = false;
                        if (e.data.code === 0) {
                            self.list.splice(index, 1);
                            self.$message.success(e.data.msg);
                        } else {
                            self.$message.error(e.data.msg);
                        }
                    }).catch(e => {
                        console.log(e);
                    });
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('pc/nav', '已取消删除');?>')
                });
            },
        },
        mounted: function () {
            this.getList();
        }
    });
</script>
