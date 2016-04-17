<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/9/15
 * Time: 11:52 AM
 */
namespace Sucel\Common\Includes\Validator;

class Image extends Validator {

    private $allowMime = array(
        'image/jpeg', 'image/jpg', 'image/png'
    );
    public function validate($value, $config = array()) {
        // 验证文件是否是符合格式的图片
        $setMime = getParam($config, 'mine', array());
        if (!is_array($setMime)) $setMime = array($setMime);
        if (empty($setMime)) $setMime = $this->allowMime;
        if (!is_file($value))  return array(0, getParam($config, 'message'), getParam($config, 'code'));
        $mime = mime_content_type($value);
        $valid = array_search($mime, $setMime) !== false;
        return array($valid, getParam($config, 'message'), getParam($config, 'code'));
    }
}