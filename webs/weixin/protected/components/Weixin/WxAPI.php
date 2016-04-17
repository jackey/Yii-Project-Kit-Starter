<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 8/12/15
 * Time: 2:57 PM
 */

class WxAPI {

    /**
     * @var WxAuth
     */
    protected $wxAuth;

    private $apiMapper = array(
        'userinfo' => 'https://api.weixin.qq.com/sns/userinfo',
    );

    private $accessToken;
    private $openId;

    public function __construct($accessToken, $openId) {
        $this->accessToken = $accessToken;
        $this->openId = $openId;
        $this->wxAuth = Yii::app()->wxAuth;
    }

    public function getUserBasicInfo() {
        $url = $this->apiMapper['userinfo'];
        $query = http_build_query(array(
            'access_token' => $this->accessToken,
            'openid' => $this->openId,
            'lang' => 'zh_CN'
        ));

        \Sucel\Common\Includes\Logger::log()->info($query);

        $res = Yii::app()->curl->get(sprintf("%s?%s", $url, $query));

        \Sucel\Common\Includes\Logger::log()->info('userinfo: '. json_encode($res));

        return @json_decode($res, true);
    }


}