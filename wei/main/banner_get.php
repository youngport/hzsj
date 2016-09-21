<?php
include '../base/condbwei.php';
include '../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

$sql = "select imgurl,links from sk_banner order by paixu asc limit 0,4";
$json = "";
$spArr = $do->selectsql($sql);
if(count($spArr)>0){
    $json .= "[";
    for($j=0;$j<count($spArr);$j++){
        $json .= "{\"imgurl\":\"".$spArr[$j]["imgurl"]."\",";
        $json .= "\"links\":\"".$spArr[$j]["links"]."\"},";
    }
    $json = substr($json, 0, strlen($json)-1)."]";
}else{
    $json .= "[]";
}
echo $json;
?>