<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = intval($_POST["goodsid"]);//ID
$guige = addslashes($_POST["guige"]);//规格
$sul = intval($_POST["sul"]);//数量
//$color = $_POST["color"];//颜色
$tax_rate = addslashes($_POST["tax_rate"]);//税收
//echo $tax_rate ;exit;

$cartnum = $_SESSION["cartnum"];
$kucun=$do->getone("select kucun from sk_goods where id=$goodsid");
if($sul>$kucun['kucun']){
	echo "{\"success\":\"4\"}";
	exit;
}elseif(isset($_POST['ck'])&&$_POST['ck']==1){
	echo "{\"success\":\"1\"}";
	exit;
}
if($_POST['ms']==1){
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id='$goodsid'))";
    $LArr = $do->selectsql($sql);
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id='$goodsid'))";
    $MArr = $do->selectsql($sql);
    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id='$goodsid'))";
    $GArr = $do->selectsql($sql);
    if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){
    	$sql = "select openid from wei_cart where openid='$openid' and goodsid='$goodsid' and guige='$guige' and danjia=1 ";
    	$resultArr = $do->selectsql($sql);
    	$type = "";
    	if(count($resultArr)>0){
    		$sql = "update wei_cart set buynum=buynum+".$sul." where openid='$openid' and goodsid='$goodsid' and guige='$guige' and danjia=1";
    		$type = "update";
    	}else{
    		$sql = "insert into wei_cart (openid,goodsid,tax_rate,guige,buynum,danjia) values ('$openid','$goodsid','$tax_rate',$guige','$sul',1)";
    		$_SESSION["cartnum"] = intval($cartnum) + 1;
    		$type = "add";
    	}
    }else{
	   echo "{\"success\":\"3\"}";
	   die;
    }
}else{
	$sql = "select openid,buynum from wei_cart where openid='$openid' and goodsid='$goodsid' and guige='$guige'";
	$resultArr = $do->selectsql($sql);
	$type = "";
	if(count($resultArr)>0){
		$sql = "update wei_cart set buynum=buynum+".$sul." where openid='$openid' and goodsid='$goodsid' and guige='$guige'";
		$type = "update";
	}else{
		$sql = "insert into wei_cart (openid,goodsid,tax_rate,guige,buynum) values ('$openid','$goodsid','$tax_rate','$guige','$sul')";
		$_SESSION["cartnum"] = intval($cartnum) + 1;
		$type = "add";
	}
}
$tag = $do->dealsql($sql);
if($tag == true){
	echo "{\"success\":\"1\", \"type\":\"$type\"}";
}else{
	echo "{\"success\":\"0\"}";
}

?>
<?php
/* 
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$goodsid = $_POST["goodsid"];//ID
$guige = $_POST["guige"];//规格
$sul = $_POST["sul"];//数量
//$color = $_POST["color"];//颜色
$cartnum = $_SESSION["cartnum"];

if($_POST['huodl']){//获取活动类 //功效 1, 名牌 2, 类别 3
    if($_POST['huodl']==3)
        $ssql = "select g.canyuhd from sk_goods g join sk_items_cate c on g.cate_id =c.id where g.id=".$goodsid."  g.status=1 and (c.huodong=1 or (select huodong from sk_items_cate ct where c.pid=ct.id)=1)";
    else if($_POST['huodl']==2)
        $ssql = "select g.canyuhd from sk_goods g join sk_mingp x on g.mp_id=x.id where g.id=".$goodsid." and x.huodong=1 and g.status=1 and canyuhd=1";
    else if($_POST['huodl']==1)
        $ssql = "select g.canyuhd from sk_goods g join sk_gongx x on g.gx_id=x.id where g.id=".$goodsid." and x.huodong=1 and g.status=1 and canyuhd=1";
    $resultArr = $do->selectsql($ssql);
}
if($_POST['ms']==1&&$resultArr[0]['canyuhd']==1){
	$sql = "select openid from wei_cart where openid='$openid' and goodsid='$goodsid' and guige='$guige' and danjia=1 and huodint=".$_POST['huodl'];
	$resultArr = $do->selectsql($sql);
	$type = "";
	if(count($resultArr)>0){
		$sql = "update wei_cart set buynum=buynum+".$sul." where openid='$openid' and goodsid='$goodsid' and guige='$guige' and danjia=1 and huodint=".$_POST['huodl'];
		$type = "update";
	}else{
		$sql = "insert into wei_cart (openid,goodsid,guige,buynum,danjia,huodint) values ('$openid','$goodsid','$guige','$sul',1,".$_POST['huodl'].")";
		$_SESSION["cartnum"] = intval($cartnum) + 1;
		$type = "add";
	}
}else{
	$sql = "select openid from wei_cart where openid='$openid' and goodsid='$goodsid' and guige='$guige'";
	$resultArr = $do->selectsql($sql);
	$type = "";
	if(count($resultArr)>0){
		$sql = "update wei_cart set buynum=buynum+".$sul." where openid='$openid' and goodsid='$goodsid' and guige='$guige'";
		$type = "update";
	}else{
		$sql = "insert into wei_cart (openid,goodsid,guige,buynum) values ('$openid','$goodsid','$guige','$sul')";
		$_SESSION["cartnum"] = intval($cartnum) + 1;
		$type = "add";
	}
}
$tag = $do->dealsql($sql);
if($tag == true){
	echo "{\"success\":\"1\", \"type\":\"$type\"}";
}else{
	echo "{\"success\":\"0\"}";
}
 */
?>