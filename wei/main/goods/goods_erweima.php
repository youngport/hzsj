<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];

$id = intval($_POST['pd']);
$url=$do->getone("select url from sk_goods_erweima where openid='$openid' and goods_id=$id");
if(!empty($url)){
	echo $url['url'];
	exit;
}
//——————————————————————————————————————————————————————————————————————————————————————————————————————
		//二维码生成
		//include '../../base/commonFun.php';
		include '../../base/phpqrcode/phpqrcode.php';
		//$com = new commonFun();
		$errorCorrectionLevel = 'L'; // 容错级别
		$matrixPointSize = 6; // 生成图片大小
		
		
		//订单号 $out_trade_no
		//购买人id 在订单号里 buyer_id
		//得出该订单里有多少个商品跟该订单的购买人	
		//$dingdanhao = $curtime;
		/* $i_i_sql = "select o.buyer_id as kehu,g.goods_id as shangpin,go.goodtype as type, g.quantity as quantity from sk_orders o join sk_order_goods g on o.order_id = g.order_id join sk_goods go on g.goods_id = go.id where o.order_id = '".$order_id."'";
		$i_i_resuArr = $do->selectsql($i_i_sql);
		for($k=0;$k < count($i_i_resuArr);$k++){//每种商品
			if($i_i_resuArr[$k]['type'] == 2){//判断该产品是店家商品(LED)才生成二维码
				for($qui=0;$qui < $i_i_resuArr[$k]['quantity'];$qui++){//每个商品生成二维码 */
					$i_shu = mt_rand(100,999);//___唯一编号↓
					$i_shu2 = time();
					$i_sql = "select * from sk_goods where goods_erweima='".$i_shu2.$i_shu."'";//检查该编号是否存在
					$i_resuArr = $do->selectsql($i_sql);
					if(count($i_resuArr)>0){
						$i_congfu = $i_shu;
						while($i_congfu == $i_shu){
							$i_shu = mt_rand(100,999);
						}
					}//___唯一编号↑ $i_shu2.$i_shu
					//session_start();
					$access_token = $_SESSION["access_token"];
					$headimgurl = "";//$_SESSION["headimgurl"];//二维码中间的小图标(带图片报错 )
					$link = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=".$openid."|shangp|".$id."&connect_redirect=1#wechat_redirect";
					//$link = 'https://m.hz41319.com/wei/login.php?state='.$i_i_resuArr[0]['kehu'].'&erweima_shangpin='.$i_i_resuArr[0]['shangpin'].'&typeo=2';
					$QR = '../../images/goods_erweima/' . $i_shu2.$i_shu . '.png';//存放二维码地址
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
					imagepng($QR, '../../images/goods_erweima/' . $i_shu2.$i_shu . '.png');//存放二维码地址
					//每个商品对应的二维码插入数据库
					//$do->selectsql("update sk_goods set goods_erweima='wei/images/goods_erweima/" . $i_shu2.$i_shu . ".png' where id='$id'");
					$do -> selectsql("insert into sk_goods_erweima(goods_id,openid,url)values(".$id.",'$openid','wei/images/goods_erweima/" . $i_shu2.$i_shu . ".png')");
					//$com->sendImg($access_token ,$openid, $curtime . '.png');//貌似是信息推送，估计是把二维码或者链接推送到该微信号什么的
					$goods_erweima = "";
		//——————————————————————————————————————————————————————————————————————————————————————————————————————
		echo "wei/images/goods_erweima/" . $i_shu2.$i_shu . ".png";
?>