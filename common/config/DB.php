<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:46 AM
 */

// 表配置
/**
 * mk => '分表字段',
 * type => '分表方式',
 * table_post => '表名称前缀长度'
 */
$tables = array(

    't_sms_code' => array('db' =>'wonjoy_ticket'),

    't_order' => array('db' => 'wonjoy_ticket'),

    't_ticket' => array('db' => 'wonjoy_ticket'),

    't_product_base' => array('db' => 'wonjoy_ticket'),

    't_product_set' => array('db' => 'wonjoy_ticket'),

    't_unique_id' => array('db' => 'wonjoy_ticket'),

    't_user' => array('db' => 'wonjoy_ticket'),
);

$config = array();

// 正式数据库
if (isProduct()) {
    $connection = array(
        'driver' => 'mysql',
        'user' => 'root',
        'password' => 'admin',
        'charset' => 'utf8mb4',
        'master' => array(
            'host' => '127.0.0.1',
        ),
        'slaves' => array(
            array(
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'admin',
                'charset' => 'utf8mb4'
            ),
        ),
        'tables' => $tables
    );
    $config = array(
        'connection' => $connection,
        'tables' => $tables
    );
}
// 测试数据库
else {
    $connection = array(
        'driver' => 'mysql',
        'user' => 'root',
        'password' => 'admin',
        'charset' => 'utf8mb4',
        'port' => '3360',
        'master' => array(
            'host' => '127.0.0.1',
        ),
        'slaves' => array(
            array(
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'admin',
                'port' => '3360',
                'charset' => 'utf8mb4'
            ),
        ),
        'tables' => $tables
    );
    $config = array(
        'connection' => $connection,
        'tables' => $tables
    );
}

return $config;