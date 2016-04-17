<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 19/11/15
 * Time: 1:58 PM
 */

namespace Sucel\Common\Includes;

class LogWriteStream {
    private $group; // 日志组 - 不同类型的日志分配到不同的文件中
    private $path ;
    private $project;

    private static $_openedFiles;

    public function __construct($group, $project) {
        $this->setGroup($group)
            ->setProject($project);

        $this->path = SUCELIT_PATH.DIRECTORY_SEPARATOR.'logs';
    }

    public function setProject($project) {
        $this->project = $project;
        return $this;
    }

    public function setGroup($group) {
        $this->group = $group;
        return $this;
    }

    /**
     * 写入消息
     * @param string $message
     * @return LogWriteStream
     */
    public function write($message) {
        $logPath = implode(DIRECTORY_SEPARATOR, array($this->path, $this->project));
        $name = $this->group.'.'.date('Y-m-d'). '.log';
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755);
        }
        $absPath = implode(DIRECTORY_SEPARATOR, array($logPath, $name));
        if (!empty(LogWriteStream::$_openedFiles[$absPath])) {
            $file = LogWriteStream::$_openedFiles[$absPath];
        }
        else {
            $file = fopen($absPath, 'a+');
            LogWriteStream::$_openedFiles[$absPath] = $file;
        }

        fwrite($file, $message);
        return $this;
    }

    public function info($message) {
        $category = 'info';
        if (!is_string($message)) $message = json_encode($message);
        $formatMessage = sprintf("[%s]\t[%s]\t[%s]\r\n", $category, date('Y-m-d H:i:s'), $message);
        return $this->write($formatMessage);
    }

    public function debug($message) {
        $category = 'debug';
        $formatMessage = sprintf("[%s]\t[%s]\t[%s]\r\n", $category, date('Y-m-d H:i:s'), $message);
        return $this->write($formatMessage);
    }

    public function error($message) {
        $category = 'error';
        $formatMessage = sprintf("[%s]\t[%s]\t[%s]\r\n", $category, date('Y-m-d H:i:s'), $message);
        return $this->write($formatMessage);
    }

    public function __destruct() {
//        if (is_array(self::$_openedFiles)) {
//            foreach(self::$_openedFiles as $file) {
//                if (is_resource($file)) {
//                    fclose($file);
//                }
//            }
//        }
    }
}

class Logger {

    const DB = 'db';
    const API = 'api';
    const ERROR = 'error';
    const LOG = 'log';

    /**
     * @param $group
     * @return LogWriteStream
     */
    public static function getLogger($group) {
        return new LogWriteStream($group, PROJECT_NAME);
    }

    public static function api() {
        return self::getLogger(self::API);
    }

    public static function db() {
        return self::getLogger(self::DB);
    }

    public static function error() {
        return self::getLogger(self::ERROR);
    }

    public static function log() {
        return self::getLogger(self::LOG);
    }
}