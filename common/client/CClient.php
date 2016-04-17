<?php
/**
 * Created by PhpStorm.
 * User: sucel
 * Date: 27/11/15
 * Time: 2:46 PM
 */

namespace Sucel\Common\Client;

use Sucel\Common\Includes\Logger;

class CClient {

    public static $version;
    public static $apiURL;
    public static $key;

    public function request($api, $params) {
        $sign = $this->getSignString($params);
        $params['sign'] = $sign;
        if (!isProduct()) {
            $params['debug'] = 1;
        }
        $data = $this->call($api, $params, null, true);
        if ($data['code'] != 200) {
            Logger::log()->error('调用接口: '. $api. ' 参数: '. json_encode($params). ' 失败');
            Logger::log()->error(json_encode($data));
        }
        return $data;
    }

    /**
     * 加密字符串
     * @param $params
     * @return string
     */
    public function getSignString($params = array()) {
        return '';
    }

    public function call($api, $data, $file = false, $isPost = false) {
        $query = http_build_query($data);
        $url = sprintf("%s/api/%s/%s?%s", CClient::$apiURL, CClient::$version, $api, $query);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        Logger::log()->info('call: '. $url);

        // 上传文件
        if ($file || $isPost) {
            if ($file) {
                $data['image'] = "@{$file}";
            }
            else {
                $data = http_build_query($data);
            }

            curl_setopt($curl ,CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $return = curl_exec($curl);
        curl_close($curl);

        $json = @json_decode($return, true);
        if (!$json) throw new \CHttpException(500, "获取接口数据失败.");

        return $json;
    }

    public function uploadFile($api, $file) {
        $filePath = getParam($file, 'tmp_name');

        $sign = $this->getSignString();
        return $this->call($api, array('sign' => $sign), $filePath);
    }
}