<?php
include '../base/commonFun.php';
include '../base/condbwei.php';

$com = new commonFun();
$do = new condbwei();
session_start();
$code = $_SESSION["code"];
$state = $_SESSION["state"];
$hd_ms = $_SESSION["huodms"];
$json = $com->getLoginAccessToken($code);
$tokenJson = json_decode($json);
$access_token = $tokenJson->access_token;//获取用户access_token网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
$openid = $tokenJson->openid;//获取用户openid
if($openid==""){
	echo "{\"success\":\"0\"}";
}else{
	$_SESSION["openid"] = $openid;
	$_SESSION["shenaccess_token"] = $access_token;

	//$json = $com->getWeiLogin();//获取access token
	//$baseToken = json_decode($json);
	$token=$do->token();
	$json = $com->getUserBaseInf($token, $openid);//获取用户基本信息
	//echo $json;die;
	$userJson = json_decode($json);
	$_SESSION["access_token"] = $token;
	$_SESSION["subscribe"] = $userJson->subscribe;//是否关注 0未关注
	$_SESSION["unionid"] = $userJson->unionid;
	//-------------------积分返回------------------
	$jfguanzhu = $_SESSION["subscribe"];
	$sql = "select jf from sk_member where open_id='$openid'";
	$jffh = $do->selectsql($sql);
	if($jffh[0]['jf'] == 0 && $jfguanzhu != 0){
		$sql = "insert into sk_jifen(jifen,laiyuan,shijian,openid) values(5,7,".time().",'$openid')";
		$do->selectsql($sql);
		$sql = "update sk_member set jifen = jifen+5,jf=1 where open_id='$openid'";
		$do->selectsql($sql);
		/* $sql = "update sk_member set jf=1 where open_id='$openid'";
		$do->selectsql($sql); */
	}
    if($hd_ms == 1 || $hd_ms == 2 || $hd_ms == 3){
        if($hd_ms==1)
            $jflx = 6;//活动分享
        elseif($hd_ms==2)
            $jflx = 5;//普通商品分享
        else
            $jflx = 8;//乐分享文章分享
        $aa = date('Y-m-d',time());
        $dangtian = strtotime($aa);//当天 0点时间戳
        $mingtian = $dangtian+86400;//明天 0点时间戳
        
        
        $sql = "select * from sk_jifen where openid='$state' and laiyuan='$jflx' and shijian < ".$mingtian." and shijian > ".$dangtian;
        $soucangArr = $do->selectsql($sql);
        
        if(count($soucangArr)<10){
            $sql = "insert into sk_jifen (jifen,openid,laiyuan,shijian) values (2,'$state','$jflx',".time().")";
            $tag = $do->dealsql($sql);
            $sql = "update sk_member set jifen=jifen+2 where open_id='$state'";
            $do->dealsql($sql);
        }
    }
	//-------------------------------------
	$nickname = "";
	$sex = "";
	$city = "";
	$country = "";
	$province = "";
	$language = "";
	$headimgurl = "";
	$tuinickname = "";
	$tuiheadimgurl = "";
	if($userJson->subscribe != "0"){
		$nickname = $userJson->nickname;
		$sex = $userJson->sex;
		$city = $userJson->city;
		$country = $userJson->country;
		$province = $userJson->province;
		$language = $userJson->language;
		$headimgurl = $userJson->headimgurl;
	}else{
		$sql = "select wei_nickname,headimgurl from sk_member where open_id='$state'";
		$resultArr = $do->selectsql($sql);
		$tuinickname = $resultArr[0]["wei_nickname"];
		$tuiheadimgurl = $resultArr[0]["headimgurl"];
	}
	$_SESSION["nickname"] = $nickname;
	$_SESSION["sex"] = $sex;
	$_SESSION["city"] = $city;
	$_SESSION["country"] = $country;
	$_SESSION["province"] = $province;
	$_SESSION["language"] = $language;
	$_SESSION["headimgurl"] = $headimgurl;
	$_SESSION["tuinickname"] = $tuinickname;
	$_SESSION["tuiheadimgurl"] = $tuiheadimgurl;

	$today=$do->getone("select * from sk_member_count where time='".date("Y-m-d")."'");
	if(empty($today))
		$do->dealsql("insert into sk_member_count (time) value ('".date("Y-m-d")."')");
	$sql = "select usrid,pid,wei_shouname,phone,address,ticket,tickettime from sk_member where open_id='$openid'";
	$resultArr = $do->selectsql($sql);
	if(count($resultArr)==0){//认证用户是否已经在数据库中
		$tickjson = $com->getTicket($token);
		$ticJson = json_decode($tickjson);
		$_SESSION["ticket"] = $ticJson->ticket;
		//echo "<script>alert('".$state."')</script>";return;
		$sql = "insert into sk_member (pid,wei_nickname,open_id,headimgurl,login_time,ticket,tickettime,last_login) values ('$state','".addslashes($nickname)."','$openid','".$headimgurl."',now(),'".$ticJson->ticket."',now(),".time().")";
		$do->dealsql($sql);
		$do->dealsql("update sk_member_count set reg_count=reg_count+1 where time='".date("Y-m-d")."'");//每日统计新用户
		if($state!=""){
    		/*------------------------------------------------------------------------------------------------------*/
    		//用公共号发送信息
    		if($nickname=="")
    		    $weixintxt = "恭喜您又增加了一位神秘的匿名盟友";
    		else
    		    $weixintxt = "恭喜 ".$nickname." 成为了您的新盟友 ";
			$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$state."',2)");
			$do -> weixintishi($state,$weixintxt);
    		/*------------------------------------------------------------------------------------------------------*/
    		$sql = "update sk_member set pop=pop+1 where open_id='$state'";//修改上级的人气
    		$do->dealsql($sql);

//			$add_time=time();
//			$start_time=strtotime("2016-3-31");
//			$end_time=strtotime("2016-4-1");
//			if($state=="oc4cpuJr7ResBz04h46pT8MeSLUw"&&$add_time>=$start_time&&$add_time<=$end_time){
//				$sendid=$do->getone("select id from sk_erweima where openid='$state' and shenhe=1 limit 1");//获取店铺ID
//				$sendid=$sendid['id'];
//				$sendid=303;
//				$sn=substr(md5("hz".time().mt_rand(1,9999)),0,5);
//				$do->dealsql("insert into sk_coupon(sn,sendid,rec,xz,js,start_time,end_time,add_time,type,tid,is_repost) value ('$sn',$sendid,'$openid',9.99,9.89,$start_time,$end_time,$add_time,2,336,0)");
//				$txt="您收到一张洋仆淘优惠券";
//				$do->weixintishi($openid,$txt);
//				$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$txt','".time()."','".$openid."',2)");
//			}elseif($state=="oc4cpuJr7ResBz04h46pT8MeSLUw"&&$add_time<=$start_time){
//				$sendid=$do->getone("select id from sk_erweima where openid='$state' and shenhe=1 limit 1");//获取店铺ID
//				$sendid=$sendid['id'];
//				$sn=substr(md5("hz".time().mt_rand(1,9999)),0,5);
//				$start_time=strtotime("2016-3-19");
//				$end_time=strtotime("2016-4-1");
//				$add_time=time();
//				$do->dealsql("insert into sk_coupon(sn,sendid,rec,xz,js,start_time,end_time,add_time) value ('$sn',$sendid,'$openid',100,10,$start_time,$end_time,$add_time)");
//				$sn=substr(md5("hz".time().mt_rand(1,9999)),0,5);
//				$do->dealsql("insert into sk_coupon(sn,sendid,rec,xz,js,start_time,end_time,add_time) value ('$sn',$sendid,'$openid',100,10,$start_time,$end_time,$add_time)");
//			}
		}
		$_SESSION["wei_shouname"] = "";
		$_SESSION["phone"] = "";
		$_SESSION["address"] = "";
		$_SESSION["pid"] = $state;
		//$com->sendMsgJoin($baseToken->access_token, $state, $nickname);
	}else{
		//$sql = "update sk_member set logincount=logincount+1 where open_id='$openid'";
		//$do->dealsql($sql);
		/* if(strlen($resultArr[0]["pid"])==0){
			//openid登录人id
			//state上级
			$chanch = xiaoyan($openid,$state,$do);
			if($chanch == true){
				$sql = "update sk_member set wei_nickname='".$nickname."',headimgurl='".$headimgurl."' where open_id='$openid'";
				//$sql = "update sk_member set pid='$state',wei_nickname='".$nickname."',headimgurl='".$headimgurl."' where open_id='$openid'";
				$do->dealsql($sql);
				$sql = "update sk_member set pop=pop+1 where open_id='$state'";//修改上级的人气
				$do->dealsql($sql);
			}
		}else{ */
		$info=$do->getone("select last_login from sk_member where open_id='$openid'");
		if(date("Y-m-d",$info['last_login'])!=date("Y-m-d"))
			$do->dealsql("update sk_member_count set count=count+1 where time='".date("Y-m-d")."'");//每日统计访问量


	    $z_sql = "";
	    if($nickname!="")
	        $z_sql .= ",wei_nickname='".$nickname."'";
	    if($headimgurl!="")
	        $z_sql .= ",headimgurl='".$headimgurl."'";
		$sql = "update sk_member set logins=logins+1,last_login=".time().$z_sql." where open_id='$openid'";
		$do->dealsql($sql);
		/* } */
		$_SESSION["wei_shouname"] = $resultArr[0]["wei_shouname"];
		$_SESSION["phone"] = $resultArr[0]["phone"];
		$_SESSION["address"] = $resultArr[0]["address"];
		$_SESSION["pid"] = $resultArr[0]["pid"];
		$ticket = $resultArr[0]["ticket"];
		$tickettime = $resultArr[0]["tickettime"];
		$time1= strtotime($tickettime);
		$time2=time();
		$timd=$time2-$time1;
		if($timd>7000){
			$tickjson = $com->getTicket($token);
			$ticJson = json_decode($tickjson);
			$ticket = $ticJson->ticket;
			$sql = "update sk_member set ticket='".$ticket."',tickettime=now() where open_id='$openid'";
			$do->dealsql($sql);
		}
		
		$_SESSION["ticket"] = $ticket;
	}
	$sql = "select count(openid) as con from wei_cart where openid='$openid'";
	$resultArr = $do->selectsql($sql);
	$_SESSION["cartnum"] = $resultArr[0]["con"];
	
	echo "{\"success\":\"1\"}";
}
function xiaoyan($id, $comid, $do){
	if($id == $comid){
		return false;
	}
	$changeCH = true;//true可以修改，false不可修改
	$whtag = true;
	while($whtag){
		$sql = "select pid,open_id from sk_member where pid='$comid'";
		$chArr = $do->selectsql($sql);
		if(count($chArr)>0){
			$chid = $chArr[0]["open_id"];
			if($id == $chid){
				$changeCH = false;
				break;
			}else{
				$comid = $chid;
			}
		}else{
			$changeCH = true;
			break;
		}
	}
	return $changeCH;
}
?>