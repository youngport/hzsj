<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
session_start();
$cxtPath = "http://".$_SERVER['HTTP_HOST'];
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$openid = $_SESSION["openid"];
$do = new condbwei();
$com = new commonFun();
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="telephone=no" name="format-detection" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
    <title>报名入口</title>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>
        .top{background:#78DADB;padding:2rem 5rem 0 5rem;}
        .bot{padding:0rem 5rem;}
        .bot span{font-size:3px;}
        .bot button{background:#F88D71;color:#fff;font-size:2.5rem;}
        .info{width:100%;color:#717576;font-family:"heiti";margin-bottom:3rem;font-size:1.4rem;}
        .title{color:#267071;font-family:"方正兰亭超细黑简体"}
        .input-group-addon{background:#fff;border-top:0;}
        .form-control{border-left:0;border-top:0;}
    </style>
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
                title: '洋仆淘--沙龙太原站开启',
                desc: '洋仆淘邀您一起畅谈风口上的跨境电商.', // 分享描述
                link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|salon&connect_redirect=1#wechat_redirect',
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
</head>
<body style="background:#F3EABF">
<div class="container" id="container" style="padding:0;">
    <div class="top text-center">
        <table class="info">
            <tr>
                <td colspan="2" class="title"><h1>洋仆淘·太原站</h1></td>
            </tr>
            <tr>
                <td width="18%">时间:</td>
                <td width="82%" align="left">2016年2月25日下午2点到4点</td>
            </tr>
            <tr>
                <td>地点:</td>
                <td align="left">山西太原小店区平阳路璐瑶轩茶馆</td>
            </tr>
        </table>
        <div class="input-group input-group-lg">
            <span class="input-group-addon" style="border-radius:0;">姓名</span>
            <input type="text" name="name" class="form-control" aria-describedby="sizing-addon1"  style="border-radius:0;">
        </div>
    </div>
    <div class="bot text-center">
        <div class="input-group input-group-lg">
            <span class="input-group-addon"  style="border-radius:0;">手机</span>
            <input type="text" name="mobile" class="form-control" aria-describedby="sizing-addon2"  style="border-radius:0;">
        </div>
        <h6 style="color:#777">我们会及时和您电话确认</h6>
        <button class="btn btn-block" name="submit">提&nbsp;&nbsp;交</button>
    </div>
</div>
</body>
<script>
    var parh='';
    $("button[name='submit']").click(function(){
        var name=$("input[name='name']").val();
        var mobile=$("input[name='mobile']").val();
        parh=/^[\w\u4e00-\u9fa5]{2,8}$/;
        if(name!=''){
            if(parh.test(name)===false){
                alert("姓名不能超长或包含特殊字符");
                return;
            }
        }else{
            alert("请填写姓名");
            return;
        }
        if(mobile!=''){
            parh= /^(13[0-9]|15[0-9]|18[0-9])\d{8}$/;
            if(parh.test(mobile)===false){
                alert("手机格式错误");
                return;
            }
        }else{
            alert("请填写手机");
            return;
        }
        $.ajax({
            type:"POST",
            url:"salon_post.php",
            data:{name:name,mobile:mobile},
            success:function(data){
                if(data==0){
                    alert("申请失败,请稍后再试");
                }else if(data==1){
                    alert("申请成功,点击确定后将自动关闭");
                    WeixinJSBridge.call('closeWindow');
                }else if(data==3){
                    alert("姓名不能为空");
                }else if(data==4){
                    alert("姓名不能超长或包含特殊字符,请重新填写");
                }else if(data==5){
                    alert("手机格式错误,请重新填写");
                }else if(data==6){
                    alert("手机不能为空");
                }
            }
        });
    });
</script>
</html>