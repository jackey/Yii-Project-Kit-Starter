<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 5:50 PM
 */
class SiteController extends WxController {

    public function actionError() {
        if($error=Yii::app()->errorHandler->error) {
            \Sucel\Common\Includes\Logger::error()->error(serialize($error));
            $this->render('error', $error);
        }
    }
}