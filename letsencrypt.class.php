<?php
/**
 * Name: letsencrypt PHP Class
 * Author: Prk
 * Website: https://imprk.me
 * Date: 2022-06-17
 * Location: Shanghai City of People's Republic of China
 */

    class letsencrypt {

        protected $key;
        protected $email;
        protected $baseURL;

        /**
         * Construct
         * 构造器
         */
        function __construct($key, $email) {
            $this->key = $key;
            $this->email = $email;
            $this->baseURL = 'https://api.osfipin.com/letsencrypt/api';
        }

        /**
         * cURL Function / cUrl 函数
         * Author: Prk
         * 
         * The update address of this function is: https://github.com/BiliPrk/php_curl_func
         * Please keep as up to date as possible for your website safety!
         * 
         * 该函数的更新地址是：https://github.com/BiliPrk/php_curl_func
         * 为了安全起见请尽量保持最新！
         */
        private function curl($url) {
            $ch = curl_init();
            curl_setopt (
                $ch,
                CURLOPT_HTTPGET,
                1
            );
            curl_setopt (
                $ch,
                CURLOPT_CUSTOMREQUEST,
                'GET'
            );
            $header[] = "Accept: */*";
            $header[] = "Accept-Language: en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7";
            $header[] = "Connection: close";
            $header[] = "Cache-Control: max-age=0";
            $header[] = 'Authorization: Bearer ' . $this->key . ':' . $this->email;
            curl_setopt (
                $ch,
                CURLOPT_HTTPHEADER,
                $header
            );
            curl_setopt (
                $ch,
                CURLOPT_ENCODING,
                ''
            );
            curl_setopt (
                $ch,
                CURLOPT_URL,
                'https://api.osfipin.com/letsencrypt/api' . $url
            );
            curl_setopt (
                $ch,
                CURLOPT_USERAGENT,
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36 Edg/102.0.1245.39'
            );
            curl_setopt (
                $ch,
                CURLOPT_TIMEOUT,
                15
            );
            $content = curl_exec (
                $ch
            );
            curl_close (
                $ch
            );
            return $content;
        }

        /**
         * Certificates List / 证书列表
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * page     Int    否      分页数值，默认为1。
         */
        public function orderList($page = 1) {
            return json_decode (
                $this->curl (
                    '/order/list?page=' . intval ( $page )
                ), true
            );
        }

        /**
         * Messages List / 消息列表
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   否      证书ID。可以不填，如果填写，获取的是该证书相关的消息
         */
        public function messageList($id) {
            return json_decode (
                $this->curl (
                    '/account/message?id=' . $id
                ), true
            );
        }

        /**
         * Certificate Info / 证书详情
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID。可以根据证书列表接口获取证书ID。证书验证时，根据返回的数据进行相关设置。
         */
        public function orderInfo($id = '0') {
            return json_decode (
                $this->curl (
                    '/order/detail?id=' . $id
                ), true
            );
        }

        /**
         * Certificate Apply / 证书申请
         * Author: Prk
         * 
         * 传入：
         * 参数名        类型    必须    说明
         * ========================================
         * domain       字符串   是      域名。多个域名英文逗号分开。如：d.com;*.d.com;d2.com
         * algorithm    字符串   否      证书加密算法：RSA,ECC。默认RSA
         * quick        字符串   否      独立通道。yes：代表启用(如果有)
         * ca           字符串   否      CA渠道：lets,zerossl,buypass。默认lets 
         */
        public function orderApply($domain, $algorithm, $quick, $ca) {
            return json_decode (
                $this->curl (
                    '/order/apply?domain=' . $domain . '&algorithm=' . $algorithm . '&quick=' . $quick . '$ca=' . $ca
                ), true
            );
        }

        /**
         * Certificate Reapply / 证书重新申请
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID。可以根据证书列表接口获取证书ID。证书验证时，根据返回的数据进行相关设置。
         */
        public function orderReapply($id) {
            return json_decode (
                $this->curl (
                    '/order/renew?id=' . $id
                ), true
            );
        }

        /**
         * Certificate Download / 证书下载
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID，长度6-8个字符
         * type    字符串   否      值为auto，则id参数传入自动验证id
         * day     字符串   否      如果到期时间在此时间内，不下载。默认为7天(最小)。
         */
        public function orderDownload($id, $type, $day) {
            $res = $this->curl (
                '/order/down?id=' . $id . '&type=' . $type . '&day=' . $day
            );
            if ( is_null ( json_decode ( $res ) ) ) return json_decode($res, true); // 如果是 JSON （失败）那就直接返回 JSON
            else $res; // 如果是文件（成功）那就直接返回文件
        }

        /**
         * Certificate Settings / 证书自动设置
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID。
         * set     字符串   是      可设置：自动重申，自动验证，关闭
         */
        public function orderSetting($id, $set = 0) {
            switch ( $set ) {
                case 0:
                    $set = '关闭';
                    break;
                case 1:
                    $set = '自动重申';
                    break;
                case 2:
                    $set = '自动验证';
                    break;
                default:
                    $set = '关闭';
                    break;
            }
            return json_decode (
                $this->curl (
                    '/order/auto?id=' . $id . '&set=' . $set
                ), true
            );
        }

        /**
         * Certificate Verify / 证书验证
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID。
         * set     字符串   否      自动验证时为空。形如：did:dns-01;did:http-01
         * set设置来自于证书详情接口的verify_data数据，形如：id:type，多个使用;分开。举例：101:dns-01;102:http-01。
         */
        public function orderVerify($id, $set) {
            return json_decode (
                $this->curl (
                    '/order/verify?id=' . $id . '&set=' . $set
                ), true
            );
        }

        /**
         * Delete Certificate / 证书删除
         * Author: Prk
         * 
         * 传入：
         * 参数名   类型    必须    说明
         * ========================================
         * id      字符串   是      证书ID。
         */
        public function orderDelete($id) {
            return json_decode (
                $this->curl (
                    '/order/delete?id=' . $id
                ), true
            );
        }
    }