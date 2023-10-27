<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<div id="app">
    <app-template url="plugin/aliapp/template-msg/setting" submit-url='plugin/aliapp/template-msg/setting'
                  sign="aliapp"
                  add-url="plugin/aliapp/template-msg/add-template" :one-key="isShow"></app-template>
</div>
<script>
    const app = new Vue({
        el: '#app',
        data() {
            return {
                isShow: false,
            };
        },
    });
</script>
