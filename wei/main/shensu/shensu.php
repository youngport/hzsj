<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://" . $_SERVER['HTTP_HOST'];

session_start();
if (!isset($_SESSION["access_token"])) {
    header("Location:" . $cxtPath . "/wei/login.php");
    return;
}

$do = new condbwei();
$com = new commonFun();
$openid = $_SESSION["openid"];

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$imgurl = 'http://' . $_SERVER['HTTP_HOST'] . "/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
        <title>盟友申诉</title>
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
        <link rel="stylesheet" href="<?php echo $cxtPath ?>/wei/demo/control/css/zyUpload.css" type="text/css">
        <!--图片弹出层样式 必要样式-->	
        <!-- 引用核心层插件 -->
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/core/zyFile.js"></script>
        <!-- 引用控制层插件 -->
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/control/js/zyUpload.js"></script>
        <!-- 引用初始化JS -->
        <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/demo/demo.js"></script>
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
        <style type="text/css">
            body{font-family: '黑体';letter-spacing : 0.5px; }
            .mengceng{background: #eee;height: 10px;}
            .img_ico{margin-left: 100px;}
            .top{background: #000;width: 100%;color: #fff;font-size:1.5em;line-height:2em;color:#fff;width:100%;text-align: center;}
            .nav{background: #eee;width: 100%;color: #3d3d3d;font-size:1.5em;line-height:2em;color:#000;width:100%;text-indent: 1em;}
            .nav1{background: #fff;width: 100%;color: #3d3d3d;font-size:1.5em;line-height:3em;color:#000;width:100%;text-indent: 1em;}
            .nav3{background: #eee;width: 100%;color: #3d3d3d;font-size:1.5em;line-height:1.5em;color:#000;width:100%;text-indent: 1em;}
            .sub{ width:17em;height:2.3em;font-size:1.7em;color:#fff;text-align:center;background:#fe7182;line-height:2.5em;margin:0  auto;margin-top: 20px;
                  border-radius:4px;
                  -moz-border-radius:4px;
                  -webkit-border-radius:4px;
                  -o-border-radius:4px;
                  -ms-border-radius:4px;}
            input{width: 80%;height:20px;border: none;text-indent: 0.5em;}
            .agree{
                line-height: 2.5em; width: 100%; margin-left: 20px;font-size: 1.5em;
            }
        </style>
    </head>
    <body>
        <div class="mengceng"></div> 
        <a href="tel://400-888-3658">
            <div class="nav1" >客服热线<font style="color:#999;font-size:1.3em">&nbsp&nbsp&nbsp400-888-3658</font><img class="img_ico" src="./fankui.png" /></div>
        </a>
        <div class="nav">微信号</div>
        <div class="nav1"><input type="text" name="name" id="wei_id" placeholder="需要申诉的盟友的微信号"/>
        </div>
        <div class="nav" style="font-size:1.3em;text-indent: 1em;"><font style="color:#999">申诉前请确认您和您的盟友已经关注商城</font></div>
        <div class="nav3" >上传图片</div>
        <div id="demo" class="demo"></div>  
        <div class="nav3">联系方式</div>
        <div class="nav1"><input type="tel" name="number" id="number" placeholder="请留下您的联系方式，方便联系您" />
        </div>
        <div class="agree" ><input type="checkbox" name="flag" id="flag" value="111" style="width:18px;font-size: 2.5em;"  /> 同意 <a><<洋仆淘申诉协议>></a></div>
        <div class="sub" id="fabiao">提交</div> 
        <script>
            
            //发表ajax
            $('#fabiao').click(function () {
                var isAllow=$('#flag').prop('checked');
                if ($('#wei_id').val() == "")
                    alert('请输入微信号！');
                else if ($('#number').val() == "")
                    alert('联系方式不能为空！');
                else if (imgname == "")
                    alert('请上传图片！');
                else if(isAllow==false) {
                       alert("您必须同意申诉申请表");
                       return;
                }else {
                    $.ajax({
                        type: "POST",
                        url: "shensu_insert.php",
                        data: {"wei_id": $('#wei_id').val(), "number": $('#number').val().replace(/\r/gi, "<br\/><br\/>").replace(/\n/gi, "<br\/><br\/>"), "imgname": imgname},
                        success: function (data) {
                            if (data == 1) {
                                alert('已发表成功，等待审核');
                                /*location.href="lfx_index.php";*/
                            } else
                                alert('发表失败，请稍后重试');
                        }
                    });
                }
            });

        </script>

        <div style="clear:both;height:90px;"></div>
    </body>
</html>
