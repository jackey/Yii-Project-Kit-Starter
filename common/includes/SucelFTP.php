<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 12/11/15
 * Time: 12:01 PM
 */

namespace Sucel\Common\Includes;

require_once SUCELIT_PATH.'/plugin/FtpClient/FtpWrapper.php';
require_once SUCELIT_PATH.'/plugin/FtpClient/FtpException.php';
require_once SUCELIT_PATH.'/plugin/FtpClient/FtpClient.php';

class SucelFTP extends \FtpClient\FtpClient{

    private static $_instance;

    private $mimeExt = array(
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
        'image/jpg' => 'jpg',
    );

    public static function instance() {
        if (self::$_instance) return self::$_instance;
        $appConfig = appConfig();
        $ftpConfig = getParam($appConfig, 'ftp');
        if (empty($ftpConfig)) throw new \CException('FTP服务器还未设置', 500);
        $host = getParam($ftpConfig, 'host');
        $port = getParam($ftpConfig, 'port', 21);
        $account = getParam($ftpConfig, 'account');
        $password = getParam($ftpConfig, 'password');

        $ftp = new self();
        $ftp->connect($host, false, $port);
        $ftp->login($account, $password);
        self::$_instance = $ftp;

        return self::$_instance;
    }

    private function getUUIDFileName() {
        $uuid = uniqid('image');
        return substr(sha1($uuid), 5, 8); // 8位大小的文件名
    }

    /**
     * 上传图片
     * @param $fullImagePath
     * @param $ext
     * @throws \FtpClient\FtpException
     */
    public function uploadImage($fullImagePath) {
        $imagePath = './images';
        $isExist = $this->isDir($imagePath);
        if (!$isExist) $this->mkdir($imagePath);
        $this->chdir($imagePath);

        $subFolder = implode(DIRECTORY_SEPARATOR, array(date('Y'), date('m'), date('d')));
        if (!$this->isDir($subFolder)) {
            $this->mkdir($subFolder, true);
        }

        $mime = mime_content_type($fullImagePath);
        $ext = getParam($this->mimeExt, $mime);
        $uploadTo = $subFolder.DIRECTORY_SEPARATOR.$this->getUUIDFileName().'.'. $ext;
        $ret = $this->put($uploadTo, $fullImagePath, FTP_IMAGE);

        if (!$ret) return false;

        // 返回FTP文件路径
        $uri = substr($imagePath.DIRECTORY_SEPARATOR.$uploadTo, 2);

        $this->cdup();

        return $uri;
    }
}