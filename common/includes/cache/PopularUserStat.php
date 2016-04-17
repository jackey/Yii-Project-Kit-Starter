<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 17/11/15
 * Time: 11:35 AM
 */

namespace Sucel\Common\Includes\Cache;
use Sucel\Service\Dao\User\UserDao;

/**
 * 热门用户统计
 * 统计方法：
 * 所有图片点赞数 (PL) + 所有图片评论数 (PC) + 评论被引用次数 (CR) + 创建群组图片点赞数 (GPL )+ 创建群组图片评论数 (GPC)
 * @package Sucel\Common\Includes\Cache
 */
class PopularUserStat extends CCache {

    protected $keyFormat = 'user:{id}:{type}';

    private static $hour = 1;

    private $categoryOfLikeStat = 'user_popular_like_stat'; // 用户点赞统计
    private $categoryOfCommentStat = 'user_popular_comment_stat'; // 用户评论统计
    private $categoryOfPopularStat = 'user_popular_stat'; // 用户热门统计

    /**
     * @param string $class
     * @return PopularUserStat
     */
    public static function instance($class = __CLASS__) {
        return parent::instance($class);
    }

    public function group() {
        return REDIS_STAT;
    }

    /**
     * 用户图片被Like
     * @param $uid
     * @return boolean
     */
    public static function photoLiked($uid) {
        $instance = self::instance();
        $key = $instance->key($uid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 获取被Like过的用户id列表
     * @return array
     * @throws \CHttpException
     */
    public static function photoLikedAuthors() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $uids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $uids[] = getParam($matches, 1);
        }
        return $uids;
    }

    /**
     * 计算在$hour小时内被点赞数量
     * @param $uid
     * @return string
     * @throws \CHttpException
     */
    public static function photoLikeInHourCount($uid) {
        $instance = self::instance();
        $key = $instance->key($uid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $uid
     * @throws \CHttpException
     */
    public static function photoLikeCleanDataInHour($uid) {
        $id = $uid;
        $instance = self::instance();
        $key = $instance->key($id, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 统计被评论的次数
     * @param $uid
     * @return int
     * @throws \CHttpException
     */
    public static function photoComment($uid) {
        $id = $uid;
        $instance = self::instance();
        $key = $instance->key($id, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 返回在$hour 指定的小时区间内被评论个数
     * @param $uid
     * @return string
     * @throws \CHttpException
     */
    public static function photoCommentInHourCount($uid) {
        $instance = self::instance();
        $key = $instance->key($uid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $uid
     * @throws \CHttpException
     */
    public static function photoCommentCleanDataInHour($uid) {
        $id = $uid;
        $instance = self::instance();
        $key = $instance->key($id, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 获取被评论过的用户id列表
     * @return array
     * @throws \CHttpException
     */
    public static function photoCommentAuthors() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $uids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $uids[] = getParam($matches, 1);
        }
        return $uids;
    }

    /**
     * 更新用户的热度
     * @param $uid
     * @param $score
     * @throws \CHttpException
     */
    public static function updateUserPopularScore($uid, $score) {
        $instance = self::instance();
        $key = $instance->key('', $instance->categoryOfPopularStat);

        $redis = $instance->getDatabase();
        $redis->zadd($key, array($uid=>$score));
    }

    /**
     * 按照用户热度返回用户uid 列表
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \CHttpException
     */
    public static function getPopularUsers($limit = 10, $offset = 0) {
//        $instance = self::instance();
//        $key = $instance->key('', $instance->categoryOfPopularStat);
//
//        $redis = $instance->getDatabase();
//        return $redis->zrevrange($key, $limit, $offset + $limit);

        //TODO:: 从 Redis 统计中返回
        $query = new \CDbCriteria();
        $query->limit = $limit;
        $query->offset = $offset;
        $userDaos = UserDao::model()->findAll($query);
        $uids = array();
        foreach ($userDaos as $userDao) {
            $uids[] = $userDao->Fid;
        }
        return $uids;
    }

}