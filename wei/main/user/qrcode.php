<?php
include '../../base/commonFun.php';
include '../../base/phpqrcode/phpqrcode.php';
$com = new commonFun();

$errorCorrectionLevel = 'L'; // 容错级别
$matrixPointSize = 6; // 生成图片大小
session_start();
$access_token = $_SESSION["access_token"];
$headimgurl = $_SESSION["headimgurl"];
$openid = addslashes($_POST['openid']);
$curtime = time ();
$link = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state='.$openid.'&connect_redirect=1#wechat_redirect';
$QR = '../../images/qrcode/' . $curtime . '.png';
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
imagepng($QR, '../../images/qrcode/' . $curtime . '.png');
$com->sendImg($access_token ,$openid, $curtime . '.png');//貌似是信息推送，估计是把二维码或者链接推送到该微信号什么的
?>