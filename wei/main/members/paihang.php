<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$imgurl =  'http://'.$_SERVER['HTTP_HOST']."/";
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
	<title>和众排行榜</title>
	<style>
	boty,html,div,ul,li{ margin:0;padding:0;}
	ul,li{ list-style:none;}
	.souweidiv{ width:100%; height:70px;}	
	.souweidiv .img{ width:70px; height:70px;}	
	.souweidiv .img img{ width:50px; height:50px;border-radius:25px;-moz-border-radius:25px;-webkit-border-radius:25px;-o-border-radius:25px;-ms-border-radius:25px;}	
	.souweidiv .pop{ font-size:16px; color:#555; line-height:50px;}	

	.jkl{ width:90%;margin:0 auto; padding:10px; background:#fff;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-o-border-radius:5px;-ms-border-radius:5px;}	


	.viewport{  border-collapse:collapse; width:100%; margin:0 auto; }	
	.viewport .biank{ margin-top:5px;}	
	.viewport .shuz{ width:5%;}	
	.viewport .img{ width:25%;}	
	.viewport .img img{ width:50px;height:50px;border-radius:25px;-moz-border-radius:25px;-webkit-border-radius:25px;-o-border-radius:25px;-ms-border-radius:25px;}	
	.viewport .jdt{ width:60%;}	
	.viewport .jdt .jis{ font-size:12px; color:#888;margin-right:10px;}	
	.viewport .jdt .waik{ width:100%; height:6px; border:1px solid #fe7182;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;-o-border-radius:3px;-ms-border-radius:3px;}	
	.viewport .jdt .neik{ height:6px; background:#fe7182; float:left; width:100%;}	
	.viewport .dz{ width:10%; font-size:12px; color:#888;}	
	.viewport .dz img{ width:20px;}	
	</style>
</head>
<body style="margin:0px;padding:0px;background:#fe7182">
<table class="souweidiv">
	<tr>
		<td class="img" align="right" ><img id="swimg" src="" /></td>
		<td class="pop" id="swname">占据了首位</td>
	</tr>
</table>
<div class="jkl">
	<div class="zaidw">
		<table id="viewport" class="viewport">
		</table>
	</div>
</div>
</body>
<script>
$.ajax({
	type: "POST",
	url: "paihangget.php",
	data: "",
	success: function(data){
		var data = eval("("+data+")");
		var appStr = "";
		var jdt = 0;
		var jdt_width = 0;
		$("#viewport").empty();
		if(data.length>0){
			for(var i=0;i<data.length;i++){
				if(i==0){
					$('#swimg').attr('src',data[i]['headimgurl']);
					$('#swname').html(data[i]['wei_nickname']+"占据了首位");
					appStr += "<tr class=\"biank\">";
					jdt = data[i]['pop'];
				}else
					appStr += "<tr class=\"biank\" style=\" border-top:1px solid #f4f4f4;\">";

				appStr += "<td class=\"shuz\">"+(i+1)+".</td><td class=\"img\"><img src=\""+data[i]['headimgurl']+"\"/></td>";
				appStr += "<td class=\"jdt\" align=\"right\">";
				appStr += "<div class=\"jis\">"+data[i]['pop']+"</div>";
				if(i!=0){
					appStr += "<div><div class=\"waik\"><div class=\"neik\" style=\"width:"+data[i]['pop']/jdt*jdt_width+"px\"></div></div>";

				}else{
					appStr += "<div><div class=\"waik\"><div class=\"neik\"></div></div>";
				}
					
				appStr += "</td><td class=\"dz\" align=\"center\"><div class=\"zanh\" data-zj="+data[i]['phzan']+" >"+data[i]['phzan']+"</div><img class=\"djiu\" data-yz="+data[i]['sfyz']+" data-zid=\""+data[i]['openid']+"\" src=\"<?php echo $imgurl; ?>wei/img/paihdianzan.png\"/></td></tr>";
				$("#viewport").append(appStr);
				appStr = "";
				jdt_width = $('.waik').width();
			}
			$('.djiu').click(function(){
				$(this).animate({         //连续动画
				       'margin-top':5
			     },70).animate({         //连续动画
				       'margin-top':0
			     },70).animate({         //连续动画
				       'margin-top':5
			     },70).animate({         //连续动画
				       'margin-top':0
			     },70)
				if($(this).attr('data-yz')==0){
					var $this = $(this);
					$.ajax({
						type: "POST",
						url: "phz.php",
						data: {openid:$(this).attr('data-zid')},
						success: function(data){
							$this.attr('data-yz','1');
							var q = parseInt($this.prevAll('div').attr('data-zj'))+1;
							$this.prevAll('div').html(q);
						}
					})
				}else
					alert('已赞');
			})
		}
	}
});
</script>
</html>