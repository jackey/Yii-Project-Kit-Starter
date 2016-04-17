<?php

require_once SUCELIT_PATH.'/common/includes/Functions.php';
require_once SUCELIT_PATH.'/common/autoload.php';
require_once SUCELIT_PATH.'/service/api/autoload.php';
require_once SUCELIT_PATH.'/service/decorative/autoload.php';

define('YII_ENABLE_EXCEPTION_HANDLER', FALSE);
define('YII_ENABLE_ERROR_HANDLER', FALSE);

define('NOW', time());

// 注册类自动加载类
// common 自动加载
spl_autoload_register(array('Sucel\Common\AutoLoad', 'autoload'), true, false);

// Plugin
require_once SUCELIT_PATH.'/plugin/sms/SMS.php';
require_once SUCELIT_PATH.'/plugin/predis/autoload.php';
require_once SUCELIT_PATH.'/plugin/umeng/TopSdk.php';

// 时区
date_default_timezone_set('Asia/Shanghai');

// 加载配置
loadGlobalConfig();

