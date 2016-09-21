<?php
session_start();
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/
include '../../../base/condbwei.php';
include '../../../base/commonFun.php';
	include_once("./log_.php");
	include_once("../WxPayPubHelper/WxPayPubHelper.php");

	$do = new condbwei();
	$com = new commonFun();
    //使用通用通知接口
	$notify = new Notify_pub();

	//存储微信的回调
	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
	$notify->saveData($xml);
	
	//验证签名，并回应微信。
	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
	if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
	
	//以log文件形式记录回调信息
	$log_ = new Log_();
	$log_name="./notify_url.log";//log文件路径
	//$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");

	if($notify->checkSign() == TRUE)
	{
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			//$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
			header("Location:".$cxtPath."/wei/main/order/myorder.php?type=0");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			//$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
			header("Location:".$cxtPath."/wei/main/order/myorder.php?type=0");
		}
		else{
			$openid = $_SESSION["openid"];//积分记录是从哪个盟友中来的
			//此处应该更新一下订单状态，商户自行增删操作
			$out_trade_no = $notify->data["out_trade_no"];
			$access_token = $notify->data["attach"];
			//修改订单状态
			$sql = "update sk_orders set status='1',payment_name='微信支付',payment_code='$out_trade_no',pay_time=".time().",order_time=now() where order_sn='$out_trade_no'";
			$do->dealsql($sql);
			
			//检测有无团购标记
			
			$sql="SELECT goods_id,(select buyer_id from sk_orders where sk_orders.order_id = sk_order_goods.order_id) openid FROM `sk_order_goods` WHERE order_id=(select order_id from sk_orders where order_sn='$out_trade_no') and goods_expired=2";
			//$sql="SELECT goods_id FROM `sk_order_goods` WHERE order_id=(select order_id from sk_orders where order_sn='$out_trade_no') and goods_expired=2";
			$gsid=$do->selectsql($sql);
			if(!empty($gsid)){
				foreach($gsid as $v){
					$sql="select id,count(*) count from sk_gs where gid='".$v['goods_id']."' and start_time<".time()." and end_time>".time()." and status=1";
					$gs=$do->getone($sql);
					if($gs['count']>0){
						$sql="update sk_gs_jl set status=1 where gsid=".$gs['id']." and openid='".$v['openid']."'";
						$do->dealsql($sql);
						$sql="update sk_gs set number=number+1 where id=".$gs['id'];
						$do->dealsql($sql);
					}
				}
			}
			//团购结束



			//$typeo = $_SESSION["typeo"];//获取是否是从店家led产品二维码进入————————————————————
			//$totalPrice = $notify->data["total_fee"];
			$havaDaili = false;//false没有代理商品，true有代理商品
			$totalPrice = 0;//商品分成的价格
			//$shangpin3 = false;//是否买了LED产品—————————
			$sql = "select k.goods_id,k.quantity,g.fp_price,g.goodtype,o.is_member from sk_orders o left join sk_order_goods k on k.order_id=o.order_id left join sk_goods g on g.id=k.goods_id where o.order_sn='$out_trade_no'";
			//------o.order_sn 订单表id --- g.fp_price分配利润价格  --- g.goodtype普通/代理/LED商品————————————
			$resuArr = $do->selectsql($sql);
			for($k=0;$k<count($resuArr);$k++){					
				if($resuArr[$k]['goodtype'] == 1 ){//该购买的商品不是LED产品就算上去——————————
					$totalPrice += floatval($resuArr[$k]['fp_price'])*intval($resuArr[$k]['quantity']);
					$havaDaili = true;//只要代理产品了，即修改客户身份
					$_SESSION["tiao_zhongxin"] = 1;//因为加入会员 所以跳转到个人中心
				}
			}
			//是否会员商品，重新设置分配利润价格
			if ( $resuArr[0]['is_member'] ==1) {
					$goods_id = $resuArr[0]['goods_id'];
					$sql = "select fp_price from sk_user_goods where gid = $goods_id";
					$ug_price = $do->getone($sql);
					if ($ug_price['fp_price']>0) {
						$totalPrice = floatval($ug_price['fp_price']);
						$havaDaili = true;//只要代理产品了，即修改客户身份
						$_SESSION["tiao_zhongxin"] = 1;//因为加入会员 所以跳转到个人中心
					}

				}	
			//获取当时利润分配
			$do->selectsql("update sk_order_goods set profit=(select fp_price from sk_goods where sk_order_goods.goods_id=sk_goods.id) where order_id = (select order_id from sk_orders where order_sn = '$out_trade_no')");
			$i = 1;
			$sql = "select m.pid,m.open_id,m.wei_nickname,o.order_id,o.is_shouyi,o.goods_amount,o.order_amount,o.couponid,o.mcouponid,o.discount,o.is_member from sk_orders o left join sk_member m on o.buyer_id=m.open_id where o.order_sn='$out_trade_no'";
			$resuArr = $do->selectsql($sql);
			
            //是否收益抵扣，更新收益余额
				if ($resuArr[0]['is_shouyi'] ==1) {
					$sql = "select shouyi from sk_member where open_id='".$resuArr[0]['open_id']."'";
                  
                    $ug_shouyi = $do->getone($sql);
					$ug_shouyi['shouyi'] -= $resuArr[0]['order_amount'];
					
					if ($ug_shouyi['shouyi'] < 0) {
						
						$ug_shouyi['shouyi'] = 0 ;
					}
					$do->dealsql("update sk_member set shouyi='".$ug_shouyi['shouyi']."' where open_id='".$resuArr[0]['open_id']."'");                    					
				}

			if($resuArr[0]['couponid']>0){
				$cs=$do->getone("select count(*) count from sk_coupon where id=".$resuArr[0]['couponid']." and rec='".$resuArr[0]['open_id']."'");
				if($cs['count']>0) {
					$do->dealsql("update sk_coupon set status=2 where id=" . $resuArr[0]['couponid']);//修改优惠券状态
				}
			}elseif($resuArr[0]['mcouponid']>0){
				$do->dealsql("update sk_mcoupon set money=money-".$resuArr[0]['discount']." where id=".$resuArr[0]['mcouponid']);//修改优惠券状态
			}
			$shangopenid = $resuArr[0]["pid"];//获取购买人上级的openid
			if(!$havaDaili) {
				if($resuArr[0]['is_member']==1){
					$sql = "update sk_member set jointag='1',join_time=now() where open_id='".$resuArr[0]["open_id"]."'";
					$do->dealsql($sql);
					$sql="insert into sk_message(intro,create_time,rec,type) value ('欢迎您的加入，恭喜您成为会员','".time()."','".$resuArr[0]["open_id"]."',2)";
					$do->dealsql($sql);
				}
				if($resuArr[0]["pid"]!='') {
					$weixintxt = "您的盟友 " . $resuArr[0]["wei_nickname"] . "，购买了商品 ，总价  " . $resuArr[0]["goods_amount"] . " 元";
					$do->weixintishi($resuArr[0]["pid"], $weixintxt);
					$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','" . time() . "','" . $resuArr[0]["pid"] . "',2)");
				}
			}
			if($havaDaili==true){//用户购买了代理商品，修改用户信息
				$sql = "select jointag from sk_member where open_id='".$resuArr[0]["open_id"]."'";
				$ckArr = $do->selectsql($sql);
				if($ckArr[0]["jointag"]!=2){
					$sql = "update sk_member set jointag='1',join_time=now() where open_id='".$resuArr[0]["open_id"]."'";
					$do->dealsql($sql);
					if($ckArr[0]["jointag"]==0){
						$sql="insert into sk_message(intro,create_time,rec,type) value ('欢迎您的加入，恭喜您成为会员','".time()."','".$resuArr[0]["open_id"]."',2)";
						$do->dealsql($sql);

						$i = 1;

						if($shangopenid != ""){
						    $tru_fal = true;
						    $cunchu = $do -> sql_cunchu($resuArr[0]['open_id']);//调用存储 获取顶端店家
						    if($cunchu[0]['pid'] == "" || $cunchu[0]['joingat'] != 2){//最顶端不是店家则执行
						        $tru_fal = false;
						    }
						    while($i<=3){
						        $resultArr = selPat($shangopenid, $do);//获取上级的基本信息
						        if(count($resultArr)==0) break;
						        $shangopenid = $resultArr[0]["pid"];
						        $fenpei = 0;
						            if($i == 1){
						                $fenpei = $totalPrice * 0.5;
						            }else if($i == 2){
						                $fenpei = $totalPrice * 0.2;
						            }else if($i == 3){
						                $fenpei = $totalPrice * 0.1;
						            }
						        if( $tru_fal && $i == 1 && ($totalPrice * 0.2) > 0){//店铺商家占有利益分成 2成
						            $sql = "insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$cunchu[0]['pid']."','".($totalPrice * 0.2)."' ,now(),'2',".time().")";
						            $do -> dealsql($sql);
						            $name = $do -> selectsql("select wei_nickname from sk_member where open_id = '".$cunchu[0]['pid']."'");
									$weixintxt="亲爱的 ".$name[0]['wei_nickname']."，".$resuArr[0]['wei_nickname']." 加入会员，您获取了店家收益 ".($totalPrice * 0.2)." 元，分享美好的事物与朋友，得到的喜悦胜于佣金，然并卵，佣金也是美好的事物（小编醉了）";
						            $do -> weixintishi($cunchu[0]['pid'],$weixintxt);
									$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$cunchu[0]['pid']."',2)");
						            $sql = "update sk_member set shouyi = shouyi + ".($totalPrice * 0.2)." where open_id='".$cunchu[0]['pid']."'";
						            $do -> dealsql($sql);

									$agent=$do->getone("select agentid,dianpname from sk_role_store inner join sk_erweima on pid=openid where pid='".$cunchu[0]['pid']."'");//查看是否有代理人
									if(!empty($agent)){
										$do -> dealsql("insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$agent['agentid']."',1,now(),'2',".time().")");
										$weixintxt="您所代理的'".$agent['dianpname']."'成交了一笔订单,获得收益1元";
										$do -> weixintishi($agent['agentid'],$weixintxt);
										$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$agent['agentid']."',2)");
										$do -> dealsql("update sk_member set shouyi = shouyi +1 where open_id='".$agent['agentid']."'");
									}
						        }

						        $sql = "select id from wei_pop where order_sn='$out_trade_no' and openid='".$resultArr[0]["open_id"]."'";
						        $havaArr = $do->selectsql($sql);
						        $dp_fencheng = 1;//普通三级盟友分成占一份
						        if($resultArr[0]["open_id"]==$cunchu[0]['pid']){
						            $dp_fencheng = 2;//店铺商家在三级盟友内  分成占两份
						        }

						        if(count($havaArr) < $dp_fencheng && $fenpei > 0 && ($resultArr[0]["jointag"]=="1"||$resultArr[0]['jointag']==2)){//判断上级是否有资格参与分成 且上级还未参与该订单的分成
						            $sql = "insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$resultArr[0]["open_id"]."','$fenpei' ,now(),'2',".time().")";
						            $do->dealsql($sql);
									$weixintxt="亲爱的 ".$resultArr[0]["wei_nickname"]."， ".$resuArr[0]['wei_nickname']."  加入会员，您获取会员收益  ".$fenpei." 元。有人问我，成为洋仆淘会员并获得收益是种怎样的感受？我只想说，you can you up ，no can 问客服~";
						            $do -> weixintishi($resultArr[0]["open_id"],$weixintxt);
									$do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$resultArr[0]["open_id"]."',2)");
						            $sql = "update sk_member set shouyi = shouyi + ".$fenpei." where open_id='".$resultArr[0]["open_id"]."'";
						            $do->dealsql($sql);
						            $openid = $resultArr[0]["open_id"];
						        }else{
						            $i = 100;//跳出循环
						        }
						        $i++;
						    }
						}
					}
				}
			}


			while($i<=3){
				$resultArr = selPat($shangopenid, $do);//获取上级的基本信息
				if(count($resultArr)==0) break;
    				$shangopenid = $resultArr[0]["pid"];
    				$fenprice = 0;

    				if($i == 1){
    					$fenprice = $totalPrice * 0.5;
    				}else if($i == 2){
    					$fenprice = $totalPrice * 0.2;
    				}else if($i == 3){
    					$fenprice = $totalPrice * 0.1;
    				}
    				$pop = intval($resultArr[0]["pop"]);
    				$leiorder = floatval($resultArr[0]["leiorder"]);
    				$sql = "select id from wei_pop where order_sn='$out_trade_no' and openid='".$resultArr[0]["open_id"]."'";
    				$havaArr = $do->selectsql($sql);
    				if(count($havaArr)==0 && $fenprice > 0 && ($resultArr[0]["jointag"]=="1"||$resultArr[0]['jointag']==2)){//判断上级是否有资格参与分成 且上级还未参与该订单的分成
    					$openid = $resultArr[0]["open_id"];
    				}else{
    				    return;
    				}
				$i++;
			}
		}
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
	function selPat($openid, $do){
		$sql = "select * from sk_member where open_id='$openid'";
		$resultArr = $do->selectsql($sql);
		return $resultArr;
	}
?>