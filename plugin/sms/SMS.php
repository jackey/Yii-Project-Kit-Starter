<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/11/15
 * Time: 11:56 PM
 */

class SMS {
    private $sign = '顽主WonJoy';
    private $appName = '顽主';
    private  $templates = array(
        'register' => '【#company#】感谢您注册#app#，您的验证码是#code#',
        'valid_with_expired' => '【#company#】亲爱的#name#，您的验证码是#code#。有效期为#hour#小时，请尽快验证',
    );
    private $apiUrl = 'http://yunpian.com/v1/sms';
    private $apiKey = '0488cc987da8f6f7bc2ff8b719774262';
    private $port = 80;
    private $timeout = 2;

    private static $instance;

    public static function instance() {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 随机生成验证码
     * @param int $length
     * @param bool|false $hasApha
     */
    public static function randomCode($length = 4, $hasApha = false) {
        $string = "0987654321";
        if ($hasApha) $string .= "abcdefghijklmnopqrstuvwsyz";
        $code = array();
        for ($i = 0; $i < $length; $i++) $code[] = $string[rand(0, strlen($string) - 1)];

        return implode('', $code);
    }

    /**
     * 发送注册验证码
     * @param $phone
     * @param $code
     * @param $appName
     * @return bool
     */
    public function sendRegisterCode($phone, $code = '', $appName = '') {
        if (empty($code)) $code = self::randomCode();
        if (empty($appName)) $appName = $this->appName;
        $msg = $this->templates['register'];
        $msg = str_replace("【#company#】", $this->sign, $msg);
        $msg = str_replace("#code#", $code, $msg);
        $msg = str_replace("#app#", $appName, $msg);

        $result = $this->call('send', array(
            'text' => $msg,
            'mobile' => $phone
        ));
        $result = json_decode($result, true);
        if (isset($result['code']) && $result['code'] == 0) return true;
        return false;
    }

    /**
     * 发送有时间限制的验证码
     * @param $phone
     * @param $code
     * @param $uname
     * @param $hour
     * @return bool
     */
    public function sendValidWithExpireCode($phone, $code, $uname, $hour) {
        $msg = $this->templates['valid_with_expired'];
        $msg = str_replace('#name#', $uname, $msg);
        $msg = str_replace('#code#', $code, $msg);
        $msg = str_replace('#hour#', $hour, $msg);
        $msg = str_replace("#company#", $this->sign, $msg);

        $result = $this->call('send', array(
            'text' => $msg,
            'mobile' => $phone
        ));
        $result = json_decode($result, true);
        if (isset($result['code']) && $result['code'] == 0) return true;
        return false;
    }

    /**
     * 调用接口
     * @param $api
     * @param $data
     * @return mixed
     */
    public function call($api, $data) {
        $post = array_merge(array('apikey' => $this->apiKey), $data);
        $url = "{$this->apiUrl}/{$api}.json";
        return $this->request($url,  $post);
    }

    /**
     * 发起请求
     * @param $url
     * @param $method
     * @param $data
     * @return mixed
     */
    public function request($url, $query) {
        $query = http_build_query($query);
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded; charset=utf-8\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;

    }
}