<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 10/12/15
 * Time: 4:07 PM
 */

class ReportController extends WxController {

    public function actionOrder() {
        $key = "sahdoknc02839sjdks902";

        $passKey = $this->getParam('secret', "");

        if ($passKey != $key) {
            header('Content-Type: text/html; charset=utf-8');
            print "您无权访问";
            die();
        }


        $query = new \CDbCriteria();
        $query->addCondition('Fstatus=:status');
        $query->params[':status'] = \Sucel\Service\Dao\OrderDao::STATUS_PAIED;

        $orderDaos = \Sucel\Service\Dao\OrderDao::model()->findAll($query);

        $this->render('order', array('orderDaos' => $orderDaos));
    }
}