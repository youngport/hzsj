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
//$dianji = $_GET["ddjm"];

//$tiaoid="";//跳转页面所带参数
$do = new condbwei(); 
$com = new commonFun();

$redirect_uri =  'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$imgurl = 'http://'.$_SERVER['HTTP_HOST']."/";
$shareCheckArr = $com->shareFun($redirect_uri, $_SESSION["access_token"], $_SESSION["ticket"]);

// $sql = "select jointag from sk_member where open_id='$openid'";
// $shifou = $do -> selectsql($sql);
// $show_heide = false;
if($shifou[0]['jointag']!=0){
    $show_heide = true;
}

//一级分类
$sql_1 = "select id,name from sk_items_cate where pid=5";
$directory = $do -> selectsql($sql_1);
//二级
$sql = "select id,name from sk_items_cate where pid=65";
	$result = $do -> selectsql($sql);
	foreach ($result as $key => $value) {
		//三级
		$sql_ = "select good_name,img,id,cate_id,canyuhd from sk_goods where cate_id=".$value['id']." limit 0,6";
		$result[$key]['val'] = $do -> selectsql($sql_);
	}
//$img_url = "";//分享图片路径


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="telephone=no" name="format-detection" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>分类搜索</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/home.css"/>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/common.js?v2.0"></script>

	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/fenlei.css">
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/fenlei.js">
	</script>
<script>
	$(function(){
		$(".zuo ul li").click(function(){
			$(this).addClass("on").siblings().removeClass("on");
			$(".qie .you").hide().eq($(".zuo ul li").index(this)).show();
			var id = $(this).attr("data-id");
			var url = "<?php echo $cxtPath.'/'; ?>";
			$.ajax({
				type: "POST",
				url: "f_post.php",
				data: {id:id},
				success: function(data){
					var one = "";			
					var data = eval("("+data+")");
					//alert(data);
					$(".you").empty();
					for(var i=0;i<data.length;i++){
						one = "<div class='lei' style='margin-left:2%;'><h2>"+data[i].name+"</h2><ul class='clearfix u3'>";
                        var show = data[i].val;
                        var show = eval(show);
	                    if (show.length>0) { 
	                    	var two = "";
	                        for(var j=0;j<show.length;j++){//alert(i)
	                         two += "<li><a href=\"<?php echo $cxtPath ?>/wei/main/goods/goods.php?ms="+show[j].canyuhd+"&cate="+show[j].cate_id+"&id="+show[j].id+"\ style='display:block'><img src='"+url+show[j].img+"' style='width:78%;display:block;margin:0 auto;'><span style='display:block;text-align:center;'>"+show[j].good_name+"</span></a></li>";
	                         }
	                     }
	                     two += "</ul></div>";
						$(".you").append(one+two);				
					}						
				}
			})
		 })
	})
</script>		
</head>
<body>
		<div class="bigbox">
		<form action="flss_xq.php" method="post">
		  <div class="head"><input type="text"  id='productName' name='productName' placeholder="搜索"></div>
		</form>
		<div class="botbox clearfix">
			<div class="zuo">
				<ul>
				    <?php foreach ($directory as $key => $value) { ?>
				    	<li data-id="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></li>
				    <?php } ?>
				</ul>
			</div>
		<div class="qie clearfix">		
			<div class="you">
			<?php foreach ($result as $key => $value) {?>		
				<div class="lei">
					<h2><?php echo $value['name']; ?></h2>
					<ul class="clearfix u1">
						<?php foreach ($value['val'] as $k => $val) { ?>			
							<li><a href="<?php echo $cxtPath ?>/wei/main/goods/goods.php?ms=<?php echo $val['canyuhd'] ?>&cate=<?php echo $val['cate_id'] ?>&id=<?php echo $val['id'] ?>" style="display:block"><img src="<?php echo $cxtPath."/".$val['img'] ?>" style="width:78%;display:block;margin:0 auto;"><span style="display:block;text-align:center;"><?php echo $val['good_name']; ?></span></a></li>
						<?php	} ?>
					</ul>
				</div>
            <?php	} ?>
			</div>
			<div class="you" style="display:none"></div>
			<div class="you" style="display:none"></div>
			<div class="you" style="display:none"></div>
		    </div>
		</div>
	</div>
<?php include '../gony/nav.php';?>
<?php include '../gony/guanzhu.php';?>
<?php include '../gony/returntop.php';?>	
<div style="clear:both;height:55px;"></div>
</body>
</html>












