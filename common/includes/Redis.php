<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 10/11/15
 * Time: 1:26 AM
 */

namespace Sucel\Common\Includes;

use \Predis\Client as RedisClient;

class Redis {
    static $_instances;

    /**
     * 获取Redis 连接实例
     * @param $group
     * @return \Predis\Client
     * @throws \CHttpException
     */
    public static function getInstance($group) {
        $configs = redisConfig();
        if (empty($configs[$group])) throw new \CException(500, 'Redis 并未配置'. $group. ' 组');
        $config = $configs[$group];
        if (empty(self::$_instances[$group])) {
            $redis = new RedisClient(array(
                'host' => $config['host'],
                'timeout' => $config['timeout'],
                'database' => $group,
                'port' => getParam($config, 'port', 6379)));
            self::$_instances[$group] = $redis;
        }

        return self::$_instances[$group];
    }
}