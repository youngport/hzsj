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
$openid=$_SESSION['openid'];
$cz=$_REQUEST['cz'];
$id=$_REQUEST['id'];
$sn=$_REQUEST['sn'];
$status=1;
if($cz!=''){
    $sql="select * from sk_coupon where id=$id and sn='$sn'";
    $info=$do->getone($sql);
    if(empty($info)){
        echo 7;exit;
    }elseif(($cz==1&&$info['rec']!=$openid)){
        $status=0;
    }elseif(($cz==1&&$info['status']==0)||($cz==2&&$info['status']==0)){
        $status=2;
    }elseif(($cz==1&&$info['status']==2)||($cz==2&&$info['status']==2)){
        $status=3;
    }elseif($cz==2&&$info['status']==1){
        $status=5;
    }elseif($info['end_time']<time()){
        $status=6;
    }elseif($info['is_repost']==0){
        $status=8;
    }
    if($status==1){
        if($cz==1) {
            $sql = "update sk_coupon set status=3 where id=$id";
        }elseif($cz==2){
            $sql="update sk_coupon set status=1,rec='$openid' where id=$id";
        }
        $do->dealsql($sql);
    }
    if($cz==1) {
        echo $status;
    }elseif($cz==2) {
        $sql="select wei_nickname from sk_member where open_id='$openid'";
        $name[0]=$do->getone($sql);
        $sql="select wei_nickname from sk_member where open_id='".$info['rec']."'";
        $name[1]=$do->getone($sql);
            if($status==1){               
                $wtxt1=$name[0]['wei_nickname']."领取了您的优惠券";
                $wtxt2="您领取了".$name[1]['wei_nickname']."的优惠券";
                $do->weixintishi($info['rec'],$wtxt1);
                $do->weixintishi($openid,$wtxt2);
                $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$wtxt1','".time()."','".$info['rec']."',2)");
                $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$wtxt2','".time()."','".$openid."',2)");
        }else{          
            $wtxt2="该优惠券已被领取了";
            $do->weixintishi($openid,$wtxt2);
            $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$wtxt2','".time()."','".$openid."',2)");

        }
        
        header("location:jifen.php?type=0");
    }
}