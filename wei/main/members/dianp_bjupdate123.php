<?php
header("Content-type: text/html; charset=utf-8"); 

include '../../base/condbwei.php';
include '../../base/commonFun.php';


function quotes($content){
	if (is_array($content))	{
		foreach ($content as $key=>$value){
			$content[$key] = addslashes($value);
		}
	}else{
		$content=addslashes($content);
	}
	return htmlspecialchars($content);
}
function upload($img,$dpi=0){
	$hzs=array('jpg','png','jpeg','gif');
	$size=5;
	$hz=ltrim(strrchr($img['name'],'.'),'.');
	if($img['error']!=0){
		echo "<script>alert('图片上传错误！');window.location= 'dianp_bj.php?dpi=".$dpi."';</script>";
		exit;
	}
	if(!in_array($hz,$hzs)){
		echo "<script>alert('图片格式非法！');window.location= 'dianp_bj.php?dpi=".$dpi."';</script>";
		exit;
	}
	if($img['size']>$size*1024*1024){
		echo "<script>alert('图片大小超出限制！');window.location= 'dianp_bj.php?dpi=".$dpi."';</script>";
		exit;
	}
	$today = date("YmdHis");
	$path="../../up_zhang/";
	$numbers = range (1,1000);
	$result = array_slice($numbers,0,10);
	$name = md5($today.$result[0]).'.'.$hz;
	if(file_exists($path.$name)){
		$numbers = range (1,1000);
		$result = array_slice($numbers,0,10);
		$name = md5($today.$result[0]).'.'.$hz;
	}
	if(move_uploaded_file($img['tmp_name'],$path.$name)){
		return "/wei/up_zhang/".$name;
	}
}

$cxtPath = "http://".$_SERVER['HTTP_HOST'];
$do = new condbwei();
$com = new commonFun();

session_start();
$openid = $_SESSION["openid"];
if(!isset($_SESSION["access_token"])){
	header("Location:".$cxtPath."/wei/login.php");
	return;
}

if($_GET['id']) {
	$dpi = intval($_GET['id']);
	$dpname = quotes($_POST['dianp_name']);
	$xxdizhi = quotes($_POST['dianp_xxdizhi']);
	$zuobiao = quotes($_GET['zuobiao']);
	$dianp_num = quotes($_POST['dianp_num']);
	$dianp_img='';
	$dianp_img2='';
	$_FILES['dianp_img']['name']==''||$dianp_img = upload($_FILES['dianp_img'],$dpi);
	$_FILES['dianp_img2']['name']==''||$dianp_img2 = upload($_FILES['dianp_img2'],$dpi);
//$path="../../up_zhang/"; //上传路径
//if(!file_exists($path))
//{
//	mkdir('$path', 0700);
//}
//if($_FILES["file"]["size"] < 2000000)
//{
//	$filetype = $_FILES['file']['type'];
//	if($filetype == 'image/jpeg'){
//		$type = '.jpg';
//	}else if ($filetype == 'image/jpg'){
//		$type = '.jpg';
//	}else if ($filetype == 'image/pjpeg'){
//		$type = '.jpg';
//	}else if($filetype == 'image/gif'){
//		$type = '.gif';
//	} else if($filetype == 'image/png'){
//		$type = '.png';
//	}
//	$today = date("YmdHis"); //获取时间并赋值给变量
//	 $numbers = range (1,1000);
//	shuffle ($numbers);
//	$result = array_slice($numbers,0,1);
//	$name = md5($today.$result[0]);
//	if (file_exists("upload/" . $_FILES["file"]["name"])){
//		$numbers = range (1,1000);
//		$result = array_slice($numbers,0,10);
//		$name = md5($today.$result[0]);
//	}else{
//		 if(move_uploaded_file($_FILES["file"]["tmp_name"],$path.$name.$type)){
//			$PHP_SELF=$_SERVER['PHP_SELF'];
//			//$url='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strrpos($PHP_SELF,'/')+1);
//			//$url='http://'.$_SERVER['HTTP_HOST'];
//		 	//$order_id = $_GET["id"];
//			//$sql = "update sk_orders set zhangdan_img='/wei/up_zhang/".$name.$type."' where order_id='$order_id'";
//			//$do->dealsql($sql);
//			$dianp_img = "/wei/up_zhang/".$name.$type;
//		 }
		 //else
		 	//echo "<script>alert('店面图上传失败！');window.location= 'dianp_bj.php?dpi=".$dpi."';</script>";
//	}
//}else{
//	echo "<script>alert('图片大小超出限制！');window.location= 'dianp_bj.php?dpi=".$dpi."';</script>";
//}
$sql = "select dianp_lxfs from sk_erweima where id='$dpi'";
$Arr = $do->getone($sql);
if (empty($Arr['dianp_lxfs'])) {
	$sql = "update sk_erweima set dianp_lxfs='$dianp_num' where id='$dpi'";
	$do->selectsql($sql);
}
	if($dianp_img==""&&$dianp_img2=="")
		$sql = "update sk_erweima set shenhe=0,zuobiao='$zuobiao',xxdizhi='$xxdizhi',dianpname='$dpname',dianp_num='$dianp_num' where id='$dpi'";
	elseif($dianp_img!=''&&$dianp_img2!='')
		$sql = "update sk_erweima set shenhe=0,zuobiao='$zuobiao',xxdizhi='$xxdizhi',dianpname='$dpname',dianp_num='$dianp_num',dianp_img='$dianp_img',dianp_img2='$dianp_img2' where id='$dpi'";
	elseif($dianp_img!='')
		$sql = "update sk_erweima set shenhe=0,zuobiao='$zuobiao',xxdizhi='$xxdizhi',dianpname='$dpname',dianp_num='$dianp_num',dianp_img='$dianp_img' where id='$dpi'";
	elseif($dianp_img2!='')
		$sql = "update sk_erweima set shenhe=0,zuobiao='$zuobiao',xxdizhi='$xxdizhi',dianpname='$dpname',dianp_num='$dianp_num',dianp_img2='$dianp_img2' where id='$dpi'";
	$do->selectsql($sql);
	echo "<script>alert('修改成功');window.location= 'dianp.php';</script>";
}
