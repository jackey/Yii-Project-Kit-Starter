<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 16/11/15
 * Time: 12:26 PM
 */

namespace Sucel\Common\Includes\Cache;

class LikeCount extends CCache {

    protected $keyFormat = 'like:count:{category}:{id}';

    public function group() {
        return REDIS_STAT;
    }

    /**
     * @param string $class
     * @return LikeCount
     */
    public static function instance($class = __CLASS__) {
        return parent::instance($class);
    }

    /**
     * 更新组内图片点赞数
     * @param $photoId
     * @throws \CHttpException
     * @return fixed
     */
    public static function groupPhotoLike($photoId) {
        $category = 'group_photo';
        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->incr($key);
    }

    /**
     * 取消点赞
     * @param $photoId
     * @return int
     * @throws \CHttpException
     */
    public static function groupPhotoUnLike($photoId) {
        $category = 'group_photo';
        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->decr($key);
    }

    /**
     * 获取组内图文点赞数
     * @param $photoId
     * @return string
     * @throws \CHttpException
     */
    public static function groupPhotoCount($photoId) {
        $category = 'group_photo';

        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->get($key);
    }

    /**
     * 更新用户图片点赞数
     * @param $photoId
     * @throws \CHttpException
     * @return fixed
     */
    public static function userPhotoLike($photoId) {
        $category = 'user_photo';
        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->incr($key);
    }

    /**
     * 取消点赞
     * @param $photoId
     * @return int
     * @throws \CHttpException
     */
    public static function userPhotoUnLike($photoId) {
        $category = 'user_photo';
        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->decr($key);
    }

    /**
     * 获取用户图文点赞数
     * @param $photoId
     * @return string
     * @throws \CHttpException
     */
    public static function userPhotoCount($photoId) {
        $category = 'user_photo';

        $likeCount = self::instance();
        $redis = $likeCount->getDatabase();
        $key = $likeCount->key($category, $photoId);

        return $redis->get($key);
    }

}