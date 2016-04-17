<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 16/11/15
 * Time: 12:26 PM
 */

namespace Sucel\Common\Includes\Cache;

use Sucel\Common\Description\ErrorCodeDesc;
use Sucel\Common\Includes\Redis;

abstract class CCache {

    protected $keyFormat = '';

    protected static $_instances = array();

    abstract function group();

    /**
     * 获取Redis 对应Group (数据库)
     * @return \Predis\Client
     * @throws \CException
     */
    public function getDatabase() {
        return Redis::getInstance($this->group());
    }

    public function key() {
        $params = func_get_args();
        $format = $this->keyFormat;
        if (empty($format)) throw new \CHttpException(ErrorCodeDesc::REDIS_KEY_FORMAT_NO_DEFINED, ErrorCodeDesc::mean(ErrorCodeDesc::REDIS_KEY_FORMAT_NO_DEFINED));
        preg_match_all("/(\{[^\{^\}]+\})/i", $format, $matches);

        $tokens = getParam($matches, 0);
        if ($tokens) {
            $replace = array();
            foreach ($tokens as $index => $token) {
                $replace[$token] = getParam($params, $index);
            }
            return str_replace(array_keys($replace), array_values($replace), $format);
        }
        return $format;
    }

    public static function instance($class = __CLASS__) {
        if (empty(self::$_instances[$class])) {
            self::$_instances[$class] = new static();
        }

        return self::$_instances[$class];
    }
}