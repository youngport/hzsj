<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["goodsid"]);


$y=date("Y",time());
$m=date("m",time());
$d=date("d",time()); 
$start_time = mktime(0, 0, 0, $m, $d ,$y); 
$time = time();
$miao = $time-$start_time;

$yu = $miao%30;
$shu = ($miao-$yu)/30;


//$sql = "SELECT count(*) cut FROM sk_goods s where sg_date=current_date and shangou=1 ";
//$goodsArr = $do->selectsql($sql);
$sql="select count(*) cut from sk_goods a inner join sk_sg b on a.id=b.gid where b.status=1 and b.start_time<".time()." and end_time>".time();
$goodsArr=$do->getone($sql);
$shu = $shu%$goodsArr['cut'];//轮到第几个商品闪购
if($shu==0)
	$shu = $goodsArr['cut'];//最后一个
$shu--;//从0开始算起
	
//$sql = "SELECT s.id,s.sg_int FROM sk_goods s where sg_date=current_date and shangou=1 limit ".$shu.",1 ";
$sql="select a.id,b.number sg_int from sk_goods a inner join sk_sg b on a.id=b.gid where b.status=1 and b.start_time<".time()." and end_time>".time()." limit $shu,1";
$goodsArr = $do->getone($sql);
if($goodsArr['id'] == $goodsid && $goodsArr['sg_int']>0){
 	//$ssql = "select shangoujia,id,good_name,img,guige,goodtype,pcode from sk_goods where id=".$goodsid;
	$sql="select b.id sgid,a.id,b.price shangoujia,good_name,img,guige,goodtype,pcode from sk_goods a inner join sk_sg b on a.id=b.gid where a.id=$goodsid";
	$goodsArr = $do->getone($sql);
	$curtime = date('YmdHis',time()).mt_rand(1000,9999);
		//----------------------获取本机IP
		function getIPaddress()
		{
		    $IPaddress='';
		    if (isset($_SERVER)){
		        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
		        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
		            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
		        } else {
		            $IPaddress = $_SERVER["REMOTE_ADDR"];
		        }
		    } else {
		        if (getenv("HTTP_X_FORWARDED_FOR")){
		            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
		        } else if (getenv("HTTP_CLIENT_IP")) {
		            $IPaddress = getenv("HTTP_CLIENT_IP");
		        } else {
		            $IPaddress = getenv("REMOTE_ADDR");
		        }
		    }
		    return $IPaddress;
		}
		//--------------------获取地址
		function getip(){
			$ip=file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.getIPaddress());
			$ip=json_decode($ip,true);
			return $ip['data']['region'].$ip['data']['city'];
		}
		$do->dealsql("insert into sk_orders (order_sn,buyer_id,status,order_time,add_time,ipdz,erm,jifendh,sg_xiadan,goods_amount) values ('$curtime','$openid','0','".date('Y-m-d H:i:s',time())."',".time().",'".getip()."',0,0,1,".$goodsArr['shangoujia'].")");//插入订单表
		$order_id=$do->getone("select order_id from sk_orders where order_sn='$curtime' and buyer_id='$openid' and status=0");
		$order_id=$order_id['order_id'];
		$sql = "insert into sk_order_goods (order_id,goods_id,goods_name,specification,price,quantity,goods_images,goods_code) values ('$order_id','".$goodsArr["id"]."','".addslashes($goodsArr['good_name'])."','".$goodsArr['guige']."','".$goodsArr['shangoujia']."','1','".$goodsArr['img']."','".$goodsArr['pcode']."')";//插入订单商品表
		$do->dealsql($sql);
		$sql = "update sk_goods set kucun = kucun - 1 where id='".$goodsArr["id"]."'";//商品库存减去
		$do->dealsql($sql);
		$sql = "update sk_sg set number=number-1 where id='".$goodsArr["sgid"]."'";//商品库存减去
		$do->dealsql($sql);
		
		echo "{\"success\":\"1\",\"orderid\":\"$order_id\"}";

}else{
	echo "{\"success\":\"0\"}";
}
?>