// HTML / Style
$(document).ready(function() {
    $(".loading").css("display","block");
    $("canvas").css("display","block");
    var music = document.getElementById("bgMusic");
    $("#audioBtn").click(function(){
        if(music.paused){
            $(".mscBtn").addClass("on");
            $(".mscBtn").removeClass("off");
            music.play();
            $("#audioBtn").removeClass("pause").addClass("play");
        }else{
            $(".mscBtn").addClass("off");
            $(".mscBtn").removeClass("on");
            music.pause();
            $("#audioBtn").removeClass("play").addClass("pause");
        }
    });
});

$(window).load(function(){
    $(".loading").css("display","none");
    $("canvas").css("display","none");
});

// Submit Order
(function ($) {
    $(function () {
        (function (container) {
            var w = $(window).width();
            $(".advertise", container).css("height", .6 * w + "px");

            $(".choosebutton", container).click(function(){
                $(".choosebutton").removeClass("active");
                $(this).addClass("active");
            });

            $(".buynow", container).click(function(){

                function showUserInoformationPopup () {
                    $(".grey").css({
                        display: 'block',
                        opacity: 0
                    });
                    $(".detailinfo").css({
                        display: 'block',
                        opacity: 0
                    });
                    setTimeout(function () {
                        $('.grey').css({
                            opacity:.8
                        });
                        $('.detailinfo').css({
                            opacity:1
                        });
                    });
                }

                // 检查输入
                var count = $('input[name="count"]', container).val();
                if (!jQuery.isNumeric(count) || count < 1) {
                    alert('您至少需要够买一张票');
                }
                else {
                    showUserInoformationPopup();
                }
            });

            $(".grey", container).click(function(){
                $(".grey").css({
                    display: 'none',
                    opacity: 0.8
                });
                $(".detailinfo").css({
                    display: 'none',
                    opacity: 1
                });
                setTimeout(function () {
                    $('.grey').css({
                        opacity:0
                    });
                    $('.detailinfo').css({
                        opacity:0
                    });
                });
            });

            $(".codeimg").click(function(){
                var $el = $(this);
                $(".white").css({
                    display: 'block'
                });
                $(".bigcodeimg").find('>img').attr('src', $el.attr('src')).end().css({
                    display: 'block'
                });
                setTimeout(function () {
                    $('.white').css({
                        opacity:1
                    });
                    $('.bigcodeimg').css({
                        opacity:1
                    });
                });
            });

            $(".close").click(function(){
                $(".white").css({
                    display: 'none',
                });
                $(".bigcodeimg").css({
                    display: 'none',
                });
                setTimeout(function () {
                    $('.white').css({
                        opacity:0
                    });
                    $('.bigcodeimg').css({
                        opacity:0
                    });
                });
            });

            function executeValidatorAndReturnValuesIfValid() {
                var validators = {
                    realname: function (val) {
                        if (val.trim().length <= 0) {
                            alert('请输入您的真实姓名');
                            return false;
                        }
                        return true;
                    },
                    phone: function (val) {
                        if (val.trim().length <= 0) {
                            alert('请输入您的手机号码');
                            return false;
                        }
                        return true;
                    },
                    count: function (val) {
                        val = val * 1;
                        if(val == NaN || val < 1) {
                            alert('至少购买一张票');
                            return false;
                        }
                        return true;
                    },
                    date: function (val) {
                        val = val * 1;
                        if(val == NaN || val < 1) {
                            alert('请选择日期');
                            return false;
                        }
                        return true;
                    }
                };

                var values = {};
                for (var name in validators) {
                    var $el = $('input[name="'+name+'"]', container),
                        fn = validators[name];
                    if (!fn($el.val())) return false;
                    values[name] = $el.val();
                }
                return values;
            }

            var timer,
                price = $('input[name="price"]').val();
            $('input[name="count"]', container).keyup(function () {
                if (timer ) clearTimeout(timer);
                var $el = $(this),
                    total = ($el.val() * price / 100);

                timer = setTimeout(function () {
                    $('.price_cnt', container).text(total);
                }, 500);
            });

            // 计算价格
            setInterval(function () {
                var $el = $('input[name="count"]', container),
                    price = $('input[name="price"]').val(),
                    total = ($el.val() * price / 100) ;

                $('.price_cnt', container).text(total);
            }, 500);

            $('.minus').click(function () {
                // 减
                var $count = $('input[name="count"]', container);
                if ($count.val() * 1 - 1 < 1) return;
                $count.val($count.val()*1 - 1);
                $(this).siblings('.number').text($count.val());

            }).siblings('.plus').click(function () {
                // 加
                var $count = $('input[name="count"]', container);
                $count.val($count.val()*1 + 1);
                $(this).siblings('.number').text($count.val());
            });

            wx.ready(function () {
                $('.btn-buy', container).click(function (event) {
                    event.preventDefault();
                    var values = executeValidatorAndReturnValuesIfValid(),
                        $el = $(this);

                    if ($el.hasClass('paying')) return;
                    $el.addClass('paying');

                    if (values) {
                        $el.text('支付准备中');
                        $.ajax({
                            url: '/order/submit',
                            method: 'post',
                            data: values,
                            datType: 'json'
                        }).fail(function (res) {
                            alert("对不起 服务器出错 请稍后再试");
                            $el.removeClass('playing');
                        }).done(function (res) {
                            wx.chooseWXPay({
                                timestamp: res['data']['timeStamp'],
                                nonceStr: res['data']['nonceStr'],
                                package: res['data']['package'],
                                signType: res['data']['signType'],
                                paySign: res['data']['paySign'],
                                success: function () {
                                    window.location.href = '/order/ticket';
                                },
                                error: function (res) {
                                    alert('对不起 微信支付出现问题 请稍后再试');
                                }
                            });
                        }).always(function () {
                            $el.text('去支付');
                            $el.removeClass('playing');
                        });
                    }
                    return false;
                });
            });

            $('.choosebutton', container).click(function () {
                var $el = $(this);
                $('input[name="date"]', container).val($el.data('id'));
            });

        })($('.submit-order-container'));
    });
})(jQuery, wx);