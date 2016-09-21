<?php header("Content-Type:text/html;charset=utf-8"); ?> 
<?  
    include './base/condbwei.php';
    
    $do = new condbwei();
 	//订阅成功后，收到首次推送信息是在5~10分钟之间，在能被5分钟整除的时间点上，0分..5分..10分..15分....
	$param=$_POST['param'];
	
	function decodeUnicode($str)
	{
	    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
	        create_function(
	            '$matches',
	            'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
	        ),
	        $str);
	}
	
	try{
    	$param_arr = json_decode($param, true);
		//$param包含了文档指定的信息，...这里保存您的快递信息,$param的格式与订阅时指定的格式一致
		$param_arr['lastResult']['com']=addslashes($param_arr['lastResult']['com']);
		$param_arr['lastResult']['nu']=addslashes($param_arr['lastResult']['nu']);
		$param_arr['lastResult']['state']=intval($param_arr['lastResult']['state']);
	    if($param_arr['lastResult']['nu']!=""){
    	    $keyou = "select count(*) cou from sk_kuaidi where name='".$param_arr['lastResult']['com']."' and danhao='".$param_arr['lastResult']['nu']."'";
    	    $keyouArr = $do->selectsql($keyou);
    	    if($keyouArr[0]['cou']>0)
    	       $where_ext = "update sk_kuaidi set xinxi='".decodeUnicode(json_encode($param_arr['lastResult']['data']))."', zhuangtai = ".$param_arr['lastResult']['state']." where name='".$param_arr['lastResult']['com']."' and danhao='".$param_arr['lastResult']['nu']."'";
    	    else
    	       $where_ext = "insert into sk_kuaidi ( name, danhao, xinxi, zhuangtai) values ( '".$param_arr['lastResult']['com']."','".$param_arr['lastResult']['nu']."','".decodeUnicode(json_encode($param_arr['lastResult']['data']))."',".$param_arr['lastResult']['state'].")";
	       $do->selectsql($where_ext);
	       if($param_arr['lastResult']['state']==3){
	           $openid = $do->getone("select buyer_id from sk_orders a left join sk_order_goods b on a.order_id=b.order_id where  b.shipping_name='".$param_arr['lastResult']['com']."' and b.invoice_no=".$param_arr['lastResult']['nu']);
	            //$do -> weixintishi($openid[0]['buyer_id'],"您的快递 ".$param_arr['lastResult']['nu']." 已签收,请到商城确认收货");

			   if(!empty($openid)) {
				   $name = $do->getone("select wei_nickname from sk_member where open_id = '" . $openid['buyer_id'] . "'");
				   $weixintxt = "亲爱的 " . $name['wei_nickname'] . "，您的快递（单号 " . $param_arr['lastResult']['nu'] . "）已签收,快快到商城点击【确认收货】吧，获取积分兑换礼品哦~";
				   $do->weixintishi($openid['buyer_id'], $weixintxt);
				   $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','" . time() . "','" . $openid['buyer_id'] . "',2)");
			   }
	       }
	    }
		echo  '{"result":"true","returnCode":"200","message":"成功"}';
		//要返回成功（格式与订阅时指定的格式一致），不返回成功就代表失败，没有这个30分钟以后会重推
	} catch(Exception $e){
		echo  '{"result":"false","returnCode":"500","message":"失败"}';
		//保存失败，返回失败信息，30分钟以后会重推
	}
	   
?>