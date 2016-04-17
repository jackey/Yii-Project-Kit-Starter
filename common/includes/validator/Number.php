<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

use Sucel\Common\Description\ErrorDesc;

class Number extends Validator {

    public function validate($value, $config = array()) {
        $valid = is_numeric($value);
        return array($valid ,getParam($config, 'message', ErrorDesc::IS_NUMBER), getParam($config, 'code', ErrorDesc::mean(ErrorDesc::IS_NUMBER)));
    }
}