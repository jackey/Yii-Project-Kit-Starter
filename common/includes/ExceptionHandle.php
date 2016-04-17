<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 6:08 PM
 */

namespace Sucel\Common\Includes;

class ExceptionHandle {

    /**
     * @param \Exception $exception
     */
    public static function handleException($exception) {
        // disable error capturing to avoid recursive errors
        restore_error_handler();
        restore_exception_handler();
        print_r($exception->getFile(). ' '. $exception->getLine());
        print_r($exception->getMessage());

        print_r($exception->getTraceAsString());

        Logger::error()->error($exception->getFile() .' ' . $exception->getLine().' '. $exception->getMessage());
        die();
    }
}