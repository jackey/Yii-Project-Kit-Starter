<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 25/11/15
 * Time: 11:42 AM
 */
namespace Sucel\Common\Includes\Timeline;

class TimeLineTemplate {

    public $user = array();
    public $photo = array();
    public $time = '';
    public $type = 0;
    public $message = '';
    public $comments = array();
    public $group;

    public function __construct() {
        $this->group = new \stdClass();
    }
}