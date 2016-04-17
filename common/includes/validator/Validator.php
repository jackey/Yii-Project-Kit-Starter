<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:50 AM
 */

namespace Sucel\Common\Includes\Validator;

abstract class Validator {
    abstract public function validate($value, $config = array());

    /**
     * 验证器 - 快捷方法
     * @param $vName
     * @param $value
     * @param $config
     */
    public static function check($vName, $value, $config = array()) {
        $class = "\\Sucel\\Common\\Includes\\Validator\\". $vName;
        if (!class_exists($class)) throw new \CException("验证器不存在", 500);
        $v = new $class;
        return $v->validate($value, $config);
    }
}

