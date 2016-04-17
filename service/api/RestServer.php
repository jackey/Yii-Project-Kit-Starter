<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/3/15
 * Time: 6:12 PM
 */

namespace Sucel\Service\Api;

use Sucel\Common\Description\ErrorDesc;
use Sucel\Common\Includes\Logger;

class RestServer extends \CAction {

    public static $VERSION = '';
    public static $APINAME = '';
    public static $PARAMS = '';

    const MODE_STICK = 'strict'; //强制模式
    const MODE_LOOSE = 'loose'; // 松散模式

    const FORMAT_JSON = 'JSON';
    const FORMAT_XML = 'XML';

    private $responseCode = 200;
    private $errorMsg = '';
    private $debugInfo = array();

    public function __get($name) {
        return \Yii::app()->request->getQuery($name, false);
    }

    public function setErrorCode($code) {$this->responseCode = $code;}

    public function getErrorCode() {return $this->responseCode;}

    public function setErrorMsg($msg) {$this->errorMsg = $msg;}

    public function getErrorMsg() {return $this->errorMsg;}

    public function run() {
        global $version;

        $apiName = self::$APINAME; // user.login.code
        if (empty($apiName)) {
            echo 'welcome wonjoy api website';
            die();
        }
        $parts = explode('.', $apiName);
        $dir = ucfirst(array_shift($parts));
        $namespace = "\\Sucel\\Service\\Api\\{$dir}";
        array_walk($parts, function (&$v) {$v = ucfirst($v);});
        $className = implode('', $parts);
        $fullPathClass = "{$namespace}\\$className";

        try {
            // 运行接口
            $apiInstance = new $fullPathClass();

            $valid = $this->beforeSign($apiInstance);
            if (!$valid) throw new \CHttpException(500, '签名验证失败', 500);

            $apiInstance->setRequest(\Yii::app()->request);
            $data = $apiInstance->doJob();

            // 打印日志
            Logger::api()->info(sprintf(" \r\n API: %s \r\n version: %s \r\n params: %s \r\n result:%s \r\n", $apiName, $version, mergeArrayToPrint($_REQUEST), mergeArrayToPrint($data)));
            if (!empty($_FILES)) {
                Logger::api()->info(sprintf("files: %s\r\n", print_r($_FILES, true)));
            }

            $this->output($data);
        }
        catch (\CException $e) {
            $this->setErrorCode(isset($e->statusCode) ? $e->statusCode: $e->getCode());
            $this->setErrorMsg($e->getMessage());
            Logger::api()->error(sprintf(" \r\n API: %s \r\n version: %s \r\n params: %s \r\n result:%s ", $apiName, $version, mergeArrayToPrint($_REQUEST) , mergeArrayToPrint(array(
                'code' => isset($e->statusCode) ? $e->statusCode: $e->getCode(),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ))));
            $this->output();
        }
    }

    public function outputJSON($data = array()) {
        define('REQUEST_END', time());
        print_r(array(
            'request_start' => REQUEST_TIME,
            'request_end' => microtime(true)
        ));
        $debug = ob_get_contents();
        $this->debugInfo = $debug;
        ob_clean();
        header('Content-Type: application/json; charset=utf8');
        $struct = array(
            'message' => $this->getErrorMsg(),
            'code' => $this->getErrorCode(),
            'data' => $data,
            'debug' => $this->debugInfo
        );
        echo json_encode($struct);
        die();
    }

    public function output($data = array(), $format = self::FORMAT_JSON) {
        $fn = 'output'.$format;
        if (method_exists($this, $fn)) {
            $this->$fn($data);
        }
    }

    public function outputXML($data) {
        //TODO::
    }

    /**
     * @param BaseApi $apiInstance
     * @throws
     * @return bool
     */
    public function beforeSign($apiInstance) {

        $params = array();
        foreach ($apiInstance->rules() as $name => $_) {
            $params[$name] = $this->$name;
        }

        ksort($params);
        $signStr = '';
        foreach ($params as $key => $val) {
            $signStr[] = $key. '='. $val;
        }
        $clientSignStr = $this->sign;
        $timestamp = $this->timestamp;
        $key = $this->key;
        $signStr = implode('&', $signStr);
        $appConfig = appConfig();

        if ($this->debug && !isProduct()) return true;

        if (!$key || !$clientSignStr) throw new \CHttpException(ErrorDesc::SIGN_REQUIRED, ErrorDesc::mean(ErrorDesc::SIGN_REQUIRED));

        $sign = md5(sprintf("%s-%s-%s-%s", $signStr, $timestamp, $key, $appConfig['wonjoy_api_secret']));
        if ($sign != $clientSignStr) return false;

        return true;
    }
}