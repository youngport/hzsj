<?php

class mcouponAction extends baseAction
{
	public function index()
	{
		$mcoupon = M('mcoupon');
		if(isset($_GET['open_id'])&&$_GET['open_id']!=''){
			$where['open_id']=$this->_get('open_id');
		}
		if(isset($_GET['start_time'])&&$_GET['start_time']!=''){
			$where['start_time']=array('EGT',strtotime($this->_get('start_time')));
		}
		if(isset($_GET['end_time'])&&$_GET['end_time']!=''){
			$where['end_time']=array('ELT',strtotime($this->_get('end_time')));
		}
		import("ORG.Util.Page");
		$count = $mcoupon->where($where)->count();
		$p = new Page($count,20);
		$list = $mcoupon->where($where)->order(" id desc")->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->assign('get',$_GET);
		$this->display();
	}


	function add()
	{
		if($_POST) {
			$mcoupon = M("mcoupon");
			$message = M("message");
			$where['open_id']=$this->_post("open_id");
			$where['jointag']=2;
			$count=M('member')->where($where)->count();
			if($count==0){
				$this->error("该用户不存在或不是店铺会员");
			}
			if($mcoupon->create()){
				$mcoupon->start_time=strtotime($mcoupon->start_time);
				$mcoupon->end_time=strtotime($mcoupon->end_time);
				$mcoupon->add_time=time();
				$mcoupon->y_money=$mcoupon->money;
                                $mcoupon->add_name=$_SESSION['admin_info']['user_name'];
				$add_time = date('Y-m-d',$mcoupon->start_time);
				$end_time = date('Y-m-d',$mcoupon->end_time);
				$rec = $mcoupon->open_id;
				$js = $mcoupon->money;
                                $res=$mcoupon->add();
                                //echo  $mcoupon->getLastSql(); exit;
				if($res){
                                    
					$text = "恭喜您获取一张".$js."元的现金券，有效时间为".$add_time."到".$end_time."快去使用吧";
				 $this -> weixintishi($rec,$text );
				$message->query("insert into sk_message(intro,create_time,rec,type) value ('$text','".time()."','$rec',2)");													
					$this->success("添加成功");    
				}
			}
		}else{
			$this->display();
		}
	}

	function edit()
	{
		$mcoupon = M("mcoupon");
		if($_POST) {
			$where['open_id']=$this->_post("open_id");
			$where['jointag']=2;
			$count=M('member')->where($where)->count();
			if($count==0){
				$this->error("该用户不存在或不是店铺会员");
			}
			if($mcoupon->create()){
				$mcoupon->start_time=strtotime($mcoupon->start_time);
				$mcoupon->end_time=strtotime($mcoupon->end_time);
				if($mcoupon->save()){
					$this->success("修改成功");
				}
			}
		}else{
			$id=intval($_GET['id']);
			$info=$mcoupon->find($id);
			$this->assign("info",$info);
			$this->display();
		}
	}

	function delete()
    {
		$coupon = M('mcoupon');
		if(isset($_POST['id'])&&is_array($_POST['id'])){
			$ids = implode(',',$_POST['id']);
			$coupon->delete($ids);
			$this->success(L('operation_success'));
		}
    }
function weixintishi($openidd,$weixintishi_text){
        $weixin_token = M('weixin_token');
        $token_txt = $weixin_token -> select();
        $token_url = $token_txt[0]['token'];
        if((time() - $token_txt[0]['time'])>7100){//数据库里 微信token 已过期  需要跟新
            $ch = curl_init();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $return = curl_exec ( $ch );
            curl_close ( $ch );
            $output_array = json_decode($return,true);
            $token_url = $output_array['access_token'];
            $whe['id'] = $token_txt[0]['id'];
            $data_up['time'] = time();
            $data_up['token'] = $token_url;
            $weixin_token -> where($whe) -> save($data_up);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token_url;
        $data = "{
                        \"touser\": \"".$openidd."\",
                        \"msgtype\": \"text\",
                        \"text\": {
                            \"content\": \"".htmlspecialchars($weixintishi_text)."\"
                        }
                    }";
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        $output_array = json_decode($return,true);
    }
}
?>