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
	<title>客流统计</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css?v1.6" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/msgbox.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/msgbox/alertmsg.js"></script>
	<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script> <!-- 统计表 -->
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
	html,body{background:#fff;}
.top{ width:100%;font-size:1.5em;}	
.top tr{ height:3em;}	
.top tr span{ height:2.2em;width:90%;display:inline-block;line-height:2.2em;}	
a:link{text-decoration:none; color:#000;}
a:visited{text-decoration:none; color:#000;}
a:hover{text-decoration:none; color:#000;}
a:active{text-decoration:none; color:#000;}
a.web:visited{text-decoration:none; color:#000;}	
.tongji{ width:100%;font-size:1.5em;background:#f5f5f5;}	
.tongji tr{ height:6em;}	
.tongji tr td{ width:33%;}	
.tongji tr span{ font-size:2.2em;color:#579acb;display:block;}	
.tongji_yuan{width:100%; font-size:1.5em;}	
.tongji_yuan tr{height:7em;}	
.tongji_yuan tr td{width:50%;border:1px solid #f5f5f5;}	
.daoru_yuan{
    width: 100%;margin-bottom:0.3em;
}
</style>
</head>
<body style="margin:0px;padding:0px;">
<table class="top">
    <tr>
        <td align=center valign=middle><span style="border-bottom:0.2em solid #579acb;">昨日统计</span></td>
       <td width=34% align=center valign=middle> <a href="keliu_z.php"><span>本周统计</span></a></td>
       <td  align=center valign=middle> <a href="keliu_y.php"><span>本月统计</span></a></td>
    </tr>
</table>
<table class="tongji">
    <tr>
        <td align=center valign=middle>所有访客<span id="syfk">0</span></td>
        <td  align=center valign=middle>旧访客<span id="jfk">0</span></td>
        <td  align=center valign=middle>新访客<span id="xfk">0</span></td>
        <!-- <td  align=center valign=middle>会员访客<span>0</span></td> -->
    </tr>
	<tr>
		<td align=center valign=middle colspan="3">
			<div style="float:left;width:50%">进入商城新访客<span id="get_new">0</span></div>
			<div style="float:left;width:50%">进入商城旧访客<span id="get_old">0</span></div>
		</td>
	</tr>
</table>
<img style="margin:2em auto;display: block;" id="jiazaizhong" src="../../../data/wei_CssJsImg/img/jiazaizong.gif" />
<div id="container" style="width:95%;height:300px"></div>
<!-- <div class="daoru_yuan" style="width:95%;height:300px" id="daoru_yb"></div>
<div class="daoru_yuan"  style="width:95%;height:300px" id="xiaofei_yb"></div> -->
<div style="height:50px;"></div>
<script>
$(function () { 
	$.ajax({
		type: "POST",
		url: "keliu_get.php",
		data: {"xuan":0},
		success: function(data){
			$('#jiazaizhong').hide();
			var data = eval("("+data+")");
			if(data.mac!=0){
    			$('#xfk').html(data.sy_tj);
    			$('#jfk').html(data.jiu_fanke);
				$('#get_new').html(data.get_new);
				$('#get_old').html(data.get_old);
    			$('#syfk').html(parseInt(data.sy_tj,10)+parseInt(data.jiu_fanke,10));
    			//var total_fanke_tu = data.total_fanke_tu.split(",");
    			var jiu_fanke_tu = data.jiu_fanke_tu.split(",");//旧访客曲线图
    			var zx_tu = data.zx_tu.split(",");//新访客曲线图
    		    var total_fanke_tu = new Array(); //总访客曲线图
                for(var i=0;i<jiu_fanke_tu.length;i++){  
                	jiu_fanke_tu[i] = parseInt(jiu_fanke_tu[i],10);  
                	total_fanke_tu[i] = parseInt(jiu_fanke_tu[i],10);
                } 
                /* for(var i=0;i<total_fanke_tu.length;i++){  
                	total_fanke_tu[i] = parseInt(total_fanke_tu[i],10);  
                } */ 
                for(var i=0;i<zx_tu.length;i++){  
                	zx_tu[i] = parseInt(zx_tu[i],10);  
                	total_fanke_tu[i] += parseInt(zx_tu[i],10);
                } 
    			
    			$('#container').highcharts({
    		        title: {
    		            text: '访客统计',
    		            x: -20 //center
    		        },
    		        credits: {
    		            enabled:false
    		        },
    		        subtitle: {
    		            text: '',
    		            x: -20
    		        },
    		        xAxis: {
    		            categories: ['00:00-04:00', '04:00-08:00', '08:00-12:00', '12:00-16:00', '16:00-20:00', '20:00-24:00']
    		        },
    		        yAxis: {
    		            title: {
    		                text: '访问量 (次)'
    		            },
    		            plotLines: [{
    		                value: 0,
    		                width: 1,
    		                color: '#808080'
    		            }]
    		        },
    		        tooltip: {
    		            valueSuffix: '次'
    		        },
    		        plotOptions: {
    		            line: {
    		                dataLabels: {
    		                    enabled: true
    		                },
    		                enableMouseTracking: false
    		            }
    		        },
    		        series: [ {
    		            name: '所有访客',
    		            data: total_fanke_tu
    		        } , {
    		            name: '旧访客',
    		            data: jiu_fanke_tu
    		        }, {
    		            name: '新访客',
    		            data: zx_tu
    		        } ]
    		    });
			}else
				$('#container').html("暂无数据");
    		}
	});
	 

	    /* $('#daoru_yb').highcharts({
	        chart: {
	            plotBackgroundColor: null,
	            plotBorderWidth: null,
	            plotShadow: false
	        },
	        title: {
	            text: '导入商城访客占比',
                verticalAlign:'bottom',  
	        },
	        tooltip: {
	    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	        },
	        plotOptions: {
	            pie: {
	                //innerSize:'20%',
	                allowPointSelect: true,
	                cursor: 'pointer',
	                dataLabels: {
	                    enabled: true,
	                    color: '#000000',
	                    connectorColor: '#000000',
	                    distance: -50,
	                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
	                }
	            }
	        },
	        credits: {
	            enabled:false
	        },
	        series: [{
	            type: 'pie',
	            name: 'Browser share',
	            data: [
	            	{name:'导入商城访客',y:45,color:'#b3d7c6'},
	            	{name:'游客',y:55,color:'#fbe0c3'},
	            ]
	        }]
	    });
	    $('#xiaofei_yb').highcharts({
	        chart: {
	            plotBackgroundColor: null,
	            plotBorderWidth: null,
	            plotShadow: false
	        },
	        title: {
	            text: '商城消费访客占比',
                verticalAlign:'bottom',  
	        },
	        tooltip: {
	    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	        },
	        plotOptions: {
	            pie: {
	                //innerSize:'20%',
	                allowPointSelect: true,
	                cursor: 'pointer',
	                dataLabels: {
	                    enabled: true,
	                    color: '#000000',
	                    connectorColor: '#000000',
	                    distance: -50,
	                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
	                }
	            }
	        },
	        credits: {
	            enabled:false
	        },
	        series: [{
	            type: 'pie',
	            name: 'Browser share',
	            data: [
	            	{name:'商城消费访客',y:45,color:'#fbe0c3'},
	            	{name:'游客',y:55,color:'#f1eda2'},
	            ]
	        }]
	    }); */
});


</script>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
</body>
</html>