<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/3/15
 * Time: 6:18 PM
 */

namespace Sucel\Service\Api;

use Sucel\Common\Includes\Validator\Validator;

abstract class BaseApi {

    /**
     * @var CRequest
     */
    private $request;

    public function setRequest($request) {
        $this->request = $request;
    }

    public function get($name, $default = ''){
        return $this->request->getQuery($name, $default);
    }

    public function __get($name) {
        $v = $this->get($name);
        if (empty($v)) {
            $rules = $this->rules();
            if (!empty($rules[$name]) && !empty($rules[$name]['default'])) return $rules[$name]['default'];
        }
        return $v;
    }

    public function rules() {return array();}

    abstract public function run();

    public function doJob() {
        $this->beforeRun();
        // 验证参数
        $rules = $this->rules();
        foreach ($rules as $param => $rule) {
            list($valid, $message, $code) = $this->runValidation($rule, $this->get($param));
            if (!$valid) throw new \CHttpException($code, $message);
        }

        $data = $this->run();
        $this->afterRun();

        return $data;
    }

    public function runValidation($rule, $value) {
        $validResult = array(true, 0, 0); // 默认返回
        $validators = getParam($rule, 'validator', array());
        foreach ($validators as $ruleKey => $config) {
            $validResult = Validator::check($ruleKey, $value, $config);
            if (!$validResult[0]) return $validResult;
        }
        return $validResult;
    }

    public function beforeRun() {}

    public function afterRun() {}
}