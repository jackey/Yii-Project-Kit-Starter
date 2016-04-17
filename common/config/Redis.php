<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:46 AM
 */

define('REDIS_CACHE', 1); // 缓存数据库
define('REDIS_QUEUE', 2); // 队列数据库
define('REDIS_WEBSITE_SESSION', 3); // 网站session数据库
define('REDIS_WEIXIN_SESSION', 4); // weixin session 数据库
define('REDIS_STAT', 5); // 统计数据库
define('REDIS_DEFAULT', 6); // 默认数据库


if (isProduct()) {
    return array(
        REDIS_QUEUE => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_CACHE => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_WEBSITE_SESSION => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_WEIXIN_SESSION => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_STAT => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_DEFAULT => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
    );
}
else {
    return array(
        REDIS_QUEUE => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_CACHE => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_WEBSITE_SESSION => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_WEIXIN_SESSION => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_STAT => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
        REDIS_DEFAULT => array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 1),
    );
}
