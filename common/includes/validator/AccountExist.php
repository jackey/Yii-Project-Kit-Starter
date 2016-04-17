<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

use Sucel\Common\Description\ErrorCodeDesc;
use Sucel\Service\Dao\User\UserDao;

class AccountExist extends Validator {

    public function validate($value, $config = array()) {
        $userDao = UserDao::model()->findByPk($value);
        $valid = !!$userDao;
        return array($valid ,getParam($config, 'message', ErrorCodeDesc::mean(ErrorCodeDesc::USER_NOT_EXIST)), getParam($config, 'code', ErrorCodeDesc::USER_NOT_EXIST));
    }
}