<?php

use Sucel\Service\Api\RestServer;

class IndexController extends Controller{
    public function actionIndex() {
        $this->render('index');
    }
}