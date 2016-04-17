<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 2:55 PM
 */

namespace Sucel\Common\Client;

class UserClient extends CClient {

    public function register($phone, $openid = '', $avatar = '', $nickname = '', $realname  = '') {
        return $this->request('user.register', array(
            'phone' => $phone,
            'openid' => $openid,
            'avatar' => $avatar,
            'realname' => $realname,
            'nickname' => $nickname
        ));
    }
}