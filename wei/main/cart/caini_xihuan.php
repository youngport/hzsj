<?php
include '../../base/condbwei.php';

$do = new condbwei();
session_start();
$caini_xihuan = $_SESSION["caini_xihuan"];
if($caini_xihuan=="")
    $sql = "select id,good_name,img,price from sk_goods where id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid in(select id from sk_items_cate where pid = 5))) and status = 1 and goodtype = 0 and canyuhd=0 and sort_order not in(1,2,3,4,5)  order by rand() limit 6";
else
    $sql = "select id,good_name,img,price from sk_goods where id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid = '$caini_xihuan') or cate_id = '$caini_xihuan') and status = 1 and goodtype = 0 and canyuhd=0 and sort_order not in(1,2,3,4,5) order by rand() limit 6";
$resultArr = $do->selectsql($sql);
//$json = $com->createJson(count($resultArr), $resultArr, "goodsid,guige,color,buynum,img,good_name,price,goodtype,huodongjia,tuangoujia,shangoujia,danjia");
$cha = 0;
if(count($resultArr)<6)
    $cha = 6 - count($resultArr);

$json = "{\"total\":\"".count($resultArr)."\", \"root\":[";
if(count($resultArr)>0){
    for($i=0; $i<count($resultArr); $i++){
        $json .= "{\"id\":\"".$resultArr[$i]["id"]."\",";
        $json .= "\"good_name\":\"".$resultArr[$i]["good_name"]."\",";
        $json .= "\"img\":\"".$resultArr[$i]["img"]."\",";
		$json .= "\"price\":\"".$resultArr[$i]["price"]."\"},";
    }
    if($cha>0){
        $sql = "select id,good_name,img,price from sk_goods where id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid in(select pid from sk_items_cate where id = '$caini_xihuan')) and cate_id <> '$caini_xihuan') and status = 1 and goodtype = 0 and canyuhd=0 and sort_order not in(1,2,3,4,5)  order by rand() limit ".$cha;
        $resultArr = $do->selectsql($sql);
        if(count($resultArr)>0){
            for($i=0; $i<count($resultArr); $i++){
                $json .= "{\"id\":\"".$resultArr[$i]["id"]."\",";
                $json .= "\"good_name\":\"".$resultArr[$i]["good_name"]."\",";
                $json .= "\"img\":\"".$resultArr[$i]["img"]."\",";
        		$json .= "\"price\":\"".$resultArr[$i]["price"]."\"},";
            }
        }
    }
    $json = substr($json, 0, strlen($json)-1)."]}";
}else if($cha>0){
    $sql = "select id,good_name,img,price from sk_goods where id in(select id from sk_goods where cate_id in(select id from sk_items_cate where pid in(select pid from sk_items_cate where id = '$caini_xihuan')) and cate_id <> '$caini_xihuan') and status = 1 and goodtype = 0 and canyuhd=0 and sort_order not in(1,2,3,4,5)  order by rand() limit ".$cha;
    $resultArr = $do->selectsql($sql);
    if(count($resultArr)>0){
        for($i=0; $i<count($resultArr); $i++){
            $json .= "{\"id\":\"".$resultArr[$i]["id"]."\",";
            $json .= "\"good_name\":\"".$resultArr[$i]["good_name"]."\",";
            $json .= "\"img\":\"".$resultArr[$j]["img"]."\",";
    		$json .= "\"price\":\"".$resultArr[$i]["price"]."\"},";
        }
        $json = substr($json, 0, strlen($json)-1)."]}";
    }
}else{
    $json = "{\"total\":\"0\",\"root\":[]}";
}
echo $json;
?>