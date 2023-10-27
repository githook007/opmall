<?php
/**
 * link: https://www.opmall.com/
 * copyright: Copyright (c) 2018 hook007
 * author: opmall
 */
?>
<div id="app">
    <app-template url="plugin/bdapp/template-msg/setting" submit-url='plugin/bdapp/template-msg/setting'
                  sign="bdapp"
                  add-url="plugin/bdapp/template-msg/add-template"></app-template>
</div>
<script>
    const app = new Vue({
        el: '#app'
    });
</script>
