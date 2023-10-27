<?php defined('YII_ENV') or exit('Access Denied'); ?>
<style>
    .set-el-button {
        padding: 0 !important;
        border: 0;
        margin: 0 5px;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body {
        padding: 20px;
        background-color: #fff;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('pc/banner', '轮播图列表');?></span>
            <div style="float: right;margin-top: -5px">
                <el-button type="primary" @click="edit()" size="small"><?= \Yii::t('pc/banner', '添加');?></el-button>
            </div>
        </div>
        <div class="table-body">
            <el-table v-loading="loading" border :data="list" style="width: 100%;margin-bottom: 15px">
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="title" label="<?= \Yii::t('pc/banner', '标题');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('pc/banner', '轮播图');?>">
                    <template slot-scope="scope">
                        <app-image mode="aspectFill" width='300px' height='150px' :src="scope.row.pic_url"></app-image>
                    </template>
                </el-table-column>
                <el-table-column prop="page_url" label="<?= \Yii::t('pc/banner', '跳转地址');?>"></el-table-column>
                <el-table-column prop="sort" label="<?= \Yii::t('pc/banner', '排序');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('pc/banner', '操作');?>" width="150">
                    <template slot-scope="scope">
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/banner', '编辑');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="edit(scope.row)">
                                <img src="statics/img/mall/edit.png" alt="">
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/banner', '删除');?>" placement="top">
                            <el-button circle type="text" size="mini" @click="destroy(scope.row.id)">
                                <img src="statics/img/mall/del.png" alt="">
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </el-table>
            <div flex="box:last cross:center">
                <div></div>
                <el-pagination
                        v-if="pagination"
                        style="display: inline-block;float: right;"
                        background
                        :page-size="pagination.pageSize"
                        @current-change="pageChange"
                        layout="prev, pager, next"
                        :total="pagination.total_count">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <el-dialog title="<?= \Yii::t('pc/banner', '操作');?>" :visible.sync="dialog" width="50%">
        <el-form label-width="100px" ref="showData" :rules="showDataRules" :model="showData">
            <el-form-item label="<?= \Yii::t('pc/banner', '标题');?>" size="small" prop="title">
                <el-input v-model="showData.title"></el-input>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('pc/banner', '轮播图');?>" size="small" prop="pic_url">
                <app-attachment title="<?= \Yii::t('pc/banner', '选择图片');?>" @selected="singlePicUrl">
                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('pc/banner', '建议尺寸');?>" placement="top">
                        <el-button size="mini"><?= \Yii::t('pc/banner', '选择图片');?></el-button>
                    </el-tooltip>
                </app-attachment>
                <app-image width='300px' height='180px' :src="showData.pic_url"></app-image>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('pc/banner', '跳转地址');?>" size="small" prop="page_url">
                <el-input v-model="showData.page_url"></el-input>
            </el-form-item>
            <el-form-item label="<?= \Yii::t('pc/banner', '排序');?>" size="small" prop="sort">
                <el-input v-model="showData.sort"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialog = false"><?= \Yii::t('pc/banner', '取消');?></el-button>
            <el-button type="primary" @click="save()"><?= \Yii::t('pc/banner', '确定');?></el-button>
        </div>
    </el-dialog>
</div>

<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                list: [],
                page: 1,
                pagination: null,
                dialog: false,
                showData: {
                    id: "",
                    title: '',
                    pic_url: '',
                    sort: 1,
                },
                showDataRules: {
                    title: [
                        {min: 1, max: 100, message: "<?= \Yii::t('pc/banner', '标题长度在1');?>"},
                    ],
                    sort: [
                        {required: false, pattern: /^[1-9]\d{0,8}$/, message: '<?= \Yii::t('pc/banner', '排序必须在9位正整数内');?>'}
                    ],
                    pic_url: [
                        {required: true, message: '<?= \Yii::t('pc/banner', '图片不能为空');?>', trigger: ['blur', 'change']},
                    ]
                },
            };
        },
        methods: {
            edit: function(row) {
                this.dialog = true;
                if(row) {
                    this.showData = row;
                }else{
                    this.showData = {
                        title: '',
                        pic_url: '',
                        sort: 1,
                    };
                }
            },

            destroy: function(id){console.log(id)
                this.$confirm('<?= \Yii::t('pc/banner', '确认删除该记录吗');?>?', '<?= \Yii::t('pc/banner', '提示');?>', {
                    type: 'warning'
                }).then(() => {
                    request({
                        params: {
                            r: 'pc/banner/destroy',
                            id: id,
                        },
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.getList();
                        }else{
                            this.$alert(e.data.msg, '<?= \Yii::t('pc/banner', '提示');?>', {
                                confirmButtonText: '<?= \Yii::t('pc/banner', '确定');?>'
                            })
                        }
                    }).catch(e => {
                    });
                });
            },

            //分页
            pageChange(page) {
                loadList('pc/banner/index',page).then(e => {
                    this.list = e.list;
                    this.pagination = e.pagination;
                });
            },

            getList() {
                this.loading = true;
                request({
                    params: {
                        r: 'pc/banner/index',
                        page: this.page,
                    },
                }).then(e => {
                    if (e.data.code === 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                    }
                }).catch(e => {
                });
                this.loading = false;
            },

            save(){
                this.$refs.showData.validate((valid) => {
                    if (valid) {
                        this.loading = true;
                        request({
                            params: {
                                r: 'pc/banner/edit',
                            },
                            data: this.showData,
                            method: 'post'
                        }).then(e => {
                            if (e.data.code === 0) {
                                this.dialog = false;
                                this.getList();
                            }
                            this.loading = false;
                        }).catch(e => {
                        });
                    }
                });
            },

            singlePicUrl(e) {
                if (e.length) {
                    this.showData.pic_url = e[0].url;
                    this.$refs.showData.validateField('pic_url');
                }
            },
        },
        mounted() {
            this.getList();
        }
    })
</script>