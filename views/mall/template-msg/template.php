<?php
/**
 * Created by PhpStorm.
 * User: opmall
 * Date: 2020/4/2
 * Time: 14:11
 * @copyright: ©2021 hook007
 * @link: https://www.opmall.com
 */
?>
<div id="app" v-cloak>
    <app-template url="<?= $url?>" add-url="<?= $addUrl?>"
                  submit-url="<?= $submitUrl?>"></app-template>
</div>
<script>
    const app = new Vue({
        el: '#app',
    });
</script>
