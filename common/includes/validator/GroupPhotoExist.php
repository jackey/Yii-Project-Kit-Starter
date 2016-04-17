<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

use Sucel\Common\Description\ErrorCodeDesc;
use Sucel\Service\Dao\Group\GroupPhotoDao;
use Sucel\Service\Dao\User\UserDao;

class GroupPhotoExist extends Validator {

    public function validate($value, $config = array()) {
        $photoDao = GroupPhotoDao::model()->findByPk($value);
        $valid = !!$photoDao;
        return array($valid ,getParam($config, 'message', ErrorCodeDesc::mean(ErrorCodeDesc::PHOTO_NOT_EXIST)), getParam($config, 'code', ErrorCodeDesc::PHOTO_NOT_EXIST));
    }
}