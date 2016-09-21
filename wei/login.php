<?php
$cxtPath = "http://".$_SERVER['HTTP_HOST'];

session_start();
if(isset($_GET["code"])){
	$code = $_GET["code"];
	$state = $_GET["state"];
	$arr = explode("|",$state);
	$state = $arr[0];
	if($arr[1]=='hdshangp'||$arr[1]=='ms'||$arr[1]=='jinrihuodong'){
            $_SESSION["huodms"] = 1;
        }
	elseif($arr[1] == "shangp"){
            $_SESSION["huodms"] = 2;
        }
	else if($arr[1] == "lfx"){
            $_SESSION["huodms"] = 3;
        }
        
//        if(substr($arr[1],0,4)=='mac_'){
//             $mac= urldecode(substr($arr[1],4)); //获取用户分享过来的mac地址
//             $_SESSION["new_mac"]=$mac;  // 存进session 便于后面保存到新用户的资料里面
//             file_put_contents('log.log', date("Y-m-d H:i:s"). "code ".$_GET["code"] . "mac地址:".$mac."-----state:".$state.PHP_EOL, FILE_APPEND | LOCK_EX); //日志
//        }
        
        $mac_arr=$arr[count($arr)-1];
        if(substr($mac_arr,0,4)=='mac_'){
             $mac= urldecode(substr($mac_arr,4)); //获取用户分享过来的mac地址
             $_SESSION["new_mac"]=$mac;  // 存进session 便于后面保存到新用户的资料里面
             file_put_contents('log.log', date("Y-m-d H:i:s"). "mac地址:".$mac."-----state:".$state.PHP_EOL, FILE_APPEND | LOCK_EX); //日志
        }
	
        
	$weixin_fabu_shangping = "";
	if(substr($state,0,4)=="hzsj"&&(int)substr($state,4)){
	    $weixin_fabu_shangping = (int)substr($state,4);
	}else{
	    $_SESSION["state"] = $state;
	}

	$_SESSION["code"] = $code;
	if($arr[1]==2){
		$_SESSION["typeo"] = $arr[1];
		$_SESSION["erweima"] = $arr[2];
	}elseif($arr[1] == "mingpian"){//名片分享
		$_SESSION["mpid"] = $arr[0];
                                    //$_SESSION["state"] = $arr[0];
	}
}else{
	$_SESSION["state"] = "";
	header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3fa82ee7deaa4a21&redirect_uri=http%3A%2F%2Ftest.ypt5566.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=&connect_redirect=1#wechat_redirect");
	//header("Location:https://localhost/wwwroot/wei/login.php);
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
	<meta name="viewport" content="width=320, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon.png"/>
	<title>用户登录</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/xmapp.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $cxtPath ?>/wei/css/base.css" />
	<script type="text/javascript" src="<?php echo $cxtPath ?>/wei/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
$(function(){
	$.ajax({
		type:"POST",
		url:"main/logincheck.php",
		data:"",
		success:function(data){
			//alert(data);return;
			data = eval("("+data+")");
			if(data.success=="1"){
				<?php if($arr[1] == "mingpian"){?> 
			    location.replace("index.php");
					//location.replace('main/user/mingpian.php');//跳转到我的名片
				<?php }else if((int)$weixin_fabu_shangping){?>
			        location.replace('main/goods/goods.php?id=<?php echo $weixin_fabu_shangping; ?>');
				<?php }else if($arr[1] == "hdshangp"){?>
				    location.replace('main/goods/goods.php?ms=1&id=<?php echo $arr[2]; ?>');
				<?php }else if($arr[1] == "shangp"){?>
				    location.replace('main/goods/goods.php?id=<?php echo $arr[2]; ?>');
				<?php }else if($arr[1] == "ms"){?>
					location.replace('main/ms/msq.php?n=<?php echo $arr[2];?>');
				<?php }else if($arr[1] == "jinrihuodong"){?>
					location.replace('main/ms/huodongq.php?d=<?php echo $arr[2];?>');
				<?php }else if($arr[1] == "lfx"){?>
                                        <?php if(is_numeric($arr[2])) {   ?>
					location.replace('main/lefenxiang/lfx_xiangqin.php?spid=<?php echo $arr[2];?>');
                                        <?php }else{ ?>
                                          location.replace('main/lefenxiang/lfx_index.php');
                                        <?php } ?>
				<?php }else if($arr[1] == "tuangou"){?>
					location.replace('main/ms/tuangou_xq.php?id=<?php echo $arr[2]; ?>');
				<?php }else if($arr[1] == "huiyuan"){?>
                                        <?php  if(is_numeric($arr[2])){ ?>
					   location.replace('main/category/goods_detail.php?id=<?php echo $arr[2];  ?>');
                                        <?php  }else{ ?>
                                           location.replace('main/category/member.php'); 
                                        <?php  } ?>
				<?php }else if($arr[1] == "xiaoxi_content"){?>
				location.replace('main/members/xiaoxi_content.php?id=<?php echo $arr[2]; ?>');
				<?php }else if($arr[1] == "tiyandian"){?>
				location.replace('main/xianxia/tiyandian_xq.php?id=<?php echo $arr[2]; ?>');
				<?php }else if($arr[1] == "cate"){?>
				location.replace('main/category/flss_xq.php?fl=<?php echo $arr[2]; ?>&quid=<?php echo $arr[3]; ?>');
				<?php }else if($arr[1] == "salon"){?>
				location.replace('main/salon/salon.php');
				<?php }else if($arr[1] == "coupon"){?>
                                    <?php if($arr[2] == "list"){ ?>
                                        location.replace('main/jifen/yhq.php');
                                    <?php  }elseif($arr[2] == "detail"){  ?>
                                        location.replace('main/jifen/yhq_detail.php?&id=<?php echo $arr[3];?>');
                                    <?php  }else{  ?>
                                        location.replace('main/jifen/coupon_cz.php?cz=2&id=<?php echo $arr[2];?>&sn=<?php echo $arr[3]; ?>');
                                    <?php } ?>
                                <?php }else if($arr[1] == "rotor"){?>
				location.replace('main/rotor/index.php');
				<?php }else {?>
					location.replace("start.php");
				<?php }?>
			}else{
				alert("网络问题，请稍后重试");
                                //location.replace(location.href);
                                //location.reload() ;
			}
		}
	});
});
</script>
</head>
<body style="background:white;text-align:center;">
	<img src="images/load.gif" style="margin-top:50%;width:40px;height:40px;"/>
	<div style="font-size:16px;padding-top:10px;">正在登录中，请稍后</div>
</body>
</html>