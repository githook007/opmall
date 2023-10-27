<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2019 hook007
 * author: fjt
 */
?>
<style>

</style>

<div id="app" v-cloak>
    <el-card show="never" v-loading="loading">
        <div slot="header"></div>
        <div slot="body"></div>
    </el-card>
</div>

<script>
    const app = new Vue({
        el: 'app',
        data() {
            return {
                loading: false,
            }
        }
    });

</script>
