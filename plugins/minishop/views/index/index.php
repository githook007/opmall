<?php
/**
 * Created by PhpStorm
 * Date: 2021/3/9
 * Time: 5:17 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
Yii::$app->loadViewComponent('goods/app-select-goods');
Yii::$app->loadViewComponent('goods/app-attr');
Yii::$app->loadViewComponent('layout/app-contain');
Yii::$app->loadViewComponent('layout/app-search-input');
Yii::$app->loadViewComponent('template/app-select-cat', dirname(__DIR__));
?>
<style>
    .operate .el-button--mini.is-circle {
        padding: 0;
    }

    .operate img {
        width: 36px;
        height: 36px;
    }

    .refreshModal {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        opacity: .5;
        background: #000;
        z-index: 2000;
    }
</style>
<div id="app" v-cloak>
    <app-contain :loading="loading" edit-button-name="<?= \Yii::t('plugins/minishop', '添加商品');?>" head-name="<?= \Yii::t('plugins/minishop', '交易组件');?>" :edit-button="form.can_use"
                 @edit-click="edit.dialogVisible = true">
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
                    <app-search-input placeholder="<?= \Yii::t('plugins/minishop', '请输入商品id或名称搜索');?>" @search="searchFunc"></app-search-input>
                    <el-alert
                            style="margin-bottom:20px;"
                            type="info"
                            title="请先前往《设置》-《物流设置》-《退货地址》添加一条默认地址，再添加商品"
                            :closable="false">
                    </el-alert>
                    <div style="padding: 10px; background-color: #f7f7f7">
                        <el-button size="mini" @click="clickRefresh"><?= \Yii::t('plugins/minishop', '同步刷新');?></el-button>
                    </div>
                    <el-table
                            ref="multipleTable"
                            :data="form.list"
                            border
                            style="width: 100%;margin-bottom: 15px"
                            @sort-change="sortChange" v-loading="listLoading">
                        <el-table-column width='110' prop="goods_id" label="ID" :sortable="true"></el-table-column>
                        <el-table-column prop="cat" label="<?= \Yii::t('plugins/minishop', '分类');?>" width="160">
                            <template slot-scope="scope">
                                <el-tag size="mini">{{scope.row.cat}}</el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column prop="title" label="<?= \Yii::t('plugins/minishop', '商品名称');?>" width="380">
                            <template slot-scope="scope">
                                <div flex="dir:left box:first">
                                    <div style="padding-right: 10px">
                                        <app-image mode="aspectFill" :src="scope.row.cover_pic"></app-image>
                                    </div>
                                    <div v-line-clamp="2">{{scope.row.title}}</div>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column prop="price" label="<?= \Yii::t('plugins/minishop', '售价');?>" :sortable="true" width="120"></el-table-column>
                        <el-table-column prop="stock" label="<?= \Yii::t('plugins/minishop', '库存');?>" :sortable="true" width="120"></el-table-column>
                        <el-table-column prop="created_at" label="<?= \Yii::t('plugins/minishop', '提交时间');?>" width="250"></el-table-column>
                        <el-table-column prop="apply_status" label="<?= \Yii::t('plugins/minishop', '审核状态');?>" width="120">
                            <template slot-scope="scope">
                                <el-tag v-if="scope.row.apply_status == 0" type="info" size="mini"><?= \Yii::t('plugins/minishop', '未审核');?></el-tag>
                                <el-tag v-else-if="scope.row.apply_status == 1" size="mini"><?= \Yii::t('plugins/minishop', '审核中');?></el-tag>
                                <el-tag v-else-if="scope.row.apply_status == 2" type="success" size="mini"><?= \Yii::t('plugins/minishop', '审核通过');?></el-tag>
                                <template v-else>
                                    <div style="color: #FA706C;font-size: 12px;cursor: pointer"
                                         @click="reject(scope.row)">
                                        <el-tag type="danger" size="mini"><?= \Yii::t('plugins/minishop', '审核驳回');?></el-tag>
                                        <div flex="dir:left cross:center">
                                            <img src="statics/img/plugins/minishop/error.png" alt=""
                                                 style="width: 16px;height: 16px;margin: 0 2px 0 0;">
                                            <div><?= \Yii::t('plugins/minishop', '查看失败原因');?></div>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column prop="status" label="<?= \Yii::t('plugins/minishop', '状态');?>" width="120">
                            <template slot-scope="scope">
                                <el-tag v-if="scope.row.status == 0" type="warning" size="mini"><?= \Yii::t('plugins/minishop', '下架中');?></el-tag>
                                <el-tag v-else type="success" size="mini"><?= \Yii::t('plugins/minishop', '销售中');?></el-tag>
                            </template>
                        </el-table-column>
                        <el-table-column label="<?= \Yii::t('plugins/minishop', '操作');?>" fixed="right" width="220">
                            <template slot-scope="scope">
                                <div class="operate">
                                    <el-button @click="apply(scope.row)" type="text" circle size="mini">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '提交审核');?>" placement="top">
                                            <img src="statics/img/plugins/minishop/apply.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                    <el-button @click="up(scope.row.id)" type="text" circle size="mini"
                                               v-if="scope.row.status === 0 && scope.row.apply_status === 2">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '上架');?>" placement="top">
                                            <img src="statics/img/plugins/minishop/up.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                    <el-button @click="down(scope.row.id)" type="text" circle size="mini"
                                               v-if="scope.row.status === 1 && scope.row.apply_status === 2">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '下架');?>" placement="top">
                                            <img src="statics/img/plugins/minishop/down.png" alt="">
                                        </el-tooltip>
                                    </el-button>
                                    <el-button @click="updateClick(scope.row)" type="text" circle size="mini"
                                               v-if="scope.row.apply_status === 2">
                                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '免审核更新');?>" placement="top">
                                            <img src="statics/img/plugins/minishop/refresh.png" alt="">
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
                    <div flex="dir:right" style="margin-top: 20px;">
                        <el-pagination
                                @current-change="pagination"
                                background
                                hide-on-single-page
                                :page-size="pageSize"
                                :current-page="current_page"
                                layout="total, prev, pager, next, jumper"
                                :total="pageCount">
                        </el-pagination>
                    </div>
                </el-card>
                <el-dialog title="<?= \Yii::t('plugins/minishop', '添加商品');?>" :visible.sync="edit.dialogVisible" width="30%" :show-close="false">
                    <span>
                        <el-form size="small" label-width="100px">
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '商品分类');?>" required>
                                <div>
                                    <el-tag v-if="edit.cat_info" type="warning" closable
                                            @close="removeCat">{{edit.cat_info.label}}</el-tag>
                                    <el-button type="primary" size="mini" @click="openCat"><?= \Yii::t('plugins/minishop', '选择分类');?></el-button>
                                    <div v-if="edit.cat_info && edit.cat_info.qualification_type != 0">
                                        <span style="color: #ff4544; font-size: 12px;"><?= \Yii::t('plugins/minishop', '该类目分类需要提供相应的资质验证');?>"
                                        <el-button type="text" @click="$navigate({r:'plugin/minishop/mall/cat/index'}, true)"><?= \Yii::t('plugins/minishop', '类目验证');?></el-button>
                                        "</span>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '选择品牌');?>">
                                <div>
                                    <el-select v-model="edit.form.brand_id" style="width: 100%">
                                        <el-option v-for="(item, index) in form.brand_list" :key="index"
                                                   :value="item.brand_id" :label="item.brand_wording">
                                        </el-option>
                                    </el-select>
                                    <div>
                                        <span style="color: #ff4544; font-size: 12px;"><?= \Yii::t('plugins/minishop', '如该商品还未添加品牌');?>"
                                        <el-button type="text" @click="$navigate({r:'plugin/minishop/mall/brand/index'}, true)"><?= \Yii::t('plugins/minishop', '品牌验证');?></el-button>
                                        "</span>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '商品信息');?>" required>
                                <div>
                                    <el-button type="primary" @click="select_goods_dialog" size="mini" v-if="edit.is_update == 0"><?= \Yii::t('plugins/minishop', '选择商品');?></el-button>
                                    <div flex="dir:left" v-if="edit.goods_info"
                                         style="padding: 5px;border: 1px #eeeeee solid;margin-top: 10px">
                                        <div style="padding-right: 10px;flex-grow: 0">
                                            <app-image mode="aspectFill" :src="edit.goods_info.cover_pic"></app-image>
                                        </div>
                                        <div style="flex-grow: 1">
                                            <div v-line-clamp="1" style="line-height: 25px">{{edit.goods_info.name}}</div>
                                            <div style="color: #ff4544;line-height: 25px">{{edit.goods_info.price}}</div>
                                        </div>
                                        <div style="flex-grow: 0" v-if="edit.is_update == 0">
                                            <el-button @click="destroy_goods" type="text" circle size="mini">
                                                <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/minishop', '删除');?>" placement="top">
                                                    <img src="statics/img/mall/del.png" alt="">
                                                </el-tooltip>
                                            </el-button>
                                        </div>
                                    </div>
                                </div>
                            </el-form-item>
                            <el-form-item label="<?= \Yii::t('plugins/minishop', '商品资质图片');?>">
                                <div>
                                    <div style="margin-top: 7px; flex-wrap: wrap" flex>
                                        <app-image style="margin: 0 20px 20px 0" :show-delete="true"
                                                   mode="aspectFill" width='80px' height='80px'
                                                   v-if="edit.form.qualification_pics.length > 0"
                                                   v-for="(item, index) in edit.form.qualification_pics"
                                                   :key="index"
                                                   @image-delete="imgDelete(index)"
                                                   :src="item"></app-image>
                                        <app-attachment :multiple="true" @selected="editQualificationPics">
                                            <app-image mode="aspectFill" width='80px' height='80px'
                                                       :show-tips="true"
                                                       :src="''"></app-image>
                                        </app-attachment>
                                    </div>
                                    <div v-if="edit.cat_info && edit.cat_info.product_qualification_type != 0"
                                         style="font-size: 12px;color: #909399;line-height: 1.5;margin-top: 7px">
                                        {{edit.cat_info.product_qualification}}
                                    </div>
                                </div>
                            </el-form-item>
                        </el-form>
                    </span>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="edit.dialogVisible = false" size="mini"><?= \Yii::t('plugins/minishop', '取消');?></el-button>
                        <el-button type="primary" @click="addGoods" size="mini" :loading="edit.editLoading"><?= \Yii::t('plugins/minishop', '确定');?></el-button>
                    </span>
                </el-dialog>
                <app-select-goods :multiple="false" sign="mall" :is_cancel="true" ref="edit_goods"
                                  @selected="selectGoods" url="plugin/minishop/mall/index/get-goods">
                </app-select-goods>
                <app-select-cat url="plugin/minishop/mall/index/cat" @cat-cancel="catCancel" @cat-submit="catSubmit"
                                :cat-dialog-visible="edit.catDialogVisible"></app-select-cat>
                <el-dialog title="<?= \Yii::t('plugins/minishop', '免审核更新');?>" width="1100px" :visible.sync="update.dialogVisible">
                    <div v-loading="update.loading">
                        <el-form label-width="180px" size="small">
                            <app-attr v-model="update.attr" :attr-groups="update.attrGroups"></app-attr>
                        </el-form>
                    </div>
                    <div slot="footer" class="dialog-footer">
                        <el-button size='small' @click="update.dialogVisible = false"><?= \Yii::t('plugins/minishop', '取消');?></el-button>
                        <el-button size='small' type="primary"
                                   :loading="update.submitLoading" @click="updateSubmit"><?= \Yii::t('plugins/minishop', '确定');?></el-button>
                    </div>
                </el-dialog>
                <div class="refreshModal" v-if="refreshDialogVisible">
                    <div style="margin: 0 auto; width: 145px; margin-top: calc(50vh - 73px); position: relative">
                        <img src="statics/img/plugins/minishop/loading.gif" style="width: 145px;height: 145px">
                        <div style="position: absolute;top: 115px;left: 38px;color: #fff"><?= \Yii::t('plugins/minishop', '数据加载中');?>...</div>
                    </div>
                </div>
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
                    list: [],
                    brand_list: [],
                },
                catList: [],
                search: {
                    keyword: '',
                    sort_prop: '',
                    sort_type: 1
                },
                page: 1,
                pageSize: 20,
                pageCount: 0,
                current_page: 1,
                edit: {
                    dialogVisible: false,
                    catDialogVisible: false,
                    editLoading: false,
                    goods_info: null,
                    cat_info: null,
                    form: {
                        third_cat_id: 0,
                        goods_id: 0,
                        brand_id: 2100000000,
                        qualification_pics: []
                    },
                    is_update: 0,
                    default: null
                },
                update: {
                    dialogVisible: false,
                    loading: false,
                    attr: [],
                    attrGroups: [],
                    submitLoading: false,
                    id: 0,
                },
                refreshDialogVisible: false
            };
        },
        created() {
            this.loading = true;
            this.getList();
            this.edit.default = JSON.parse(JSON.stringify(this.edit.form))
        },
        watch: {
            'edit.dialogVisible': {
                handler: function (to) {
                    if (this.edit.dialogVisible === false) {
                        this.edit.goods_info = null
                        this.edit.cat_info = null
                        this.edit.form = this.edit.default
                        this.edit.is_update = 0
                    }
                }
            }
        },
        methods: {
            getList() {
                this.listLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/index',
                        page: this.page,
                        search: this.search,
                    },
                    method: 'get',
                }).then(e => {
                    this.loading = false
                    this.listLoading = false
                    if (e.data.code === 0) {
                        this.form = e.data.data
                        this.pageCount = e.data.data.pagination.total_count;
                        this.pageSize = e.data.data.pagination.pageSize;
                        this.current_page = e.data.data.pagination.current_page;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false
                    this.listLoading = false
                });
            },
            // 排序排列
            sortChange(row) {
                if (row.prop && row.order) {
                    this.search.sort_prop = row.prop;
                    this.search.sort_type = row.order === "descending" ? 0 : 1;
                } else {
                    this.search.sort_prop = '';
                    this.search.sort_type = '';
                }
                this.getList();
            },
            pagination(currentPage) {
                let self = this;
                self.page = currentPage;
                self.getList();
            },
            clickRefresh() {
                this.refresh(1);
            },
            refresh(page = 1) {
                this.refreshDialogVisible = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/refresh',
                        page: page
                    },
                    method: 'get'
                }).then(e => {
                    if (e.data.code === 0) {
                        if (e.data.data.retry === 1) {
                            this.refresh(e.data.data.page)
                        } else {
                            this.refreshDialogVisible = false;
                            this.getList();
                        }
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {

                });
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
            up(id) {
                let self = this;
                let text = '<?= \Yii::t('plugins/minishop', '是否上架该商品');?>';
                self.$confirm(text, '<?= \Yii::t('plugins/minishop', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/minishop', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/minishop', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.operate(id, 'up');
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('plugins/minishop', '已取消');?>')
                });
            },
            down(id) {
                let self = this;
                let text = '<?= \Yii::t('plugins/minishop', '是否下架该商品');?>';
                self.$confirm(text, '<?= \Yii::t('plugins/minishop', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/minishop', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/minishop', '取消');?>',
                    type: 'warning'
                }).then(() => {
                    self.operate(id, 'down');
                }).catch(() => {
                    self.$message.info('<?= \Yii::t('plugins/minishop', '已取消');?>')
                });
            },
            operate(id, type) {
                let self = this;
                self.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/operate',
                    },
                    method: 'post',
                    data: {
                        id: id,
                        _csrf: _csrf,
                        operate: type
                    }
                }).then(e => {
                    self.listLoading = false;
                    if (e.data.code === 0) {
                        self.getList();
                        self.$message.success(e.data.msg);
                    } else {
                        self.$message.error(e.data.msg);
                    }
                });
            },
            select_goods_dialog() {
                this.$refs['edit_goods'].showModel();
            },
            selectGoods(goods_warehouse) {
                this.edit.goods_info = {
                    name: goods_warehouse.name,
                    price: goods_warehouse.price,
                    cover_pic: goods_warehouse.goodsWarehouse.cover_pic,
                }
                this.edit.form.goods_id = goods_warehouse.id
            },
            destroy_goods() {
                this.edit.goods_info = null;
                this.edit.form.goods_id = 0
            },
            openCat() {
                this.edit.catDialogVisible = true;
            },
            catSubmit(cat_info) {
                if (cat_info) {
                    this.edit.cat_info = cat_info
                    this.edit.form.third_cat_id = cat_info.value
                }
                this.edit.catDialogVisible = false;
            },
            catCancel() {
                this.edit.catDialogVisible = false;
            },
            removeCat() {
                this.edit.cat_info = null;
                this.edit.form.third_cat_id = 0;
            },
            addGoods() {
                if (!this.edit.cat_info) {
                    this.$message.error('<?= \Yii::t('plugins/minishop', '请选择分类');?>');
                    return '';
                }
                if (!this.edit.goods_info) {
                    this.$message.error('<?= \Yii::t('plugins/minishop', '请选择商品');?>');
                    return '';
                }
                this.edit.editLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/goods',
                    },
                    method: 'post',
                    data: {
                        goods_id: this.edit.form.goods_id,
                        _csrf: _csrf,
                        third_cat_id: this.edit.form.third_cat_id,
                        qualification_pics: this.edit.form.qualification_pics
                    }
                }).then(e => {
                    this.edit.editLoading = false;
                    if (e.data.code === 0) {
                        this.edit.dialogVisible = false;
                        this.getList();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            apply(row) {
                this.edit.is_update = 1;
                this.edit.form.third_cat_id = row.third_cat_id
                this.edit.form.goods_id = row.goods_id;
                this.edit.form.brand_id = row.brand_id;
                this.edit.form.qualification_pics = row.qualification_pics;
                this.edit.goods_info = {
                    name: row.title,
                    price: row.price,
                    cover_pic: row.cover_pic,
                }
                this.edit.cat_info = {
                    label: row.cat,
                    value: row.third_cat_id
                }
                this.edit.dialogVisible = true;
            },
            updateClick(row) {
                this.update.dialogVisible = true;
                this.update.loading = true;
                this.update.id = row.id
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/operate',
                    },
                    method: 'post',
                    data: {
                        id: row.id,
                        _csrf: _csrf,
                        operate: 'attr'
                    }
                }).then(e => {
                    this.update.loading = false;
                    if (e.data.code === 0) {
                        this.update.attr = e.data.data.attr;
                        this.update.attrGroups = e.data.data.attr_groups;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            updateSubmit() {
                this.update.submitLoading = true;
                this.$request({
                    params: {
                        r: 'plugin/minishop/mall/index/update',
                    },
                    method: 'post',
                    data: {
                        id: this.update.id,
                        attr: this.update.attr,
                        _csrf: _csrf,
                    }
                }).then(e => {
                    this.update.submitLoading = false;
                    if (e.data.code === 0) {
                        this.$message.success('<?= \Yii::t('plugins/minishop', '更新成功');?>');
                        this.update.attr = [];
                        this.update.id = 0;
                        this.update.dialogVisible = false;
                        this.getList();
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            searchFunc(keyword) {
                this.search.keyword = keyword
                this.getList()
            },
            editQualificationPics(e, params) {
                for (let i in e) {
                    this.edit.form.qualification_pics.push(e[i].url)
                }
            },
            imgDelete(index) {
                this.edit.form.qualification_pics.splice(index, 1)
            },
            reject(row) {
                let html = '<div style="text-align: center;color: #606266">' + row.audit_info.reject_reason + '</div>';
                this.$confirm(html, '<?= \Yii::t('plugins/minishop', '失败原因');?>', {
                    confirmButtonText: '<?= \Yii::t('plugins/minishop', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('plugins/minishop', '取消');?>',
                    type: 'none',
                    showClose: false,
                    dangerouslyUseHTMLString: true
                });
            }
        }
    });
</script>