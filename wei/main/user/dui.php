<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$do = new condbwei();
$com = new commonFun();
$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
$openid = $_SESSION["openid"];
$pwd=$do->getone("select usrpwd from sk_member where open_id='$openid'");
if($pwd['usrpwd']==''){
    $_SESSION['confirm']=1;
    header("location:../members/confirm.php?edit=1");
    exit;
}
$userArr = $com->getUserDet($openid, $do);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="telephone=no" name="format-detection" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <title>提现</title>
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="//cdn.bootcss.com/jquery-validate/1.14.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
    <script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
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
        .money{border:0;-webkit-box-shadow:inset 0 0px 0px rgba(0,0,0,0);padding-top:11px}
        .zf{display:none}
        .yc{display:none;background:#000;z-index:1000;width:100%;height:100%;position:absolute;top:0;-moz-opacity: 0.7; opacity:.70; filter: alpha(opacity=70);}
        .get{display:none;background:#fff;z-index:1001;position:absolute;top:20%;left:5%;width:90%;padding:0.6em;}
        .get span{position:absolute;top:0.6em;left:0.7em;font-size:1.2em}
        .get h4{border-bottom:1px solid #ededed;margin:0;padding:0.7em;}
        .get h6{border-bottom:1px solid #ededed;margin:0;padding:0.7em;color:#F35E5E}
    </style>
</head>
<body style="background:#F2F2F2">
<div class="container-fluid" style="padding:0;">
    <div class="container" style="background:#fff;padding:0;">
        <table class="table text-center" style="margin:0;">
            <tr  style="color:#5D5D5D">
                <td colspan="2" width="50%"><h4>银行卡&nbsp;&nbsp;<input type="radio" name="txd" value="0" data-type="yh" checked/></h4></td>
                <td colspan="2" width="50%"><h4>支付宝&nbsp;&nbsp;<input type="radio" name="txd" value="1" data-type="zf"/></h4></td>
            </tr>
            <tr style="color:#858585">
                <td width="30%"  style="vertical-align:middle;">
                    <div class="yh"><h5>银行卡</h5><h5>户&nbsp;&nbsp;&nbsp;主</h5></div>
                    <div class="zf"><h5>支付宝帐号</h5></div>
                </td>
                <td colspan="2" class="text-left" width="50%"  style="vertical-align:middle;">
                    <div class="yh">
                            <?php if($userArr[0]["yin_code"]!=''){?>
                            <h5><?php echo $userArr[0]["yin_code"];?></h5><h5><?php echo $userArr[0]["yin_username"];?></h5>
                            <?php }else{?>
                            <h5>信息还未填写</h5>
                            <?php }?>
                    </div>
                    <div class="zf">
                        <h5><?php echo $userArr[0]["pay_code"]!=''?$userArr[0]["pay_code"]:'信息还未填写';?></h5>
                    </div>
                </td>
                <td width="10%" style="vertical-align:middle;">
                    <a href="userinf.php"><img src="../../img/d.png" class="img-responsive pull-right" style="margin-right:5px;width:50%"/></a>
                </td>
            </tr>
            <tr style="border-top:0.8em solid #F2F2F2">
                <td>
                    <h4>金额</h4>
                </td>
                <td colspan="3">
                    <input type="text" name="txje" class="form-control money"  placeholder="最多能提现<?php echo $userArr[0]['shouyi']?>"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="container text-center">
        <h5 class="text-left">&nbsp;&nbsp;&nbsp;&nbsp;3天内到账</h5>
        <button class="btn btn-danger confirm btn-lg" style="width:75%;margin-top:0.8em;">确&nbsp;&nbsp;定</button>
    </div>
</div>
<div class="yc">&nbsp;</div>
<div class="text-center get">
    <span class="close"><a href="javascript:void(0);" style="color:#959595">X</a></span>
    <h4>输入密码</h4>
    <input type="password" class="form-control password" style="margin:0.6em 0px;" placeholder="请输入提现密码"/>
    <h6 class="text-right"><a href="../members/checkValidaCode.php">忘记密码?</a></h6>
    <button class="btn btn-danger submit" style="width:60%;margin-top:0.8em">完成</button>
</div>
</body>
<script>
    var shouyi=parseFloat("<?php echo $userArr[0]['shouyi']?>");
    $("input[name='txd']").click(function(){
        $("."+$(this).attr("data-type")).show().siblings().hide();
    });
    $(".close").click(function(){
        $(".yc").hide();
        $(".get").hide();
    });
    $(".confirm").click(function(){
        var txd=$("input[name='txd']:checked").val();
        var txje=parseFloat($(".money").val());
        if(txd>1) {
            msgError("请选择提现方式");
            return;
        }
        var tx=$(".money").val();
        if(tx==''|| tx==null){
            msgError("请输入提现金额！");
            return;
        }
        if(txje<=0||txje>shouyi){
            msgError("您的提现金额大于可提现收益！");
            return;
        }
        $(".yc").show();
        $(".get").show();
    });
    $(".submit").click(function(){
        var txd=$("input[name='txd']:checked").val();
        var txje=parseFloat($(".money").val());
        var password=$(".password").val();
        if(txd>1) {
            msgError("请选择提现方式");
            return;
        }
        var tx=$(".money").val();
        if(tx==''|| tx==null){
            msgError("请输入提现金额！");
            return;
        }
        if(txje<=0||txje>shouyi){
            msgError("您的提现金额大于可提现收益！");
            return;
        }
        if(password==''){
            msgError("密码不能为空");
            return;
        }
        $.ajax({
            type: "POST",
            url: "duisave.php",
            data: {txd:txd,txje:txje,password:password},
            success: function(data){
                data = eval("("+data+")");
                if(data.success == "1"){
                    msgSuccessUrl("申请提现成功", "duiorder.php");
                }else if(data.success == "2"){
                    msgError("首次提现少于10元，申请失败");
                }else if(data.success == "3"){
                    msgError("提现不足50元，申请失败");
                }else if(data.success == "4"){
                    msgError("您的提现金额大于可提现收益！");
                }else if(data.success == "5"){
                    msgError("密码错误");
                }else if(data.success == "0"){
                    msgError("您暂无可提现收益");
                }
            }
        });
    });
</script>
</html>