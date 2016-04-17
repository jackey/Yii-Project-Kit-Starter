<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:46 AM
 */

function loadGlobalConfig() {
    global $globalConfig;

    $dbConfig = require_once SUCELIT_PATH.'/common/config/DB.php';
    $mongodbConfig = require_once SUCELIT_PATH.'/common/config/Mongodb.php';
    $redisConfig = require_once SUCELIT_PATH.'/common/config/Redis.php';
    $appConfig = require_once SUCELIT_PATH.'/common/config/App.php';

    $globalConfig['db'] = $dbConfig;
    $globalConfig['mongodb'] = $mongodbConfig;
    $globalConfig['redis'] = $redisConfig;
    $globalConfig['app'] = $appConfig;

    return $globalConfig;
}

function initSystemHandler() {
    set_error_handler(array('Sucel\Common\Includes\ErrorHandle', 'handleError'), error_reporting());
    set_exception_handler(array('Sucel\Common\Includes\ExceptionHandle', 'handleException'));

    register_shutdown_function('fatal_handler');
}

function fatal_handler() {
    $error = error_get_last();
    \Sucel\Common\Includes\Logger::error()->error(print_r($error, true));

    print_r($error);
}

function dbConfig() {
    global $globalConfig;

    return $globalConfig['db'];
}

function redisConfig() {
    global $globalConfig;

    return $globalConfig['redis'];
}

function mongoDbConfig() {
    global $globalConfig;

    return $globalConfig['mongodb'];
}

function appConfig() {
    global $globalConfig;

    return $globalConfig['app'];
}

function isProduct() {
    return ENV != 'dev';
}

function getParam($values, $key, $default = '') {
    if (isset($values[$key])) return $values[$key];

    return $default;
}

function resourceURL($uri) {
    $projectName = PROJECT_NAME;
    $uri = $projectName.'/'. $uri;

    $appConfig = appConfig();
    $url = sprintf("http://%s/%s", $appConfig['static_server'], $uri);

    return $url;
}

/**
 * 加密用户密码
 * @param $pass
 * @param string $prefix
 * @return string
 */
function encryptPassword($pass, $prefix = '##$##') {
    $str = "{$pass}{$prefix}";
    return md5(md5($str));
}

/**
 * 返回图片全路径
 * @param $uri
 * @return string
 */
function uploadImageURL($uri) {
    $appConfig = appConfig();
    $isArray = false;
    if (strpos($uri, ',') !== false) {
        $uris = explode(',', $uri);
        $isArray = true;
    }
    else {
        $uris = array($uri);
    }
    $retURLs = array();
    foreach ($uris as $path) {
        if (strpos($path, 'http') !== false) $retURLs[] = $path;
        else {
            $retURLs[] = sprintf("http://%s/%s", $appConfig['image_server'], $path);
        }
    }
    if ($isArray) return $retURLs;
    return array_shift($retURLs);
}

/**
 * 返回图片URI地址
 * @param $url
 * @return array()|string
 */
function uploadImageURI($url) {
    $appConfig = appConfig();
    $host = "http://{$appConfig['image_server']}/";

    $isArray = false;
    if (is_string($url) && strpos($url, ',') !== false) {
        $urls = explode(',', $url);
    }
    else if (!is_array($url)) {
        return str_replace($host, "", $url);
    }
    else {
        $urls = $url;
    }

    $uris = array();
    foreach ($urls as $url) {
        $uris[] = uploadImageURI($url);
    }

    return $uris;
}

function deniedImageURL() {
    $appConfig = appConfig();
    return $appConfig['deny_photo'];
}

/**
 * 时间戳转换成时间说明 (1小时前， 5分钟前)
 * @param $timestamp
 * @return string
 */
function timeToDesc($timestamp) {
    //TODO:: 完成转换
    return '5分钟前';
}


function mergeArrayToPrint($array) {
    if (is_array($array)) {
        $str = [];
        foreach ($array as $key => $name) {
            $str[] = "${key} => ". print_r($name, true);
        }
        return implode(" ", $str);
    }
    return print_r($array, true);
}