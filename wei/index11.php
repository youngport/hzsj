<?php
header("Content-Type:text/html; charset=UTF-8");
include './base/condbwei.php';
include './base/commonFun.php';

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
		

if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
$openid = $_SESSION["openid"];

$subscribe = $_SESSION["subscribe"];

$do = new condbwei();
$com = new commonFun();
$menuArr = $com->mainMenu($do);
$goodsNum = $com->getTotalGoodsNum($do);

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);
?>
<!DOCTYPE html>
<html>
<head>
	<title>洋仆淘跨境商城</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css?v1.8"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.1"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/csjs/base.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/csjs/module.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/csjs/box.css">
    <style>
		body{font-family:'黑体';}	
    	.index_daohang{ height:35px; background:#999; margin:0; padding:0;}
		.index_daohang_z{ width:100%; display:none; background:#fff;}
		.index_daohang_z ul li { border-bottom:1px solid #ececec; padding:5px 0 5px 10px;}
		.index_daohang_z ul li span{ display:inline-block; padding:7px 5px; color:#555; font-size:12px;}
		.yijidaoh a:link{ color:#333;}
		.yijidaoh a:hover{ color:#333;}
		.yijidaoh a:visited{ color:#333;}
		.flss_ssk{ width:90%; margin:10px auto; height:30px; border:1px solid #ebebeb;}
		.flss_ssk .search_text{ width:100%; height:30px; background:#fff; color:#555; margin:0 auto;}
		.index_datu_div{width:33%;float:left;display:block;}
		.index_datu_div img{max-width:100%;margin:0 auto;display:block;}
		.index_xiaotu_ul{width:67%;float:left;}
		.index_xiaotu_ul li{list-style-type:none;width:49%;float:left;border-bottom:1px solid #ebebeb; border-left:1px solid #ebebeb;}
		.index_xiaotu_ul li img{max-width:100%;margin:0 auto;display:block;}
		body,html,div,img,ul,li{margin:0;padding:0;list-style:none;}


        .you{position:fixed;bottom:100px;right:0;}		
        .daoh_tubiao img{ width:16px;}
        .daoh_tubiao{ display: inline-block; float:right;width:24px;height:24px; margin-right:10px;}	

		.index_text { width:96%; font-size:1.3em; border:1px solid #dadada;padding-left:1.3em;padding-right:0.3em;height:2em;background:url("<?php echo $imgurl ?>wei/img/index_fangda.png") no-repeat 0 49%;background-size:1.4em;
                    border-radius:4px;
                    -moz-border-radius:4px;
                    -webkit-border-radius:4px;
                    -o-border-radius:4px;
                    -ms-border-radius:4px;
        }
		.index_table { width:100%; margin:0.5em 0;}

        .index_lei{ display: inline-block; width:1.8em;height:1.8em;}

		.swipe {float:left;overflow:hidden;text-align:center;width:90%;margin-left:5%;padding-bottom:0.6em;}
		.swipe ul{overflow:hidden;}
		.swipe p{margin:0;font-size:0.7em}
		.swipe a{color:#000;}
		.swipe li {border-right:1px solid #ededed;position:relative;float:left}
		.swipe li  img{width:100%;}
		.twogo{position: absolute;left:0;right:0;top:0;bottom:0;}
		.prev{position:absolute;top:35%;width:5%;}
		.next{position:absolute;top:35%;left:95%;width:5%;}
    </style>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?51d7fc420f430ff5508533110b192a3a";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/touchslider.js"></script>

</head>
<body>
<marquee style="width:100%;background:#6e9bce;color:#fff;padding:0.2em 0px;margin-bottom:-5px" scrollamount="5">国家政策要求：购买境外商品，必须填写身份证号码+姓名以便清关，请亲们正确填写！如有疑问请咨询客服！下单请填写清地址，保持电话通畅！
</marquee>
<div id="dingshen">
<?php include './main/gony/guanzhu.php';?>
<div id="viewport" class="viewport" style="margin-bottom:92px;">
	<div class="slider card card-nomb" style="width:100%;visibility: visible;">
		<!-- banner轮播Start -->
		<div id="focus" class="focus">
			<div class="hd">
				<ul></ul>
			</div>
			<div class="bd">
				<ul id="banner_li">
				
				</ul>
			</div>
		</div>
		<script>
		$.ajax({
			type : "POST",
			url : "./main/banner_get.php",
			data : "",
			success : function(data){
				var data = eval("("+data+")");
				var appStr = "";
				if(data.length>0){
					for(var i=0;i<data.length;i++){
						appStr += "<li><a href='"+data[i].links+"' target=\"_blank\"><img name=\"ad_img\" src='<?php echo $cxtPath ?>"+data[i].imgurl+"'/></a></li>";
					}
					$("#banner_li").append(appStr);

					    TouchSlide({
						slideCell:"#focus",
						titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
						mainCell:".bd ul",
						delayTime:600,
						interTime:4000,
						effect:"leftLoop",
						autoPlay:true,//自动播放
						autoPage:true, //自动分页
						switchLoad:"_src" //切换加载，真实图片路径为"_src"
					});
				}
			}
		});
		$(function(){
			$.ajax({
				type : "POST",
				url : "./main/goods/goods_index.php",
				data : "type=1",
				success : function(data){
					var data = eval("("+data+")");
					var appStr = '<div class="prev"><img src="css/img/prev.png"/></div><div class="swipe"><ul id="slider"><li style="display:block"><div></div></li>';
					$.each(data,function(k,v){
						appStr+="<li><div>";
						appStr+="<a href='main/goods/goods.php?id="+v['id']+"'>";
						if(v['cate_id']==88)
							appStr+='<img src="<?php echo $cxtPath;?>/wei/img/88_2.png" class="twogo" style="width:34%"/>';

						appStr+="<img src='../"+v['img']+"'/>";
						appStr+='<p style="margin-bottom:0.2em;margin-top:-1em">'+v['good_name']+'...</p>';
						appStr+='<p style="font-size=0.8em;">￥<strong style="color:#fa7183">'+v['price']+'</strong>元&nbsp;&nbsp;<font style="color:#9C9C9C;font-size:0.8em">市场价:<s>'+v['orgprice']+'元</s></font></p>';
						appStr+="</a></div></li>";
					});
					appStr+='</ul></div><div class="next"><img src="css/img/next.png"/></div><div style="clear:both"></div>';
					$(".index_hots").html(appStr);
					$("#slider li").css("width",$(window).width()*0.3);
					$(".swipe p").css("font-size",$(window).width()*0.03);
					var t1=new TouchSlider('slider',{duration:800, interval:3000, direction:0, autoplay:true, align:'left', mousewheel:false, mouse:true, fullsize:false});
					t1.pause();
					$("#slider li:eq(0)").remove();
					$("#slider li:last").css("border-right","0");
				}
			});$.ajax({
				type : "POST",
				url : "./main/goods/goods_index.php",
				data : "type=2",
				success : function(data){
					var data = eval("("+data+")");
					var appStr = '<ul style="text-align:center">';
					$.each(data,function(k,v){
						appStr+='<li style="padding-bottom:0.5em"><a href="main/ms/huodongq.php?d='+v['huodong_id']+'">';
						appStr+="<img src='."+v['hd_img']+"' style='width:95%'/></a></li>";
				});
					appStr+='</ul>';
					$(".index_tuijian").html(appStr);
				}
			});
		});
		</script>
		<div class="n_classify">
        <ul class="classify_index clearfix">
			<li>
                <a href="./main/category/flss_xq.php?quid=65&categoryname=进口母婴">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img src="<?php echo $cxtPath ?>/wei/img/muying.png"></span>
                    </div>
                    <p>母婴</p>
                </a>
	        </li>
			<li>
				<a href="./main/category/flss_xq.php?quid=100&categoryname=美妆个护">
					<div class="n_listImgs" style="background:#fff;">
						<span style="width:40px;"><img  src="<?php echo $cxtPath ?>/wei/img/meizhuang.png"></span>
					</div>
					<p>美妆</p>
				</a>
			</li>
			<li>
				<a href="./main/category/flss_xq.php?quid=118&categoryname=保健品">
					<div class="n_listImgs" style="background:#fff;">
						<span style="width:40px;"><img src="<?php echo $cxtPath ?>/wei/img/baojian.png"></span>
					</div>
					<p>保健</p>
				</a>
			</li>
            <li>
                <a href="./main/category/flss_xq.php?quid=147&categoryname=进口食品">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img  src="<?php echo $cxtPath ?>/wei/img/lingshi.png"></span>
                    </div>
                    <p>零食</p>
                </a>
            </li>
        	<li>
                <a href="./main/xianxia/xianxia.php">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img src="<?php echo $cxtPath ?>/wei/img/xianxiatiyan.png"></span>
                    </div>
                    <p>线下体验</p>
                </a>
	        </li>
            <li>
                <a href="./main/ms/msq.php">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img  src="<?php echo $cxtPath ?>/wei/img/muzhi.png"></span>
                    </div>
                    <p>免税活动</p>
                </a>
            </li>
            <li>
                <a href="./main/jifen/jifen.php?type=0">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img  src="<?php echo $cxtPath ?>/wei/img/jifenduihuan.png"></span>
                    </div>
                    <p>我的优惠</p>
                </a>
            </li>
            <li>
                <a href="./main/category/fenlei.php">
                    <div class="n_listImgs" style="background:#fff;">
                       	<span style="width:40px;"><img  src="<?php echo $cxtPath ?>/wei/img/joinus.png"></span>
                    </div>
                    <p>加盟我们</p>
                </a>
            </li>
        </ul>

    </div>
    
    <form action="./main/category/flss_xq.php" method="post" id="searchProductForm">
    <table class="index_table"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width=90%; align="center" valign="middle"><input placeholder="请输入搜索词" class="index_text" name="productName" id="productName" type="search"></td>
            <td width=10%; align="left" valign="middle"><a href="<?php echo $imgurl ?>wei/main/category/flss.php"><img class="index_lei"  src="<?php echo $imgurl ?>wei/img/index_lei.jpg" ></img></a></td>
        </tr>
    </table>
	</form>
    
		<!-- banner轮播End -->
	</div>
	<!--热销单品-->
	<div class='n_layout'>
		<div class='layout_top' style='border-color:#fe7182;'>
			<h3>热销单品</h3>
			<a href='main/category/flss_xq.php?hots=1&categoryname=热销单品'>更多</a>
		</div>
		<div class='index_hots' style='margin-top:0.3em;position:relative;text-align:center'>
			<img src="images/load.gif" style="width:40px;height:40px;"/>
		</div>
	</div>
	<div class='n_layout'>
		<div class='layout_top' style='border-color:#fe7182;'>
			<h3>今日推荐</h3>
		</div>
		<div class='index_tuijian' style='margin-top:0.3em;text-align:center'>
			<img src="images/load.gif" style="width:40px;height:40px;"/>
		</div>
	</div>
<!--今日推荐-->
<?php
//$a= "<div class='n_layout'><div class='layout_top' style='border-color:#fe7182;'><h3>今日推荐</h3></div>";
//$a .= "<div class=\"index_tuijian\" style='margin-top:0.3em'>";
//$huodong=$do->selectsql("select huodong_id,hd_img from sk_huodong where hd_youxiao =1 order by paixu asc");
//$a.="<ul style='text-align:center'>";
//foreach($huodong as $v){
//	$a.="<li style='padding-bottom:0.5em'><a href='main/ms/huodongq.php?d=".$v['huodong_id']."'><img src='.".$v['hd_img']."' style='width:95%'/></a></li>";
//}
//$a.="</ul>";
//$a.="</div></div>";
//echo $a;
?>
<?php 

$menuArr = $com->mainMenu($do);
//$json = $com->createBaseJson($menuArr, "id,name");

$b = "";
if(count($menuArr)>0){
	for($i=0;$i<count($menuArr);$i++){
		$a = "";
		$a .= "<div class='n_layout'><div class='layout_top' style='border-color:#fe7182;'><h3>".$menuArr[$i]['name']."</h3>";
		$a .= "<a href='main/category/flss_xq.php?quid=".$menuArr[$i]["id"]."&categoryname=".$menuArr[$i]["name"]."'>更多</a></div>";
		$a .= "<div class=\"index_waik\">";
		$shopStr = "";
		$sql = "select go.cate_id,go.good_name,go.img,go.img2,go.price,s.name 
from sk_goods go join sk_items_cate as s on go.cate_id = s.id 
where (go.cate_id in(select id from sk_items_cate where pid='".$menuArr[$i]["id"]."') 
or cate_id='".$menuArr[$i]["id"]."') and recom=1 and go.status=1 and goodtype='0' order by sort_order asc limit 0,5;";
		$goodsArr = $do->selectsql($sql);
		if(count($goodsArr)>0){
			for($j=0;$j<count($goodsArr);$j++){
				if($j==0){
					$shopStr .="<div class=\"index_datu_div\"><a href='main/category/flss_xq.php?quid=".$menuArr[$i]["id"]."&categoryname=".$menuArr[$i]["name"]."'><img src=\"".$imgurl.$goodsArr[$j]["img2"]."\"></a></div><ul class=\"index_xiaotu_ul\">";
				}elseif($j==1 || $j==2){
					$shopStr .="<li><a href=\"".$cxtPath."/wei/main/category/flss_xq.php?quid=".$menuArr[$i]["id"]."&fl=".$goodsArr[$j]["cate_id"]."&categoryname=".$goodsArr[$j]["name"]."\"><img src=\"".$imgurl.$goodsArr[$j]["img"]."\"></a></li>";
				}else{
					$shopStr .="<li style=\"border-bottom:0px;\"><a href=\"".$cxtPath."/wei/main/category/flss_xq.php?quid=".$menuArr[$i]["id"]."&fl=".$goodsArr[$j]["cate_id"]."&categoryname=".$goodsArr[$j]["name"]."\"><img src=\"".$imgurl.$goodsArr[$j]["img"]."\"></a></li>";
				}
			}
			$shopStr .="</ul>";
		}else{
			$shopStr .= "";
		}
		$b .= $a.$shopStr."<div style=\"clear:both\"></div></div></div>";
	}
	echo $b;
}
?>




</div>
<?php include './main/gony/nav.php';?>
</div>



    
<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>

<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/ong.js"></script>

<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/csjs/box.js"></script>



<script type="text/javascript">
wx.config({
	//debug:true,
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
		],success: function (res) {
// 			alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
		},fail: function (res) {
// 			alert(JSON.stringify(res));
		}
	});
});
window.onload=function(){
	var wid = Math.floor($('.index_xiaotu_ul').width()/2)-2;
	$('.index_xiaotu_ul li').width(wid+'px');
	$('.index_datu_div img').height($('.index_xiaotu_ul img').height()*2+1+'px');
}
//wx.error(function(res){
	//alert(res.errMdg);
//})
function WeiXinAddContact(wxid, cb)   
{   
    if (typeof WeixinJSBridge == 'undefined') return false; 
        WeixinJSBridge.invoke('addContact', {   
            webtype: '1',   
            username: wxid   
        }, function(d) {   
        	   alert(wxid);  
          	   alert(d.err_msg);  
          	   alert(d.err_desc);  
            // 返回d.err_msg取值，d还有一个属性是err_desc   
            // add_contact:cancel 用户取消   
            // add_contact:fail　关注失败   
            // add_contact:ok 关注成功   
            // add_contact:added 已经关注   
            WeixinJSBridge.log(d.err_msg);
            cb && cb(d.err_msg);
        });   
};
</script>

<?php include './main/gony/returntop.php';?>
<script src="http://kefu.qycn.com/vclient/state.php?webid=110105" language="javascript" type="text/javascript"></script>
</body>
</html>