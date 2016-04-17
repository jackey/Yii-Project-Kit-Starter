<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 9:44 AM
 */

namespace Sucel\Common\Includes\Queue;

use Sucel\Common\Includes\Redis;

abstract class CQueue {

    /**
     * @var
     */
    protected static $_queue;
    /**
     * @var \Predis\Client
     */
    private $redis;

    private function __construct() {
        $this->redis = Redis::getInstance(REDIS_QUEUE);
    }

    abstract public function queueName();

    public static function queue() {
        if (empty(self::$_queue)) self::$_queue = new static();
        return self::$_queue;
    }

    public function enqueue($data) {
        return $this->redis->lpush($this->queueName(), serialize($data));
    }

    public function dequeue() {
        return unserialize($this->redis->rpop($this->queueName()));
    }

    public function size() {
        return $this->redis->llen($this->queueName());
    }
}