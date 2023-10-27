<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 10:24 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
?>
<div id="app-contain" v-cloak>
    <el-card v-loading="loading" style="border:0" shadow="never"
             body-style="background-color: #f3f3f3;padding: 10px 0 0 0;">
        <div slot="header">
            <tempalte v-if="parentName">
                <span style="color:#409EFF;cursor: pointer" @click="$navigate({r:parentUrl})">{{parentName}}</span>
                <span>/</span>
            </tempalte>
            <span>{{headName}}</span>
            <el-button style="float: right; margin: -5px 0" type="primary" size="small" v-if="editButton"
                       @click="editClick">
                {{editButtonName}}
            </el-button>
        </div>
        <slot></slot>
    </el-card>
</div>
<script>
    Vue.component('app-contain', {
        template: '#app-contain',
        props: {
            loading: {
                type: Boolean,
                default: false
            },
            headName: '',
            editButton: {
                type: Boolean,
                default: false
            },
            editButtonName: '',
            parentName: '',
            parentUrl: '',
        },
        methods: {
            editClick() {
                this.$emit('edit-click');
            }
        }
    });
</script>

