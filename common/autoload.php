<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 11:09 AM
 */

namespace Sucel\Common;

class AutoLoad {

    const prefix = 'sucel';

    public static function autoload($class) {

        $parts = explode("\\", $class);

        if (count($parts) > 1 && strtolower($parts[0]) == self::prefix) {
            array_shift($parts);
            $className = ucfirst(array_pop($parts));
            array_walk($parts, function (&$v) {
                  $v = strtolower($v);
            });
            $classFile = SUCELIT_PATH.DIRECTORY_SEPARATOR. implode(DIRECTORY_SEPARATOR, $parts).DIRECTORY_SEPARATOR. $className.'.php';
            if (file_exists($classFile)) require_once $classFile;
        }
    }
}

