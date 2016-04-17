<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

class ArrayOrEmpty extends Validator {

    public function validate($value, $config = array()) {
        if (empty($value)) return array(true, 0, 0);
        return array(is_array($value), getParam($config, 'message', '必须是数组'), getParam($config, 'code', 500));
    }
}