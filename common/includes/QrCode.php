<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 9/12/15
 * Time: 10:14 PM
 */

namespace Sucel\Common\Includes;

require_once SUCELIT_PATH.'/plugin/phpqrcode/phpqrcode.php';

class QrCode {

    static $logoURI = '/weixin/images/qrcode_logo.png';
    static $cacheKey = 'qrcode_logo';

    public static function generateQRCodeAndUploadToFTP($string) {

        $imageName = uniqid('qrcode_').'.png';
        $imagePath = SUCELIT_PATH.'/logs/qrcode';

        if (!is_dir($imagePath)) {
            mkdir($imagePath, 0755, true);
        }
        $imageFullPath = "{$imagePath}/$imageName";

        \QRcode::png($string, $imageFullPath ,QR_ECLEVEL_H, 9);

        self::applyLogoToQRCode($imageFullPath);

        $uri = SucelFTP::instance()->uploadImage($imageFullPath);

        unlink($imageFullPath);

        return $uri;
    }

    public static function applyLogoToQRCode($qrcode) {

        $redis = Redis::getInstance(REDIS_CACHE);
        $logoString = $redis->get(self::$cacheKey);
        if (!$logoString) {
            $appConfig = appConfig();
            $URL = sprintf("http://%s/%s", $appConfig['static_server'], self::$logoURI);

            $logoString = file_get_contents($URL);
            $redis->set(self::$cacheKey, $logoString);
        }

        $qrcodeImage = imagecreatefromstring(file_get_contents($qrcode));
        $logoImage = imagecreatefromstring($logoString);
        $qrWidth = imagesx($qrcodeImage);
        $qrHeight = imagesx($qrcodeImage);
        $logoWidth = imagesx($logoImage);
        $logoHeight = imagesx($logoImage);

        $percentWidth = 8; // Logo 的大小占 QRCode 的面积比

        $logoOnQRWidth = $qrWidth / $percentWidth;
        $scale = $logoWidth / $logoOnQRWidth;
        $logoOnQRHeight = $logoHeight / $scale;

        $fromX = ($qrWidth - $logoOnQRWidth) / 2;
        imagecopyresampled($qrcodeImage, $logoImage, $fromX, $fromX, 0, 0, $logoOnQRWidth, $logoOnQRHeight, $logoWidth, $logoHeight);
        imagepng($qrcodeImage, $qrcode);
    }
}

