<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 11:05 AM
 */

namespace Sucel\Common\Includes;

class AInstance {

    static $_instance;

    public static function Instance($reset = false) {
        if ($reset || !self::$_instance) self::$_instance = new static;
        return self::$_instance;
    }
}