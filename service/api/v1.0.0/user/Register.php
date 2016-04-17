<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 12:52 PM
 */

namespace Sucel\Service\Api\User;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Api\BaseApi;
use Sucel\Service\Model\UserModel;

class Register extends BaseApi {

    public function rules() {
        return array(
            'nickname' => array(),
            'phone' => array(
                'validator' => array(
                    'Required' => array(
                        'code' => ErrorDesc::USER_PHONE_REQUIRED,
                        'message' => ErrorDesc::mean(ErrorDesc::USER_PHONE_REQUIRED),
                    )
                )
            ),
            'realname' => array(),
            'openid' => array(),
            'avatar' => array()
        );
    }

    public function run() {
        $userDao = UserModel::register($this->phone, $this->realname,$this->nickname,$this->avatar,$this->openid);

        return array(
            'uid' => $userDao->Fid,
            'phone' => $userDao->Fphone,
            'nickname' => $userDao->Fnickname,
            'openid' => $userDao->Fopenid,
            'realname' => $userDao->Frealname,
            'avatar' => $userDao->Favatar
        );
    }
}