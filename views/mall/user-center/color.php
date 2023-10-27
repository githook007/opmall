<style>
    .color-input {
        margin-left: 5px;
        width: 70px;
    }

    .color-span {
        color: #999999;
        margin-bottom: -20px;
    }
</style>
<template id="color">
    <div>
        <div flex="dir:left cross:center">
            <el-color-picker v-model="v" size="small"></el-color-picker>
            <el-input size="small" class="color-input" v-model="v" :style="{width: height? '100%' : '70px'}"></el-input>
        </div>
    </div>
</template>
<script>
    Vue.component('color', {
        template: '#color',
        props: {
            height: Boolean,
            value: String,
        },
        data() {
            return {
                v: this.value,
            };
        },
        watch: {
            value(newData) {
                this.v = newData;
            },
            v(newData) {
                this.$emit('input', newData)
            }
        },
    });
</script>
