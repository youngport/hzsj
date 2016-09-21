<?php
function quotes($content){
	if (is_array($content))	{
		foreach ($content as $key=>$value){
			$content[$key] = addslashes($value);
		}
	}
	else{
		$content=addslashes($content);
	}
	return htmlspecialchars($content);
}

include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$time = date('Y-m-d', strtotime("-30 days")) ; //30天前
//echo $time;
if($_POST['cz']=='del'){
	if(is_numeric($_POST['id']))
		$id=intval($_POST['id']);
	else{
		$id = implode(',',$_POST['id']);
	}
	$sql = "delete from sk_soucang where yonghu='$openid' and id in(".$id.")";
	if($do->dealsql($sql))echo 'ok';else echo 'no';
	exit;
}
if($_POST['type']==0){
	$sql = "select sk_goods.good_name,sk_goods.img,sk_goods.id,sk_soucang.sc_time,sk_soucang.id sid from sk_goods join sk_soucang on sk_soucang.chanpin=sk_goods.id where sk_soucang.yonghu='$openid' and sk_soucang.type=1 and sk_soucang.sc_time>'$time'";
	echo "select sk_goods.good_name,sk_goods.img,sk_goods.id,sk_soucang.sc_time,sk_soucang.id sid from sk_goods join sk_soucang on sk_soucang.chanpin=sk_goods.id where sk_soucang.yonghu='$openid' and sk_soucang.type=1 and sk_soucang.sc_time>'$time'";
	if(isset($_POST['cz'])&&$_POST['cz']!='del')$sql.=" and sk_goods.good_name like '%".quotes($_POST['cz'])."%'";
}
elseif($_POST['type']==1){
	//$sql="select sk_message.id,title,abst,img from sk_message join sk_soucang on sk_soucang.chanpin=sk_message.id where sk_soucang.yonghu='$openid' and sk_soucang.type=2 or sk_soucang.type=3";
	$sql="select id,chanpin,type from sk_soucang where type!=1 and yonghu='$openid'";
	$res=$do->selectsql($sql);
	$data=array();
	foreach($res as $v){
		if($v['type']==2){
			$sql="select id,title,abst,img,create_time from sk_message where id=".$v['chanpin'];
			if(isset($_POST['cz'])&&$_POST['cz']!='del')$sql.=" and title like '%".quotes($_POST['cz'])."%' or intro like '%".quotes($_POST['cz'])."%'";
			$rec=$do->getone($sql);
			if(!empty($rec)){
				$rec['sid']=$v['id'];
				$data[]=$rec;
			}
		}else{
			$sql="select sk_lfx.id,biaoti title,neirong abst,shijian,headimgurl,wei_nickname,sk_lfx_img.imgurl img from sk_lfx inner join sk_lfx_img on sk_lfx.id=sk_lfx_img.lfxid left join sk_member on sk_lfx.openid=sk_member.open_id where sk_lfx.id=".$v['chanpin'];
			if(isset($_POST['cz'])&&$_POST['cz']!='del')$sql.=" and sk_lfx.biaoti like '%".quotes($_POST['cz'])."%'";
			$rec=$do->getone($sql);
			if(!empty($rec)){
				$rec['sid']=$v['id'];
				$rec['shijian']=date('Y-m-d',$rec['shijian']);
				$rec['abst']=mb_substr(str_replace('<br/><br/>','&nbsp;',htmlspecialchars_decode($rec['abst'])),0,35,'utf8').'......';
				$data[]=$rec;
			}
		}
	}
	echo json_encode($data);exit;
}
$resultArr = $do->selectsql($sql);
print_r($resultArr);
//$json = $com->createJson(count($resultArr), $resultArr, "good_name,img,id");


echo json_encode($resultArr);

?>