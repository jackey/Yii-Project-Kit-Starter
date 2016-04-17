<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 3:09 PM
 */

class Validator {

    public static function phone($phone) {
        if(preg_match("/^1[3458][0-9]{9}$/", $phone)){
            return true;
        }else{
            return false;
        }
    }

    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}