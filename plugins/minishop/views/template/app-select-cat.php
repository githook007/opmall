<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 4:12 下午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
?>
<style>
    .app-add-cat .app-goods-cat-list {
        border: 1px solid #E8EAEE;
        border-radius: 5px;
        margin-top: -5px;
        padding: 10px 0;
        overflow: scroll;
        height: 400px;
    }

    .app-add-cat .el-dialog__body h3 {
        font-weight: bold;
        color: #999999;
    }

    .app-add-cat .app-goods-cat-list .cat-item {
        cursor: pointer;
        padding: 5px 10px;
        line-height: 40px;
    }

    .app-add-cat .app-goods-cat-list .active {
        background: #FAFAFA;
    }

    .app-add-cat .el-radio {
        margin-right: 0;
        margin-top: 13px;
    }
</style>
<div id="app-select-cat" v-cloak>
    <el-dialog title="<?= \Yii::t('plugins/minishop', '选择分类');?>" width="1100px" :visible.sync="catDialogVisible" :before-close="cancel" :show-close="false">
        <div class="app-add-cat">
            <el-row v-loading="catLoading" :gutter="20" style="margin-top: -30px;">
                <template v-if="catList.length > 0">
                    <el-col :span="8">
                        <h3><?= \Yii::t('plugins/minishop', '一级分类');?></h3>
                        <div class="app-goods-cat-list active">
                            <div :class="{'active': current1 == option.value ? true : false}" class="cat-item"
                                 flex="dir:left box:first"
                                 v-for="(option,index) in catList"
                                 :key="option.value">
                                <span style="display: none;">{{option.label}}</span>
                                <div @click="selectCats(option,1)" flex="box:last cross:center">
                                    <span>{{option.label}}</span>
                                    <i v-if="option.children" class="el-icon-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="8" v-if="children.length > 0">
                        <h3><?= \Yii::t('plugins/minishop', '二级分类');?></h3>
                        <div class="app-goods-cat-list">
                            <div :class="{'active': current2 == option.value ? true : false}" class="cat-item"
                                 flex="dir:left box:first"
                                 v-for="(option,index) in children"
                                 :key="option.value">
                                <span style="display: none;">{{option.label}}</span>
                                <div @click="selectCats(option,2)" flex="box:last cross:center">
                                    <span>{{option.label}}</span>
                                    <i v-if="option.children" class="el-icon-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </el-col>
                    <el-col :span="8" v-if="third.length > 0">
                        <h3><?= \Yii::t('plugins/minishop', '三级分类');?></h3>
                        <div class="app-goods-cat-list">
                            <div :class="{'active': current3 == option.value ? true : false}" class="cat-item"
                                 flex="dir:left box:first"
                                 v-for="(option,index) in third"
                                 :key="option.value">
                                <el-radio @change="optionClick(option)" v-model="select_cat.value"
                                          :label="option.value" size="mini">
                                    <span style="display: none;">{{option.label}}</span>
                                </el-radio>
                                <div @click="optionClick(option)" flex="box:last cross:center">
                                    <span>{{option.label}}</span>
                                </div>
                            </div>
                        </div>
                    </el-col>
                </template>
            </el-row>
        </div>
        <span slot="footer" class="dialog-footer">
                <el-button size='small' @click="cancel"><?= \Yii::t('plugins/minishop', '取消');?></el-button>
                <el-button size='small' type="primary" @click="dialogSubmit"><?= \Yii::t('plugins/minishop', '确定');?></el-button>
            </span>
    </el-dialog>
</div>
<script>
    Vue.component('app-select-cat', {
        template: '#app-select-cat',
        props: {
            url: '',
            catDialogVisible: {
                type: Boolean,
                default: false
            },
        },
        data() {
            return {
                catLoading: false,
                children: [],
                third: [],
                current1: '',
                current2: '',
                current3: '',
                select_cat: {
                    label: 0,
                    value: 0
                },
                catList: [],
            }
        },
        watch: {
            catDialogVisible: {
                handler() {
                    if (this.catDialogVisible) {
                        this.catLoading = true;
                        this.getCat();
                    }
                }
            }
        },
        methods: {
            getCat() {
                this.$request({
                    params: {
                        r: this.url
                    },
                    method: 'get'
                }).then(e => {
                    this.catLoading = false;
                    if (e.data.code === 0) {
                        this.catList = e.data.data.list;
                    } else {
                        this.$message.error(e.data.msg);
                    }
                });
            },
            // 选择分类
            selectCats(option, type) {
                let self = this;
                if (type === 1) {
                    self.current1 = option.value;
                    self.children = [];
                    self.third = [];
                    if (option.children) {
                        option.children.forEach(function (item) {
                            self.children.push(item);
                        })
                    }
                }

                if (type === 2) {
                    self.current2 = option.value;
                    self.third = [];
                    if (option.children) {
                        option.children.forEach(function (item) {
                            self.third.push(item);
                        })
                    }
                }

                if (type === 3) {
                    self.current3 = option.value;
                }
            },
            optionClick(option) {
                this.select_cat = JSON.parse(JSON.stringify(option))
            },
            dialogSubmit() {
                if (this.select_cat.value !== 0) {
                    let temp = JSON.parse(JSON.stringify(this.select_cat));
                    this.select_cat = {
                        label: 0,
                        value: 0
                    }
                    this.$emit('cat-submit', temp)
                }
            },
            cancel() {
                this.$emit('cat-cancel')
            }
        }
    })
</script>
