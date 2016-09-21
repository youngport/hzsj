<?php
//$mysql=new mysqli("localhost","root","7bea8eacd0","zhangzongweisha");
include './base/condbwei.php';
include './base/commonFun.php';
$do=new condbwei();
$mysql=new mysqli("localhost","root","7bea8eacd0","zhangzongweisha");
$sql="select open_id from sk_member where pid='oc4cpuJr7ResBz04h46pT8MeSLUw'";
$users=$mysql->query($sql);
if($users->num_rows>0) {
    $sendid=303;
    $start_time=strtotime("2016-3-31");
    $end_time=strtotime("2016-4-1");
    $add_time=time();
    while ($user=$users->fetch_assoc()) {
        $openid = $user['open_id'];
        $sql="select jointag from sk_member where open_id='$openid'";
        $jointag=$mysql->query($sql);
        $jointag=$jointag->fetch_assoc();
        if($jointag['jointag']==0) {
            $sql = "select count(*) count from sk_coupon where sendid=$sendid and rec='$openid' and type=2 and tid=336";
            $count = $mysql->query($sql);
            $count = $count->fetch_assoc();
            if ($count['count'] > 0) {
                continue;
            }
            $sn = substr(md5("hz" . time() . mt_rand(1, 9999)), 0, 5);
            $sql = "insert into sk_coupon(sn,sendid,rec,xz,js,start_time,end_time,add_time,type,tid,is_repost) value ('$sn',$sendid,'$openid',9.99,9.89,$start_time,$end_time,$add_time,2,336,0)";
            $mysql->query($sql);
            $txt="您收到一张洋仆淘优惠券";
            $do->weixintishi($openid,$txt);
            $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$txt','".time()."','".$openid."',2)");
        }
    };
}
$users->free();
$mysql->close();
?>