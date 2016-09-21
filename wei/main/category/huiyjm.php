<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$cartnum = $_SESSION["cartnum"];
$categoryid = intval($_GET["categoryid"]);
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>会员加盟</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
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
	$('#fenlei_lidai').click(function(){
		$(this).css({
			'color' : '#555',
			'background' : '#fff',
		})
		$('#fenlei_lidian').css({
			'color' : '#fff',
			'background' : '#ccc',
		})
		$('#fenlei_dianjia').hide()
		$('#fenlei_daili').show();
	})
	$('#fenlei_lidian').click(function(){
		$(this).css({
			'color' : '#555',
			'background' : '#fff',
		})
		$('#fenlei_lidai').css({
			'color' : '#fff',
			'background' : '#ccc',
		})
		$('#fenlei_daili').hide()
		$('#fenlei_dianjia').show();
	})
	$('#fenlei_dianjia').hide()
});
</script>
<style>
body{ background:#FFF;}
.fenlei_dd li{ width:50%; text-align:center; padding:15px 0; background:#ccc; color:#fff; float:left; font-size:14px;}
.fenlei_danfloat{ width:50%; float:left; margin:15px 0 0 0;}
.fenlei_imgk{ width:98%; margin:0 auto;}
.fenlei_imgk img{ max-width:100%; margin:0 auto;}
.fenlei_name{ width:95%; text-align:center; line-height:18px; font-size:12px; color:#555; height:34px;}
</style>
</head>
<body style="background:#FFFFFF;">
<div id="viewport" class="viewport">
	<ul class="fenlei_dd"><li style="color:#555; background:#fff;" id="fenlei_lidai">会员到加盟</li><li id="fenlei_lidian">店家加盟</li></ul>
    <div id="fenlei_daili">
    <div style="background:#FFFFFF;">
	<?php
    $sql = "select id,good_name,img,price from sk_goods where goodtype='1'";
	$goodsArr = $do->selectsql($sql);
    $sql = "select count(*) shu from sk_member where jointag in(1,2)";
	$huiyuansu = $do->selectsql($sql);
	$huiyuansu1 = $huiyuansu[0]['shu']+1;
	$er = "";
	if(count($goodsArr)>0){
		for($j=0;$j<count($goodsArr);$j++){
			//$er .= "<div class=\"fenlei_danfloat\"><div class=\"fenlei_imgk\"><a href='$cxtPath/wei/main/goods/goods.php?id=".$goodsArr[$j]["id"]."'><img data-original=\"".$imgurl.$goodsArr[$j]["img"]."\" src=\"".$imgurl.$goodsArr[$j]["img"]."\"/></a></div><div class=\"fenlei_name\"><a>".$goodsArr[$j]["good_name"]."</a></div></div>";
			$er .= "<div style=\"height:5px;clear:both;\"></div><div style=\" width:99%; margin:10px auto;\"><img src=\"".$imgurl.$goodsArr[$j]["img"]."\" style=\" max-width:100%;\"/></div>";
			$er .="<div style=\" width:90%; margin:10px auto;text-align:center; font-size:12px;\">已有会员 ".$huiyuansu[0]['shu']." 位，您即将成为第 ".$huiyuansu1." 位会员</div>";
			$er .= "<a href='$cxtPath/wei/main/goods/goods_hy.php?id=".$goodsArr[$j]["id"]."'><div style=\" width:120px; text-align: center; margin:10px auto; background:#fe7182; height:30px; font-size: 14px;color:#fff; line-height:30px; \">".$goodsArr[$j]["good_name"]."</div></a><div style=\"height:20px;\"></div>";
		}
	}
	echo $er;
    ?>    	
    <div style=" float:none;clear:both;"></div>
    </div>
    </div>
    <div id="fenlei_dianjia">
    <div style="background:#FFFFFF;">
	<?php
    $sql = "select id,good_name,img,price from sk_goods where goodtype='2'";
	$goodsArr = $do->selectsql($sql);
	$er = "";
	if(count($goodsArr)>0){
		for($j=0;$j<count($goodsArr);$j++){
			$er .= "<div class=\"fenlei_danfloat\"><div class=\"fenlei_imgk\"><a href='$cxtPath/wei/main/goods/goods.php?id=".$goodsArr[$j]["id"]."'><img data-original=\"".$imgurl.$goodsArr[$j]["img"]."\" src=\"".$imgurl.$goodsArr[$j]["img"]."\"/></a></div><div class=\"fenlei_name\"><a>".$goodsArr[$j]["good_name"]."</a></div></div>";
		}
	}
	echo $er;
    ?> 
    <div style=" float:none;clear:both;"></div>
    </div>
    </div>
    <div style="height:80px;"></div>
    <!--<div style=" width:90%; margin:10px auto;"><img src="http://wx.qlogo.cn/mmopen/2TFYC1kjdlN8vEgibbZUCU2fZYtwsfyT2uhTJTWyL4kJKPUr5buscYariaicpRZ43kuic7NZriajgRZM6QxEQIdt8gdr3QtNtk0GH/0" style=" max-width:100%;"/></div>
    <div style=" width:90%; margin:10px auto;text-align:center; font-size:12px;">已有会员456位，您即将成为第457位会员</div>
    <div style=" width:120px; text-align: center; margin:10px auto; background:#fe7182; height:30px; font-size: 14px;color:#fff; line-height:30px; font-family:'微软雅黑'">立即加入</div>
	 <div class="card card-list">
		<div class="col3">
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
		</div>
    	<div style="clear:both;"></div>
    </div>-->
</div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>