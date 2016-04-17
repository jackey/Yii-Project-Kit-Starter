<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no" />
    <meta name="description" content="「顽主·曼荼罗秀」故事、邂逅、印记…" />
    <meta name="description" content="途·印记－「顽主·曼荼罗秀」" / >
    <link rel="shortcut icon" href="<?php echo resourceURL('images/pic.jpg')?>" />

    <title>途·印记－「顽主·曼荼罗秀」</title>

    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <link rel="stylesheet" href="<?php echo resourceURL('css/reset.css')?>?time=<?php NOW?>"/>
    <link rel="stylesheet" href="<?php echo resourceURL('css/style.css')?>?time=<?php NOW?>"/>
    <script>
        var s_title = '途·印记－「顽主·曼荼罗秀」',
            s_link = 'http://ticket.wonjoy.com.cn/',
            s_img = '<?php echo resourceURL('images/share.png')?>',
            s_desc = '「顽主·曼荼罗秀」故事、邂逅、印记…';

        (function (stitle, slink, simg, sdesc) {
            var config = '<?php
                    $jsConfig = Yii::app()->wxAuth->getJSConfig();
                    echo json_encode($jsConfig);
                ?>';
            config = JSON.parse(config);
            config['jsApiList'] = ['chooseWXPay', 'onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'onMenuShareQZone'];
            console.log(config);

            wx.config(config);

            wx.ready(function () {
                wx.onMenuShareTimeline({
                    title: stitle,
                    link: slink,
                    imgUrl: simg,
                    desc: sdesc,
                    success: function () {
                    },
                    cancel: function() {
                        //TODO::
                    }
                });

                wx.onMenuShareAppMessage({
                    title:stitle,
                    link: slink,
                    imgUrl: simg,
                    desc: sdesc,
                    type: 'link',
                    success: function () {

                    },
                    cancel: function () {

                    }
                });

                wx.onMenuShareQQ({
                    title:stitle,
                    link: slink,
                    imgUrl: simg,
                    desc: sdesc,
                    success: function () {

                    },
                    cancel: function () {

                    }
                });

                wx.onMenuShareWeibo({
                    title:stitle,
                    link: slink,
                    imgUrl: simg,
                    desc: sdesc,
                    success: function () {

                    },
                    cancel: function () {

                    }
                });
            });
        })(s_title, s_link, s_img, s_desc);
    </script>
</head>
<body>
<div class="body">
    <div class="wrapper">
        <div class="loading">

        </div>
        <?php echo $content?>
    </div>
</div>

<script type="text/javascript" src="<?php echo resourceURL('bower_components/jquery/dist/jquery.min.js')?>"></script>
<script type="text/javascript" > var Zepto = jQuery;</script>
<script type="text/javascript"  src="<?php echo resourceURL('scripts/main.js')?>?time=<?php NOW?>"></script>
<script type="text/javascript"  src="<?php echo resourceURL('scripts/coffee.js')?>?time=<?php NOW?>"></script>
<script type="text/javascript"  src="<?php echo resourceURL('scripts/init.mix.js')?>?time=<?php NOW?>"></script>
<script type="text/javascript"  src="<?php echo resourceURL('scripts/99_main.js')?>?time=<?php NOW?>"></script>

<script>

</script>

</body>
</html>