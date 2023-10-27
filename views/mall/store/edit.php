<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */

Yii::$app->loadViewComponent('app-rich-text')
?>

<style>
    .form-body {
        padding: 20px 0;
        background-color: #fff;
        margin-bottom: 20px;
    }

    .form-button {
        margin: 0 !important;
    }

    .form-button .el-form-item__content {
        margin-left: 0 !important;
    }

    .button-item {
        padding: 9px 25px;
    }

    .del-btn {
        position: absolute;
        right: -8px;
        top: -8px;
        padding: 4px 4px;
        border-radius: 50%;
    }
</style>
<section id="app" v-cloak>
    <el-card class="box-card" shadow="never" style="border:0" body-style="background-color: #f3f3f3;padding: 10px 0 0;"
             v-loading="cardLoading">
        <div slot="header">
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><span style="color: #409EFF;cursor: pointer"
                                          @click="$navigate({r:'mall/store/index'})"><?= \Yii::t('mall/store', '门店管理');?></span></el-breadcrumb-item>
                <el-breadcrumb-item><?= \Yii::t('mall/store', '门店编辑');?></el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <div class="form-body">
            <el-form :model="ruleForm" :rules="rules" size="small" ref="ruleForm" label-width="120px">
                <el-row>
                    <el-col :span="12">
                        <el-form-item label="<?= \Yii::t('mall/store', '门店名称');?>" prop="name">
                            <el-input v-model="ruleForm.name" placeholder="<?= \Yii::t('mall/store', '请输入门店名称');?>"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '联系电话');?>" prop="mobile">
                            <el-input v-model="ruleForm.mobile" placeholder="<?= \Yii::t('mall/store', '请输入门店联系电话');?>"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '门店地址');?>" prop="address">
                            <el-input v-model="ruleForm.address" placeholder="<?= \Yii::t('mall/store', '请输入门店地址');?>"></el-input>
                        </el-form-item>
                        <el-form-item prop="latitude_longitude">
                            <template slot='label'>
                                <span><?= \Yii::t('mall/store', '门店经纬度');?></span>
                                <el-tooltip effect="dark" content="<?= \Yii::t('mall/store', '可在地图上选择位置');?>"
                                            placement="top">
                                    <i class="el-icon-info"></i>
                                </el-tooltip>
                            </template>
                            <el-input v-model="ruleForm.latitude_longitude" placeholder="<?= \Yii::t('mall/store', '请输入门店经纬度');?>"></el-input>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '地图');?>">
                            <app-map @map-submit="mapEvent"
                                     :address="ruleForm.address"
                                     :lat="ruleForm.latitude"
                                     :long="ruleForm.longitude">
                                <el-button size="mini"><?= \Yii::t('mall/store', '展开地图');?></el-button>
                            </app-map>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '门店评分');?>" prop="score">
                            <el-select v-model="ruleForm.score" placeholder="<?= \Yii::t('mall/store', '请选择');?>">
                                <el-option
                                        v-for="item in scoreOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '是否启用');?>" prop="score">
                            <el-switch :active-value="1" :inactive-value="0"
                                       v-model="ruleForm.status">
                            </el-switch>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="<?= \Yii::t('mall/store', '门店封面图');?>" prop="cover_url">
                            <app-attachment v-model="ruleForm.cover_url" :multiple="false" :max="1">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="<?= \Yii::t('mall/store', '建议尺寸');?>:150 * 150"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/store', '选择图片');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <app-image mode="aspectFill" width='80px' height='80px'
                                       :src="ruleForm.cover_url"></app-image>
                        </el-form-item>

                        <el-form-item label="<?= \Yii::t('mall/store', '门店轮播图');?>" prop="pic_url">
                            <app-attachment :max="6" @selected="picUrl" :multiple="true">
                                <el-tooltip class="item"
                                            effect="dark"
                                            content="<?= \Yii::t('mall/store', '建议尺寸');?>:750 * 360"
                                            placement="top">
                                    <el-button size="mini"><?= \Yii::t('mall/store', '选择图片');?></el-button>
                                </el-tooltip>
                            </app-attachment>
                            <div style="margin-top: 20px;">
                                <template v-if="ruleForm.pic_url.length">
                                    <draggable v-model="ruleForm.pic_url" flex="warp:warp">
                                        <div style="margin-right: 20px;position: relative;cursor: move;"
                                             v-for="(item, index) in ruleForm.pic_url"
                                             :key="item.id">
                                            <app-attachment @selected="updatePicUrl" :params="{'currentIndex': index}">
                                                <app-image mode="aspectFill"
                                                           width="80px"
                                                           height='80px'
                                                           :src="item.pic_url">
                                                </app-image>
                                            </app-attachment>
                                            <el-button class="del-btn"
                                                       size="mini" type="danger" icon="el-icon-close"
                                                       @click="delPic(index)"></el-button>
                                        </div>
                                    </draggable>
                                </template>
                                <template v-else>
                                    <app-image mode="aspectFill"
                                               width="80px"
                                               height='80px'
                                               :src="ruleForm.default_url">
                                    </app-image>
                                </template>
                            </div>
                        </el-form-item>
                        <el-form-item label="<?= \Yii::t('mall/store', '营业时间');?>" prop="start_time">
                            <el-time-select
                                    :disabled="ruleForm.is_all_day ? true : false"
                                    placeholder="<?= \Yii::t('mall/store', '起始时间');?>"
                                    v-model="ruleForm.start_time"
                                    :picker-options="{
      start: '00:00',
      step: '00:15',
      end: '23:45'
    }">
                            </el-time-select>
                            <el-time-select
                                    :disabled="ruleForm.is_all_day ? true : false"
                                    placeholder="<?= \Yii::t('mall/store', '结束时间');?>"
                                    v-model="ruleForm.end_time"
                                    :picker-options="{
      start: '00:00',
      step: '00:15',
      end: '23:45',
      minTime: ruleForm.start_time
    }">
                            </el-time-select>
                        </el-form-item>
                        <el-form-item>
                            <el-checkbox v-model="ruleForm.is_all_day" @change="allDayChange" :true-label="1" :false-label="0"><?= \Yii::t('mall/store', '全天营业');?></el-checkbox>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="<?= \Yii::t('mall/store', '门店描述');?>" prop="description">
                    <app-rich-text style="width: 455px" v-model="ruleForm.description"
                                   :value="ruleForm.description"></app-rich-text>
                </el-form-item>
            </el-form>
        </div>
        <el-button class="button-item" :loading="btnLoading" type="primary" @click="store('ruleForm')" size="small"><?= \Yii::t('mall/store', '保存');?>
        </el-button>
    </el-card>
</section>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                ruleForm: {
                    name: '',
                    mobile: '',
                    address: '',
                    latitude_longitude: '',
                    cover_url: '',
                    pic_url: [],
                    score: 5,
                    description: '',
                    business_hours: '',
                    start_time: '',
                    end_time: '',
                    is_all_day: 0,
                    status: 1,
                },
                scoreOptions: [
                    {
                        label: '1<?= \Yii::t('mall/store', '分');?>',
                        value: 1
                    },
                    {
                        label: '2<?= \Yii::t('mall/store', '分');?>',
                        value: 2
                    },
                    {
                        label: '3<?= \Yii::t('mall/store', '分');?>',
                        value: 3
                    },
                    {
                        label: '4<?= \Yii::t('mall/store', '分');?>',
                        value: 4
                    },
                    {
                        label: '5<?= \Yii::t('mall/store', '分');?>',
                        value: 5
                    },
                ],
                rules: {
                    name: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店名称');?>', trigger: 'change'},
                    ],
                    mobile: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店联系方式');?>', trigger: 'change'},
                    ],
                    address: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店地址');?>', trigger: 'change'},
                    ],
                    latitude_longitude: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店经纬度');?>', trigger: 'change'},
                        {
                            validator(rule, value, callback, source, options) {
                                let str = value.split(",");
                                if (str.length < 2) {
                                    callback("<?= \Yii::t('mall/store', '经纬度不合规范');?>")
                                } else {
                                    callback();
                                }
                            }
                        }
                    ],
                    business_hours: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店营业时间');?>', trigger: 'change'},
                    ],
                    description: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请输入门店描述');?>', trigger: 'change'},
                    ],
                    cover_url: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请添加门店封面图');?>', trigger: 'change'},
                    ],
                    pic_url: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请添加门店轮播图');?>', trigger: 'change'},
                    ],
                    start_time: [
                        {required: true, message: '<?= \Yii::t('mall/store', '请添加营业时间');?>', trigger: 'change'},
                    ],
                },
                btnLoading: false,
                cardLoading: false,
            };
        },
        methods: {
            store(formName) {
                this.$refs[formName].validate((valid) => {
                    let self = this;
                    if (valid) {
                        self.btnLoading = true;
                        request({
                            params: {
                                r: 'mall/store/edit'
                            },
                            method: 'post',
                            data: {
                                form: self.ruleForm,
                            }
                        }).then(e => {
                            self.btnLoading = false;
                            if (e.data.code == 0) {
                                self.$message.success(e.data.msg);
                                navigateTo({
                                    r: 'mall/store/index'
                                })
                            } else {
                                self.$message.error(e.data.msg);
                            }
                        }).catch(e => {
                            self.$message.error(e.data.msg);
                            self.btnLoading = false;
                        });
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            getDetail() {
                let self = this;
                self.cardLoading = true;
                request({
                    params: {
                        r: 'mall/store/edit',
                        id: getQuery('id')
                    },
                    method: 'get',
                }).then(e => {
                    self.cardLoading = false;
                    if (e.data.code == 0) {
                        self.ruleForm = e.data.data.detail;
                    } else {
                        self.$message.error(e.data.msg);
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            coverUrl(e) {
                if (e.length) {
                    this.ruleForm.cover_url = e[0].url;
                    this.$refs.ruleForm.validateField('cover_url');
                }
            },
            picUrl(e) {
                if (e.length) {
                    let self = this;
                    self.ruleForm.pic_url = [];
                    e.forEach(function (item, index) {
                        self.ruleForm.pic_url.push({
                            id: item.id,
                            pic_url: item.url
                        });
                    });
                    this.$refs.ruleForm.validateField('pic_url');
                }
            },
            mapEvent(e) {
                this.ruleForm.address = e.address;
                this.ruleForm.latitude_longitude = e.lat + ',' + e.long;
            },
            delPic(index) {
                this.ruleForm.pic_url.splice(index, 1);
            },
            updatePicUrl(e, params) {
                this.ruleForm.pic_url[params.currentIndex].id = e[0].id;
                this.ruleForm.pic_url[params.currentIndex].pic_url = e[0].url;
            },
            allDayChange(checked) {
                if (checked && (!this.ruleForm.start_time || !this.ruleForm.end_time)) {
                    this.ruleForm.start_time = '00:00';
                    this.ruleForm.end_time = '24:00';
                }
            },
        },
        mounted: function () {
            if (getQuery('id')) {
                this.getDetail();
            }
        }
    });
</script>
