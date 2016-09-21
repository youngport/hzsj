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
$ajaxdata = "";
$ssk = "";
if($_POST["productName"]){//搜索框搜索
	$productName = addslashes($_POST["productName"]);
	$ajaxdata = "productName:'".$productName."'";
}
elseif($_GET["quid"]){//不采用搜索框搜索
	$quid = intval($_GET["quid"]);
	$ajaxdata = "quid:'".$quid."'";
	$ssk .= "quid:'".$quid."'";
}elseif($_GET['hots']){
	$hots = intval($_GET["hots"]);
	$ajaxdata = "hots:'".$hots."'";
	$ssk .= "hots:'".$hots."'";
}
if($_GET["pp"]){//品牌
	$pp=intval($_GET["pp"]);
	$ajaxdata .= ",mp:'".$pp."'";
	$ssk .= ",mp:'".$pp."'";
}
elseif($_GET["fl"]){//分类
	$fl=intval($_GET["fl"]);
	$ajaxdata .= ",fl:'".$fl."'";
	$ssk .= ",fl:'".$fl."'";
}
elseif($_GET["gx"]){//功效
	$gx=intval($_GET["gx"]);
	$ajaxdata .= ",gx:'".$gx."'";
	$ssk .= ",gx:'".$gx."'";
}
$openid = $_SESSION["openid"];
$subscribe = $_SESSION["subscribe"];
if($ssk!="")
	$ssk .= ",";

$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<?php
if($_GET['fl']==88){
	$title="进口奶源 两罐起订";
	$desc="选自国外优质奶源，为您挑选最接近母乳的奶粉";
	$img=$cxtPath."/data/goods/m_566ec45b36f4d.jpg";
}elseif($_GET['fl']==68){
	$title="婴儿用品 安全可靠";
	$desc="分享优越，呵护新生，宝宝的安全是我们的责任";
	$img=$cxtPath."/data/goods/m_566ec574ee0c8.jpg";
}elseif($_GET['fl']==67){
	$title="营养辅食 一个不少";
	$desc="营养均衡，超强吸收，为宝宝的未来打下坚实的基础";
	$img=$cxtPath."/data/goods/m_566ff48853f86.jpg";
}elseif($_GET['fl']==71){
	$title="尿不湿  干爽每一天";
	$desc="柔软亲肤，超强吸收，给宝宝最干爽的环境";
	$img=$cxtPath."/data/goods/m_566ff5a29f1a6.jpg";
}elseif($_GET['fl']==101){
	$title="国际护肤 年轻姿态";
	$desc="美，不只是说说而已，和众，至为您挑选最好的";
	$img=$cxtPath."/data/goods/m_56729ae20c249.jpg";
}elseif($_GET['fl']==102){
	$title="进口美妆 容姿焕发";
	$desc="精致生活 从选择爱惜自己开始，和众给你最完美的自己";
	$img=$cxtPath."/data/goods/m_56729b4cf08c9.jpg";
}elseif($_GET['fl']==145){
	$title="香水喷雾 清香怡人";
	$desc="纯净、自然、时尚、自由，随心喷洒，浪漫香氛";
	$img=$cxtPath."/data/goods/m_56727f2a96837.jpg";
}elseif($_GET['fl']==103){
	$title="护发洗浴 全身呵护";
	$desc="舒适生活，呵护自己，不放过身体的任何一个角落";
	$img=$cxtPath."/data/goods/m_56728b503f7a7.jpg";
}elseif($_GET['fl']==144){
	$title="功能保健 精力充沛";
	$desc="自然、无害、健康，功能保健，绝不松懈，健康无可替代";
	$img=$cxtPath."/data/goods/m_5673639b54a06.jpg";
}elseif($_GET['fl']==143){
	$title="营养保健 健康活力";
	$desc="营养元素，一个不少，让您年轻健康活力";
	$img=$cxtPath."/data/goods/m_5673660cf172e.jpg";
}elseif($_GET['fl']==137){
	$title="男性保健 舒缓压力";
	$desc="精力充沛 男性本色，不让压力打垮自己";
	$img=$cxtPath."/data/goods/m_567366ba123f7.jpg";
}elseif($_GET['fl']==136){
	$title="女性保健 年轻舒适";
	$desc="年轻姿态，健康心态，让一切从保健开始";
	$img=$cxtPath."/data/goods/m_567366d37c5f9.jpg";
}elseif($_GET['fl']==150){
	$title="饼干糕点 休闲必备";
	$desc="让你躺在加重也能尽享境外糕点，综合口味";
	$img=$cxtPath."/data/goods/m_567507ec4da32.jpg";
}elseif($_GET['fl']==148){
	$title="酒水饮品 极致格调";
	$desc="品味生活的真谛，享受低调奢华。红酒、饮品应有尽有";
	$img=$cxtPath."/data/goods/m_5675081b59732.jpg";
}elseif($_GET['fl']==162){
	$title="糖果蜜饯 甜在心间";
	$desc="海量糖果向你袭来，吃在嘴里，甜在心中";
	$img=$cxtPath."/data/goods/m_56750873b4080.jpg";
}elseif($_GET['fl']==149){
	$title="海量坚果 精挑细选";
	$desc="休闲必备，为您精挑细选，绿色安全，粒粒饱满";
	$img=$cxtPath."/data/goods/m_567508396421a.jpg";
}
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
	<title>搜索</title>
	<!-- <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" /> -->
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
        title: "<?php echo $title;?>",
        desc: "<?php echo $desc;?>", // 分享描述
		link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|cate|<?php echo $_GET['fl'];?>|<?php echo $_GET['quid'];?>&connect_redirect=1#wechat_redirect',
		imgUrl: "<?php echo $img;?>",
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
var limitsj = 0;//0降序  1升序
var gengduolei = 0 ;//更多加载搜索类型
var limittj = "";
var sqllimit = 0;
var lei_g = "";
var pa_g = "";

$(function(){
	$('#jiazai').click(function(){
		paix(lei_g,pa_g);
	})
})


$(function(){
	if(parseInt(cartnum)>0){
		$("#ShoppingCartNum").css("display", "block").text(cartnum);
	}
	$.ajax({
		type: "POST",
		url: "flss_xqget.php",
		data: {<?php echo $ajaxdata ?>,sqllimit:sqllimit},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			if(data.length>0){
				var ding = data.length;//循环多少次
				if(data.length>20){
					var ding = 20;
					sqllimit+=20;
					$('#jiazai').css({display:"block"});//初始化
				}else
					$('#jiazai').css({display:"none"});
				appStr = "<div class='card card-list'><div class=\"col3\">";
				for(var i=0;i < ding;i++) {
					appStr += "<div class=\"row1\">";
					if (data[i].cate_id == 88)
						appStr += "<img src=\"<?php echo $cxtPath ?>/wei/img/88_2.png\" class='twogo'/>";
					appStr+="<a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?ms="+data[i].canyuhd+"&cate="+data[i].cate_id+"&id="+data[i].id+"\"><span class=\"imgurl\"><img data-original=\"<?php echo $imgurl ?>"+data[i].img+"\" style=\"display: inline;\" src=\"<?php echo $imgurl ?>"+data[i].img+"\"><div class='xiantiao'></div></span><div class='jiage'>";
					if(data[i].canyuhd==0){
					      console.log("aa:"+data[i].cate_id);
                                               if(data[i].cate_id == 88){
                                                   appStr += "<span style='color:red'>两罐价:</span>￥<span>"+data[i].price; 
                                               }else{
						 appStr += "￥<span>"+data[i].price;
                                               }
					}else{
						appStr += "<font style='color:red'>活动价&nbsp;&nbsp;</font>￥<span>"+data[i].huodongjia;
					}
					appStr +="</span></div><span class=\"p\"><span>"+data[i].good_name+"</span></span></a></div>";
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
		$('#jiazai').css({display:"none"});

		//-----------------------------------------
		sqllimit = 0;
		$("#viewport").empty();
		gengduolei = 1;
		lei_g = "price";
		pa_g = $(this).attr('data-asc');
		//------------------------------------------
		$('.paix_ul li').find('img').attr('src','<?php echo $cxtPath ?>/wei/images/wzt.jpg')
		paix("price",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/shang.jpg')
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/xia.jpg')
		}
	});
	$('#renqi').click(function(){
		$('#jiazai').css({display:"none"});

		//-----------------------------------------
		sqllimit = 0;
		$("#viewport").empty();
		gengduolei = 1;
		lei_g = "hits";
		pa_g = $(this).attr('data-asc');
		//------------------------------------------
		$('.paix_ul li').find('img').attr('src','<?php echo $cxtPath ?>/wei/images/wzt.jpg')
		paix("hits",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/shang.jpg')
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/xia.jpg')
		}
	});
	$('#xiaoliang').click(function(){
		$('#jiazai').css({display:"none"});

		//-----------------------------------------
		sqllimit = 0;
		$("#viewport").empty();
		gengduolei = 1;
		lei_g = "xiaoliang";
		pa_g = $(this).attr('data-asc');
		//------------------------------------------
		$('.paix_ul li').find('img').attr('src','<?php echo $cxtPath ?>/wei/images/wzt.jpg')
		paix("xiaoliang",$(this).attr('data-asc'));
		if($(this).attr('data-asc')=="asc"){
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','desc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/shang.jpg')
		}else{
			$('.paix_ul li').attr('data-asc','desc');
			$(this).attr('data-asc','asc');
			$(this).find('img').attr('src','<?php echo $cxtPath ?>/wei/images/xia.jpg')
		}
	});
});
function paix(lei,pa){
	$.ajax({
		type: "POST",
		url: "flss_xqget.php",
		data: {<?php echo $ajaxdata ?>,leix:lei,paix:pa,sqllimit:sqllimit},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			//$("#viewport").empty();
			if(data.length>0){
				var ding = data.length;//循环多少次
				if(data.length>20){
					var ding = 20;
					sqllimit+=20;
					$('#jiazai').css({display:"block"});
				}else
					$('#jiazai').css({display:"none"});
				appStr = "<div class='card card-list'><div class=\"col3\">";
				for(var i=0;i<ding;i++){
					appStr += "<div class=\"row1\">";
					if (data[i].cate_id == 88)
						appStr += "<img src=\"<?php echo $cxtPath ?>/wei/img/88_2.png\" class='twogo'/>";
					appStr+="<a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?id="+data[i].id+"\"><span class=\"imgurl\"><img data-original=\"<?php echo $imgurl ?>"+data[i].img+"\" style=\"display: inline;\" src=\"<?php echo $imgurl ?>"+data[i].img+"\"><div class='xiantiao'></div></span><div class='jiage'>";
					if(data[i].canyuhd==0){
                                            console.log("aa:"+data[i].cate_id);
                                               if(data[i].cate_id == 88){
                                                   appStr += "<span style='color:red'>两罐价:</span>￥<span>"+data[i].price; 
                                               }else{
						 appStr += "￥<span>"+data[i].price;
                                               }
					}else{
						appStr += "<font style='color:red'>活动价&nbsp;&nbsp;</font>￥<span>"+data[i].huodongjia;
					}
					appStr +="</span></div><span class=\"p\"><span>"+data[i].good_name+"</span></span></a></div>";
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

	.top{position:fixed;left:0;top:0;z-index:100;width:100%;background:#fff}
	.paix_ul{ width:100%; height:2.5em; background:#fff; line-height:2.5em; text-align:center; font-size:1.6em; border-bottom:1px solid #b8b8b8;}
	.paix_ul li{ float:left; width:33%; height:2.5em;}
    .paix_ul li img{ width:1em;display:inline-block;margin-top:-0.2em;}
	#productName{width:100%;font-size:1.3em; border:1px solid #dadada;padding-left:1.3em;padding-right:0.3em;height:2.5em;margin: 10px 0px;background:url("<?php echo $imgurl ?>wei/img/index_fangda.png") no-repeat 0 49%;background-size:1.4em;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;-o-border-radius:4px;-ms-border-radius:4px;}


	.fanhuisouye{ font-size:14px; height:30px; line-height:30px; text-indent:10px; border:1px solid #fff;}
	.fanhuisouye a:link{ color:#666;}
	.fanhuisouye a:hover{ color:#666;}
	.fanhuisouye a:visited{ color:#666;}
	.flss_qu a:link{ color:#333; font-size:14px;}
	.flss_qu a:hover{ color:#333; font-size:14px;}
	.flss_qu a:visited{ color:#333; font-size:14px;}
	.flss_lei a:link{ color:#555; font-size:12px;}
	.flss_lei a:hover{ color:#555; font-size:12px;}
	.flss_lei a:visited{ color:#555; font-size:12px;}
	.flss_ssk{ width:90%; margin:10px auto; height:30px; border:1px solid #fff;}
	.flss_ssk .search_text{ width:100%; height:30px; background:#fff; color:#555; margin:0 auto;}	

	.jiazai{ width:96%; margin:1em auto 0 auto; height:35px; display:none; line-height:35px; background:#fe7182; text-align:center; color:#fff; font-size:14px;}
html{background:#f5f5f4;}
body{background:none;font-family:"黑体";}
.card .col3 .row1{border:none;width: 50%;}
.row1{position: relative}
.card-list .row1 a{padding-top:1em;}
.card-list{background:none;padding-top:6em;}
.jiage{ background:#fff;width:98%;margin:0 auto;height:auto;text-indent:0.3em;color:#000;font-size:1.7em;}
.jiage span{ margin-left:0.3em;color:#e84242;}
.card-list .row1 .imgurl{ background:#fff;width:98%;height:auto;padding:0;color:#000;}
.card-list .row1 .p{ width:98%;margin:0 auto;background:#fff;height:3.8em;}
.card-list .row1 .p span{ display:block;width:96%;margin:0.5em auto;}
.xiantiao{ border-bottom:1px solid #d9d9d9;width:92%;margin:0 auto;}

.twogo{position: absolute;left:0.3em;right:0;top:1em;bottom:0;width:32%}
</style>
</style>
</head>
<body>
<!-- <div class="fanhuisouye"><a href="./flss.php"><返回分类搜索</a></div>
<form method="post" id="searchProductForm" onsubmit="return sousk()">
<div class="flss_ssk">
     <input placeholder="请输入搜索词" class="search_text" name="productName" id="productName" type="search" value="<?php echo $_POST["productName"]; ?>">
</div>
</form> -->
<div class='top'>
	<div class='search'>
		<form action="flss_xq.php" method="post">
			<input type='text' id='productName' name='productName' placeholder="请输入搜索词"/>
		</form>
	</div>
	<ul class="paix_ul">
		<li id="xiaoliang" data-asc="asc" >销量<img src="<?php echo $cxtPath ?>/wei/images/xia.jpg"/></li>
		<li id="jiage" style="width:34%;" data-asc="desc" >价格<img src="<?php echo $cxtPath ?>/wei/images/wzt.jpg"/></li>
		<li id="renqi" data-asc="desc" >人气<img src="<?php echo $cxtPath ?>/wei/images/wzt.jpg"/></li>
	</ul>
</div>
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
<div class="jiazai" id="jiazai">加载更多</div>
<?php include '../gony/nav.php';?>

<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>
<div style="clear:both;height:55px;"></div>
</body>
</html>