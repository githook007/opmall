<?php
/**
 * Created By PhpStorm
 * Date: 2021/4/21
 * Time: 11:02 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
?>
<style>
    .input-item {
        display: inline-block;
        width: 270px;
        margin: 0 0 20px;
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
</style>
<div id="app-search-input" v-cloak>
    <div class="input-item">
        <el-input @keyup.enter.native="search" size="small" :placeholder="placeholder"
                  v-model="keyword" clearable @clear="search">
            <el-button slot="append" icon="el-icon-search" @click="search"></el-button>
        </el-input>
    </div>
</div>
<script>
    Vue.component('app-search-input', {
        template: '#app-search-input',
        props: {
            placeholder: '',
        },
        data() {
            return {
                keyword: ''
            }
        },
        methods: {
            search() {
                this.$emit('search', this.keyword)
            }
        }
    })
</script>
