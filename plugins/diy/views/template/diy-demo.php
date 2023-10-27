<?php
/**
 * Created by IntelliJ IDEA.
 * author: opmall
 * Date: 2019/4/25
 * Time: 13:48
 */
?>
<template id="diy-demo">
    <div>
        <div class="diy-component-preview">{{data.text}}</div>
        <div class="diy-component-edit">
            <el-form label-width="100px" @submit.native.prevent>
                <el-form-item label="<?= \Yii::t('plugins/diy', '文字');?>">
                    <el-input v-model="data.text"></el-input>
                </el-form-item>
            </el-form>
        </div>
    </div>
</template>
<script>
    Vue.component('diy-demo', {
        template: '#diy-demo',
        props: {
            value: Object,
        },
        data() {
            return {
                data: {
                    text: "<?= \Yii::t('plugins/diy', 'a1');?>",
                }
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
            }
        },
        methods: {}
    });
</script>