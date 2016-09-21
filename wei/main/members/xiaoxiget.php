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
function gs_time($data){
	foreach($data as $k=>$v){
		$data[$k]['create_time']=date('m月d日 A H:i',$v['create_time']);
		$data[$k]['title']=htmlspecialchars_decode($v['title']);
		$data[$k]['abst']=htmlspecialchars_decode($v['abst']);
		$data[$k]['intro']=htmlspecialchars_decode($v['intro']);
	}
	return $data;
}
$openid = $_SESSION["openid"];
if($_POST['type']){
	$limit=intval($_POST['p']);
	$type = intval($_POST['type']);
	if($type==1)
		$sql="select id,title,abst,intro,img,create_time,rec,status,type from sk_message where status=1 and rec='$openid' and recpid='' order by create_time desc limit ".$limit.',5';
	if($type==2){
		$sql="select id,title,abst,intro,img,create_time,rec,status,type,(select count(*) from sk_message_read where mid=a.id and open_id='$openid') is_read from sk_message a where status=1 and rec='".$openid."' or recpid=(select if(pid!='',pid,0) from sk_member where open_id='$openid') order by is_read asc,create_time desc limit ".$limit.',5';
	}
	$resultArr = gs_time($do->selectsql($sql));

	//$json = $com->createBaseJson($resultArr, "id,title,abst,intro,img,create_time,rec,status,is_read,type");
	//echo $json;
	echo json_encode($resultArr);
	//print_r($resultArr);
}else if(is_numeric($_POST['sc'])){
	$sql="select id from sk_soucang where yonghu='".$openid."' and type=2 and chanpin=".intval($_POST['sc']);
	if($data=$do->getone($sql)){
		$sql="delete from sk_soucang where id=".$data['id'];
		if($do->dealsql($sql))echo 2;
	}else{
		$sql="insert into sk_soucang (yonghu,chanpin,type) values ('".$openid."',".intval($_POST['sc']).",2)";
		if($do->dealsql($sql))echo 1;
	}
}
?>