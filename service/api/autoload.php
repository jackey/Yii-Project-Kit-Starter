<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 9:51 AM
 */

namespace Sucel\Service\Api;

use Sucel\Common\Includes\Redis;

class AutoLoad {

    /**
     * 获取系统定义的接口和对应版本
     * array(user.info => array(1.0.0, 1.0.1, 1.1.0), user.login => array(1.0.0))
     * )
     */
    public static function getDefinedVersions($reset = false) {
        static $apiVersions = array();
        $cacheKey = 'api_versions';
        if (!empty($apiVersions) && !$reset) return $apiVersions;

        if (isProduct() && !$reset) {
            $apiVersions = Redis::getInstance(REDIS_CACHE)->get($cacheKey);
            if ($apiVersions) return unserialize($apiVersions);
        }

        $basePath = SUCELIT_PATH.'/service/api';
        $versionDirectoryIterators = array();
        foreach (new \DirectoryIterator($basePath) as $version) {
            if ($version->isDir() && !$version->isDot()) {
                $fileRealPath = $version->getRealPath();
                $versionDirectoryIterators[] = new \DirectoryIterator("{$fileRealPath}");
            }
        }

        foreach ($versionDirectoryIterators as $apiVersionIterator) {
            $verstr = basename($apiVersionIterator->getPath());
            foreach ($apiVersionIterator as $baseApi) {
                if ($baseApi->isDir() && !$baseApi->isDot()) {
                    $baseApiDirectoryIterator = new \DirectoryIterator($baseApi->getRealPath());
                    $apiPrefix = $baseApi->getFileName();
                    foreach ($baseApiDirectoryIterator as $apiName) {
                        if (!$apiName->isDot()) {
                            $apiSuffix = str_replace('.php', '', $apiName->getBasename());
                            $api = "{$apiPrefix}.$apiSuffix";
                            if (empty($apiVersions[$api])) {
                                $apiVersions[$api] = array();
                            }
                            $apiVersions[$api][] = $verstr;
                        }
                    }
                }
            }
        }

        if (isProduct())  Redis::getInstance(REDIS_CACHE)->set($cacheKey, serialize($apiVersions));

        return $apiVersions;
    }

    public static function autoload($class) {
        global $version;
        $ver = "v{$version}";
        $parts = explode("\\", $class);
        $apiVersions = self::getDefinedVersions();

        if (count($parts) > 1 && strtolower($parts[0]) == 'sucel'
            && array_search('Api', $parts) !== false) {
            array_shift($parts); // 去掉 sucel
            $apiEndName = array_pop($parts);
            $apiEndName = ucwords($apiEndName);
            $apiSpaceName = strtolower(array_pop($parts));
            array_walk($parts, function (&$v) {
                $v = strtolower($v);
            });

            $prefixParts = array('service', 'api');
            $apiName = "{$apiSpaceName}.{$apiEndName}"; // group.CreateAdmin

            $versions = getParam($apiVersions, $apiName);
            if (empty($versions)) return;
            $pos = array_search($ver, $versions);
            // 接口版本没有被找到情况下，获取一个最新版本
            if ($pos === false) {
                $ver = array_pop($versions);
            }
            $classFile = SUCELIT_PATH.DIRECTORY_SEPARATOR
                .implode(DIRECTORY_SEPARATOR, $prefixParts)
                .DIRECTORY_SEPARATOR
                .implode(DIRECTORY_SEPARATOR, array($ver, $apiSpaceName))
                .DIRECTORY_SEPARATOR."{$apiEndName}.php";

            if (is_file($classFile)) require_once $classFile;
        }
    }
}