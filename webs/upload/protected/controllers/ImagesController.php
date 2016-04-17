<?php
/**
 * Created by PhpStorm.
 * User: jackeychen
 * Date: 11/2/15
 * Time: 10:29 AM
 */

use Sucel\Service\Dao\User\UserDao;

use Sucel\Common\Includes\UniqueID;

class ImagesController extends Controller {

    public function run($actionID) {
        $uri = substr($_SERVER['REQUEST_URI'], 1);
        $localFile = fopen('php://temp', 'w');
        $ftp = \Sucel\Common\Includes\SucelFTP::instance();
        $ftp->fget($localFile, $uri, FTP_BINARY, 0);
        fseek($localFile, 0);

        $this->renderImage($localFile);
    }

    public function renderImage($resource) {
        $size = fstat($resource)['size'];
        $content = fread($resource, $size);
        $finfo = finfo_open();
        $mime = finfo_buffer($finfo, $content, FILEINFO_MIME_TYPE);
        header('Content-Type: '. $mime);
        header('Content-Length: '. $size);

        echo $content;
        die();
    }
}