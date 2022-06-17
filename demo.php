<?php
/**
 * Demo
 * 请求示例
 */

    require_once (
        'letsencrypt.class.php'
    );

    /**
     * First, you need to pass in your APIKEY and Email / PhoneNumber to construct the value of the request header Authorization
     * 首先需要传入你的 APIKEY 和 邮箱/手机号 来构造请求头 Authorization 的值
     */
    $letsencrypt = new letsencrypt (
        'abcd1234',
        'admin@qq.com'
    );

    /**
     * Get all the certificates of the first page, this step can be traversed to the page through the Vue.js auxiliary loop.
     * 这里可以加载第一页的全部证书，也可以通过 Vue.js 帮助你将内容循环遍历到页面上
     * 
     * example:
     * 
     * <div id="app">
     *     <ul>
     *         <li v-for="(data, index) in json" :key="index">{{ data }}</li>
     *     </ul>
     * </div>
     * <script type="text/javascript">
     *     new Vue ({
     *         el: "#app",
     *         data() {
     *             return {
     *                 json: <?php echo $letsencrypt->orderList(1); ;?>
     *             }
     *         }
     *     })
     * </script>
     */
    echo $letsencrypt->orderList ( 1 );