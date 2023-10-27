<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/25
 * Time: 8:18 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('layout/app-contain');
?>
<div id="app" v-cloak>
    <app-contain head-name="<?= \Yii::t('plugins/minishop', '品牌管理');?>" edit-button-name="<?= \Yii::t('plugins/minishop', '新建');?>" :loading="loading" :edit-button="form.can_use"
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
                        <el-table-column prop="brand_wording" label="<?= \Yii::t('plugins/minishop', '品牌名称');?>"></el-table-column>
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
                        <el-pagination v-if="form.pagination"
                                       style="float: right;margin-right: 24px"
                                       background
                                       :page-size="form.pagination.pageSize"
                                       @current-change="pageChange"
                                       layout="total,prev, pager, next, jumper" :current-page="form.pagination.current_page"
                                       :total="form.pagination.totalCount">
                        </el-pagination>
                    </el-col>
                </el-card>
                <el-dialog title="<?= \Yii::t('plugins/minishop', '新增');?>" :visible.sync="edit.dialogVisible" width="624px" :show-close="false">
                    <span>
                        <el-form size="small" label-width="130px" ref="form" :model="edit.form">
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '品牌名称');?>" prop="brand_wording" :rules="{
                            required: true, message: '<?= \Yii::t('plugins/minishop', '品牌名称不能为空');?>', trigger: 'blur'
                            }">
                                <el-input v-model="edit.form.brand_wording"></el-input>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '品牌类型');?>" required>
                                <el-select v-model="edit.form.brand_audit_type" style="width: 100%">
                                    <el-option v-for="(item, index) in registerType" :key="index"
                                               :value="item.value" :label="item.label"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '商标分类');?>" required>
                                <el-select v-model="edit.form.trademark_type" style="width: 100%">
                                    <el-option v-for="(item, index) in trademarkType" :key="index"
                                               :value="item.value" :label="item.label"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '经营类型');?>" required>
                                <el-select v-model="edit.form.brand_management_type" style="width: 100%">
                                    <el-option v-for="(item, index) in brandManagementType" :key="index"
                                               :value="item.value" :label="item.label"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '是否进出口');?>" required>
                                <el-select v-model="edit.form.commodity_origin_type" style="width: 100%">
                                    <el-option v-for="(item, index) in commodityOriginType" :key="index"
                                               :value="item.value" :label="item.label"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '营业执照');?>" prop="license" :rules="{
                            required: true, message: '<?= \Yii::t('plugins/minishop', '营业执照不能为空');?>', trigger: 'blur'
                            }">
                                <div>
                                    <app-attachment v-model="edit.form.license" :multiple="false" :max="1">
                                        <el-button size="mini"><?= \Yii::t('plugins/minishop', '选择图片');?></el-button>
                                    </app-attachment>
                                    <div style="margin-top: 7px">
                                        <app-image mode="aspectFill" width='80px' height='80px'
                                                   :src="edit.form.license"></app-image>
                                    </div>
                                    <div style="font-size: 12px;color: #909399;line-height: 1.5;margin-top: 7px">
                                        <?= \Yii::t('plugins/minishop', '营业执照或组织机构代码');?>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item :label="register_type_R ? '<?= \Yii::t('plugins/minishop', '商标注册号');?>' : '<?= \Yii::t('plugins/minishop', '商标申请号');?>'"
                                          prop="trademark_registrant_nu"
                                          :rules="register_type_R ? {
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标注册号不能为空');?>', trigger: 'blur'
    } : {
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标申请号不能为空');?>', trigger: 'blur'
    }">
                                <el-input v-model="edit.form.trademark_registrant_nu"></el-input>
                            </el-form-item>
                            <template v-if="edit.form.brand_management_type != 3">
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标注册人姓名');?>" v-if="register_type_R"
                                              prop="trademark_registrant"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标注册人姓名不能为空');?>', trigger: 'blur'
    }">
                                    <el-input v-model="edit.form.trademark_registrant"></el-input>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标申请人姓名');?>" v-if="register_type_TM"
                                              prop="trademark_applicant"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标申请人姓名不能为空');?>', trigger: 'blur'
    }">
                                    <el-input v-model="edit.form.trademark_applicant"></el-input>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '销售授权书');?>" v-if="brand_management_type_2"
                                              prop="sale_authorization"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '销售授权书不能为空');?>', trigger: 'blur'
    }">
                                    <div>
                                        <div style="margin-top: 7px; flex-wrap: wrap" flex>
                                            <app-image style="margin: 0 20px 20px 0" :show-delete="true"
                                                       mode="aspectFill" width='80px' height='80px'
                                                       v-if="edit.form.sale_authorization.length > 0"
                                                       v-for="(item, index) in edit.form.sale_authorization"
                                                       :key="index"
                                                       @image-delete="imgDelete(index)"
                                                       :src="item"></app-image>
                                            <app-attachment :multiple="true" @selected="editSaleAuthorization">
                                                <app-image mode="aspectFill" width='80px' height='80px'
                                                           :show-tips="true"
                                                           :src="''"></app-image>
                                            </app-attachment>
                                        </div>
                                    </div>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标有效期');?>" v-if="register_type_R"
                                              prop="trademark_authorization_period"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标有效期不能为空');?>', trigger: 'blur'
    }">
                                    <el-date-picker style="width: 100%"
                                                    v-model="edit.form.trademark_authorization_period"
                                                    type="datetime" value-format="yyyy-MM-dd HH:mm:ss"
                                                    placeholder="<?= \Yii::t('plugins/minishop', '选择日期和时间');?>">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标注册证书');?>" v-if="register_type_R"
                                              prop="trademark_registration_certificate"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标注册证书不能为空');?>', trigger: 'blur'
    }">
                                    <div>
                                        <app-attachment v-model="edit.form.trademark_registration_certificate"
                                                        :multiple="false" :max="1">
                                            <el-button size="mini"><?= \Yii::t('plugins/minishop', '选择图片');?></el-button>
                                        </app-attachment>
                                        <div style="margin-top: 7px">
                                            <app-image mode="aspectFill" width='80px' height='80px'
                                                       :src="edit.form.trademark_registration_certificate"></app-image>
                                        </div>
                                    </div>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标申请时间');?>" v-if="register_type_TM"
                                              prop="trademark_application_time"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标申请时间不能为空');?>', trigger: 'blur'
    }">
                                    <el-date-picker style="width: 100%"
                                                    v-model="edit.form.trademark_application_time"
                                                    type="datetime" value-format="yyyy-MM-dd HH:mm:ss"
                                                    placeholder="<?= \Yii::t('plugins/minishop', '选择日期和时间');?>">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item label="<?= \Yii::t('plugins/minishop', '商标注册申请');?>" v-if="register_type_TM"
                                              prop="trademark_registration_application"
                                              :rules="{
      required: true, message: '<?= \Yii::t('plugins/minishop', '商标注册申请');?><?= \Yii::t('plugins/minishop', '不能为空');?>', trigger: 'blur'
    }">
                                    <template slot="label">
                                        <span><?= \Yii::t('plugins/minishop', '商标注册申请');?></span>
                                        <el-tooltip effect="dark" placement="top"
                                                    content="<?= \Yii::t('plugins/minishop', '商标注册申请');?><?= \Yii::t('plugins/minishop', '受理通知书');?>">
                                            <i class="el-icon-info"></i>
                                        </el-tooltip>
                                    </template>
                                    <div>
                                        <app-attachment v-model="edit.form.trademark_registration_application"
                                                        :multiple="false" :max="1">
                                            <el-button size="mini"><?= \Yii::t('plugins/minishop', '选择图片');?></el-button>
                                        </app-attachment>
                                        <div style="margin-top: 7px">
                                            <app-image mode="aspectFill" width='80px' height='80px'
                                                       :src="edit.form.trademark_registration_application"></app-image>
                                        </div>
                                    </div>
                                </el-form-item>
                            </template>
                        </el-form>
                    </span>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="edit.dialogVisible = false" size="mini"><?= \Yii::t('plugins/minishop', '取消');?></el-button>
                        <el-button type="primary" @click="apply" size="mini" :loading="edit.editLoading"><?= \Yii::t('plugins/minishop', '确定');?></el-button>
                    </span>
                </el-dialog>
            </div>
        </template>
    </app-contain>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
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
                    editLoading: false,
                    form: {
                        id: 0,
                        license: '',
                        brand_wording: '',
                        brand_audit_type: 1,
                        trademark_type: "1",
                        brand_management_type: 1,
                        commodity_origin_type: 1,
                        trademark_registrant_nu: '',
                        trademark_registrant: '',
                        trademark_authorization_period: '',
                        trademark_registration_certificate: '',
                        trademark_applicant: '',
                        trademark_application_time: '',
                        trademark_registration_application: '',
                        sale_authorization: []
                    },
                    is_update: 0,
                    default: null
                },
                registerType: [
                    {
                        label: '<?= \Yii::t('plugins/minishop', '国内品牌申请R标');?>',
                        value: 1,
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '国内品牌申请TM标');?>',
                        value: 2,
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '海外品牌申请R标');?>',
                        value: 3,
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '海外品牌申请TM标');?>',
                        value: 4,
                    },
                ],
                brandManagementType: [
                    {
                        label: '<?= \Yii::t('plugins/minishop', '自有品牌');?>',
                        value: 1
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '代理品牌');?>',
                        value: 2
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '无品牌');?>',
                        value: 3
                    }
                ],
                commodityOriginType: [
                    {
                        label: '<?= \Yii::t('plugins/minishop', '是');?>',
                        value: 1
                    },
                    {
                        label: '<?= \Yii::t('plugins/minishop', '否');?>',
                        value: 2
                    },
                ],
                page: 1
            }
        },
        created() {
            this.loading = true;
            this.getList();
            this.edit.default = JSON.parse(JSON.stringify(this.edit.form))
        },
        methods: {
            getList() {
                this.listLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/brand/index',
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
            apply() {
                this.edit.editLoading = true;
                let data = this.edit.form;
                data._csrf = _csrf;
                this.$refs.form.validate((valid) => {
                    if (valid) {
                        this.$request({
                            params: {
                                r: 'plugin/minishop/mall/brand/index',
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
                this.edit.form = JSON.parse(JSON.stringify(row))
            },
            editClick() {
                this.edit.dialogVisible = true
                this.edit.is_update = 0;
                this.edit.form = JSON.parse(JSON.stringify(this.edit.default))
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
                        r: 'plugin/minishop/mall/brand/operate',
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
            editSaleAuthorization(e, params) {
                for (let i in e) {
                    this.edit.form.sale_authorization.push(e[i].url)
                }
            },
            imgDelete(index) {
                this.edit.form.sale_authorization.splice(index, 1)
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
            },
            trademarkType() {
                let type = [];
                for (let i = 1; i <= 45; i++) {
                    type.push({
                        label: '<?= \Yii::t('plugins/minishop', '第');?>' + i + '<?= \Yii::t('plugins/minishop', '类');?>',
                        value: '' + i
                    })
                }
                return type;
            },
            register_type_R() {
                return this.edit.form.brand_audit_type === 1|| this.edit.form.brand_audit_type === 3
            },
            register_type_TM() {
                return this.edit.form.brand_audit_type === 2 || this.edit.form.brand_audit_type === 4
            },
            brand_management_type_2() {
                return this.edit.form.brand_management_type === 2
            }
        },
        watch: {
            'edit.form.brand_audit_type': {
                handler() {
                    this.$refs.form.clearValidate();
                }
            },
            'edit.form.brand_management_type': {
                handler() {
                    this.$refs.form.clearValidate();
                }
            },
        }
    });
</script>

