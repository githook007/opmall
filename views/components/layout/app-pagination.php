<?php
/**
 * Created By PhpStorm
 * Date: 2021/9/6
 * Time: 4:10 下午
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com/
 */
?>
<div id="app-pagination" v-cloak>
    <div flex="dir:right" style="margin-top: 20px;">
        <el-pagination
            @current-change="change"
            background
            hide-on-single-page
            :page-size="pageSize"
            :current-page="current_page"
            layout="total, prev, pager, next, jumper"
            :total="pageCount">
        </el-pagination>
    </div>
</div>
<script>
    Vue.component('app-pagination', {
        template: '#app-pagination',
        props: {
            pagination: Object,
        },
        data() {
            return {
                page: 1,
                pageSize: 20,
                pageCount: 0,
                current_page: 1,
            };
        },
        watch: {
            pagination: {
                handler() {
                    if (this.pagination) {
                        this.pageCount = this.pagination.total_count;
                        this.pageSize = this.pagination.pageSize;
                        this.current_page = this.pagination.current_page;
                    }
                },
                deep: true
            }
        },
        methods: {
            change(currentPage) {
                this.$emit('change', currentPage);
            },
        }
    });
</script>


