<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

class Phone extends Validator {

    public function validate($value, $config = array()) {
        //TODO:: 验证手机号码
        return array(true, 0, 0);
    }
}