<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$openid = $_SESSION["openid"];


$do = new condbwei();
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

$jrhd_fenxiang = $do -> selectsql("select * from sk_jrhdlfx where hd_youxiao = 1 limit 0,1");
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
	<title>免税活动</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/css.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/img/swiper.min.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>
<script type="text/javascript">
$(function(){
	weixin(0);
	var hjjk=document.getElementById("hd2");
	var tli=hjjk.getElementsByTagName("li");
	var oli=hjjk.getElementsByClassName('img1');
	var oli3=hjjk.getElementsByClassName('img2');
	var bod=document.getElementById("bd2");
	var kk=bod.getElementsByTagName("div");

	    
		  var i=0;
		  
		 for(i=0;i<tli.length;i++)
		 {   tli[i].index=i;
		   
		//oli3[i].style.display="none";
		oli3[0].style.display="block";
		oli[0].style.display="none";
			 tli[i].onclick=function(){
				 for(j=0;j<tli.length;j++)
				 {
					 tli[j].className="" ;
					 kk[j].style.display="none";
				     oli[j].style.display="none";
					 oli3[j].style.display="block";
					 } 
			kk[this.index].style.display="block";
			 this.className="activ2";
			  //oli[i].style.display="block";
			 //oli3[i].style.display="block";
			
	}
		}
		var get_n="<?php echo isset($_GET['n'])?intval($_GET['n']):0;?>";
		if(get_n==0) {
			huod();
			$('#sangoux').css({'margin-top':'-10000px'});
		}else if(get_n==1)
			tuang();
		else if(get_n==2)
			sanggou();

	$('#jthd').click(function(){
		jthd();
		weixin(0);
	});
	$('#tuang').click(function(){
		tuang();
		weixin(1);
	});
	$('#sangou').click(function(){
		sanggou();
		weixin(2);
	});


	function jthd(){
		$('body').css({background:'#fff'});
		$("#jthd").addClass("activ2").siblings().removeClass("activ2");
		$("#huodong").show();
		$("#tuangx").hide();
		$('#sangoux').css({'margin-top':'-10000px'});
		huod();
	}
	function tuang(){
		$('body').css({background:'#fff'});
		$("#tuang").addClass("activ2").siblings().removeClass("activ2");
		$("#tuangx").show();
		$('#sangoux').css({'margin-top':'-10000px'});
		$("#huodong").hide();
		tuangou();
	}
	function sanggou(){
		$('body').css({background:'#ffc107'});
		$("#sangou").addClass("activ2").siblings().removeClass("activ2");
		$("#sangoux").show();
		$("#tuangx").hide();
		$("#huodong").hide();
		$('#sangoux').css({'margin-top':'0px'});
	}

	//sg();
});
function weixin(n){
	wx.config({
		appId: '<?php echo $shareCheckArr["appid"];?>',
		timestamp: '<?php echo $shareCheckArr["timestamp"];?>',
		nonceStr: '<?php echo $shareCheckArr["noncestr"];?>',
		signature: '<?php echo $shareCheckArr["signature"];?>',
		jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','hideMenuItems'] // 功能列表，我们要使用JS-SDK的什么功能
	});
	wx.ready(function(){
		var shareData = {
			//title: '<?php echo $jrhd_fenxiang[0]['biaoti']?>',
			//desc: '<?php echo $jrhd_fenxiang[0]['neirong']?>', // 分享描述
			title:"洋仆淘闪购活动开启了，超低价格hold不住！",
			desc:"品牌奶粉、爆款马油，可莱丝面膜疯狂来袭，抄底价哦！！",
			link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=<?php echo $openid; ?>|ms|'+n+'&connect_redirect=1#wechat_redirect',
			imgUrl: 'http://<?php echo $_SERVER['HTTP_HOST']."/wei/images/logo.png" ?>',
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
}
function huodongye(d){
	window.location.href="huodongq.php?d="+d;
}
function huod(){
	$.ajax({
		type: "POST",
		url: "msqget.php",
		data: {san:1},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			$("#huodong").empty();
			if(data.length>0){
				appStr = "<div class='card card-list'><div class=\"col3\">";
				for(var i=0;i<data.length;i++){
					appStr += "<li onclick=\"huodongye("+data[i].huodong_id+");\">";
					appStr += "<img src=\"<?php echo $cxtPath ?>"+data[i].hd_img+"\" class=\"imgtt1\"/>";
					appStr += "<a></a></li>";
					
				}
				appStr += "</div><div style='clear:both;'></div></div>";
				$("#huodong").append(appStr);
			}
		}
	});	
}
function tuangou(){
	$.ajax({
		type: "POST",
		url: "msget.php",
		data: {san:2},
		success: function(data){
			var data = eval("("+data+")");
			var appStr = "";
			$("#tuangx").empty();
			if(data.length>0){
				appStr = "";
				for(var i=0;i<data.length;i++){
					appStr += "<div class=\"tuandange\"><a href=\"tuangou_xq.php?kd="+data[i].id+"\"><img src=\"<?php echo $cxtPath ?>/"+data[i].view+"\" class=\"imgtt\"/></a>";
					appStr += "<div class=\"tuan_name\">"+data[i].good_name+"</div><div class=\"tuan_jj\">"+data[i].remark+"</div>";
					appStr += "<div class=\"rentuan clearfix\" style=\"margin:10px auto;padding:0;width:96%; padding-bottom:12%;\">";
					//appStr += "<div><img src=\"<?php echo $cxtPath ?>/wei/css/img/ertw.jpg\" style=\"width:100%;top:0;left:0;right:0; position: absolute;\"/>";
					appStr += "<div class=\"leftbt left\"><span>￥"+Number(data[i].orgprice)+"</span><img src=\"<?php echo $cxtPath ?>/wei/css/img/we554.jpg\" class=\"imghf\"/><span style=\"margin-top:7%\">运费</span></div>";
					appStr += "<div class=\"rightbt right\">";
					appStr += "<p style=\"font-size:22px;\">￥"+Number(data[i].price)+"</p>";
					appStr += "<span>再省"+(data[i].orgprice - data[i].price)+"元</span>";
					appStr += "<span>包邮</span>";
					appStr += "</div>";
					appStr += "</div><div style=\"clear:both;\"></div></div>";
				}
				$("#tuangx").append(appStr);
			}
		}
	});	
}
var shu = 0;
function sg(){
	$.ajax({
		type:"POST",
		url:"shangouget.php",
		data:{},
		success:function(data){
			if(data==2){
				$("#shanga").empty();
				appStr = "<div style='color:#fff;font-size:1em;width:50%;margin:1em auto;'>暂无闪购商品</div>";
				$("#sangoujz").append(appStr);
				return;
			}
			if('<?php echo $openid?>'== 'oc4cpuKadXviOYYPCRwrrQ2Xvfds')
				 alert(data);
			var data = eval("("+data+")");
			shu = data[0].shu - 1;
			SS = 29 - data[0].yu - 1;//闪购倒计时
			data = data[0].shop;
			var appStr = "";
			$("#shanga").empty();
			if(data.length>0){
				appStr = "";
				for(var i=0;i<data.length;i++){
					appStr += "<div class=\"swiper-slide\">";
					appStr += "<div class=\"swiperbox clearfix\" style=\"position:relative;\"><div class=\"dingwei\">";
					appStr += "<div class=\"hold hold1\">";
					appStr += "<div class=\"pie pie1\"></div>";
					appStr += "</div>";
					appStr += "<div class=\"hold hold2\">";
					appStr += " <div class=\"pie pie2\"></div>";
					appStr += "</div>";
					appStr += "<div class=\"bg\"> </div>";
					appStr += "<div class=\"time\"><span style=\"line-height:40px;\"></span><em></em></div></div>";
					appStr += "<div class=\"img clearfix\"><img src=\"<?php echo $cxtPath ?>/"+data[i].img+"\" width=50% /></div>";
					appStr += " <div class=\"buttons clearfix\"><span>￥"+data[i].shangoujia+"<font>市场价："+data[i].orgprice+"</font></span>";
					appStr += "<a class='qg_anniu' data-sg=\""+data[i].id+"\" ";
					if(parseInt(data[i].sg_int)<=0)
						   appStr += "data-ku=0";
						//appStr += "<a class='qg_anniu' onclick=\"shangou('"+data[i].id+"')\">立即闪购</a>";
					else
					   appStr += "data-ku=1";
					appStr += " >立即闪购</a>";
					appStr += "</div><p>"+data[i].good_name+"</p></div>";
					appStr += "</div>";
				}
				sshu = data.length;
				$("#sangoujz").append(appStr);
				jiazaisangou();
				daojishi = $(".dingwei");//获取倒计时布局元素
				qg_anniu = $('.qg_anniu');//获取按钮对象元素

				for(var u=0;u<qg_anniu.length;u++){
					if(qg_anniu.eq(u).attr('data-ku')==0){
						qg_anniu.eq(u).html('已被抢完');
					   $('#lijizhifu').hide();
					}
				}
				chushihua_daojishi();
			}
		}
	})
}

//if('<?php echo $openid;?>' == 'oc4cpuKadXviOYYPCRwrrQ2Xvfds'||'<?php echo $openid;?>' == '')


var t = 0;
var top_yd = 0;
var qg_j = 1;
var time = null;
var sshu = 0;

function goumai_liji(th){
	$.ajax({
		type: "POST",
		url: "shangouAddOrder.php",
		data: {goodsid:th,sshu:shu},
		success: function(data){
			//$('#xxqq').css({display:'block'});
			//$('#xxqq').html("第几个："+shu+"    多少秒："+SS+"      "+data);

			data = eval("("+data+")");
			if(data.success == "1"){
				var urljs="../order/order.php?orderid="+data.orderid;
				window.location.href = urljs;
			}else if(data.success == "0"){
				qg_anniu.unbind('click');
			    qg_anniu.eq(shu).attr('data-ku',0);
			    qg_anniu.eq(shu).css({'background-color':'#ccc'});//替换按钮背景色
			    qg_anniu.eq(shu).html('已被抢完');//替换按钮背景色
				//th.attr('data-w',0);
				//th.css({"background":"#8f9090"});
				//var ls = th.find('span').html();
				//th.html('已被抢完<span></span>');
				//th.find('span').html(ls);
			}
		}
	});
}
</script>
<style>
.tuan_name{ font-size:16px;color:#555;line-height:20px;width:98%;margin:5px auto;}
.tuan_jj{ font-size:12px;color:#888;line-height:20px;width:98%;margin:5px auto;}
.tuandange{ border:1px solid #f4f4f4;width:98%;margin:5px auto 10px auto;}
.tuandange img{ width:96%;margin:5px auto;display:block;}
#sangoux{ position:relative;}
#shanga{ position:absolute;top:0px;}
.dingwei span{linke-height:40px;margin-left:-28px;}
.swiper-slide{background:none;}
#hd2{margin:0 auto;padding-top:15px;}
.box span{float:none;}
.img1{display:none;}
.img2{display:block;}
.qg_anniu{background:url(<?php echo $cxtPath ?>/wei/css/img/ssd.png) 9px 12px no-repeat #fe7182;color:#fff;padding-left:12px;border-radius:5px;height:40px;line-height:40px;width:90px;text-align:center;display:inline-block;float:right;margin:3% 3% 0 0;text-decoration:none;}
</style>
</head>
<body style="margin:0px;padding:0px;background:#fff">
<div class="bigbox clearfix">
<div class="jifenqq clearfix">
<div style="background:#fff;height:55px;">
<div id="hd2">
<ul><li class="activ2" id="jthd">今<a><img src="<?php echo $cxtPath ?>/wei/css/img/shangssg_03.jpg" width=20 class="img1"/><img src="<?php echo $cxtPath ?>/wei/css/img/uuuuu.jpg" width=20 class="img2" /></a>推荐</li><li id="tuang">今<a><img src="<?php echo $cxtPath ?>/wei/css/img/douhou_03.jpg" width=20 class="img1"/><img src="<?php echo $cxtPath ?>/wei/css/img/wwww.jpg" width=20 class="img2"/></a>团购</li><li id="sangou">今<a><img src="<?php echo $cxtPath ?>/wei/css/img/yyyy.jpg" width=13 class="img1"/><img src="<?php echo $cxtPath ?>/wei/css/img/douhou_05.jpg"width=13 class="img2"/></a>闪购</li></ul>
</div>
</div>
<div id="bd2">
	<div class="box clearfix" style="display:block;border:solid 1px #f3f3f2;border-right:none;border-left:none;padding-top:10px;">
		<ul class="boyuu clearfix" id="huodong">
			<!--<li>
			<img src="<?php echo $cxtPath ?>/wei/css/img/shangou_03.jpg" class="imgtt"/>
			<img src="<?php echo $cxtPath ?>/wei/css/img/ert.jpg" class="imgtt1"/>
			<a></a></li>
			<li>
			<img src="<?php echo $cxtPath ?>/wei/css/img/shangou_03.jpg" class="imgtt"/>
			<img src="<?php echo $cxtPath ?>/wei/css/img/ert.jpg" class="imgtt1"/>
			<a></a></li>
			<li>
			<img src="<?php echo $cxtPath ?>/wei/css/img/shangou_03.jpg" class="imgtt"/>
			<img src="<?php echo $cxtPath ?>/wei/css/img/ert.jpg" class="imgtt1"/>
			<a></a></li>
			<li>
			<img src="<?php echo $cxtPath ?>/wei/css/img/shangou_03.jpg" class="imgtt"/>
			<img src="<?php echo $cxtPath ?>/wei/css/img/ert.jpg" class="imgtt1"/>
			<a></a></li>-->
		</ul>
	</div>

<div class="box clearfix" style="border:solid 1px #f3f3f2;border-right:none;border-left:none;padding-top:10px;" id="tuangx">

</div>
<!-- <div class="box clearfix" id="sangoux" style="position:relative;background:#ffc107;height:100%;">
<ul class="bohu clearfix" id="shanga">
<li>
<img src="<?php echo $cxtPath ?>/wei/css/img/shangssg_06.jpg" class="imgyy"/>
<img src="<?php echo $cxtPath ?>/wei/css/img/douhou_09.jpg" class="pioo"/>
<div class="rigjhtyy right">
<p>跌宕睫毛增长修复液，25ml一瓶，可以有效帮助整张睫毛</p>
<span>59.5<font>129</font></span>
<h4><a>已被我老公抢到</a></h4>

</div>
</li>
<li>
<img src="<?php echo $cxtPath ?>/wei/css/img/shangssg_06.jpg" class="imgyy"/>
<img src="<?php echo $cxtPath ?>/wei/css/img/douhou_12.jpg" class="pioo"/>
<div class="rigjhtyy right">
<p>跌宕睫毛增长修复液，25ml一瓶，可以有效帮助整张睫毛</p>
<span>59.5<font>129</font></span>
<h4 style="background:url(<?php echo $cxtPath ?>/wei/css/img/shangssg_18.jpg) left center no-repeat;background-size:16.6%;padding-left:26px;"><a style="background:#fe7182;color:#fff;">迅速抢购</a></h4>


</div>
</li>
<li>
<img src="<?php echo $cxtPath ?>/wei/css/img/shangssg_06.jpg" class="imgyy"/>
<img src="<?php echo $cxtPath ?>/wei/css/img/douhou_09.jpg" class="pioo"/>
<div class="rigjhtyy right">
<p>跌宕睫毛增长修复液，25ml一瓶，可以有效帮助整张睫毛</p>
<span>59.5<font>129</font></span>
<h4 style="background:url(<?php echo $cxtPath ?>/wei/css/img/shangssg_22.jpg) left center no-repeat;background-size:17.6%;padding-left:28px;"><a style="background:#8f9090;color:#fff;margin-top:2px;">下一个30秒开抢</a></h4>

</div>
</li>

</ul>

</div> -->


</div>

</div>


</div>
<div class="swiper-container" style="padding-top:30px;margin-top:-5000px;" id="sangoux" >
        <div class="swiper-wrapper" id="sangoujz">
            <!-- <div class="swiper-slide">
            <div class="swiperbox clearfix">
            <div class="img clearfix"><img src="<?php echo $cxtPath ?>/wei/css/img/bagkimg.jpg" width=40/></div>
            <div class="buttons clearfix"><span>￥699<font>市场价：1080</font></span><button>已被三只小猪抢到</button></div>
            <p>兰蔻 (Lancome)新精华肌底液 50ml，Genifique美颜活肤液配方</p></div>
            </div>
           <div class="swiper-slide">
            <div class="swiperbox clearfix" style="position:relative;"><div class="dingwei">
<div class="hold hold1">
  <div class="pie pie1"></div>
</div>
<div class="hold hold2">
  <div class="pie pie2"></div>
</div>
<div class="bg"> </div>
<div class="time"><span style="line-height:40px;"></span><em></em></div></div>
            <div class="img clearfix"><img src="<?php echo $cxtPath ?>/wei/css/img/bagkimg.jpg" width=40/></div>
            <div class="buttons clearfix"><span>￥699<font>市场价：1080</font></span><a  style="background:url(<?php echo $cxtPath ?>/wei/css/img/ssd.png) 9px 12px no-repeat #fe7182;color:#fff;padding-left:12px;
            border-radius:5px;height:40px;line-height:40px;width:90px;text-align:center;display:inline-block;float:right;margin:3% 3% 0 0;text-decoration:none;" onclick="shangou()">立即闪购</a></div>
            <p>兰蔻 (Lancome)新精华肌底液 50ml，Genifique美颜活肤液配方</p></div>
            </div>
           <div class="swiper-slide">
            <div class="swiperbox clearfix" style="position:relative;"><div class="dingwei">
<div class="hold hold1">
  <div class="pie pie1"></div>
</div>
<div class="hold hold2">
  <div class="pie pie2"></div>
</div>
<div class="bg"> </div>
<div class="time"><span style="line-height:40px;"></span><em></em></div></div>
            <div class="img clearfix"><img src="<?php echo $cxtPath ?>/wei/css/img/bagkimg.jpg" width=40/></div>
            <div class="buttons clearfix"><span>￥699<font>市场价：1080</font></span><button>即将开抢</button></div>
            <p>兰蔻 (Lancome)新精华肌底液 50ml，Genifique美颜活肤液配方</p></div>
            </div> -->
            <!--<div class="swiper-slide" style="background:#ccc7c7">
            <div class="guanbi clearfix">
            <a><img src="img/clse.jpg" width=20/></a>
            <p class="clearfix">30秒内抢先完成支付<br/>即可获得宝贝
            <font>手快有，手慢无~</font></p>
            <button>去支付</button>
            <img src="img/zhifu.jpg" class="imgh"/>
             </div>
            </div>-->
            
           
 </div>
<div class="swiper-pagination"></div>
<div class="swiper-slidess" style="background:#ccc7c7;padding:20% 0;margin-top:12%;display:none;position:fixed;top:0;left:0;margin:0 auto;z-index:999;width:100%;opacity:0.96;filter:alpha(opacity=96)" id="lijizhifu">
            <div class="guanbi clearfix">
            <a id="shangou"><img src="<?php echo $cxtPath ?>/wei/css/img/clse.jpg" width=20/></a>
            <p class="clearfix">30秒内抢先完成支付<br/>即可获得宝贝
            <font>手快有，手慢无~</font></p>
            <button class="zhifubt" style="margin:0 auto;" id="quzhifu">去支付</button>
            <img src="<?php echo $cxtPath ?>/wei/css/img/zhifu.jpg" class="imgh"/>
             </div>
            </div>
</div>

<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/css/img/swiper.min.js"></script>
 <script>
 var swiper;    
 var dj_i = 0;//左边扇形
 var dj_j = 0;//右边边扇形
 var count = 0;
 var SS = 29;//倒计时
function jiazaisangou(){
    swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 30000,
        autoplayDisableOnInteraction: true});
	    var s = setInterval("showTime()",50);// 0.05秒执行一次
     /*  if(SS >= 15 ){
    	dj_i =  180/15 * SS;
    }else{
    	dj_i = 180;
    	dj_j =  180/15 * SS;//貌似倒掉过来了
    }   */
	swiper.slideTo(shu, 400, false);//切换到指定的一个slide，速度为1秒
}

function shangou(sgid){			
	//goumai_liji(sgid);
}
$('#shangou').click(function(){
	$('#lijizhifu').hide();
})

    
    




var hmc = 0;
function showTime(){// 0.05秒执行一次
	hmc ++;
	if(hmc==20){
		hmc = 0;
		count++;
		SS--;
		if(SS == 0 ){
			SS = 30;
			$(".time span").html( SS );//倒计时读秒
			dj_j = 0;
			dj_i = 12;
			sz(dj_j);
			sy(dj_i);
			if(shu < sshu - 1)
			    shu ++;
			else
				shu = 0;
			swiper.slideTo(shu, 400, false);//切换到指定的一个slide，
			chushihua_daojishi();
		}
		$(".time span").html( SS );//倒计时读秒
	}
	if(SS < 15 && SS > -1){
		dj_j = dj_j + 0.6; 
		sz(dj_j);
	}else if( SS > -1){
		dj_i = dj_i + 0.6;
		sy(dj_i);
	}
};
function sy(ivar){
	$(".pie1").css("-o-transform","rotate(" + ivar + "deg)");
	$(".pie1").css("-moz-transform","rotate(" + ivar + "deg)");
	$(".pie1").css("-webkit-transform","rotate(" + ivar + "deg)");	
}
function sz(ivar){
	sy(180);
	$(".pie2").css("-o-transform","rotate(" + ivar + "deg)");
	$(".pie2").css("-moz-transform","rotate(" + ivar + "deg)");
	$(".pie2").css("-webkit-transform","rotate(" + ivar + "deg)");
}
var daojishi;//倒计时对象组
var qg_anniu;//按钮对象组
var sg_godid = "";
function chushihua_daojishi(){
	daojishi.css({'display':'none'});//隐藏倒计时
	daojishi.eq(shu).css({display:'block'});
	qg_anniu.css({'background-color':'#ccc'});//替换按钮背景色
	
	qg_anniu.unbind('click');
	if(qg_anniu.eq(shu).attr('data-ku')==1){
    	qg_anniu.eq(shu).css({'background-color':'#fe7182'});
    	qg_anniu.eq(shu).click(function(){
    		sg_godid = $(this).attr('data-sg');
    	    $('#lijizhifu').show();
    	});
	}
}
$('#quzhifu').click(function(){
	goumai_liji(sg_godid);
})
sg();
</script>
			<!-- <div id="xxqq" style="position:fixed; top:50px; left:0; display:none;z-index:99999;"></div> -->
<?php include '../gony/guanzhu.php';?>
</body>
</html>