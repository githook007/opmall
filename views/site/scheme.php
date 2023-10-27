<?php
/**
 * Created by PhpStorm
 * Date: 2021/2/22
 * Time: 9:56 上午
 * @copyright: ©2020 hook007
 * @link: https://www.opmall.com/
 */
?>
<div id="app">

</div>
<script>
    const app = new Vue({
        el: '#app',
        created() {
            let url_scheme = '<?=$url ?>';
            console.log(url_scheme)
            if (url_scheme) {
                location.href = decodeURIComponent(url_scheme);
            }
            console.log(22)
        }
    });
</script>
