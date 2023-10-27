<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2019/1/18
 * Time: 14:12
 */
Yii::$app->loadViewComponent('share/share-edit');
Yii::$app->loadViewComponent('share/app-share-level');
Yii::$app->loadViewComponent('share/app-batch');
Yii::$app->loadViewComponent('app-platform');
?>
<style>
    .el-tabs__header {
        font-size: 16px;
    }


    .table-body {
        padding: 20px;
        background-color: #fff;
    }

    .table-body .el-button {
        padding: 0!important;
        border: 0;
        margin: 0 5px;
    }

    .input-item {
        width: 250px;
        margin: 0 0 20px;
        display: inline-block;
    }

    .input-item .el-input__inner {
        border-right: 0;
    }

    .input-item .el-input__inner:hover{
        border: 1px solid #dcdfe6;
        border-right: 0;
        outline: 0;
    }

    .input-item .el-input__inner:focus{
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

    .batch {
        margin: 0 0 20px;
        display: inline-block;
    }

    .batch .el-button {
        padding: 9px 15px !important;
    }

    .form-item {
        margin: 10px 0;
        font-size: 14px;
        color: #9e9e9e;
    }

    .form-list .el-dialog__body {
        padding: 5px 20px;
    }

    .show-img {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.3);
        z-index: 3000;
        width: 100%;
        height: 100%;
    }
    .show-img img {
        max-height: 100%;
        max-width: 100%;
        position: fixed;
        z-index: 3024;
    }
</style>
<div id="app" v-cloak>
    <el-card shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;">
        <div slot="header">
            <span><?= \Yii::t('mall/share', '分销商列表');?></span>
            <el-form size="small" :inline="true" :model="search" style="float: right;margin-top: -5px;">
                <el-form-item>
                    <app-new-export-dialog-2
                            :params="search"
                            :field_list='exportList'
                            action_url="mall/share/index">
                    </app-new-export-dialog-2>
                </el-form-item>
            </el-form>
        </div>
        <div class="table-body">
            <app-platform v-model="search.platform" @change='toSearch'></app-platform>
            <el-select size="small" v-model="search.level" @change='toSearch' class="select">
                <el-option key="all" label="<?= \Yii::t('mall/share', '全部等级');?>" value=""></el-option>
                <el-option :key="index" :label="item.name" :value="item.level"
                           v-for="(item, index) in shareLevelList"></el-option>
            </el-select>
            <div class="input-item">
                <el-input @keyup.enter.native="loadData" size="small" placeholder="<?= \Yii::t('mall/share', '请输入ID');?>" v-model="search.keyword" clearable @clear="toSearch">
                    <el-button slot="append" icon="el-icon-search" @click="toSearch"></el-button>
                </el-input>
            </div>
            <div class="batch">
                <app-batch :choose-list="choose_list" :share-level-list="shareLevelList" @to-search="loadData"></app-batch>
            </div>
            <div style="float: right">
                <el-button type="primary" size="small" style="padding: 9px 15px !important;" @click="editClick"><?= \Yii::t('mall/share', '添加分销商');?></el-button>
            </div>
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="<?= \Yii::t('mall/share', '全部');?>" name="-1"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('mall/share', '未审核');?>" name="0"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('mall/share', '已通过');?>" name="1"></el-tab-pane>
                <el-tab-pane label="<?= \Yii::t('mall/share', '已拒绝');?>" name="2"></el-tab-pane>
                <el-table :data="list" border v-loading="loading" size="small" style="margin-bottom: 15px;"
                          @selection-change="handleSelectionChange">
                    <el-table-column align='center' type="selection" width="60"></el-table-column>
                    <el-table-column prop="user_id" width="80" label="<?= \Yii::t('mall/share', '用户ID');?>"></el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '基本信息');?>" width="200">
                        <template slot-scope="scope">
                            <app-image style="float: left;margin-right: 5px;" mode="aspectFill" :src="scope.row.avatar"></app-image>
                            <div>{{scope.row.nickname}}</div>
                            <div>
                                <img :src="scope.row.platform_icon" width="24px" height="24px" alt="">
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '姓名');?>" prop="name" width="180">
                        <el-table-column label="<?= \Yii::t('mall/share', '手机号');?>" prop="mobile" width="180">
                            <template slot-scope="scope">
                                <div>
                                    <div v-if="name_id != scope.row.id" flex="dir:left cross:center">
                                        <span>{{scope.row.name}}</span>
                                        <el-button class="edit-sort" type="text" @click="editName(scope.row)">
                                            <img src="statics/img/mall/order/edit.png" alt="">
                                        </el-button>
                                    </div>
                                    <div style="display: flex;align-items: center" v-else>
                                        <el-input style="min-width: 70px" size="mini"
                                                  v-model="name"
                                                  autocomplete="off"></el-input>
                                        <el-button type="text" style="color: #F56C6C;padding: 0 5px"
                                                   icon="el-icon-error"
                                                   circle @click="quit()"></el-button>
                                        <el-button type="text"
                                                   style="margin-left: 0;color: #67C23A;padding: 0 5px"
                                                   icon="el-icon-success" circle @click="changeNameSubmit(scope.row)">
                                        </el-button>
                                    </div>
                                </div>
                                <div>{{scope.row.mobile}}</div>
                            </template>
                        </el-table-column>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '可提现佣金');?>" prop="name">
                        <el-table-column label="<?= \Yii::t('mall/share', '累计佣金');?>" prop="mobile">
                            <template slot-scope="scope">
                                <div>{{scope.row.money}}</div>
                                <div>{{scope.row.total_money}}</div>
                            </template>
                        </el-table-column>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '推荐人');?>" prop="parent_name"></el-table-column>
                    <el-table-column width='200' label="<?= \Yii::t('mall/share', '下级用户');?>">
                        <template slot-scope="scope">
                            <template v-for="(item, key, index) in share_name" v-if="scope.row[key] !== undefined">
                                <el-button type="text" @click="dialogChildShow(scope.row, index + 1)">
                                    {{item}}：{{scope.row[key]}}
                                </el-button>
                                <br>
                            </template>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '分销商等级');?>" width="120" prop="level">
                        <template slot-scope="scope">
                            <el-tag size="small" type="info" v-if="scope.row.level == 0">{{scope.row.level_name}}</el-tag>
                            <el-tag size="small" v-else>{{scope.row.level_name}}</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '状态');?>" width="80" prop="status">
                        <template slot-scope="scope">
                            <el-tag size="small" type="info" v-if="scope.row.status == 0"><?= \Yii::t('mall/share', '待审核');?></el-tag>
                            <el-tag size="small" v-if="scope.row.status == 1"><?= \Yii::t('mall/share', '通过');?></el-tag>
                            <el-tag size="small" type="danger" v-if="scope.row.status == 2"><?= \Yii::t('mall/share', '拒绝');?></el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '时间');?>" width="200">
                        <template slot-scope="scope">
                            <div v-if="scope.row.status >= 0"><?= \Yii::t('mall/share', '申请时间');?><br>{{scope.row.apply_at}}</div>
                            <div v-if="scope.row.status == 1"><?= \Yii::t('mall/share', '成为分销商时间');?><br>{{scope.row.become_at}}</div>
                            <div v-if="scope.row.status == 2"><?= \Yii::t('mall/share', '拒绝时间');?><br>{{scope.row.updated_at}}</div>
                        </template>
                    </el-table-column>
                    </el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '备注信息');?>" prop="content"></el-table-column>
                    <el-table-column label="<?= \Yii::t('mall/share', '操作');?>" width="300px" fixed="right">
                        <template slot-scope="scope">
                            <template v-if="scope.row.status == 0">
                                <el-button type="text" size="mini" circle style="margin-top: 10px" @click.native="apply(scope.row.user_id, 1)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '同意');?>" placement="top">
                                        <img src="statics/img/mall/pass.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="apply(scope.row.user_id, 2)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '拒绝');?>" placement="top">
                                        <img src="statics/img/mall/nopass.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                            <template v-if="scope.row.status == 1">
                                <el-button type="text" size="mini" circle style="margin-top: 10px" @click.native="order(scope.row.user_id)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '查看订单');?>" placement="top">
                                        <img src="statics/img/mall/share/order.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="cash(scope.row.user_id)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '提现详情');?>" placement="top">
                                        <img src="statics/img/mall/share/detail.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="content(scope.row)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '添加备注');?>" placement="top">
                                        <img src="statics/img/mall/order/add_remark.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="qrcode(scope.row)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '查看分销二维码');?>" placement="top">
                                        <img src="statics/img/mall/share/qr.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="editLevel(scope.row)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '修改分销商等级');?>" placement="top">
                                        <img src="statics/img/mall/edit.png" alt="">
                                    </el-tooltip>
                                </el-button>
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="deleteShare(scope.row.id)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '删除');?>" placement="top">
                                        <img src="statics/img/mall/del.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                            <template v-if="scope.row.form">
                                <el-button type="text" size="mini" circle style="margin-left: 10px;margin-top: 10px" @click.native="showForm(scope.row)">
                                    <el-tooltip class="item" effect="dark" content="<?= \Yii::t('mall/share', '查看表单信息');?>" placement="top">
                                        <img src="statics/img/mall/share/detail.png" alt="">
                                    </el-tooltip>
                                </el-button>
                            </template>
                        </template>
                    </el-table-column>
                </el-table>
            </el-tabs>
            <div flex="dir:right" style="margin-top: 20px;">
                <el-pagination
                        hide-on-single-page
                        background :page-size="pagination.pageSize"
                        @current-change="pageChange"
                        layout="prev, pager, next, jumper" :current-page="pagination.current_page"
                        :total="pagination.totalCount">
                </el-pagination>
            </div>
        </div>
    </el-card>
    <el-dialog
            title="<?= \Yii::t('mall/share', '下线情况');?>"
            :visible.sync="dialogChild"
            width="40%">
        <div>
            <el-table :data="childList" border v-loading="dialogLoading">
                <el-table-column type="index" label="<?= \Yii::t('mall/share', '序号');?>"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/share', '分销商');?>">
                    <template slot-scope="scope">
                        <span>{{select.nickname}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/share', '下线等级');?>" prop="nickname">
                    <template slot-scope="scope">
                        <span v-if="select.status == 1">{{share_name.first}}</span>
                        <span v-if="select.status == 2">{{share_name.second}}</span>
                        <span v-if="select.status == 3">{{share_name.third}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="<?= \Yii::t('mall/share', '昵称');?>" prop="nickname"></el-table-column>
                <el-table-column label="<?= \Yii::t('mall/share', '成为下线时间');?>" prop="junior_at"></el-table-column>
            </el-table>
        </div>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogChild = false"><?= \Yii::t('mall/share', '取消');?></el-button>
            <el-button type="primary" @click="dialogChild = false"><?= \Yii::t('mall/share', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-dialog title="<?= \Yii::t('mall/share', '添加备注');?>" :visible.sync="dialogContent">
        <el-form :model="contentForm">
            <el-form-item label="<?= \Yii::t('mall/share', '备注');?>">
                <el-input type="textarea" v-model="contentForm.content" autocomplete="off"></el-input>
                <el-input style="display: none" :readonly="true" v-model="contentForm.id"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="dialogContent = false"><?= \Yii::t('mall/share', '取消');?></el-button>
            <el-button type="primary" @click="contentConfirm" :loading="contentBtnLoading"><?= \Yii::t('mall/share', '确定');?></el-button>
        </div>
    </el-dialog>
    <el-dialog class="form-list" title="<?= \Yii::t('mall/share', '查看表单信息');?>" :visible.sync="formDialog" width="40%">
        <div class="form-item" v-for="(item,index) in form" :key="index" v-if="item.value" flex="dir:left cross:top">
            <div style="margin-right: 10px;flex-shrink: 0">{{item.label}}:</div>
            <div v-if="item.key != 'img_upload'" style="color: #353535;">{{item.value}}</div>
            <div v-else>
                <img v-if="typeof(item.value) == 'string'" :src="item.value" width="80" height="80" @click="toLook(item.value)">
                <div v-else>
                    <img v-if="img" v-for="img in item.value" :src="img" width="80" height="80" @click="toLook(img)">
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button size="small" @click="formDialog = false;form=[]"><?= \Yii::t('mall/share', '取消');?></el-button>
            <el-button size="small" type="primary" @click="formDialog = false;form=[]"><?= \Yii::t('mall/share', '确定');?></el-button>
        </span>
    </el-dialog>
    <el-dialog class="showqr" :visible.sync="showqr" width="20%" center>
        <div style="display: flex;justify-content: center">
            <app-image style="float: left;margin-right: 5px;" mode="aspectFill" :src="avatar" width="50" height="50"></app-image>
            <div>
                <div style="margin-bottom: 5px">{{nickname}}</div>
                <div><?= \Yii::t('mall/share', '分销二维码');?></div>
            </div>
        </div>
        <template v-for="item in qrimg">
            <app-image :src="item" style="margin: 20px auto 10px" height='200' width='200'></app-image>
        </template>
        <span slot="footer" class="dialog-footer">
            <el-button type="primary" style="margin-bottom: 10px;" size="small" @click="down"><?= \Yii::t('mall/share', '保存二维码图片');?></el-button>
        </span>
    </el-dialog>
    <div @click="bigImg = ''" class="show-img" flex="main:center cross:center" v-if="bigImg.length > 0">
        <img :src="bigImg" @click.stop="">
    </div>
    <share-edit v-model="edit.show" @save="loadData"></share-edit>
    <app-share-level v-model="level.show" :share="level.share" @success="levelSuccess"></app-share-level>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data: {
            qrimg: [],
            showqr: false,
            avatar: '',
            nickname: '',
            search: {
                keyword: '',
                status: -1,
                page: 1,
                platform: '',
                level: ''
            },
            loading: false,
            activeName: '-1',
            list: [],
            pagination: {},
            dialogChild: false,
            dialogLoading: false,
            formDialog: false,
            form: [],
            childList: [],
            share_name: {
                first: '<?= \Yii::t('mall/share', '一级');?>',
                second: '<?= \Yii::t('mall/share', '二级');?>',
                third: '<?= \Yii::t('mall/share', '三级');?>'
            },
            bigImg: '',
            select: {
                nickname: '',
                status: 'first',
            },
            dialogContent: false,
            contentForm: {
                content: '',
                id: ''
            },
            contentBtnLoading: false,
            exportList: [],
            edit: {
                show: false,
            },
            level: {
                show: false,
                share: null,
            },
            shareLevelList: [],
            choose_list: [],
            platformList: [],

            name: '',
            name_id: 0,
        },
        mounted() {
            this.loadData();
        },
        methods: {
            editName(row) {
                this.name_id = row.id;
                this.name = row.name;
            },
            quit() {
                this.name = null;
                this.name_id = 0;
            },
            changeNameSubmit(row) {
                let self = this;
                request({
                    params: {
                        r: 'mall/share/edit-name'
                    },
                    method: 'post',
                    data: {
                        id: row.user_id,
                        name: self.name,
                    }
                }).then(e => {
                    self.loading = false;
                    if (e.data.code === 0) {
                        row.name = self.name;
                        self.$message.success(e.data.msg);
                        self.quit();
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    self.$message.error(e.data.msg);
                    self.loading = false;
                });
            },
            toLook(img) {
                console.log(img)
                this.bigImg = img;
            },
            showForm(row) {
                this.formDialog = true;
                this.form = row.form
            },
            down() {
                for (let i = 0; i < this.qrimg.length; i++) {
                    let image = new Image();
                    image.setAttribute("crossOrigin", "anonymous");
                    image.onload = function() {
                        let canvas = document.createElement("canvas");
                        canvas.width = image.width;
                        canvas.height = image.height;
                        let context = canvas.getContext("2d");
                        context.drawImage(image, 0, 0, image.width, image.height);
                        let url = canvas.toDataURL("image/png");
                        let a = document.createElement("a");
                        let event = new MouseEvent("click");
                        a.download = this.nickname || "photo";
                        a.href = url;
                        a.dispatchEvent(event);
                    };
                    image.src = this.qrimg[i];
                }
            },

            confirmSubmit() {
                this.search.status = this.activeName
            },
            loadData() {
                this.loading = true;
                let params = {
                    r: 'mall/share/index-data'
                };
                params = Object.assign(params, this.search);
                request({
                    params: params,
                    method: 'get',
                }).then(e => {
                    this.loading = false;
                    if (e.data.code == 0) {
                        this.list = e.data.data.list;
                        this.pagination = e.data.data.pagination;
                        this.exportList = e.data.data.export_list;
                        this.shareLevelList = e.data.data.shareLevelList;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    this.loading = false;
                });
            },
            pageChange(page) {
                this.search.page = page;
                this.loadData();
            },
            handleClick(tab, event) {
                this.search.page = 1;
                this.search.status = this.activeName;
                this.loadData()
            },
            apply(user_id, status) {
                this.$prompt('<?= \Yii::t('mall/share', '请输入原因');?>', '<?= \Yii::t('mall/share', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/share', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/share', '取消');?>',
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = '<?= \Yii::t('mall/share', '执行中');?>...';
                            request({
                                params: {
                                    r: 'mall/share/apply',
                                    user_id: user_id,
                                    status: status,
                                    reason: instance.inputValue
                                },
                                method: 'get'
                            }).then(e => {
                                done();
                                instance.confirmButtonLoading = false;
                                if (e.data.code == 0) {
                                    this.loadData();
                                } else {
                                    this.$message.error(e.data.msg);
                                }
                            }).catch(e => {
                                done();
                                instance.confirmButtonLoading = false;
                            });
                        } else {
                            done();
                        }
                    }
                }).then(({value}) => {
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '<?= \Yii::t('mall/share', '取消输入');?>'
                    });
                });
            },
            order(id) {
                navigateTo({
                    r: 'mall/share/order',
                    id: id
                })
            },
            cash(user_id) {
                navigateTo({
                    r: 'mall/share/cash',
                    user_id: user_id
                })
            },
            deleteShare(id) {
                this.$prompt('<?= \Yii::t('mall/share', '此操作将删除分销商');?>', '<?= \Yii::t('mall/share', '提示');?>', {
                    confirmButtonText: '<?= \Yii::t('mall/share', '确定');?>',
                    cancelButtonText: '<?= \Yii::t('mall/share', '取消');?>',
                    type: 'warning',
                    center: true,
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = '<?= \Yii::t('mall/share', '执行中');?>...';
                            request({
                                params: {
                                    r: 'mall/share/delete',
                                    id: id,
                                    reason: instance.inputValue,
                                },
                                method: 'get'
                            }).then(e => {
                                done();
                                instance.confirmButtonLoading = false;
                                if (e.data.code == 0) {
                                    this.loadData();
                                } else {
                                    this.$message.error(e.data.msg);
                                }
                            }).catch(e => {
                                done();
                                instance.confirmButtonLoading = false;
                                this.$message.error(e.data.msg);
                            });
                        } else {
                            done();
                        }
                    }
                }).then(() => {
                }).catch(e => {
                    this.$message({
                        type: 'info',
                        message: '<?= \Yii::t('mall/share', '取消了操作');?>'
                    });
                });
            },

            toSearch() {
                this.search.page = 1;
                this.loadData();
            },
            codereq({headers, row}) {
                return new Promise((resolve, reject) => {
                    request({
                        params: {
                            r: 'mall/share/qrcode',
                            id: row.id
                        },
                        method: 'get',
                        headers: headers
                    }).then(e => {
                        if (e.data.code === 0) {
                            this.qrimg.push(...e.data.data);
                        } else {
                            this.$message.error(e.data.msg);
                        }
                        resolve();
                    }).catch(() => {
                        reject();
                    });
                });
            },
            qrcode(row) {
                this.qrimg = [];
                this.avatar = row.avatar;
                this.nickname = row.nickname;
                this.loading = true;
                let reqList = [];
                if (row.platform === 'ttapp') {
                    reqList.push(this.codereq({
                        headers: {
                            "X-tt-platform": 'toutiao'
                        }, row}));
                    reqList.push(this.codereq({
                        headers: {
                            "X-tt-platform": 'douyin'
                        }, row}));
                } else {
                    reqList.push(this.codereq({row}));
                }
                Promise.all(reqList).then(() => {
                    this.showqr = true;
                    this.loading = false;
                }).catch(() => {
                    this.loading = false;
                });

            },
            content(share) {
                this.dialogContent = true;
                this.contentForm = {
                    content: share.content,
                    id: share.id
                }
            },
            contentConfirm() {
                this.contentBtnLoading = true;
                request({
                    params: {
                        r: 'mall/share/content',
                        content: this.contentForm.content,
                        id: this.contentForm.id
                    },
                    method: 'get'
                }).then(e => {
                    this.contentBtnLoading = false;
                    if (e.data.code == 0) {
                        this.dialogContent = false;
                        this.loadData();
                        this.$message.success('<?= \Yii::t('mall/share', '保存成功');?>');
                    } else {
                        this.$message.error(e.data.data.msg);
                    }
                }).catch(e => {
                    this.contentBtnLoading = false;
                    console.log(e)
                    this.$message.error('<?= \Yii::t('mall/share', '未知错误');?>');
                });
            },
            dialogChildShow(share, status) {
                this.dialogChild = true;
                this.dialogLoading = true;
                this.select = {
                    nickname: share.nickname,
                    status: status
                };
                request({
                    params: {
                        r: 'mall/share/team',
                        status: status,
                        id: share.user_id
                    },
                    method: 'get'
                }).then(e => {
                    this.dialogLoading = false;
                    if (e.data.code == 0) {
                        this.childList = e.data.data.list;
                    }
                }).catch(e => {
                    this.dialogLoading = false;
                    this.$message.error('<?= \Yii::t('mall/share', '未知错误');?>');
                });
            },
            editClick() {
                this.edit.show = true;
            },
            editLevel(share) {
                this.level.show = true;
                this.level.share = share;
            },
            handleSelectionChange(val) {
                let self = this;
                self.choose_list = [];
                val.forEach(function (item) {
                    self.choose_list.push(item.id);
                })
            },
            levelSuccess() {
                this.loadData();
            }
        }
    });
</script>