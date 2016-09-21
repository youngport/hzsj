<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';
$do = new condbwei();
$com = new commonFun();

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
session_start();
if(!isset($_SESSION["access_token"])){
    header("Location:".$cxtPath."/wei/login.php");
    return;
}
$openid = $_SESSION["openid"];
		//——————————————————————————————————————————————————————————————————————————————————————————————————————
		//二维码生成
		include '../../base/phpqrcode/phpqrcode.php';
		//$com = new commonFun();
		
		$errorCorrectionLevel = 'L'; // 容错级别
		$matrixPointSize = 6; // 生成图片大小
		
		$i_i_sql = "select hyerweima from sk_member where open_id = '".$openid."'";
		$i_i_resuArr = $do->selectsql($i_i_sql);
			if($i_i_resuArr[0]['hyerweima'] == ""){//判断该产品是店家商品(LED)才生成二维码
					$i_shu = mt_rand(100,999);//___唯一编号↓
					$i_shu2 = time();
					if("wei/images/hyerweima/" . $i_shu2.$i_shu . ".png" == $i_i_resuArr[0]['hyerweima']){
						$i_congfu = $i_shu;
						while($i_congfu == $i_shu){
							$i_shu = mt_rand(100,999);
						}
					}//___唯一编号↑ $i_shu2.$i_shu
					session_start();
					$access_token = $_SESSION["access_token"];
					$headimgurl = "";//$_SESSION["headimgurl"];//二维码中间的小图标
					$link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state='.$openid.'|mingpian&connect_redirect=1#wechat_redirect';
					$QR = '../../images/hyerweima/' . $i_shu2.$i_shu . '.png';//存放二维码地址
					QRcode::png ( $link, $QR, $errorCorrectionLevel, $matrixPointSize, 2 );
					
					$logo = $headimgurl;
					if ($logo !== FALSE) {
						$QR = imagecreatefromstring ( file_get_contents ( $QR ) );
						$logo = imagecreatefromstring ( file_get_contents ( $logo ) );
						$QR_width = imagesx ( $QR ); // 二维码图片宽度
						$QR_height = imagesy ( $QR ); // 二维码图片高度
						$logo_width = imagesx ( $logo ); // logo图片宽度
						$logo_height = imagesy ( $logo ); // logo图片高度
						$logo_qr_width = $QR_width / 5;
						$scale = $logo_width / $logo_qr_width;
						$logo_qr_height = $logo_height / $scale;
						$from_width = ($QR_width - $logo_qr_width) / 2;
						// 重新组合图片并调整大小
						imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
					}
					imagepng($QR, '../../images/hyerweima/' . $i_shu2.$i_shu . '.png');//存放二维码地址
					//每个商品对应的二维码插入数据库
					$do->selectsql("update sk_member set hyerweima = 'wei/images/hyerweima/" . $i_shu2.$i_shu . ".png' where open_id = '".$openid."'");
					echo "wei/images/hyerweima/" . $i_shu2.$i_shu . ".png";
					//$com->sendImg($access_token ,$openid, $curtime . '.png');//貌似是信息推送，估计是把二维码或者链接推送到该微信号什么的

			}
		//——————————————————————————————————————————————————————————————————————————————————————————————————————
?>