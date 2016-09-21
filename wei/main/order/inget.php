<?php
header("Content-type: text/html; charset=utf-8"); 
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();


$path="../../up_zhang/"; //上传路径 
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
	} else if($filetype == 'image/png'){ 
		$type = '.png'; 
	} 
	$today = date("YmdHis"); //获取时间并赋值给变量
	 $numbers = range (1,1000); 
	shuffle ($numbers); 
	$result = array_slice($numbers,0,1); 
	$name = md5($today.$result[0]);
	if (file_exists("upload/" . $_FILES["file"]["name"])){	
		$numbers = range (1,1000); 
		$result = array_slice($numbers,0,10); 
		$name = md5($today.$result[0]);
	}else{
		 if(move_uploaded_file($_FILES["file"]["tmp_name"],$path.$name.$type)){
			$PHP_SELF=$_SERVER['PHP_SELF'];
			//$url='http://'.$_SERVER['HTTP_HOST'].substr($PHP_SELF,0,strrpos($PHP_SELF,'/')+1);
			//$url='http://'.$_SERVER['HTTP_HOST'];
		 	$order_id = intval($_GET["id"]);
			$sql = "update sk_orders set zhangdan_img='/wei/up_zhang/".$name.$type."' where order_id='$order_id'";
			$do->dealsql($sql);
		 	echo "<script>alert('上传成功！');window.location= 'orderdet.php';</script>";
		 }
		 else
		 	echo "<script>alert('上传失败！');window.location= 'orderdet.php';</script>";
	}
}else{
	echo "<script>alert('图片大小超出限制！');window.location= 'orderdet.php';</script>";
}
?>