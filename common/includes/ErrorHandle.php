<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 6:20 PM
 */

namespace Sucel\Common\Includes;

class ErrorHandle {

    public static function handleError($code, $message, $file, $line) {
        if ($code & error_reporting()) {
            // disable error capturing to avoid recursive errors
            restore_error_handler();
            restore_exception_handler();

            print_r(func_get_args());

            Logger::error()->error(print_r(func_get_args(), true));
        }
    }
}
