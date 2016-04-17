<?php

if (isProduct()) {
    defined('TOP_SDK_WORK_DIR') or define('TOP_SDK_WORK_DIR', '/tmp/');
    defined('TOP_SDK_DEV_MODE') or define('TOP_SDK_DEV_MODE', false);

    return array(
        'static_server' => 'img.sucel-it.com',
        'image_server' => 'uimg.sucel-it.com', // 图片上传的 HTTP 访问地址
        'ftp' => array(
            'host' => '139.196.54.159',
            'account' => 'sucel_it_ftp',
            'password' => '#023012#ll_sucelit',
        ),
        'deny_photo' => 'http://img.sucel-it.com/weixin/images/deny_photo.jpg',
        'default_group_avatar' => 'http://139.196.54.159:8086/images/2015/11/23/b3fb7068.png',
        'default_group_avatar' => 'http://139.196.54.159:8086/images/2015/11/23/bb15edb7.png',
        'umeng_app_key' => '56494ddc67e58e8e5d007eae',
        'umeng_im_app_key' => '23270972',
        'umeng_im_app_secret' => '7d8a7369b5c8acabee68efc25c797300',
        'wonjoy_api_key' => '203Sjkd#03JSHDmm',
        'wonjoy_api_secret' => '2015092039204948490284',
        ' ' => 'wxcdffd8550ab304d3',
        'weixin_app_secret' => '313b8a00b440975bf133d6d37e9a9ec8',
    );
}
else {
    defined('TOP_SDK_WORK_DIR') or define('TOP_SDK_WORK_DIR', '/tmp/');
    defined('TOP_SDK_DEV_MODE') or define('TOP_SDK_DEV_MODE', true);

    return array(
        'static_server' => '139.196.54.159:7004',
        'image_server' => '139.196.54.159:8086', // 图片上传的 HTTP 访问地址
        'ftp' => array(
            'host' => '139.196.54.159',
            'account' => 'sucel_it_ftp',
            'password' => '#023012#ll_sucelit',
        ),
        'deny_photo' => 'http://139.196.54.159:8084/weixin/images/deny_img.jpg', // 图片不可用后(被禁止等原因) 显示的默认图片,
        'default_user_avatar' => 'http://139.196.54.159:8086/images/2015/11/23/b3fb7068.png',
        'default_group_avatar' => 'http://139.196.54.159:8086/images/2015/11/23/bb15edb7.png',
        'umeng_app_key' => '56494ddc67e58e8e5d007eae',
        'umeng_im_app_key' => '23270972',
        'umeng_im_app_secret' => '7d8a7369b5c8acabee68efc25c797300',
        'wonjoy_api_key' => '203Sjkd#03JSHDmm',
        'wonjoy_api_secret' => '2015092039204948490284',
        'weixin_app_key' => 'wxcdffd8550ab304d3',
        'weixin_app_secret' => '313b8a00b440975bf133d6d37e9a9ec8',
    );
}