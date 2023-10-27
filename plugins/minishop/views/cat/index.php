<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 9:16 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('layout/app-contain');
Yii::$app->loadViewComponent('template/app-select-cat', dirname(__DIR__));
?>
<div id="app" v-cloak>
    <app-contain head-name="<?= \Yii::t('plugins/minishop', '类目管理');?>" edit-button-name="<?= \Yii::t('plugins/minishop', '新建');?>" :loading="loading" :edit-button="form.can_use"
                 @edit-click="editClick">
        <template v-if="!loading">
            <div v-if="!form.can_use">
                <el-card shadow="never" body-style="background-color: #e1f0ff; color: #409EFF; padding: 14px 20px">
                    <span>{{form.content}}</span>
                    <a href="https://mp.weixin.qq.com/" target="_blank">
                        <el-button type="primary" size="mini" @click=""><?= \Yii::t('plugins/minishop', '去开通');?></el-button>
                    </a>
                </el-card>
            </div>
            <div v-else>
                <el-card shadow="never">
                    <el-table ref="multipleTable"
                              :data="form.list"
                              border :header-cell-style="headerCellStyle"
                              style="width: 100%;margin-bottom: 15px" v-loading="listLoading">
                        <el-table-column prop="third_cat_name" label="<?= \Yii::t('plugins/minishop', '类目名称');?>"></el-table-column>
                        <el-table-column prop="status" label="<?= \Yii::t('plugins/minishop', '状态');?>">
                            <template slot-scope="scope">
                                <el-tag v-if="scope.row.status == 0" size="mini"><?= \Yii::t('plugins/minishop', '审核中');?></el-tag>
                                <el-tag v-else-if="scope.row.status == 1" type="warning" size="mini"><?= \Yii::t('plugins/minishop', '审核通过');?></el-tag>
                                <template v-else>
                                    <div style="color: #FA706C;font-size: 12px;cursor: pointer"
                                         @click="reject(scope.row)" flex="dir:left cross:center">
                                        <el-tag type="danger" size="mini"><?= \Yii::t('plugins/minishop', '审核失败');?></el-tag>
                                        <img src="statics/img/plugins/minishop/error.png" alt=""
                                             style="width: 16px;height: 16px;margin: 0 2px 0 39px;">
                                        <div><?= \Yii::t('plugins/minishop', '查看失败原因');?></div>
                                    </div>
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column label="<?= \Yii::t('plugins/minishop', '操作');?>">
                            <template slot-scope="scope">
                                <div class="operate">
                                    <el-button @click="editCat(scope.row)" type="text" circle size="mini" v-if="scope.row.status != 1">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '编辑');?>" placement="top">
                                            <img src="statics/img/mall/edit.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                    <el-button @click="destroy(scope.row, scope.$index)" type="text" circle size="mini">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '删除');?>" placement="top">
                                            <img src="statics/img/mall/del.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-col :span="24" class="toolbar" style="margin-bottom: 20px">
                        <el-pagination
                                style="float: right;margin-right: 24px"
                                background
                                :page-size="form.pagination.pageSize"
                                @current-change="pageChange"
                                layout="total,prev, pager, next, jumper" :current-page="form.pagination.current_page"
                                :total="form.pagination.totalCount">
                        </el-pagination>
                    </el-col>
                </el-card>
                <el-dialog title="<?= \Yii::t('plugins/minishop', '新增');?>" :visible.sync="edit.dialogVisible" width="30%" :show-close="false">
                    <span>
                        <el-form size="small" label-width="81px" :model="edit.form" ref="form" :rules="edit.rules">
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '商品分类');?>" prop="third_cat_id">
                                <div>
                                    <el-tag v-if="edit.cat_info" type="warning" :closable="edit.is_update == 0"
                                            @close="removeCat">{{edit.cat_info.label}}</el-tag>
                                    <el-button type="primary" size="mini" v-if="edit.is_update == 0"
                                               @click="edit.catDialogVisible = true"><?= \Yii::t('plugins/minishop', '选择分类');?></el-button>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '营业执照');?>" prop="license">
                                <div>
                                    <app-attachment v-model="edit.form.license" :multiple="false" :max="1">
                                        <el-button size="mini"><?= \Yii::t('plugins/minishop', '选择图片');?></el-button>
                                    </app-attachment>
                                    <div style="margin-top: 7px">
                                        <app-image mode="aspectFill" width='80px' height='80px'
                                                   :src="edit.form.license ? edit.form.license : ''"></app-image>
                                    </div>
                                    <div style="font-size: 12px;color: #909399;line-height: 1.5;margin-top: 7px">
                                        <?= \Yii::t('plugins/minishop', '营业执照或组织机构代码');?>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '类目资质');?>" prop="certificate">
                                <div>
                                    <div style="margin-top: 7px; flex-wrap: wrap" flex>
                                        <app-image style="margin: 0 20px 20px 0" :show-delete="true"
                                                   mode="aspectFill" width='80px' height='80px'
                                                   v-if="edit.form.certificate.length > 0"
                                                   v-for="(item, index) in edit.form.certificate"
                                                   :key="index"
                                                   @image-delete="imgDelete(index)"
                                                   :src="item"></app-image>
                                        <app-attachment :multiple="true" @selected="editCertificate">
                                            <app-image mode="aspectFill" width='80px' height='80px'
                                                       :show-tips="true"
                                                       :src="''"></app-image>
                                        </app-attachment>
                                    </div>
                                    <div style="font-size: 12px;color: #909399;line-height: 1.5;margin-top: 7px"
                                         v-if="edit.cat_info">
                                        {{edit.cat_info.qualification}}
                                    </div>
                                </div>
                            </el-form-item>
                        </el-form>
                    </span>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="edit.dialogVisible = false" size="mini"><?= \Yii::t('plugins/minishop', '取消');?></el-button>
                        <el-button type="primary" @click="apply" size="mini" :loading="edit.editLoading"><?= \Yii::t('plugins/minishop', '确定');?></el-button>
                    </span>
                </el-dialog>
                <app-select-cat url="plugin/minishop/mall/cat/cat"
                                @cat-cancel="edit.catDialogVisible = false" @cat-submit="catSubmit"
                                :cat-dialog-visible="edit.catDialogVisible"></app-select-cat>
            </div>
        </template>
    </app-contain>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            var validCat = (rule, value, callback) => {
                if (this.edit.form.third_cat_id == 0) {
                    callback(new Error("<?= \Yii::t('plugins/minishop', '商品分类不能为空');?>"))
                } else {
                    callback()
                }
            };
            return {
                loading: false,
                listLoading: false,
                form: {
                    can_use: false,
                    content: '',
                },
                edit: {
                    catDialogVisible: false,
                    dialogVisible: false,
                    cat_info: null,
                    editLoading: false,
                    form: {
                        id: 0,
                        third_cat_id: 0,
                        license: null,
                        certificate: [],
                    },
                    is_update: 0,
                    rules: {
                        third_cat_id: [
                            {validator: validCat, message: '<?= \Yii::t('plugins/minishop', '商品分类不能为空');?>', trigger: 'change', required: true},
                        ],
                        license: [
                            {required: true, message: '<?= \Yii::t('plugins/minishop', '营业执照不能为空');?>', trigger: 'change'},
                        ],
                        certificate: [
                            {type: 'array', required: true, message: '<?= \Yii::t('plugins/minishop', '类目资质不能为空');?>', trigger: 'change'},
                        ],
                    }
                },
                page: 1
            }
        },
        created() {
            this.loading = true;
            this.getList();
        },
        methods: {
            getList() {
                this.listLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/cat/index',
                        page: this.page
                    },
                    method: 'get',
                }).then(e => {
                    this.loading = false
                    this.listLoading = false
                    if (e.data.code === 0) {
                        this.form = e.data.data
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false
                    this.listLoading = false
                });
            },
            catSubmit(cat_info) {
                if (cat_info) {
                    this.edit.cat_info = cat_info
                    this.edit.form.third_cat_id = cat_info.value
                }
                this.edit.catDialogVisible = false;
            },
            editCertificate(e, params) {
                for (let i in e) {
                    this.edit.form.certificate.push(e[i].url)
                }
            },
            removeCat () {
                this.edit.cat_info = null;
                this.edit.form.third_cat_id = 0;
            },
            apply() {
                this.edit.editLoading = true;
                let data = this.edit.form;
                data._csrf = _csrf;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.$request({
                            params: {
                                r: 'plugin/minishop/mall/cat/index',
                            },
                            method: 'post',
                            data: data
                        }).then(e => {
                            this.edit.editLoading = false;
                            this.edit.dialogVisible = false;
                            if (e.data.code === 0) {
                                this.$message.success(e.data.msg);
                                this.getList();
                            } else {
                                this.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            this.edit.editLoading = false;
                        });
                    } else {
                        this.edit.editLoading = false;
                    }
                })
            },
            editCat(row) {
                this.edit.dialogVisible = true
                this.edit.is_update = 1;
                this.edit.cat_info = {
                    label: row.third_cat_name,
                    value: row.third_cat_id,
                    qualification: row.qualification
                }
                this.edit.form = {
                    id: row.id,
                    third_cat_id: row.third_cat_id,
                    license: row.license,
                    certificate: row.certificate,
                }
            },
            editClick() {
                this.edit.dialogVisible = true
                this.edit.is_update = 0;
                this.edit.cat_info = null;
                this.edit.form = {
                    id: 0,
                    third_cat_id: 0,
                    license: null,
                    certificate: [],
                }
            },
            destroy(row, index) {
                let self = this;
                let text = '<?= \Yii::t('plugins/minishop', '删除该条数据');?>';
                self.$confirm(text, '<?= \Yii::t('plugins/minishop', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/minishop', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/minishop', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.operate(row.id, 'delete');
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('plugins/minishop', '已取消删除');?>')
                });
            },
            operate(id, operate) {
                let data = {
                    _csrf: _csrf,
                    id: id,
                    operate: operate
                };
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/cat/operate',
                    },
                    method: 'post',
                    data: data
                }).then(e => {
                    if (e.data.code === 0) {
                        this.$message.success(e.data.msg);
                        this.getList();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                });
            },
            pageChange(page) {
                this.page = page;
                this.getList()
            },
            imgDelete(index) {
                this.edit.form.certificate.splice(index, 1)
            },
            reject(row) {
                let html = '<div style="text-align: center;color: #606266">' + row.reject_reason + '</div>';
                this.$confirm(html, '<?= \Yii::t('plugins/minishop', '失败原因');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/minishop', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/minishop', '取消');?>',
                    type: 'none',
                    showClose: false,
                    dangerouslyUseHTMLString: true
                });
            }
        },
        computed: {
            headerCellStyle() {
                return {backgroundColor: '#F5F7FA'}
            }
        },
        watch: {
            'edit.form.third_cat_id': {
                handler() {
                    this.$refs.form.clearValidate('third_cat_id')
                }
            },
            'edit.form.license': {
                handler() {
                    this.$refs.form.clearValidate('license')
                }
            },
            'edit.form.certificate': {
                handler() {
                    this.$refs.form.clearValidate('certificate')
                }
            },
        }
    });
</script>
