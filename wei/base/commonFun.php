<?php
class commonFun {
	public $appid = "wx8b17740e4ea78bf5";
	public $secret = "bbd06a32bdefc1a00536760eddd1721d";
	public $tokenurl = "http://m.hz41319.com/wei/main/token.php";
	function createBaseJson($resultArr, $col){
		$json = "[";
		$colArr = explode(",", $col);
		if(count($resultArr)>0){
			for($i=0; $i<count($resultArr); $i++){
				for($j=0; $j<count($colArr); $j++){
					if(count($colArr)==1){
						$json .= "{\"".$colArr[$j]."\":\"".$resultArr[$i][$colArr[$j]]."\"},";
					}else if($j==0){
						$json .= "{\"".$colArr[$j]."\":\"".$resultArr[$i][$colArr[$j]]."\",";
					}else if($j>0 && $j<count($colArr)-1){
						$json .= "\"".$colArr[$j]."\":\"".$resultArr[$i][$colArr[$j]]."\",";
					}else{
						$json .= "\"".$colArr[$j]."\":\"".$resultArr[$i][$colArr[$j]]."\"},";
					}
				}
			}
			$json = substr($json, 0, strlen($json)-1)."]";
		}else{
			$json = "[]";
		}
		return $json;
	}
	function createBaseSingleJson($resultArr, $col){
		$json = "";
		$colArr = explode(",", $col);
		if(count($resultArr)>0){
			for($j=0; $j<count($colArr); $j++){
				$value = $resultArr[0][$colArr[$j]];
				if($colArr[$j]=="photo" && $value!="") $value = $this->doman.$value;
				else if($colArr[$j]=="title_img" && $value!="") $value = $this->doman.$value;
				else if($colArr[$j]=="holder_photo" && $value!="") $value = $this->doman.$value;
	
				if($j==0){
					$json .= "{\"".$colArr[$j]."\":\"".$value."\",";
				}else if($j>0 && $j<count($colArr)-1){
					$json .= "\"".$colArr[$j]."\":\"".$value."\",";
				}else{
					$json .= "\"".$colArr[$j]."\":\"".$value."\"}";
				}
			}
		}else{
			$json = "{}";
		}
		return $json;
	}
	function createJson($count, $resultArr, $col){
		$json = "{\"total\":\"".$count."\", \"root\":[";
		$colArr = explode(",", $col);
		if(count($resultArr)>0){
			for($i=0; $i<count($resultArr); $i++){
				for($j=0; $j<count($colArr); $j++){
					if($colArr[$j]=="ID" && $resultArr[$i][$colArr[$j]]=="") continue;
					$value = $resultArr[$i][$colArr[$j]];
					if($colArr[$j]=="photo" && $value!="") $value = $this->doman.$value;
					else if($colArr[$j]=="title_img" && $value!="") $value = $this->doman.$value;
					else if($colArr[$j]=="holder_photo" && $value!="") $value = $this->doman.$value;
						
					if($j==0){
						$json .= "{\"".$colArr[$j]."\":\"".$value."\",";
					}else if($j>0 && $j<count($colArr)-1){
						$json .= "\"".$colArr[$j]."\":\"".$value."\",";
					}else{
						$json .= "\"".$colArr[$j]."\":\"".$value."\"},";
					}
				}
			}
			$json = substr($json, 0, strlen($json)-1)."]}";
		}else{
			$json = "{\"total\":\"0\",\"root\":[]}";
		}
		return $json;
	}
	//公共方法微信公众账号 start
	function submitGet($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		return $result;
	}
	//获取登录认证时的access token
	function getLoginAccessToken($code){
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->secret."&code=$code&grant_type=authorization_code";
		return $this->submitGet($url);
	}
	//获取access token
	function getWeiLogin(){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->secret;
		return $this->submitGet($url);
	}
	//获取用户基本信息
	function getUserBaseInf($access_token, $openid){
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
		return $this->submitGet($url);
	}
	function getTicket($access_token){
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
		return $this->submitGet($url);
	}
	function shareFun($redirect_uri, $accesstoken, $ticket){
		$timestamp = time();
		$nonceStr = rand(100000,999999);
		$Parameters = array();
// 		$json = $this->getTicket($accesstoken);
// 		$ticketJson = json_decode($json);
		//===============下面数组 生成SING 使用=====================
		$Parameters['url'] = $redirect_uri;
		$Parameters['timestamp'] = "$timestamp";
		$Parameters['noncestr'] = "$nonceStr";
		$Parameters['jsapi_ticket'] = $ticket;
		// 生成 SING
		$addrSign = $this->genSha1Sign($Parameters);
		
		$retArr = array();
		$retArr['appid'] = "$this->appid";
		$retArr['timestamp'] = "$timestamp";
		$retArr['noncestr'] = "$nonceStr";
		$retArr['signature'] = "$addrSign";
		return $retArr;
	}
	function sendMsgMoBan($access_token, $openid){
		$time=date('Y-m-d H:i:s',time());
		$template=array(
			'touser'=>$openid,
			'template_id'=>"en7J-g17hRJuPdXmIeRXwAeWLza8ZMSrY3JCdwV4K7A",    //模板的id
			'url'=>"http://m.hz41319.com/wei/main/gui.php",
			'topcolor'=>"#FF0000",
			'data'=>array(
				'first'=>array('value'=>"规则通知",'color'=>"#000000"),    //函数传参过来的name
				'keyword1'=>array('value'=>"万家乐商城",'color'=>'#00008B'),        //函数传参过来的zu
				'keyword2'=>array('value'=>"平台直销规则",'color'=>'#00008B'),   //时间
				'remark'=>array('value'=>"点击详情查看规则",'color'=>'#FF0000'),//函数传参过来的ramain
			)
		);
		$json_template=json_encode($template);
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$json_template);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	function sendMsgYongjin($access_token, $openid, $yongjin){
		$time=date('Y-m-d H:i:s',time());
		$template=array(
				'touser'=>$openid,
				'template_id'=>"LKJygeguPw6_OougsXjZjMwxjmYESV6zCZnkCf4H9mc",    //模板的id
				'url'=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=&connect_redirect=1#wechat_redirect",
				'topcolor'=>"#FF0000",
				'data'=>array(
						'first'=>array('value'=>"您获得了一笔新的佣金。",'color'=>"#000000"),    //函数传参过来的name
						'keyword1'=>array('value'=>$yongjin,'color'=>'#00008B'),        //函数传参过来的zu
						'keyword2'=>array('value'=>$time,'color'=>'#00008B'),   //时间
						'remark'=>array('value'=>"请进入店铺查看详情。",'color'=>'#FF0000'),//函数传参过来的ramain
				)
		);
		$json_template=json_encode($template);
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$json_template);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	function sendMsgJoin($access_token, $openid, $wei_nickname){
		$time=date('Y-m-d H:i:s',time());
		$template=array(
				'touser'=>$openid,
				'template_id'=>"en7J-g17hRJuPdXmIeRXwAeWLza8ZMSrY3JCdwV4K7A",    //模板的id
				'url'=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8b17740e4ea78bf5&redirect_uri=http%3A%2F%2Fm.hz41319.com%2Fwei%2Flogin.php&response_type=code&scope=snsapi_userinfo&state=&connect_redirect=1#wechat_redirect",
				'topcolor'=>"#FF0000",
				'data'=>array(
						'first'=>array('value'=>"账户开通提醒通知",'color'=>"#000000"),    //函数传参过来的name
						'keyword1'=>array('value'=>$wei_nickname,'color'=>'#00008B'),        //函数传参过来的zu
						'keyword2'=>array('value'=>$time,'color'=>'#00008B'),   //时间
						'remark'=>array('value'=>"请进入店铺查看详情。",'color'=>'#FF0000'),//函数传参过来的ramain
				)
		);
		$json_template=json_encode($template);
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$json_template);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	function sendImg($access_token, $openid, $imgpath){
		$time=date('Y-m-d H:i:s',time());
		$template=array(
				'touser'=>$openid,
				'template_id'=>"bbd06a32bdefc1a00536760eddd1721d",    //模板的id
				'url'=>"http://m.hz41319.com/wei/main/user/showQrcode.php?imgpath=".$imgpath,
				'topcolor'=>"#FF0000",
				'data'=>array(
						'first'=>array('value'=>"推广二维码生成通知",'color'=>"#000000"),    //函数传参过来的name
						'keynote1'=>array('value'=>"万家乐商城",'color'=>'#00008B'),        //函数传参过来的zu
						'keynote2'=>array('value'=>$time,'color'=>'#00008B'),   //时间
						'remark'=>array('value'=>"点击链接查看详情。",'color'=>'#FF0000'),//函数传参过来的ramain
				)
		);
		$json_template=json_encode($template);
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$json_template);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	//公共方法微信公众账号 end
	
	//主流业务 start
	//获取首页主分类
	function mainMenu($do){
		$sql = "select id,name from sk_items_cate where pid='5'";
		return $do->selectsql($sql);
	}
	function mpMenu($do){
		$sql = "select id,name from sk_mingp_cate where status=1";
		return $do->selectsql($sql);
	}
	//获取首页导航
	function indexDaohang($do){
		
	}
	//查询商品的总数量
	function getTotalGoodsNum($do){
		$sql = "select count(id) as con from sk_goods";
		return $do->selectsql($sql);
	}
	//根据商品id获取商品详情
	function getGoodDet($id, $do){
		$sql = "select s.id,s.cate_id,s.fh_type,s.gx_id,s.good_name,s.img,s.fp_price,s.price,s.guige,s.desc,s.kucun,s.hits,s.remark,s.orgprice,s.huodongjia,s.zhekou,s.status,s.dhjifen,s.goodtype,s.xiaoliang,s.canyuhd,s.goods_erweima,s.yieldly,s.period,s.suit,s.effect from sk_goods s where id=$id";
		return $do->selectsql($sql);
	}
	function getGoodAdd($id,$do,$cis){
		$sql = "UPDATE sk_goods SET hits = '".($cis + 1)."' WHERE id = ".$id;
		$do->selectsql($sql);
	}
	//根据用户openid获取用户基本信息
	function getUserDet($openid, $do){
		$sql = "select wei_username,shouyi,wei_phone,pay_code,yin_username,yin_code,brank,brank_adda,
				brank_addb,brank_zhi,login_time,pop,join_time,hyerweima,jointag,headimgurl,pid from sk_member where open_id='$openid'";
		return $do->selectsql($sql);
	}
	//主流业务 end
	function logWd($er){
		$of = fopen('dir.txt','a');//创建并打开dir.txt
		fwrite($of,$er."<-->");//把执行文件的结果写入txt文件
		fclose($of);
	}
	//创建签名SHA1
	function genSha1Sign($Parameters){
		$signPars = '';
		ksort($Parameters);
		foreach($Parameters as $k => $v) {
			if("" != $v && "sign" != $k) {
				if($signPars == '')
					$signPars .= $k . "=" . $v;
				else
					$signPars .=  "&". $k . "=" . $v;
			}
		}
		//$signPars = http_build_query($Parameters);
		$sign = SHA1($signPars);
		$Parameters['sign'] = $sign;
		return $sign;
	}
}
?>