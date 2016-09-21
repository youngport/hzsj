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
$subscribe = $_SESSION["subscribe"];

$sql = "select id,totpop,audit_tag,submit,wy_zfb from wei_exchange where openid='$openid' order by submit desc";
$duiArr = $do->selectsql($sql);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>会员中心</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
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
var cartnum = "<?php echo $cartnum ?>";
$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
});

</script>
<style>
.shemek{ font-size:16px;float:left;}
.dsq{ font-size:16px;float:right;margin-right:15px;}
.zhuantai{ font-size:14px;float:right;color:#fe7182;margin-right:15px;}
.sshijian{ font-size:14px;float:left;color:#888;}
.lhg{height:25px;line-height:25px;}
.dantz{border:1px solid #c2c2c2;height:70px;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">

 <table width="100%" cellspacing="0">    
	<!-- <tr class="dantz">
		<td align="center" width="80px"><img width="50px" src="<?php echo $cxtPath ?>/wei/images/wy.png" /></td>
		<td align="center">
            <div class="lhg"><span class="shemek">兑换到银行卡中</span><span class="dsq">5.00</span></div>
            <div class="lhg"><span class="sshijian">2015-09-30 12:03:26</span><span class="zhuantai">兑换中</span></div>
		</td>
	</tr> -->
    <?php 
    if(count($duiArr) ==0){
        echo "<tr class=\"dantz\" style=\"border:none;\"><td align=\"center\" style=\"color:#888;font-size:18px\">暂无兑换记录</td></tr>";
    }
    for($i=0;$i<count($duiArr);$i++){
        $html_dg = "<tr class=\"dantz\">";
        $html_dg .= "<td align=\"center\" width=\"80px\"><img width=\"50px\" src=\"".$cxtPath."/wei/images/";
        if($duiArr[$i]["wy_zfb"]==0)
            $html_dg .= "wy.png";
        else if($duiArr[$i]["wy_zfb"]==1)
            $html_dg .= "zfb.png";
        $html_dg .= "\" /></td><td align=\"center\">";
        $html_dg .= "<div class=\"lhg\"><span class=\"shemek\">";
        if($duiArr[$i]["wy_zfb"]==0)
            $html_dg .= "兑换到银行卡中";
        else if($duiArr[$i]["wy_zfb"]==1)
            $html_dg .= "兑换到支付宝中";
        $html_dg .= "</span><span class=\"dsq\">".$duiArr[$i]["totpop"]."</span></div>";
        $html_dg .= "<div class=\"lhg\"><span class=\"sshijian\">".$duiArr[$i]["submit"]."</span><span class=\"zhuantai\">";
        if($duiArr[$i]["audit_tag"]==0)
            $html_dg .= "兑换中";
        elseif($duiArr[$i]["audit_tag"]==1)
            $html_dg .= "兑换成功";
        elseif($duiArr[$i]["audit_tag"]==2)
            $html_dg .= "兑换失败";
        $html_dg .= "</span></div></td></tr>";
        echo $html_dg;
    }
    ?>
</table>
	<?php
	/* for($i=0;$i<count($duiArr);$i++){
		echo "<div style='width:100%;height:2.8em;padding-left:1em;'>";
		echo "<div style='width:75%;float:left;'>";
		echo "<p>兑换佣金：".$duiArr[$i]["totpop"]."</p>";
		echo "<p>兑换时间：".$duiArr[$i]["submit"]."</p>";
		echo "</div>";
		$audit_tag = $duiArr[$i]["audit_tag"];
		if($audit_tag == "0"){
			echo "<div style='width:25%;float:left;padding-top:0.5em;'>兑换中</div>";
		}else if($audit_tag == "1"){
			echo "<div style='width:25%;float:left;padding-top:0.5em;color:green;'>兑换成功</div>";
		}else if($audit_tag == "2"){
			echo "<div style='width:25%;float:left;padding-top:0.5em;color:red;'>拒绝兑换</div>";
		}
		
		echo "</div>";
	} */
	?>
	<div style="height:50px;"></div>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
<?php include './main/gony/returntop.php';?>
<script>

</script>
</body>
</html>