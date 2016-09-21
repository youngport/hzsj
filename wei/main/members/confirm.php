<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
session_start();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];


if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}

if(!isset($_SESSION['confirm'])) {
    echo "<script>window.history.back(-1);</script>";
    exit;
}
$openid = $_SESSION["openid"];

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$result=$do->getone("select phone_tel from sk_member where open_id='$openid'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="telephone=no" name="format-detection" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>设置密码</title>
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
            appId: '<?php echo $shareCheckArr["appid"];?>',
            timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
            nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
            signature: '<?php echo $shareCheckArr["signature"];?>',
            jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
        });
        wx.ready(function(){
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
        .mob{border:0;background:#F2F2F2 url(../../img/mobile.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
        .ver{border:0;background:#F2F2F2 url(../../img/ver.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
        .suo{border:0;background:#F2F2F2 url(../../img/suo.png) no-repeat scroll right;background-size:60%;padding-left:2em;}
    </style>
</head>
<body style="background:#F2F2F2">
<div class="container-fluid" style="padding:0;">
    <div class="container-fluid" style="background:#fff">
        <h4 class="text-center" style="color:#6E6E6E">亲,给您的小金库加上<img src="../../img/suo.png" style="width:7%"/>呗</h4>
        <form class="confirm">
            <table class="table">
                <tr>
                    <td colspan="2">
                        <div class="input-group" style="width:100%">
                            <span class="input-group-addon mob" id="basic-addon1"></span>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="请填写手机号码" value="<?php echo $result['phone_tel'];?>"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="65%">
                        <div class="input-group" style="width:100%">
                            <span class="input-group-addon ver" id="basic-addon1"></span>
                            <input type="text" class="form-control" id="voiceverify" name="voiceverify" placeholder="请输入验证码"/>
                        </div>
                    </td>
                    <td width="35%" align="right"><button type="button" class="btn btn-danger" style="width:100%" id="get_verify">短信验证码</button></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="input-group" style="width:100%">
                            <span class="input-group-addon suo" id="basic-addon1"></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="请设置密码"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="input-group" style="width:100%">
                            <span class="input-group-addon suo" id="basic-addon1"></span>
                            <input type="password" class="form-control" id="repassword" name="repassword" placeholder="请确认密码"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><button name="submit" class="btn btn-danger btn-lg center-block">确认</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="conainer-fluid" style="padding:1.5em 1em">
        <p class="text-center" style="color:#939393">注意：为了确保您的收益安全，请如实填写手机号和收益转出帐号，每次修改收益转出帐号，都必须验证密码。</p>
    </div>
</div>
</body>
<script>
    $("td").css("border",0);
    jQuery.validator.addMethod("isPhone", function(value,element) {
        var mobile = /^(13[0-9]|15[0-9]|18[0-9])\d{8}$/;
        return this.optional(element) || (mobile.test(value));
        }, "手机格式错误，请重新填写");
    jQuery.validator.addMethod("isPassword", function(value,element) {
        var password =/^(\w){6,12}$/;
    return this.optional(element) || (password.test(value));
    }, "密码格式错误，只允许包含字母、数字、下划线 6到12位");
    $(".confirm").validate({
        //debug:true,
        rules:{
            mobile:{
                required:true,
                isPhone:true
            },
            voiceverify:{
                required:true,
                minlength:6,
                maxlength:6
            },
            password:{
                required: true,
                isPassword:true
            },
            repassword:{
                required: true,
                equalTo:"#password"
            }
        },
        messages:{
            mobile:{
                required:"请输入手机"
            },
            voiceverify:"请填写验证码",
            password:{
                required:"请输入密码"
            },
            repassword:{
                required: "请输入确认密码",
                equalTo:"两次输入密码不一致"
            }
        },
        submitHandler:function(form){
            $.ajax({
                type:"POST",
                url:"confirmget.php",
                data:$(form).serialize(),
                success:function(data){
                    console.log(data);
                    if(data==1) {
                        alert("设置完成");
                        if("<?php echo $_GET['edit'];?>"==1)
                            history.back(-1);
                        else
                            location.href ="../../index.php";
                    }else if(data==2)
                        msgError("手机格式错误");
                    else if(data==3)
                        msgError("请先获取验证码");
                    else if(data==4)
                        msgError("验证码错误");
                    else if(data==5)
                        msgError("验证码过期,请重新刷新");
                    else if(data==6)
                        msgError("密码格式错误");
                    else if(data==7)
                        msgError("两次输入密码不一致");
                }
            });
        }
    });


    var down="<?php
        if(isset($_SESSION['check']['voiceverify'])){
            $time=time()-$_SESSION['check']['voiceverify']['time'];
            if($time>60){
                echo 0;
            }else{
                echo 60-$time;
            }
        }else{
            echo 0;
        }
        ?>";

    countdown();

    function countdown(){
        if(down>0){
            var int=setInterval(function(){
                $("#get_verify").html("重新发送("+down+")");
                $("#get_verify").attr("disabled","disabled");
                if(down>0){
                    down--;
                }else{
                    $("#get_verify").html("获取验证码");
                    $("#get_verify").removeAttr("disabled");
                    clearInterval(int);
                }
            },1000);
        }
    }

    $("#get_verify").click(function(){
        var mobile=$("input[name='mobile']").val();
        $.ajax({
            type:"POST",
            //url:"../verify.php",
            url:"../msmverify.php",
            data:{mobile:mobile},
            success:function(data){
                if(data==0){
                    msgError("手机号码错误");
                }else if(data==1){
                    msgSuccess("发送成功");
                    down=60;
                    countdown();
                }else if(data==2){
                    msgError("发送失败,请稍后再试");
                }else if(data==3){
                    msgError("60秒内不能重复发送");
                }
            }
        });
    });
</script>
</html>