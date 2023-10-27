<?php
?>
<style>
    .app-setting-index-list {
        width: 582px;
        flex-wrap: wrap;
    }

    .app-setting-index-item {
        flex-shrink: 0;
        width: 150px;
        height: 150px;
        margin: 30px 22px 0;
        border-radius: 16px;
        border: 2px solid #e2e2e2;
        text-align: center;
        cursor: pointer;
        font-size: 16px;
        color: #353535;
    }

    .app-setting-index-item.active {
        border: 2px solid #3399ff;
    }

    .app-setting-index-item img {
        margin-bottom: 10px;
    }
</style>

<template id="app-setting-index">
    <el-dialog title="<?= \Yii::t('components/other', '设置首页');?>" :close-on-click-modal="clickModal" :before-close="cancel" :visible.sync="indexDialog" width="660px">
        <div flex="dir:top cross:center">
            <div style="color: #353535;font-size: 15px;"><?= \Yii::t('components/other', '请选择用户端');?></div>
            <div class="app-setting-index-list" flex="dir:left">
                <div v-for="(item,index) in platform" :key="item.key" flex="dir:top main:center cross:center" class="app-setting-index-item" :class="item.check ? 'active':''" @click="choose(index)">
                    <img width="85" height="85" :src="item.icon" alt="">
                    <div v-if="item.key == 'wxapp'"><?= \Yii::t('components/other', '微信');?></div>
                    <div v-if="item.key == 'aliapp'"><?= \Yii::t('components/other', '支付宝');?></div>
                    <div v-if="item.key == 'ttapp'"><?= \Yii::t('components/other', '头条/抖音');?></div>
                    <div v-if="item.key == 'bdapp'"><?= \Yii::t('components/other', '百度');?></div>
                    <div v-if="item.key == 'wechat'"><?= \Yii::t('components/other', '公众号');?></div>
                    <div v-if="item.key == 'mobile'">H5</div>
                    <div v-if="item.key == 'app'">APP</div>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button size="small" @click="cancel"><?= \Yii::t('components/other', '取消');?></el-button>
            <el-button :loading="loading" size="small" type="primary" @click="submit"><?= \Yii::t('components/other', '确定');?></el-button>
        </span>
    </el-dialog>
</template>

<script>
    Vue.component('app-setting-index', {
        template: '#app-setting-index',
        props: {
            show: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            loading: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            list: {
                type: String
            }
        },
        data() {
            return {
                indexDialog: false,
                platform: [],
                clickModal: false
            }
        },
        created() {
            request({
                params: {
                    r: 'mall/index/platform',
                },
                method: 'get',
            }).then(e => {
                if (e.data.code == 0) {
                    this.platform = e.data.data;
                    for(let item of this.platform) {
                        item.check = false;
                    }
                } else {
                    this.$message.error(e.data.msg);
                }
            })
        },
        watch: {
            show: {
                deep: true,
                handler(data) {
                    this.indexDialog = data;
                },
            },
            list: {
                deep: true,
                handler(data) {
                    if(data) {
                        let list = JSON.parse(data);
                        for(let row of this.platform) {
                            row.check = false;
                            for(let item of list) {
                                if(item.icon == row.icon) {
                                    row.check = true;
                                }
                            }
                        }
                    }
                },
            }
        },
        methods: {
            choose(index) {
                this.platform[index].check = !this.platform[index].check;
                this.$forceUpdate();
            },
            cancel() {
                for(let row of this.platform) {
                    row.check = false;
                }
                this.$emit('cancel', false)
            },
            submit() {
                let platform = [];
                for(let item of this.platform) {
                    if(item.check) {
                        platform.push(item.key)
                    }
                }
                this.$emit('click', platform)
                setTimeout(()=>{
                    for(let row of this.platform) {
                        row.check = false;
                    }
                })
            }
        }
    });
</script>
