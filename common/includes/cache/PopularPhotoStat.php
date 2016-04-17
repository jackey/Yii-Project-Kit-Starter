<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 18/11/15
 * Time: 11:01 AM
 */

namespace Sucel\Common\Includes\Cache;

use Sucel\Service\Dao\Group\GroupPhotoDao;

class PopularPhotoStat extends CCache {

    private $hour = 1;

    protected $keyFormat = 'photo:{photo_id}:{category}';
    private $categoryOfPopular = 'photo_popular_stat';
    private $categoryOfLikeStat = 'photo_popular_like_stat';
    private $categoryOfCommentStat = 'photo_popular_comment_stat';

    /**
     * @param string $class
     * @return PopularPhotoStat
     */
    public static function instance($class = __CLASS__) {
        return parent::instance($class);
    }

    public function group() {
        return REDIS_STAT;
    }

    /**
     * 图片被Like
     * @param $pid
     * @return boolean
     */
    public static function liked($pid) {
        $instance = self::instance();
        $key = $instance->key($pid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 获取被Like过的图片列表
     * @return array
     * @throws \CHttpException
     */
    public static function photoLiked() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $pids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $pids[] = getParam($matches, 1);
        }
        return $pids;
    }

    /**
     * 计算在$hour小时内被点赞数量
     * @param $pid
     * @return string
     * @throws \CHttpException
     */
    public static function photoLikeInHourCount($pid) {
        $instance = self::instance();
        $key = $instance->key($pid, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $pid
     * @throws \CHttpException
     */
    public static function photoLikeCleanDataInHour($pid) {
        $id = $pid;
        $instance = self::instance();
        $key = $instance->key($id, $instance->categoryOfLikeStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 统计被评论的次数
     * @param $pid
     * @return int
     * @throws \CHttpException
     */
    public static function likedPhotoList($pid) {
        $id = $pid;
        $instance = self::instance();
        $key = $instance->key($id, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        return $redis->zadd($key, NOW, uniqid(NOW));
    }

    /**
     * 返回在$hour 指定的小时区间内被评论个数
     * @param $pid
     * @return string
     * @throws \CHttpException
     */
    public static function photoCommentInHourCount($pid) {
        $instance = self::instance();
        $key = $instance->key($pid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW;
        $min = NOW - self::$hour * 60;
        return $redis->zcount($key, $min, $max);
    }

    /**
     * 保持集合中$hour小时内的数据
     * @param $pid
     * @throws \CHttpException
     */
    public static function photoCommentCleanDataInHour($pid) {
        $instance = self::instance();
        $key = $instance->key($pid, $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();

        $max = NOW - self::$hour * 60;
        $redis->zremrangebyscore($key, 0, $max);
    }

    /**
     * 获取被评论过的图片id列表
     * @return array
     * @throws \CHttpException
     */
    public static function commentPhotoList() {
        $instance = self::instance();
        $reg = $instance->key('*', $instance->categoryOfCommentStat);
        $redis = $instance->getDatabase();
        $keys = $redis->keys($reg);
        $pids = array();
        foreach ($keys as $redisKey) {
            preg_match("/:([0-9]+):/", $redisKey, $matches);
            $pids[] = getParam($matches, 1);
        }
        return $pids;
    }

    /**
     * 更新图片的热度
     * @param $gid
     * @param $score
     * @throws \CHttpException
     */
    public static function updatePhotoScore($gid, $score) {
        $instance = self::instance();
        $key = $instance->key('', $instance->categoryOfPopular);

        $redis = $instance->getDatabase();
        $redis->zadd($key, array($gid=>$score));
    }

    /**
     * 按照热度返回图片列表
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \CHttpException
     */
    public static function getPopularPhotos($limit = 10, $offset = 0) {
//        $instance = self::instance();
//        $key = $instance->key('', $instance->categoryOfPopular);
//
//        $redis = $instance->getDatabase();
//        return $redis->zrevrange($key, $limit, $offset + $limit);

        $query = new \CDbCriteria();
        $query->limit = $limit;
        $query->offset = $offset;
        $photoDaos = GroupPhotoDao::model()->findAll($query);
        $pids = array();
        foreach ($photoDaos as $photoDao) {
            $pids[] = $photoDao->Fid;
        }
        return $pids;
    }
}