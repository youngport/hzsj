<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
session_start();
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];


if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}
$openid = $_SESSION["openid"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$result = $do->getone("select phone from sk_member where open_id='$openid'");
if(empty($result['phone'])){
    echo "<script>window.history.back(-1);</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <title>验证</title>
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
        <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="//cdn.bootcss.com/jquery-validate/1.14.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
        <script type="text/javascript">
            wx.config({
                appId: '<?php echo $shareCheckArr["appid"]; ?>',
                timestamp: '<?php echo $shareCheckArr["timestamp"]; ?>',
                nonceStr: '<?php echo $shareCheckArr["noncestr"]; ?>',
                signature: '<?php echo $shareCheckArr["signature"]; ?>',
                jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
            });
            wx.ready(function () {
                var shareData = {
                    title: '洋仆淘跨境商城——源自于深圳自贸区的专业跨境电商平台',
                    desc: '洋仆淘,打造专业 诚信的跨境贸易服务平台,结合线下感受线上支付的O2O模式，精准速效的整合全球优质商品资源，推送予有相关需求的消费者.', // 分享描述
                    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>&connect_redirect=1#wechat_redirect',
                    imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST'] ?>/wei/images/logo.png',
                    success: function (res) {
                        msgSuccess("分享成功");
                    },
                    cancel: function (res) {
                    }
                };
                wx.onMenuShareTimeline(shareData);
                wx.onMenuShareAppMessage(shareData);
                wx.onMenuShareQQ(shareData);
                wx.onMenuShareWeibo(shareData);
                wx.hideMenuItems({
                    menuList: [
                        'menuItem:readMode',
                        'menuItem:openWithSafari',
                        'menuItem:openWithQQBrowser',
                        'menuItem:copyUrl'
                    ]
                });
            });
        </script>
        <style>
            .confirm input{background:#F2F2F2;border:0;}
            .confirm button{width:60%;}
            .mob{border:0;padding-left:2em;}
            .ver{border:0;padding-left:2em;}

            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
                border-top: none;
            }
        </style>
    </head>
    <body style="background:#F2F2F2">
        <div class="container-fluid" style="padding:0;">
            <div class="container-fluid" style="padding: 0;">
                <form class="confirm">
                    <table class="table">
                        <tr style="background:#fff;">
                            <td colspan="2" style="border-top:18px solid #f0f0f0;">
                                <div class="input-group" style="width:100%;background-color: white;">
                                    <span class="input-group-addon mob" style="background-color: white;border: 0;" id="basic-addon1">手机号码</span>
                                    <input type="text" class="form-control" id="mobile" style="background-color: white;box-shadow:none;" name="mobile" placeholder="请填写您设置密码时的手机号" value=""/>
                                </div>
                            </td>
                        </tr>
                        <tr style="background:#fff">
                            <td width="65%" style="border-top:18px solid #f0f0f0;">
                                <div class="input-group" style="width:100%">
                                    <span class="input-group-addon ver" id="basic-addon1" style="background-color: white;">语音验证码</span>
                                    <input type="text" class="form-control" id="voiceverify" style="background-color: white;border: 0;box-shadow:none;" name="voiceverify" placeholder="请填写语音验证码"/>
                                </div>
                            </td>
                            <td width="35%" align="right" style="vertical-align: middle;"><button type="button" style="width:100%;background-color: white;color: red;border: 0;border-left: 1px solid gray;height: 1.8em;font-size: 15px;" id="get_verify">获取验证码</button></td>
                        </tr>
                    </table>
                    <button name="submit" class="btn btn-danger btn-lg center-block">下一步</button>
                </form>
            </div>
        </div>
    </body>
    <script>
        jQuery.validator.addMethod("isPhone", function (value, element) {
            var mobile = /^(13[0-9]|15[0-9]|18[0-9])\d{8}$/;
            return this.optional(element) || (mobile.test(value));
        }, "手机格式错误，请重新填写");
        $(".confirm").validate({
            //debug:true,
            rules: {
                mobile: {
                    required: true,
                    isPhone: true
                },
                voiceverify: {
                    required: true,
                    minlength: 4,
                    maxlength: 6
                }
            },
            messages: {
                mobile: {
                    required: "请输入手机"
                },
                voiceverify: "请填写验证码"
            },
            submitHandler: function (form) {
                $.ajax({
                    type: "POST",
                    url: "checkValida1.php",
                    data: $(form).serialize(),
                    success: function (data) {
                        if (data == 1) {
                            location.href='editpwd.php';
                        } else if (data == 2)
                            msgError("手机格式错误");
                        else if (data == 3)
                            msgError("请先获取验证码");
                        else if (data == 4)
                            msgError("验证码错误");
                        else if (data == 5)
                            msgError("验证码过期,请重新刷新");
                    }
                });
            }
        });

        function countdown() {
            if (down > 0) {
                var int = setInterval(function () {
                    $("#get_verify").html("重新发送(" + down + ")");
                    $("#get_verify").attr("disabled", "disabled");
                    if (down > 0) {
                        down--;
                    } else {
                        $("#get_verify").html("获取验证码");
                        $("#get_verify").removeAttr("disabled");
                        clearInterval(int);
                    }
                }, 1000);
            }
        }

        $("#get_verify").click(function () {
            var mobile = $("input[name='mobile']").val();
            if(mobile==''){
                msgError("请填写手机号码");
                return;
            }
            $.ajax({
                type: "POST",
                url: "../msmverify.php",
                data: {mobile: mobile},
                success: function (data) {
                    if (data == 0) {
                        msgError("手机号码错误");
                    } else if (data == 1) {
                        msgSuccess("发送成功");
                        down = 60;
                        countdown();
                    } else if (data == 2) {
                        msgError("发送失败,请稍后再试");
                    } else if (data == 3) {
                        msgError("60秒内不能重复发送");
                    }
                }
            });
        });
    </script>
</html>