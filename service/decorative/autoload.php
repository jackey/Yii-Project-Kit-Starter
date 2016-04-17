<?php

namespace Sucel\Service\Decorative;

class Autoload {

    public static function autoload($class) {
        global $version;

        $parts = explode('\\', $class);
        $className = array_pop($parts);
        array_shift($parts);
        $service = array_shift($parts);
        $decorative = array_shift($parts);
        $path = strtolower(implode(DIRECTORY_SEPARATOR,  array_merge(array($service, $decorative ,"v{$version}"), $parts)));
        $class = implode(DIRECTORY_SEPARATOR, array($path, $className));

        $absPath = SUCELIT_PATH.DIRECTORY_SEPARATOR.$class.'.php';

        if (file_exists($absPath)) require_once $absPath;
    }
}