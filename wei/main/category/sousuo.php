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
$productName = addslashes($_POST["productName"]);
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
	<title>搜索“<?php echo $productName ?>”</title>
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
	$.ajax({
		type: "POST",
		url: "sousuoget.php",
		data: "productName=<?php echo $productName ?>",
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			if(data.length>0){
				appStr = "<div class='card card-list'><div class=\"col3\">";
				for(var i=0;i<data.length;i++){
					appStr += "<div class=\"row1\"><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+data[i].id+"\"><span class=\"imgurl\"><img data-original=\"<?php echo $imgurl ?>"+data[i].img+"\" style=\"display: inline;\" src=\"<?php echo $imgurl ?>"+data[i].img+"\"></span><span class=\"p\"><span>"+data[i].good_name+"</span></span></a></div>";
				}
				appStr += "</div><div style='clear:both;'></div></div>";
				$("#viewport").append(appStr);
			}
		}
	});
	$('#index_daohang').click(function(){
		$('.index_daohang_z').slideToggle();
	})
	$('#jiage').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$('#renqi').html('人气');
		$('#xiaoliang').html('销量');
		$(this).css("border-color","#900");
		paix("price",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).html('价格↑');
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).html('价格↓');
		}
	});
	$('#renqi').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$('#jiage').html('价格');
		$('#xiaoliang').html('销量');
		$(this).css("border-color","#900");
		paix("hits",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).html('人气↑');
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).html('人气↓');
		}
	});
	$('#xiaoliang').click(function(){
		$('.paix_ul li').css("border-color","#EBEBEB");
		$('#jiage').html('价格');
		$('#renqi').html('人气');
		$(this).css("border-color","#900");
		paix("xiaoliang",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).html('销量↑');
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).html('销量↓');
		}
	});
});
function paix(lei,pa){
	$.ajax({
		type: "POST",
		url: "sousuoget.php",
		data: {productName:"<?php echo $productName ?>",leix:lei,paix:pa},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			$("#viewport").empty();
			if(data.length>0){
				appStr = "<div class='card card-list'><div class=\"col3\">";
				for(var i=0;i<data.length;i++){
					appStr += "<div class=\"row1\"><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+data[i].id+"\"><span class=\"imgurl\"><img data-original=\"<?php echo $imgurl ?>"+data[i].img+"\" style=\"display: inline;\" src=\"<?php echo $imgurl ?>"+data[i].img+"\"></span><span class=\"p\"><span>"+data[i].good_name+"</span></span></a></div>";
				}
				appStr += "</div><div style='clear:both;'></div></div>";
				$("#viewport").append(appStr);
			}
		}
	});
};
</script>
<style>
	.gsbiaoti{ border-left:1px solid #cecece; display:inline-block; margin-left:10px; padding-left:10px;font-family:'微软雅黑'; font-size:16px;}
    .index_daohang{ height:35px; background:#999; margin:0; padding:0;}
	.index_daohang_z{ width:100%; display:none; background:#fff;}
	.index_daohang_z ul li { border-bottom:1px solid #ececec; padding:5px 0 5px 10px;}
	.index_daohang_z ul li span{ display:inline-block; padding:7px 5px; color:#555; font-size:12px;}
	
	.yijidaoh { font-size:16px;}
	.yijidaoh a:link{ color:#333; font-size:16px;}
	.yijidaoh a:hover{ color:#333; font-size:16px;}
	.yijidaoh a:visited{ color:#333; font-size:16px;}
	.daoh_tubiao{ display: inline-block; background:url("../../img/shouye.png") no-repeat;float:right;width:35px;height:24px;}

	.paix_ul{ width:99%; margin:0 auto; height:30px; line-height:30px; text-align:center; font-size:14px;}
	.paix_ul li{ float:left; width:33%; height:30px; border-bottom:2px solid #EBEBEB;}
</style>
</head>
<body style="background:#EBEBEB;">
<?php include '../gony/topnav.php';?>
<div class="index_daohang_z">
	<ul>
<?php
$menuArr = $com->mainMenu($do);
//$json = $com->createBaseJson($menuArr, "id,name");

$daohang = "";
if(count($menuArr)>0){
	for($i=0;$i<count($menuArr);$i++){
		$yi = "";
		$yi .= "<li class='yijidaoh'><a href='./categoryGoods.php?categoryid=".$menuArr[$i]["id"]."&categoryname=".$menuArr[$i]["name"]."'>".$menuArr[$i]['name']."</a></li>";
		$er = "<li>";
		$sql = "select id,name from sk_items_cate where pid='".$menuArr[$i]["id"]."'";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			for($j=0;$j<count($goodsArr);$j++){
				$er .="<a href='./categoryGoods.php?categoryid=".$goodsArr[$j]["id"]."&categoryname=".$goodsArr[$j]["name"]."'><span>".$goodsArr[$j]["name"]."</span></a>";
			}
		}else{
			$er .= "";
		}
		$daohang .= $yi.$er."</li>";
	}
	echo $daohang;
}
?>
    </ul>
</div>
<ul class="paix_ul">
	<li id="renqi" data-asc="asc" style="border-bottom:2px solid #900;" >人气↓</li>
	<li id="jiage" data-asc="desc" >价格</li>
	<li id="xiaoliang" data-asc="desc" >销量</li>
</ul>
<div style="clear:both;float:none;"></div>
<div id="viewport" class="viewport">
	<!-- <div class="card card-list">
		<div class="col3">
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
			<div class="row1"><a href="goods.php~id=335.html"><span class="imgurl"><img data-original="./../images/201504/thumb_img/335_thumb_G_1430072644378.jpg" style="display: inline;" src="images/168_P_1400777501815.jpg"></span><span class="p"><span>商品商品</span></span></a></div>
		</div>
    	<div style="clear:both;"></div>
    </div> -->
</div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
</body>
</html>