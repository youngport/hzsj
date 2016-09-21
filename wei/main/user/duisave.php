<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';

$do = new condbwei();
$com = new commonFun();

$txd = intval($_POST['txd']);
$txje = intval($_POST['txje']);
$password=md5(addslashes($_POST['password']));
session_start();
$openid = $_SESSION["openid"];
$count=$do->getone("select count(*) count from sk_member where open_id='$openid' and usrpwd='$password'");
if($count['count']<1){
    echo "{\"success\":\"5\"}";
    exit;
}
$exchangeid = $do->sqlTableId("wei_exchange", "id");
//$sql = "select sum(pop) as pop from wei_pop where duitag='0' and openid='$openid'";
//$popArr = $do->selectsql($sql);
$scsql = "select sctixian,shouyi from sk_member where open_id='$openid'";
$sctx_Arr = $do->selectsql($scsql);
$totpop = $txje;
if($totpop <= $sctx_Arr[0]['shouyi'] && $sctx_Arr[0]['shouyi'] > 0 ){
    if($sctx_Arr[0]['sctixian']==0){
        if($totpop >= 10){
            $sql = "insert into wei_exchange (id, openid, totpop, audit_tag, submit, wy_zfb) values ($exchangeid, '$openid', $totpop, '0', now(),".$txd.")";
            if($do->dealsql($sql)){
                $weixintxt="提示：".date('Y-m-d H:i:s',time())." 在本商城申请提现 ".$totpop." 元";
                $do -> weixintishi($openid,$weixintxt);
                $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$openid."',2)");
            }
            //$sql = "update wei_pop set duitag='2',exchangeid='$exchangeid' where openid='$openid'";
            //$do->dealsql($sql);
            $do->selectsql("update sk_member set sctixian=1,shouyi = shouyi - ".$totpop." where open_id='$openid'");
            echo "{\"success\":\"1\"}";
        }else{
            echo "{\"success\":\"2\"}";//首次提现不足10元
        }
    }else if($totpop >= 50){
        $sql = "insert into wei_exchange (id, openid, totpop, audit_tag, submit, wy_zfb) values ($exchangeid, '$openid', $totpop, '0', now(),".$txd.")";
        if($do->dealsql($sql)){
            $weixintxt="提示：".date('Y-m-d H:i:s',time())." 在本商城申请提现 ".$totpop." 元";
            $do -> weixintishi($openid,$weixintxt);
            $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$openid."',2)");
        }
        //$sql = "update wei_pop set duitag='2',exchangeid='$exchangeid' where openid='$openid'";
        //$do->dealsql($sql);
        $do->selectsql("update sk_member set shouyi = shouyi - ".$totpop." where open_id='$openid'");
        echo "{\"success\":\"1\"}";
    }else{
    	echo "{\"success\":\"3\"}";//提现不足50元
    }
}elseif($totpop >= $sctx_Arr[0]['shouyi']){
    echo "{\"success\":\"4\"}";//提现金额过大
}elseif($sctx_Arr[0]['shouyi']==0){
    echo "{\"success\":\"0\"}";//提现金额为0
}
?>