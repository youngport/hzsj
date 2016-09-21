<?php
header("Content-type: text/html; charset=utf-8"); 

include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

$path="../../up_mp/"; //上传路径 
if(!file_exists($path)) 
{ 
	mkdir('$path', 0700); 
}
if($_FILES["file"]["size"] < 2000000)
{
	$filetype = $_FILES['file']['type']; 
	if($filetype == 'image/jpeg'){ 
		$type = '.jpg'; 
	}else if ($filetype == 'image/jpg'){
		$type = '.jpg'; 
	}else if ($filetype == 'image/pjpeg'){
		$type = '.jpg'; 
	}else if($filetype == 'image/gif'){ 
		$type = '.gif'; 
	} 
	$today = date("YmdHis"); //获取时间并赋值给变量
	 $numbers = range (1,1000); 
	shuffle ($numbers); 
	$result = array_slice($numbers,0,1); 
	$name = md5($today.$result[0]);
	$touimg = "";
	if (file_exists("upload/" . $_FILES["file"]["name"])){	
		$numbers = range (1,1000); 
		$result = array_slice($numbers,0,10); 
		$name = md5($today.$result[0]);
	}else{
		 if(move_uploaded_file($_FILES["file"]["tmp_name"],$path.$name.$type)){
			//$sql = "update sk_orders set zhangdan_img='$path.$name.$type' where order_id='$order_id'";
			//$do->dealsql($sql);
			$PHP_SELF=$_SERVER['PHP_SELF'];
			//$url='http://'.$_SERVER['HTTP_HOST'];
			$touimg .= "/wei/up_mp/".$name.$type;
		 }
		 if($touimg != "")
			$sql = "update sk_member set gongsi_name='".addslashes($_POST['gongsi'])."',youxiang='".addslashes($_POST['youxiang'])."',guanwang='".addslashes($_POST['guanwang'])."',dizhi='".addslashes($_POST['dizhi'])."',dianhua='".addslashes($_POST['dianhua'])."',zhiye='".addslashes($_POST['zhiye'])."',mingpian_name='".addslashes($_POST['name'])
			."',wei_phone='".addslashes($_POST['shouji'])."',touimg='$touimg' where open_id='$openid'";
		else
			$sql = "update sk_member set gongsi_name='".addslashes($_POST['gongsi'])."',youxiang='".addslashes($_POST['youxiang'])."',guanwang='".addslashes($_POST['guanwang'])."',dizhi='".addslashes($_POST['dizhi'])."',dianhua='".addslashes($_POST['dianhua'])."',zhiye='".addslashes($_POST['zhiye'])."',mingpian_name='".addslashes($_POST['name'])
			."',wei_phone='".addslashes($_POST['shouji'])."' where open_id='$openid'";
			$do->dealsql($sql);
		 	echo "<script>alert('修改成功！');window.location= 'mingpian.php';</script>";
	}
}else{
	echo "<script>alert('图片超出大小！);window.location= 'mingpian_xiu.php';</scrip>";
}