<?php
/**
 * Created by PhpStorm
 * User: opmall
 * Date: 2020/11/10
 * Time: 9:21 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com
 */
?>
<template id="app-platform">
    <el-select size="small" style="width: 120px" v-model="platform" @change='toSearch' class="select">
        <el-option key="all" label="<?= \Yii::t('components/other', '全部平台');?>" value=""></el-option>
        <el-option :key="item.key" :label="item.name" :value="item.key" v-for="(item, index) in platformList"></el-option>
    </el-select>
</template>
<script>
    Vue.component('app-platform', {
        template: '#app-platform',
        props: {
            value: String,
        },
        data() {
            return {
                platformList: [],
                platform: '',
            };
        },
        created() {
            this.getPlatform();
        },
        watch: {
            value: {
                handler() {
                    this.platform = JSON.parse(JSON.stringify(this.value))
                },
                immediate: true
            }
        },
        methods: {
            getPlatform() {
                request({
                    params: {
                        r: 'mall/index/platform',
                    },
                    method: 'get',
                }).then(e => {
                    if(e.data.code === 0) {
                        this.platformList = e.data.data
                    }
                }).catch(e => {
                    console.log(e);
                });
            },
            toSearch() {
                this.$emit('input', this.platform)
                this.$emit('change');
            }
        }
    });
</script>

