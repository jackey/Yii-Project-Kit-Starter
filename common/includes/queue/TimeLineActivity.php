<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 9:44 AM
 */

namespace Sucel\Common\Includes\Queue;

use Sucel\Service\Dao\TimeLineDao;


/**
 * 用户发生行为
 * @package Sucel\Common\Includes\Queue
 */
class TimeLineActivity extends CQueue {

    public function queueName() {
        return 'user_time_line_activity';
    }

    /**
     * 用户发布了一张图片
     * @param $uid 谁
     * @param $pid 发的图片ID
     * @param $time
     */
    public static function userPostPhoto($uid, $pid, $time = NOW) {
        $action = TimeLineDao::ACTION_POST_PHOTO;
        $type = TimeLineDao::TYPE_PHOTO;
        $from = TimeLineDao::FROM_USER;
        $serialId = TimeLineDao::serialId($action, $type, $from, $pid, $uid, $time);
        $data = array(
            'serial_id' => $serialId,
            'from' => $from,
            'by_uid' => $uid,
            'from_id' => $pid,
            'created' => $time,
            'action' => $action
        );

        self::queue()->enqueue($data);
    }

    /**
     * 用户在群组中发布一张图片
     * @param $uid
     * @param $pid
     * @param $time
     */
    public static function groupPostPhoto($uid, $pid, $time = NOW) {
        $action = TimeLineDao::ACTION_POST_PHOTO;
        $type = TimeLineDao::TYPE_PHOTO;
        $from = TimeLineDao::FROM_GROUP;
        $serialId = TimeLineDao::serialId($action, $type, $from, $pid, $uid, $time);
        $data = array(
            'serial_id' => $serialId,
            'from' => $from,
            'by_uid' => $uid,
            'from_id' => $pid,
            'created' => $time,
            'action' => $action
        );
        self::queue()->enqueue($data);
    }

    /**
     * 用户被群组踢出
     * @param $uid
     * @param $gid
     * @param $time
     */
    public static function userBeKickedOutGroup($uid, $gid, $time = NOW) {
        $action = TimeLineDao::ACTION_KICK_OUT_GROUP;
        $type = TimeLineDao::TYPE_MESSAGE;
        $from = TimeLineDao::FROM_GROUP;
        $serialId = TimeLineDao::serialId($action, $type, $from, $gid, $uid, $time);
        $data = array(
            'serial_id' => $serialId,
            'from' => $from,
            'by_uid' => $uid,
            'from_id' => $gid,
            'created' => $time,
            'action' => $action
        );
        self::queue()->enqueue($data);
    }
}