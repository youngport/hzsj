<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}
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
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<title>意见建议反馈</title>
	<style>
	.flss_ssk{ width:95%;margin:10px auto; color:#666; font-size:12px;}
	.flss_ssk textarea{ width:97%;margin: auto; height:180px; border:1px solid #ebebeb;background:#fff; color:#555; line-height:25px; font-size:12px;}
	.tijiao{ width:60%;margin:1em auto; display:block; height:35px; border:1px solid #ebebeb; background:#ff7182; color:#fff; line-height:35px; font-size:14px; text-align:center;}
	.yzm{ margin:0 0 5px 0; height:30px; line-height:30px; color:#555;}
	.yzm span{ font-size:14px; }
	.yzm input{ width:50px; height:20px;border:1px solid #ccc;}
	.yzm .img{ display:inline-block; margin-top:5px}

	.fk_leix{ font-size:16px; color:#555; height:35px; line-height:35px; border-bottom:1px solid #ccc; padding-left:10px;}
	.fk_leix_x{ width:100%; padding:0;margin:0;}
	.fk_leix_x li{list-style:none; font-size:14px; float:left; text-align:center; width:25%; margin:10px 1% 0 4%; height:25px; line-height:25px; color:#999; border:1px solid #999;
		border-radius:8px;
		-moz-border-radius:8px;
		-webkit-border-radius:8px;
		-o-border-radius:8px;
		-ms-border-radius:8px;
	}
	.text{ height:30px; background:#fff; border:0; color:#555; width:100%;}
    input[type="button"], input[type="submit"], input[type="reset"] {
        -webkit-appearance: none;
    }
	</style>
</head>
<body style="margin:0px;padding:0px;background:#EBEBEB">   
<div class="fk_leix">反馈类型</div>
<ul class="fk_leix_x" id="fk_leix_x">
	<li data-a="1" style="background:#fe7182;border-color:#fe7182;color:#fff;">软件问题</li>
	<li data-a="0">物流问题</li>
	<li data-a="0">商品问题</li>
	<li data-a="0">退换货问题</li>
	<li data-a="0">其它</li>
</ul>
<div style="clear:both;height:0;"></div>
<div class="fk_leix" style="border-top:1px solid #ccc; margin-top:10px;">反馈内容</div>
<form method="post" id="searchProductForm" onsubmit="return fank()">
	<div class="flss_ssk">
     	<textarea id="text_f" placeholder="请输入内容" ></textarea><br />
     	(请输入500字以内)
<div class="fk_leix" style="border-top:1px solid #ccc; margin-top:10px;">联系方式</div>
<div><input placeholder="请输入手机号或邮箱（选填）" class="text" name="productName" id="productName" type="search"></div>
	<div class="yzm"><span>验证码：</span><input name="yzm" type="text" id="yzm" ><span class="img"><img src="yzm.php" onclick="this.src='yzm.php?'+Math.random();"></img></span></div>
	</div>
	<input class="tijiao" type="submit" name="Submit" value="提交"/>
</form>
<script>
function fank(){
	var liz = $('#fk_leix_x li');
	var leix = "";
	for(var i=0;i<liz.length;i++){
		if($(liz[i]).attr('data-a')==1)
			leix = $(liz[i]).html();
	}
	if($('#text_f').val()!="" && $('#text_f').val()!=null){
		$.ajax({
			type: "POST",
			url: "fankuiget.php",
			data: {text_f:$('#text_f').val(),yzm:$('#yzm').val(),lei:leix,lxfs:$('#productName').val()},
			success: function(data){
				if(data==1){
					alert('已提交！');
					window.open("user.php");
				}
				else if(data==5){
					alert('验证码错误！');
				}
				else{
					alert('提交失败！');
				}
			}
		});
	}else
		alert('内容不能为空！');
	return false;
}
$('#fk_leix_x li').click(function(){
	$('#fk_leix_x li').attr('data-a',0);
	$('#fk_leix_x li').css('background','#ebebeb');
	$('#fk_leix_x li').css('border-color','#999');
	$('#fk_leix_x li').css('color','#888');
	$(this).attr('data-a',1);
	$(this).css('background','#fe7182');
	$(this).css('border-color','#fe7182');
	$(this).css('color','#fff');
})
</script>
</body>
</html>