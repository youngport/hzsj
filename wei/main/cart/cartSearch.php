<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();
session_start();
$openid = $_SESSION["openid"];
$sql = "select w.cart_id,w.goodsid,w.guige,g.fp_price,w.color,w.buynum,g.img,g.good_name,g.price,g.kucun,g.goodtype,g.huodongjia,gs.price tuangoujia,sg.price shangoujia,w.danjia,g.status from wei_cart w left join sk_goods g on g.id=w.goodsid left join sk_gs gs on gs.gid=w.goodsid  left join sk_sg sg on sg.gid=w.goodsid where w.openid='$openid'";
$resultArr = $do->selectsql($sql);
//$json = $com->createJson(count($resultArr), $resultArr, "goodsid,guige,color,buynum,img,good_name,price,goodtype,huodongjia,tuangoujia,shangoujia,danjia");

//是否店铺会员
$dp_user = $do->selectsql("select jointag from sk_member where open_id ='$openid'");
$jointag = $dp_user[0]['jointag'];
//获取店铺会员售价比例



$json = "{\"total\":\"".count($resultArr)."\", \"root\":[";

if(count($resultArr)>0){
    for($i=0; $i<count($resultArr); $i++){
        $json .= "{\"goodsid\":\"".$resultArr[$i]["goodsid"]."\",";
        $json .= "\"guige\":\"".$resultArr[$i]["guige"]."\",";
        $json .= "\"cart_id\":\"".$resultArr[$i]["cart_id"]."\",";
        $json .= "\"color\":\"".$resultArr[$i]["color"]."\",";
        $json .= "\"img\":\"".$resultArr[$i]["img"]."\",";
        $json .= "\"good_name\":\"".$do -> gohtml($resultArr[$i]["good_name"])."\",";
        //店铺会员
if ($jointag == '2') {
        $dp_price = $do->selectsql("select dp_price from sk_dp_price where id = 1");
        //echo $dp_price;
        $dp_price = $dp_price[0]['dp_price'];
        //店铺会员价格为：商品价格-利润分出*店铺会员售价比例
        $fp_price = $resultArr[$i]["fp_price"]*$dp_price;
        $resultArr[$i]["price"]-=$fp_price;
        $json .= "\"price\":\"".$resultArr[$i]["price"]."\",";
}else
        $json .= "\"price\":\"".$resultArr[$i]["price"]."\",";   
        $json .= "\"goodtype\":\"".$resultArr[$i]["goodtype"]."\",";
        $json .= "\"huodongjia\":\"".$resultArr[$i]["huodongjia"]."\",";
        $json .= "\"shangoujia\":\"".$resultArr[$i]["shangoujia"]."\",";
        $json .= "\"tuangoujia\":\"".$resultArr[$i]["tuangoujia"]."\",";
        $json .= "\"danjia\":\"".$resultArr[$i]["danjia"]."\",";
		if($resultArr[$i]["status"]==1){//=1 商品上架 0下架
    		if($resultArr[$i]["danjia"]==1){//=1 从活动加入购物车商品
    		    $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 1 and canyuid=(select cate_id from sk_goods where id=".$resultArr[$i]["goodsid"]."))";
                $LArr = $do->selectsql($sql);
                $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 2 and canyuid=(select mp_id from sk_goods where id=".$resultArr[$i]["goodsid"]."))";
                $MArr = $do->selectsql($sql);
                $sql = "select count(*) cou from sk_huodong where hd_youxiao = 1 and hd_kaishi < '".date('Y-m-d H:i:s',time())."' and hd_jieshu > '".date('Y-m-d H:i:s',time())."' and huodong_id =(select huodongid from sk_huodong_bang where canyulei = 3 and canyuid=(select gx_id from sk_goods where id=".$resultArr[$i]["goodsid"]."))";
                $GArr = $do->selectsql($sql);
                
                if($LArr[0]['cou'] > 0||$MArr[0]['cou'] > 0||$GArr[0]['cou'] > 0){
        		    $json .= "\"spzt\":1,";
        		}else{
        		    $json .= "\"spzt\":0,";
        		}
    		}elseif($resultArr[$i]["danjia"]==2){
				$sql="select count(*) count from sk_gs where gid='".$resultArr[$i]['goodsid']."' and start_time<".time()." and end_time>".time()." and status=1";
				$gs=$do->getone($sql);
				if($gs['count'] > 0)$json .= "\"spzt\":1,";else $json .= "\"spzt\":0,";
			}else{
				$json .= "\"spzt\":1,";
			}
            if($resultArr[$i]['kucun']>0)
                $json .= "\"is_kucun\":1,";
            else
                $json .= "\"is_kucun\":0,";
		}else
		    $json .= "\"spzt\":0,";
		$json .= "\"buynum\":\"".$resultArr[$i]["buynum"]."\"},";
    }
    $json = substr($json, 0, strlen($json)-1)."]}";
}else{
    $json = "{\"total\":\"0\",\"root\":[]}";
}
//return $json;




/* $json .= "\"sangping\":";
$sql = "select quantity,goods_name,price,goods_images,specification from sk_order_goods where order_id='".$goodsArr[$i]["order_id"]."'";
$spArr = $do->selectsql($sql);
if(count($spArr)>0){
    $json .= "[";
    for($j=0;$j<count($spArr);$j++){
        $json .= "{\"goods_name\":\"".$spArr[$j]["goods_name"]."\",";
        $json .= "\"quantity\":\"".$spArr[$j]["quantity"]."\",";
        $json .= "\"price\":\"".$spArr[$j]["price"]."\",";
        $json .= "\"specification\":\"".$spArr[$j]["specification"]."\",";
        $json .= "\"goods_images\":\"".$spArr[$j]["goods_images"]."\"},";
    }
    $json = substr($json, 0, strlen($json)-1)."]},";
}else{
    $json .= "[]},";
} */










echo $json;
?>