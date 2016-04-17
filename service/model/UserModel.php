<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 12:55 PM
 */

namespace Sucel\Service\Model;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Service\Dao\UserDao;

class UserModel {

    public static function register($phone, $realname = '', $nickname = '', $avatar = '' , $openID = '') {
        $query = new \CDbCriteria();
        $query->addCondition('Fphone=:phone');
        $query->params[':phone'] = $phone;
        $userDao = UserDao::model()->find($query);

        if ($userDao) {
            return $userDao;
        }

        $userDao = self::loadUserInfoByOpenID($openID);
        if ($userDao) {
            return $userDao;
        }

        $userDao = new UserDao();
        $userDao->Fphone = $phone;
        $userDao->Fopenid = $openID;
        $userDao->Fcreated = NOW;
        $userDao->Fnickname = $nickname;
        $userDao->Favatar = $avatar;
        $userDao->Frealname = $realname;

        $userDao->save();

        return $userDao;
    }

    public static function loadUserInfoByOpenID($openid) {
        $query = new \CDbCriteria();
        $query->addCondition('Fopenid=:openid');
        $query->params[':openid'] = $openid;

        return UserDao::model()->find($query);
    }
}