<?php 
include '../base/condbwei.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST']."/";

$do = new condbwei();
$sql = "select vi_url from sk_video where vi_youxiao=1 order by vi_paixu asc limit 0,1";
$resultArr = $do->selectsql($sql);
if(count($resultArr)<1){
    echo "<script>window.location.href='./index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>轮换</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="stylesheet" href="images/css/style.css" />

<script type='text/javascript' src='images/js/modernizr.min.js?ver=2.6.1'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var CSSettings = {"pluginPath":"images"};
/* ]]> */
</script>
<script type='text/javascript' src='images/js/cute.slider.js?ver=2.0.0'></script>
<script type='text/javascript' src='images/js/cute.transitions.all.js?ver=2.0.0'></script>
</head>
<body onclick="	CKobject.getObjectById('ckplayer_a1').playOrPause();">
<div id="a1"></div>
<script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="ckplayer/ckplayer.js" charset="utf-8"></script>
<script type="text/javascript">
	var flashvars={
		p:0,
		e:1,
		hl:'<?php echo $resultArr[0]['vi_url'];?>',
		ht:'20',
		hr:''
		};
	var video=['<?php echo $resultArr[0]['vi_url'];?>->video/mp4','http://www.ckplayer.com/webm/0.webm->video/webm','http://www.ckplayer.com/webm/0.ogv->video/ogg'];
	var support=['all'];
	CKobject.embedHTML5('a1','ckplayer_a1','100%','',video,flashvars,support);
	window.onload=function(){
		CKobject.getObjectById('ckplayer_a1').playOrPause();
	}

	/* public void onPageFinished(WebView view, String url) {
		super.onPageFinished(view, url);
		//......
		view.loadUrl("javascript：onPageFinished();"); 
	} */
	/* $(document.body).one("touchstart",function(){ 
		// play it! 
	}); */
	function onPageFinished() {
    	//在這裡調用video.play播放便可以了
    	var video01 = document.getElementById("ckplayer_a1");
    	video01.play();
	}

    /* function playerstop(){
		//只有当调用视频播放器时设置e=0或4时会有效果
		alert(0);
		window.demo.clickOnAndroid();
	} */
</script>
</body>
</html>
