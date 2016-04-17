<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:50 AM
 */

namespace Sucel\Common\Includes\Validator;

class Required extends Validator {

    public function validate($value, $config = array()) {
        return array(!empty($value) ,getParam($config, 'message', '值必须'), getParam($config, 'code', 500));
    }
}