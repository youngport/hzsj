<?php
include '../../base/condbwei.php';
include '../../base/commonFun.php';


$do = new condbwei();
$com = new commonFun();

session_start();
$open_id=$_SESSION['openid'];
function selPat($openid, $do){
    $sql = "select * from sk_member where open_id='$openid'";
    $resultArr = $do->selectsql($sql);
    return $resultArr;
}

$order_id = intval($_POST["order_id"]);
$order_sn = addslashes($_POST["order_sn"]);

$sql = "select kd.zhuangtai,o.buyer_id,(select wei_nickname from sk_member where open_id = o.buyer_id) wei_nickname,o.status,o.order_sn,o.is_member from sk_orders o left join sk_kuaidi kd on kd.name = o.shipping_name and kd.danhao = o.invoice_no where o.order_id='$order_id' and o.buyer_id='$open_id'";
$arr_kdzt = $do->selectsql($sql);
$out_trade_no = $arr_kdzt[0]['order_sn'];
$shangopenid = $arr_kdzt[0]['buyer_id'];
if($arr_kdzt[0]['status']==3){//可以先确认收货

    $sql = "insert into sk_jifen(jifen,laiyuan,shijian,openid) values(3,9,".time().",'$shangopenid')";
    $do->selectsql($sql);
    $sql = "update sk_member set jifen = jifen+3 where open_id='$shangopenid'";
    $do->selectsql($sql);
    //利益分配----------------------------------------------------------------------------------------------------
    $totalPrice = 0;//商品分成的价格
    $sql = "select k.goods_id,k.quantity,k.profit as fp_price,g.goodtype,o.buyer_id,o.order_sn from sk_orders o left join sk_order_goods k on k.order_id=o.order_id left join sk_goods g on g.id=k.goods_id where o.order_sn='$out_trade_no'";
    //------o.order_sn 订单表id --- g.fp_price分配利润价格  --- g.goodtype普通/代理/LED商品————————————
    $resuArr = $do->selectsql($sql);
    for($k=0;$k<count($resuArr);$k++){
        if($resuArr[$k]['goodtype'] == 0 || $resuArr[$k]['goodtype'] == 2){
            if(floatval($resuArr[$k]['fp_price'])>0)
                $totalPrice += floatval($resuArr[$k]['fp_price'])*intval($resuArr[$k]['quantity']);
        }if($resuArr[$k]['goodtype'] == 2){//该购买的商品不是LED产品就算上去——————————
            $havaDaili = true;//只要代理产品了，即修改客户身份
        }
    }
    $i = 1;
    $resultArr = selPat($shangopenid, $do);//获取上级的基本信息
    if(count($resultArr)==0) break;
    $shangopenid = $resultArr[0]["pid"];

    if($havaDaili) {
        $do->selectsql("update sk_member set jointag = 2,join_time=now() where open_id='" . $arr_kdzt[0]['buyer_id'] . "'");//修改成为店家
    }

    if($shangopenid != "" && $arr_kdzt[0]['is_member'] == 0){
        $tru_fal = false;
        $cunchu = $do -> sql_cunchu($arr_kdzt[0]['buyer_id']);//调用存储 获取顶端店家
        if($cunchu[0]['pid'] == "" || $cunchu[0]['joingat'] != 2){//最顶端不是店家则执行
            $tru_fal = true;
        }
        while($i<=3){
            $resultArr = selPat($shangopenid, $do);//获取上级的基本信息
            if(count($resultArr)==0) break;
                $shangopenid = $resultArr[0]["pid"];
                $fenpei = 0;
                /* if($tru_fal){
                    if($i == 1){
                        $fenpei = $totalPrice * 0.7;
                    }else if($i == 2){
                        $fenpei = $totalPrice * 0.2;
                    }else if($i == 3){
                        $fenpei = $totalPrice * 0.1;
                    }
                }else{ */
                    if($i == 1){
                        $fenpei = $totalPrice * 0.5;
                    }else if($i == 2){
                        $fenpei = $totalPrice * 0.2;
                    }else if($i == 3){
                        $fenpei = $totalPrice * 0.1;
                    }
                /* } */
                if(!$tru_fal && $i == 1 && ($totalPrice * 0.2) > 0 && $cunchu[0]['pid'] != $arr_kdzt[0]['buyer_id']){//店铺商家占有利益分成 2成
                    $sql = "insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$cunchu[0]['pid']."','".($totalPrice * 0.2)."' ,now(),'2',".time().")";
                    $do -> dealsql($sql);
                    //$do -> weixintishi($cunchu[0]['pid'],$arr_kdzt[0]['wei_nickname']." 购买了商品，获取店家收益 ".($totalPrice * 0.2));
                    $name = $do -> selectsql("select wei_nickname from sk_member where open_id = '".$cunchu[0]['pid']."'");
                    $weixintxt="亲爱的 ".$name[0]['wei_nickname']."，".$arr_kdzt[0]['wei_nickname']." 购买了商城宝贝，您获取了店家收益 ".($totalPrice * 0.2)." 元，分享美好的事物与朋友，得到的喜悦胜于佣金，然并卵，佣金也是美好的事物（小编醉了）";
                    $do -> weixintishi($cunchu[0]['pid'],$weixintxt);
                    $sql = "update sk_member set shouyi = shouyi + ".($totalPrice * 0.2)." where open_id='".$cunchu[0]['pid']."'";
                    $do -> dealsql($sql);
                    $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$cunchu[0]['pid']."',2)");

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
                    $weixintxt="亲爱的 ".$resultArr[0]["wei_nickname"]."， ".$arr_kdzt[0]['wei_nickname']."  购买了商城宝贝，您获取会员收益  ".$fenpei." 元。有人问我，成为和众会员并获得收益是种怎样的感受？我只想说，you can you up ，no can 问客服~";
                    $do -> weixintishi($resultArr[0]["open_id"],$weixintxt);
                    //$do -> weixintishi($resultArr[0]["open_id"],$arr_kdzt[0]['wei_nickname']." 购买了商品，获取收益 ".$fenpei);
                    $sql = "update sk_member set shouyi = shouyi + ".$fenpei." where open_id='".$resultArr[0]["open_id"]."'";
                    $do->dealsql($sql);
                    $openid = $resultArr[0]["open_id"];
                    $do->dealsql("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$openid."',2)");
                }else{
                    $i = 100;//跳出循环
                }
            $i++;
        }
    }
    
    
    
    //利益分配-----------------------------------------------------------
    
    $sql = "update sk_orders set status='4' where order_id='$order_id'";
    $do->dealsql($sql);
    $sql = "update wei_pop set duitag='0' where order_sn='$out_trade_no'";
    $do->dealsql($sql);
    echo "{\"success\":\"1\"}";
}

?>