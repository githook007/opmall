<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/5/5
 * Time: 17:19
 */
$pluginUrl = Yii::$app->request->hostInfo . \Yii::$app->request->baseUrl . '/statics/img/mall/diy/';
?>
<template id="diy-video-channel">
    <div>
        <div class="diy-component-preview" v-if="isDiyArea">
            <div style="padding: 50px 0;text-align: center;background: #fff;"><?= \Yii::t('plugins/diy', '这是一个视频号位');?></div>
        </div>
        <div :class="isDiyArea ? 'diy-component-edit' : ''">
            <el-card shadow="never">
                <div slot="header">
                    <span>视频号说明：小程序需与视频号相同主体或关联主体</span>
                </div>
                <div>
                    <div>关联主体：</div>
                    <div>1. 小程序绑定了微信开放平台账号</div>
                    <div>2. 小程序与微信开放平台账号的关系为同主体或关联主体</div>
                    <div>3. 微信开放平台账号的主体与关联主体列表中包含视频号的主体</div>
                    <div>
                        4. 申请流程参考：
                        <el-button type="text" size="mini"
                                   @click="$navigate('https://kf.qq.com/faq/190726e6JFja190726qMJBn6.html', true)">https://kf.qq.com/faq/190726e6JFja190726qMJBn6.html
                        </el-button>
                    </div>
                    <div>
                        <el-button size="small" @click="dialogVisible = true">查看图例</el-button>
                    </div>
                </div>
            </el-card>
            <el-form label-width="100px" @submit.native.prevent>
                <el-form-item label="<?= \Yii::t('plugins/diy', '视频号id');?>" prop="finderUserName">
                    <el-input size="small" v-model="data.finderUserName"></el-input>
                </el-form-item>

                <el-form-item label="<?= \Yii::t('plugins/diy', '视频feedId');?>" prop="feedId">
                    <el-input size="small" v-model="data.feedId"></el-input>
                </el-form-item>
            </el-form>
            <el-dialog :visible.sync="dialogVisible">
                <div class="dialog">
                    <el-image
                            style="width: 100%;"
                            :src="dialogImgUrl"
                            :preview-src-list="[dialogImgUrl]">
                    </el-image>
                </div>
            </el-dialog>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-video-channel', {
        template: '#diy-video-channel',
        props: {
            value: Object,
            // 是否是diy区
            isDiyArea: {
                type: Boolean,
                default: true
            },
        },
        data() {
            return {
                data: {
                    finderUserName: '',
                    feedId: '',
                },
                dialogImgUrl: '<?=$pluginUrl ?>/video_channel_demo.png',
                dialogVisible: false,
            };
        },
        created() {
            if (!this.value) {
                this.$emit('input', JSON.parse(JSON.stringify(this.data)))
            } else {
                this.data = JSON.parse(JSON.stringify(this.value));
            }
        },
        computed: {},
        watch: {
            data: {
                deep: true,
                handler(newVal, oldVal) {
                    this.$emit('input', newVal, oldVal)
                },
            },
            value: {
                deep: true,
                handler(newVal, oldVal) {
                    this.data = this.value;
                },
            }
        },
        methods: {}
    });
</script>
