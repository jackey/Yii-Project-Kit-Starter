<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 16/11/15
 * Time: 12:26 PM
 */

namespace Sucel\Common\Includes\Cache;

/**
 * 使用Redis Set 保存组成员
 * Class GroupUser
 * @package Sucel\Common\Includes\Redis
 */
class GroupUser extends CCache {

    protected $keyFormat = 'group:user:{$gid}';

    public function group() {
        return REDIS_DEFAULT;
    }

    /**
     * @param string $class
     * @return GroupUser
     */
    public static function instance($class = __CLASS__) {
        return parent::instance($class);
    }

    /**
     * 用户加入组
     * @param $gid
     * @param $uid
     * @return int
     * @throws \CHttpException
     */
    public static function userJoinGroup($gid, $uid) {
        $instance = self::instance();
        $redis = $instance->getDatabase();
        $key = $instance->key($gid);

        return $redis->sadd($key, $uid);
    }

    /**
     * 用户推出组
     * @param $gid
     * @param $uid
     * @return int
     * @throws \CHttpException
     */
    public static function userQuiteGroup($gid, $uid) {
        $instance = self::instance();
        $redis = $instance->getDatabase();
        $key = $instance->key($gid);

        return $redis->srem($key, $uid);
    }

    /**
     * 组成员
     * @param $gid
     * @return array
     * @throws \CHttpException
     */
    public static function members($gid) {
        $instance = self::instance();
        $redis = $instance->getDatabase();
        $key = $instance->key($gid);

        return $redis->smembers($key);
    }

    public static function userIsMember($uid, $gid) {
        $instance = self::instance();
        $redis = $instance->getDatabase();
        $key = $instance->key($gid);

        return $redis->sismember($key, $uid);
    }
}