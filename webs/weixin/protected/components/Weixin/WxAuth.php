<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 8/12/15
 * Time: 11:53 AM
 */

class WxAuth extends CComponent {

    private $accessTokenURL = 'https://api.weixin.qq.com/cgi-bin/token';
    private $ticketURL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
    private $authCodeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    private $authAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    private $authRefreshTokenURL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    private $appId;
    private $appSecret;

    const SESSION_ACCESS_TOKEN_KEY = 'wx_access_token';
    const SESSION_TICKET_ID_KEY = 'wx_ticket_id';
    const SESSION_AUTH2_ACCESS_TOKEN_KEY = 'wx_auth2_access_token';

    private $web;

    public function init() {
        $appConfig = appConfig();
        $this->appId = $appConfig['weixin_app_key'];
        $this->appSecret = $appConfig['weixin_app_secret'];
        $this->web = Yii::app()->curl;
    }

    public function getFullURL($uri = false) {
        if (!$uri) $uri = $_SERVER['REQUEST_URI'];
        $isHttps = strpos($_SERVER['SERVER_PROTOCOL'], 'HTTPS') !== false;
        $protocol = $isHttps ? 'https': 'http';
        $url = sprintf('%s://%s%s', $protocol, $_SERVER['HTTP_HOST'], $uri);

        return $url;
    }

    public function getJSConfig() {
        $config = array();
        $appConfig = appConfig();

        $config['timestamp'] = NOW;
        $config['nonceStr'] = $this->randString(18);
        $config['jsapi_ticket'] = $this->getTicketID();

        // 生成签名
        $signatureData = $config;
        $signatureData['url'] = $this->getFullURL();
        ksort($signatureData);
        $signatureStr = '';
        foreach ($signatureData as $key => $val) {
            $key = strtolower($key);
            $signatureStr[] = "{$key}={$val}";
        }

        \Sucel\Common\Includes\Logger::log()->info(implode('&', $signatureStr));

         if (!isProduct()) $config['debug'] = true;
        $config['signature'] = sha1(implode('&', $signatureStr));
        $config['appId'] = $appConfig['weixin_app_key'];
        $config['jsApiList'] = array('chooseWXPay', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone');

        \Sucel\Common\Includes\Logger::log()->info(json_encode($config));
        return $config;
    }

    private function randString($length = 10) {
        $char = '0123456789abcdefghijklmnopkrstuvwxwz';
        $rand = array();
        for ($i = 0; $i < $length; $i++) {
            $index = rand(1, strlen($char));
            $rand[] = $char[$index - 1];
        }
        return implode('', $rand);
    }

    public function getAccessTokenForJSSDK($reset = false) {
        if ($reset) {
            $response = Yii::app()->curl->get($this->accessTokenURL, array(
                'grant_type' => 'client_credential',
                'appid' => $this->appId,
                'secret' => $this->appSecret
            ));

            \Sucel\Common\Includes\Logger::log()->info($response);

            if ($response) {
                $data = @json_decode($response, true);
                $data['time'] = NOW;
                Yii::app()->session[self::SESSION_ACCESS_TOKEN_KEY] = $data;
                return $data['access_token'];
            }
            else throw new \CHttpException(500, '对不起 系统内部错误 请联系顽主管理员');
        }
        else {
            $data = Yii::app()->session[self::SESSION_ACCESS_TOKEN_KEY];
            if ( ($data && !empty($data['access_token'])) && !$reset ) {
                $expireIn = $data['expires_in'];
                $time = $data['time'];
                // 过期后重新生成 Access Token
                if ($time + $expireIn < NOW) {
                    return $this->getAccessTokenForJSSDK(true);
                }
                return $data['access_token'];
            }
            else return $this->getAccessTokenForJSSDK(true);
        }
    }

    public function getTicketID($reset = false) {
        $data = Yii::app()->session[self::SESSION_TICKET_ID_KEY];
        if ( ($data &&!empty($data['ticket'])) && !$reset) {
            $expireIn = $data['expires_in'];
            $time = $data['time'];
            // 过期后重新生成 Access Token
            if ($time + $expireIn < NOW) {
                return $this->getTicketID(true);
            }
            return $data['ticket'];
        }

        $accessToken = $this->getAccessTokenForJSSDK();
        $response = $this->web->get($this->ticketURL, array(
            'access_token' => $accessToken,
            'type' => 'jsapi',
        ));
        if ($response) {
            $data = @json_decode($response, true);
            $data['time'] = NOW;
            Yii::app()->session[self::SESSION_TICKET_ID_KEY] = $data;
            return $data['ticket'];
        }
        else throw new \CHttpException(500, '对不起 系统内部错误 请联系顽主管理员');
    }

    public function getAuthCodeURL() {
        $query = http_build_query(array(
            'appid' => $this->appId,
            'redirect_uri' => $this->getFullURL('/weixin/callback'),
            'response_type' => 'code',
            'scope' => 'snsapi_userinfo',
            'state' => $this->randString(20),
        ));
        return sprintf('%s?%s#wechat_redirect', $this->authCodeURL, $query);
    }

    public function getAuth2AccessToken($code = false) {
        if ($code) {
            $query = http_build_query(array(
                'appid' => $this->appId,
                'secret' => $this->appSecret,
                'code' => $code,
                'grant_type' => 'authorization_code'
            ));
            $url = sprintf("%s?%s", $this->authAccessTokenURL, $query);

            $response = $this->web->get($url);
            $data = json_decode($response, true);
            if (!isset($data['access_token'])) throw new \CHttpException(500, '微信授权出错');

            $data['time'] = NOW;
            Yii::app()->session[self::SESSION_AUTH2_ACCESS_TOKEN_KEY] = $data;

            return $data['access_token'];
        }
        else {
            $data = Yii::app()->session[self::SESSION_AUTH2_ACCESS_TOKEN_KEY];
            if ($data && !empty($data['access_token']) ) {
                $time = getParam($data, 'time');
                $expire = getParam($data, 'expires_in');

                \Sucel\Common\Includes\Logger::log(json_encode($data));
                \Sucel\Common\Includes\Logger::log($time + $expire < NOW);

                // 过期后 重新刷新 access token
                if ($time + $expire < NOW) {
                    $refreshToken = $data['refresh_token'];
                    $query = http_build_query(array(
                        'appid' => $this->appId,
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $refreshToken
                    ));
                    $url = sprintf("%s?%s", $this->authRefreshTokenURL, $query);
                    $response = $this->web->get($url);
                    $data = json_decode($response, true);

                    \Sucel\Common\Includes\Logger::log()->info('刷新Access 返回');
                    \Sucel\Common\Includes\Logger::log()->info(json_encode(array(
                        $query, $data,$refreshToken
                    )));


                    if (!isset($data['access_token'])) throw new \CHttpException(500, '微信刷新Access Token 出错');
                    Yii::app()->session[self::SESSION_AUTH2_ACCESS_TOKEN_KEY] = $data;

                    return  $data['access_token'];
                }
                return $data['access_token'];
            }
        }
    }

    public function getWxAPIInstance() {
        static $_instance;
        if (!empty($_instance)) return $_instance;

        $_instance = new WxAPI($this->getAuth2AccessToken(), $this->getOpenID());
        return $_instance;
    }

    public function getOpenID() {
        $data = Yii::app()->session[self::SESSION_AUTH2_ACCESS_TOKEN_KEY];
        \Sucel\Common\Includes\Logger::log()->info('getOpenID() '. json_encode($data));
        return getParam($data, 'openid');
    }

    public function getUniqueID() {
        $api = $this->getWxAPIInstance();
        $userInfo = $api->getUserBasicInfo();
        return getParam($userInfo, 'unionid');
    }

    public function isAuthed() {
        try {
            $accessToken = $this->getAuth2AccessToken();
            return $accessToken;
        }
        catch (Exception $e) {
            return false;
        }
    }

}