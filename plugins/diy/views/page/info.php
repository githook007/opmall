<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/4/23
 * Time: 15:09
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
            <span><?= \Yii::t('plugins/diy', '表单提交信息');?></span>
            <app-export-dialog action_url='index.php?r=plugin/diy/mall/page/info' style="float: right;margin-top: -5px"
                               :field_list='export_list' :params="search"></app-export-dialog>
        </div>
        <div style="padding: 20px;background-color: #fff">
            <el-date-picker size="small" style="margin-bottom: 20px;" @change="changeTime" v-model="time" type="datetimerange" value-format="yyyy-MM-dd HH:mm:ss" range-separator="<?= \Yii::t('plugins/diy', '至');?>" start-placeholder="<?= \Yii::t('plugins/diy', '开始日期');?>" end-placeholder="<?= \Yii::t('plugins/diy', '结束日期');?>">
            </el-date-picker>
            <el-table v-loading="loading" border :data="list" style="margin-bottom: 15px;">
                <el-table-column prop="user_id" label="<?= \Yii::t('plugins/diy', '用户ID');?>" width="150px"></el-table-column>
                <el-table-column prop="nikename" label="<?= \Yii::t('plugins/diy', '用户信息');?>">
                    <template slot-scope="scope">
                        <app-image mode="aspectFill" style="float: left;margin-right: 8px" :src="scope.row.avatar"></app-image>
                        <div>{{scope.row.nickname}}</div>
                    </template>
                </el-table-column>
                <el-table-column prop="created_at" label="<?= \Yii::t('plugins/diy', '提交时间');?>">
                </el-table-column>
                <el-table-column prop="form_data" label="<?= \Yii::t('plugins/diy', '表单信息');?>">
                    <template slot-scope="scope">
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/diy', '查看详情');?>" placement="top">
                            <img style="cursor: pointer" @click="toDetail(scope.row.form_data)" src="statics/img/mall/order/detail.png"
                                 alt="">
                        </el-tooltip>
                        <el-tooltip class="item" effect="dark" content="<?= \Yii::t('plugins/diy', '删除');?>" placement="top">
                            <img style="cursor: pointer;margin-left: 10px;" @click="toDelete(scope.row.id)" src="statics/img/mall/del.png"
                                 alt="">
                        </el-tooltip>
                    </template>
                </el-table-column>
            </el-table>
            <div  flex="dir:right">
                <div></div>
                <div>
                    <el-pagination
                            background
                            :page-size="pagination.pageSize"
                            @current-change="pageChange"
                            layout="prev, pager, next, jumper" :current-page="pagination.current_page"
                            :total="pagination.totalCount">
                    </el-pagination>
                </div>
            </div>
        </div>
    </el-card>
    <el-dialog title="<?= \Yii::t('plugins/diy', '表单信息');?>" :visible.sync="dialogTableVisible">
<!--        <el-table :data="detail">-->
<!--            <el-table-column property="name" label="--><?//= \Yii::t('plugins/diy', '标签名称');?><!--" width="240"></el-table-column>-->
<!--            <el-table-column property="value" label="--><?//= \Yii::t('plugins/diy', '填写内容');?><!--">-->
<!--                <template slot-scope="scope">-->
<!--                    <template v-if="scope.row.key=='img_upload' && scope.row.value && [`,`,``].indexOf(scope.row.value.toString()) === -1">-->
<!--                        <img v-for="img in scope.row.value"-->
<!--                             @click="toLook(img)"-->
<!--                             style="height: 100px;width: 100px;cursor: pointer"-->
<!--                             :src="img"-->
<!--                             alt=""-->
<!--                        >-->
<!--                    </template>-->
<!--                    <div v-else>{{scope.row.value}}</div>-->
<!--                </template>-->
<!--            </el-table-column>-->
<!--        </el-table>-->
        <div style="width: 100%;padding: 0 20px 28px">
            <el-row :gutter="20" class="diy-info-style" style="font-weight: bold" flex="dir:left cross:center">
                <el-col :span="10" style="padding: 15px 0 15px 20px">表单名称</el-col>
                <el-col :span="10" style="padding: 15px 0 15px 20px">填写内容</el-col>
            </el-row>
            <el-row v-for="(info,i) of detail" :key="i" :gutter="20" class="diy-info-style" flex="dir:left cross:center" v-if="!(info.key === 'button' && info.value.is_pay == 0)">
                <el-col :span="10" style="padding: 15px 0 15px 20px">{{info.key === 'button' ? '支付信息' : info.label}}</el-col>
                <el-col :span="10" style="padding: 15px 0 15px 20px">
                    <div v-if="info.value && [`,`,``].indexOf(info.value.toString()) === -1">
                        <template v-if="info.key=== 'uvideo'">
                            <div v-for="(item,index) in info.value" :key="index" style="position: relative;display: inline-block">
                                <video :src="item"
                                       style="height: 50px;width: 50px;border-radius: 5px;margin-right: 5px"
                                ></video>
                                <el-image
                                        style="position:absolute;height: 30px;width: 30px;
                                    border-radius: 5px;margin-right: 5px;left: 10px;top: 10px;cursor:pointer"
                                        src="statics/img/mall/diy/play.png"
                                        @click="playVideo(item)"
                                ></el-image>
                            </div>
                        </template>
                        <template v-else-if="info.key === 'img_upload' || info.key === 'uimage'">
                            <el-image v-for="img in info.value"
                                      :preview-src-list="[img]"
                                      :src="img"
                                      style="height: 50px;width: 50px;border-radius: 5px;margin-right: 5px"
                            ></el-image>
                        </template>
                        <template v-else-if="info.key === 'button' && info.value.is_pay == 1">
                            <div>
                                <span v-if="info.value.title">感谢您购买{{info.value.title}}</span>
                            </div>
                            <div>
                                <div v-if="info.value.title" style="height: 10px;"></div>
                                已成功支付{{pay_price}}元
                            </div>
                        </template>
                        <template v-else-if="info.key === 'calendar'">
                            <div v-if="info.value.before && info.value.after">{{info.value.before}}~{{info.value.after}}</div>
                            <div v-else-if="info.value.fulldate">{{info.value.fulldate}}</div>
                            <div v-else>{{info.value ? info.value : '未填写'}}</div>
                        </template>
                        <template v-else-if="info.key === 'calendar'">
                            <div v-if="info.value.before && info.value.after">{{info.value.before}}~{{info.value.after}}</div>
                            <div v-else-if="info.value.fulldate">{{info.value.fulldate}}</div>
                            <div v-else>{{info.value ? info.value : '未填写'}}</div>
                        </template>
                        <template v-else-if="info.key === 'menu'">

                            <div v-if="info.value.type == 'date' && info.value.begin_at && info.value.end_at">{{info.value.begin_at}}~{{info.value.end_at}}</div>
                            <div v-else-if="info.value.type == 'date' && info.value.alone_at">{{info.value.alone_at}}</div>
                            <div v-else>{{info.value.text ? info.value.text : '未填写'}}</div>
                        </template>
                        <template v-else-if="info.key === 'agreement'">
                            {{info.value.is_check ? '同意' : '未同意'}}
                        </template>
                        <template v-else-if="info.key === 'phone'">
                            {{info.value ? info.value.mobile : '未验证'}}
                        </template>
                        <template v-else-if="info.key === 'switch'">
                            {{info.value ? '开启' : '关闭'}}
                        </template>
                        <template v-else-if="info.key === 'input'">
                            {{info.value.text ? info.value.text : '未填写'}}
                        </template>
                        <template v-else-if="info.key === 'send_data'">
                            <div class="send-data" style="font-size: 14px;">
                                <div v-if="info.value.send_balance > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送金额</div>
                                    <div>{{info.value.send_balance}}元</div>
                                </div>
                                <div v-if="info.value.send_integral > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送积分</div>
                                    <div>{{info.value.send_integral}}积分</div>
                                </div>
                                <div v-if="info.value.send_member_name" flex="dir:left cross:top">
                                    <div class="send-label">赠送会员</div>
                                    <div>{{info.value.send_member_name}}</div>
                                </div>
                                <div v-if="info.value.send_coupon_list && info.value.send_coupon_list.length > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送优惠券</div>
                                    <div>
                                        <div :key="idx" v-for="(coupon,idx) in info.value.send_coupon_list">{{coupon.send_num}}张{{coupon.name}}</div>
                                    </div>
                                </div>
                                <div v-if="info.value.send_card_list && info.value.send_card_list.length > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送卡券</div>
                                    <div>
                                        <div :key="idx" v-for="(card,idx) in info.value.send_card_list">{{card.num}}张{{card.name}}</div>
                                    </div>
                                </div>
                                <div v-if="info.value.send_scratch > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送抽奖机会</div>
                                    <div>刮刮卡抽奖机会{{info.value.send_scratch}}次</div>
                                </div>
                                <div v-if="info.value.send_pond > 0" flex="dir:left cross:top">
                                    <div class="send-label">赠送抽奖机会</div>
                                    <div>九宫格抽奖机会{{info.value.send_pond}}次</div>
                                </div>
                            </div>
                        </template>
                        <div v-else>{{info.value ? info.value : '未填写'}}</div>
                    </div>
                    <div v-else>{{info.value ? info.value : '未填写'}}</div>
                </el-col>
            </el-row>
<!--            <div style="margin-top: 50px">-->
<!--                <div flex="main:justify cross:center" style="margin-bottom: 20px;">-->
<!--                    <div flex="dir:left cross:center">-->
<!--                        <img style="cursor: pointer" src="statics/img/mall/list/info.png" alt="">-->
<!--                        <div>回复</div>-->
<!--                    </div>-->
<!--                    <div v-if="!edit_reply" @click="edit_reply = true" class="edit-btn" flex="main:center cross:center">-->
<!--                        <img src="statics/img/mall/icon_reply_edit.png" alt="">-->
<!--                        <div>修改</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="reply-content" v-if="!edit_reply"><pre>{{reply_content}}</pre></div>-->
<!--                <el-input v-else type="textarea" placeholder="请输入回复内容" :row="30"-->
<!--                          v-model="reply_content"-->
<!--                          :autosize="{ minRows: 6}">-->
<!--                </el-input>-->
<!--            </div>-->
<!--            <div style="margin-top: 20px" v-if="edit_reply" flex="main:center">-->
<!--                <el-button :loading="btnLoading" size="small" type="primary" @click="dialogTableVisible = false;reply_content=''" style="padding: 9px 29px">取消</el-button>-->
<!--                <el-button :loading="btnLoading" size="small" type="primary" @click="tabSubmit" style="margin-left: 30px;padding: 9px 29px">确定</el-button>-->
<!--            </div>-->
        </div>
<!--        <el-dialog width="50%" :visible.sync="innerVisible" append-to-body>-->
<!--            <img style="width: 100%" :src="img" alt="">-->
<!--        </el-dialog>-->
    </el-dialog>
</div>
<script>
    new Vue({
        el: '#app',
        data() {
            return {
                loading: false,
                innerVisible: false,
                list: [],
                img: '',
                detail: [],
                time: [],
                page: 1,
                pagination: [],
                dialogTableVisible: false,
                export_list: [],
                search: {
                    date_start: '',
                    date_end: ''
                }
            };
        },
        created() {
            this.loadData();
        },
        methods: {
            pageChange(e) {
                this.page = e;
                this.loadData();
            },

            changeTime(page) {
                this.page = 1;
                this.loadData();
            },

            toDetail(e) {
                this.dialogTableVisible = !this.dialogTableVisible;
                this.detail = JSON.parse(JSON.stringify(e));
            },

            toLook(e) {
                this.innerVisible = !this.innerVisible;
                this.img = e;
            },

            toDelete(res) {
                let id = res;
                this.$confirm("<?= \Yii::t('plugins/diy', '是否删除该条记录');?>", "<?= \Yii::t('plugins/diy', '提示');?>", {
                    confirmButtonText: "<?= \Yii::t('plugins/diy', '确定');?>",
                    cancelButtonText: "<?= \Yii::t('plugins/diy', '取消');?>",
                    type: 'warning',
                    center: true,
                    beforeClose: (action, instance, done) => {
                        if (action === 'confirm') {
                            instance.confirmButtonLoading = true;
                            instance.confirmButtonText = "<?= \Yii::t('plugins/diy', '执行中');?>";
                            request({
                                params: {
                                    r: 'plugin/diy/mall/page/info-del',
                                },
                                data:{
                                    id: id,
                                },
                                method: 'post'
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
                        message: "<?= \Yii::t('plugins/diy', '取消了操作');?>"
                    });
                });
            },

            loadData() {
                this.loading = true;
                if(this.time) {
                    this.search.date_start = this.time[0];
                    this.search.date_end = this.time[1];
                }else {
                    this.search.date_start = '';
                    this.search.date_end = '';
                }
                this.$request({
                    params: {
                        r: 'plugin/diy/mall/page/info',
                        page: this.page,
                        date_start: this.search.date_start,
                        date_end: this.search.date_end,
                    }
                }).then(response => {
                    this.loading = false;
                    if (response.data.code === 0) {
                        this.list = response.data.data.list;
                        this.pagination = response.data.data.pagination;
                        this.export_list = response.data.data.export_list;
                    }
                }).catch(e => {
                });
            }
        },
    });
</script>