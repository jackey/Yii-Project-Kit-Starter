<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:29 AM
 */

class IndexController extends WxController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionTest() {

        $uri = \Sucel\Common\Includes\QrCode::generateQRCodeAndUploadToFTP('hello world');
        echo uploadImageURL($uri);
        die();
    }

}