<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/3/15
 * Time: 4:39 PM
 */

namespace Sucel\Common\Includes\Database;

class CConnection {
    static $_masterConnections;
    static $_slaveConnections;

    public static function getMasterConfiguration() {
        $dbConfig = dbConfig();
        $master = array(
            'host' => $dbConfig['connection']['master']['host'],
            'user' => $dbConfig['connection']['user'],
            'password' => $dbConfig['connection']['password'],
            'charset' => $dbConfig['connection']['charset']
        );
        return $master;
    }

    public static function getSlaveConfiguration() {
        $dbConfig = dbConfig();
        $connection = $dbConfig['connection'];
        $master = self::getMasterConfiguration();
        if (is_array($dbConfig['connection']['slaves'])) {
            foreach ($dbConfig['connection']['slaves'] as $slave) {
                $slaves[] = array(
                    'host' => $slave['host'],
                    'user' => getParam($slave, 'user', $master['user']),
                    'password' => getParam($slave, 'password', $master['password']),
                    'charset' => getParam($slave, 'charset', $master['charset']),
                );
            }
        }
        // 如果没有配置Slave 则选Master为Slave
        if (empty($slaves)) {
            $slaves = array($master);
        }

        return $slaves;
    }

    /**
     * 获取主数据库链接
     * @return \CDbConnection
     */
    public static function selectMasterConnection($database) {
        $master = self::getMasterConfiguration();

        if (!empty(self::$_masterConnections[$master['host']])) return self::$_masterConnections[$master['host']];

        $dsn = $dsn = "mysql:dbname=$database;host=".$master['host'].';charset='. $master['charset'];
        $conn = new \CDbConnection($dsn, $master['user'], $master['password']);
        self::$_masterConnections[$master['host']] = $conn;
        return self::$_masterConnections[$master['host']];
    }

    /**
     * 获取从数据库链接
     * @param $database
     * @return mixed
     */
    public static function selectSlaveConnection($database) {
        $slaves = self::getSlaveConfiguration();

        //TODO:: Slave 选择算法
        $slave = $slaves[0];

        if (!empty(self::$_slaveConnections[$slave['host']])) return self::$_slaveConnections[$slave['host']];

        $dsn = "mysql:dbname=$database;host=".$slave['host'].';charset='. $slave['charset'];
        $conn = new \CDbConnection($dsn, $slave['user'], $slave['password']);
        self::$_slaveConnections[$slave['host']] = $conn;
        return $conn;
    }
}