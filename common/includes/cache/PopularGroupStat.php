<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 17/11/15
 * Time: 11:35 AM
 */

namespace Sucel\Common\Includes\Cache;
use Sucel\Service\Dao\Group\GroupDao;

/**
 * 热门用户统计
 * 统计方法：
 * 所有图片点赞数 (PL) + 所有图片评论数 (PC) + 评论被引用次数 (CR) + 创建群组图片点赞数 (GPL )+ 创建群组图片评论数 (GPC)
 * @package Sucel\Common\Includes\Cache
 */
class PopularGroupStat extends CCache {

    protected $keyFormat = 'group:{id}:{type}';

    private static $hour = 1;

    private $categoryOfLikeStat = 'group_popular_like_stat'; // 组内Like 数统计
    private $categoryOfCommentStat = 'group_popular_comment_stat'; // 组内评论发布数统计
    private $categoryOfPhotoStat = 'group_popular_photo_stat'; // 组内图文发布数统计
    private $categoryOfPopular = 'group_popular_stat';

    /**
     * @param string $class
     * @return PopularGroupStat
     */
    public static function instance($class = __CLASS__) {
        return parent::instance($class);
    }

    public function group() {
        return REDIS_STAT;
    }

    /**
     * 组内图片被Like
     * @param $gid
     * @return boolean
     */
    public static function photoLiked($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 获取被Like过的群组id列表
     * @return array
     * @throws \CHttpException
     */
    public static function photoLikedGroups() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $gids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $gids[] = getParam($matches, 1);
        }
        return $gids;
    }

    /**
     * 计算在$hour小时内被点赞数量
     * @param $gid
     * @return string
     * @throws \CHttpException
     */
    public static function photoLikeInHourCount($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $gid
     * @throws \CHttpException
     */
    public static function photoLikeCleanDataInHour($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 统计被评论的次数
     * @param $gid
     * @return int
     * @throws \CHttpException
     */
    public static function photoComment($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 返回在$hour 指定的小时区间内被评论个数
     * @param $gid
     * @return string
     * @throws \CHttpException
     */
    public static function photoCommentInHourCount($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $gid
     * @throws \CHttpException
     */
    public static function photoCommentCleanDataInHour($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 获取被评论过的群组id列表
     * @return array
     * @throws \CHttpException
     */
    public static function photoCommentGroups() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $gids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $gids[] = getParam($matches, 1);
        }
        return $gids;
    }

    /**
     * 统计上传图文次数
     * @param $gid
     * @return int
     * @throws \CHttpException
     */
    public static function uploadPhoto($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfPhotoStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 返回在$hour 指定的小时区间内上传图文个数
     * @param $gid
     * @return string
     * @throws \CHttpException
     */
    public static function uploadPhotoInHourCount($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfPhotoStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $gid
     * @throws \CHttpException
     */
    public static function uploadPhotoCleanDataInHour($gid) {
        $instance = self::instance();
        $key = $instance->key($gid, $instance->categoryOfPhotoStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 获取上传过图片的群组
     * @return array
     * @throws \CHttpException
     */
    public static function uploadPhotoGroups() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfPhotoStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $gids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $gids[] = getParam($matches, 1);
        }
        return $gids;
    }

    /**
     * 更新群组的热度
     * @param $gid
     * @param $score
     * @throws \CHttpException
     */
    public static function updateGroupPopularScore($gid, $score) {
        $instance = self::instance();
        $key = $instance->key('', $instance->categoryOfPopular);

        $redis = $instance->getDatabase();
        $redis->zadd($key, array($gid=>$score));
    }

    /**
     * 按照群组热度返回群组列表
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \CHttpException
     */
    public static function getPopularGroup($limit = 10, $offset = 0) {
//        $instance = self::instance();
//        $key = $instance->key('', $instance->categoryOfPopular);
//
//        $redis = $instance->getDatabase();
//        return $redis->zrevrange($key, $limit, $offset + $limit);
        //TODO:: 从Redis 数据库中返回
        $query = new \CDbCriteria();
        $query->limit = 10;
        $query->offset = 0;
        $groupDaos = GroupDao::model()->findAll($query);
        $gids = array();
        foreach ($groupDaos as $groupDao) {
            $gids[] = $groupDao->Fid;
        }
        return $gids;
    }

}