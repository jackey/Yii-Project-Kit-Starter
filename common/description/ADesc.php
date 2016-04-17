<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 11:01 AM
 */

namespace Sucel\Common\Description;

use Sucel\Common\Includes\AInstance;

abstract class ADesc extends AInstance{

     const DESC_NAME = 'desc_name';

    public static function mean($id) {
        $mapper = self::Instance()->mapper();
        return getParam($mapper, $id);
    }

    public abstract function mapper();
}