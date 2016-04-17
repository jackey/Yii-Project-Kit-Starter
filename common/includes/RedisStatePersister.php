<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 25/11/15
 * Time: 3:43 PM
 */

namespace Sucel\Common\Includes;

class RedisStatePersister extends \CStatePersister {

    /**
     * @var \Predis\Client
     */
    private $redis;

    private $cacheId = 'Yii.CStatePersister.State.Persister';

    public function init() {
        $this->redis = Redis::getInstance(REDIS_CACHE);
    }

    public function load() {
        $data = $this->redis->get($this->cacheId);
        return unserialize($data);
    }

    public function save($state) {
        $this->redis->set($this->cacheId, serialize($state));
    }
}