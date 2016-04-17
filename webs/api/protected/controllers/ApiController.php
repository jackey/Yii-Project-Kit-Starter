<?php

use Sucel\Service\Api\RestServer;

class ApiController extends CController{

    public function run($actionID) {
        global $version;
        $action=$this->createAction($actionID);

        if ($action) {
            if(($parent=$this->getModule())===null)
                $parent=Yii::app();
            if($parent->beforeControllerAction($this,$action))
            {
                $this->runActionWithFilters($action,$this->filters());
                $parent->afterControllerAction($this,$action);
            }
        }
        else {
            // api/{version}/apiname?param=value&param1=value

            $uri = substr(preg_replace("/\?[^\?]+/i", "" ,$_SERVER['REQUEST_URI']), 1);

            $parts = explode('/', $uri);
            RestServer::$APINAME = $parts[2];
            RestServer::$VERSION = $parts[1];
            RestServer::$PARAMS = $_GET;
            if (empty($version)) $version = RestServer::$VERSION;

            $action = $this->createAction('index');
            if(($parent=$this->getModule())===null)
                $parent=Yii::app();
            if($parent->beforeControllerAction($this,$action))
            {
                $this->runActionWithFilters($action,$this->filters());
                $parent->afterControllerAction($this,$action);
            }
        }
    }

    public function actions() {
        return array(
            'index' => array(
                'class' => 'Sucel\Service\Api\RestServer',
            )
        );
    }
}