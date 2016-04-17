<?php

define('SUCELIT_PATH', realpath(dirname('./../../../'))); // Sucel项目目录
define('BASE_PATH', realpath(dirname(__FILE__))); // 当前项目目录

// 必须
define('PROJECT_NAME', 'weixin'); // 定义项目名称

require_once BASE_PATH. '/env.php';
require_once SUCELIT_PATH.'/common/bootstrap.php';
require_once SUCELIT_PATH.'/framework/yii.php';

$config = require_once BASE_PATH.'/protected/config/'.ENV.'/main.php';

$app = Yii::createWebApplication($config);

$app->run();
