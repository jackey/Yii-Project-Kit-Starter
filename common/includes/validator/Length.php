<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

class Length extends Validator {

    public function validate($value, $config = array()) {
        $maxLength = getParam($config, 'max');
        $minLength = getParam($config, 'min');
        $len = strlen($value);
        $valid = true;
        if ($maxLength && $len >= $maxLength) $valid = false;
        if ($minLength && $len < $minLength) $valid = false;
        return array($valid ,getParam($config, 'message', '值必须是邮件'), getParam($config, 'code', 500));
    }
}