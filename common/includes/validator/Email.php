<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

class Email extends Validator {

    public function validate($value, $config = array()) {
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        return array($valid ,getParam($config, 'message', '值必须是邮件'), getParam($config, 'code', 500));
    }
}