<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 23/11/15
 * Time: 11:53 AM
 */

namespace Sucel\Common\Includes;


class UmengIM {

    private static $_instance;

    public static function client() {
        if (self::$_instance) return self::$_instance;
        $appConfig = appConfig();

        $client = new \TopClient($appConfig['umeng_im_app_key'], $appConfig['umeng_im_app_secret']);
        $client->format = 'json';
        self::$_instance = $client;

        return $client;
    }

    /**
     * umeng 添加账户
     * @param $uid
     * @param $pass
     * @param $avatar
     * @param $nickname
     * @return mixed
     */
    public static function registerAccount($uid, $pass, $avatar, $nickname) {
        $client = self::client();

        $request = new \OpenimUsersAddRequest();
        $userInfo = new \Userinfos();
        $userInfo->nick = $nickname;
        $userInfo->password = $pass;
        $userInfo->iconUrl = $avatar;
        $userInfo->userid = $uid;
        $request->setUserinfos(json_encode($userInfo));

        $response = $client->execute($request);

        Logger::log()->info(print_r($response));

        return isset($response->uid_succ)? $response->uid_succ: false;
    }

    /**
     * umeng 更新账户
     * @param $uid
     * @param $pass
     * @param $avatar
     * @param $nickname
     * @return mixed
     */
    public static function updateAccount($uid, $pass, $avatar, $nickname) {
        $client = self::client();

        $request = new \OpenimUsersUpdateRequest();
        $userInfo = new \Userinfos();
        $userInfo->nick = $nickname;
        $userInfo->password = $pass;
        $userInfo->iconUrl = $avatar;
        $userInfo->userid = $uid;
        $request->setUserinfos(json_encode($userInfo));

        $response = $client->execute($request);
        return isset($response->uid_succ)? $response->uid_succ: false;
    }

    public static function accountInfo($uids) {
        $client = self::client();

        $request = new \OpenimUsersGetRequest();
        $request->setUserids($uids);

        $response = $client->execute($request);
        return $response;
    }

    public static function delAccount($uids) {
        $client = self::client();

        $request = new \OpenimUsersDeleteRequest();
        $request->setUserids($uids);

        $response = $client->execute($request);
        return $response;
    }

    public static function login($uid, $password) {
        $client = self::client();

        $request = new \OpenaccountLong();

        $response = $client->execute($request);
        return $response;
    }


}