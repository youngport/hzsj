<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class UsermessageController extends ApiAdminbaseController {
	protected $Usermessage_model;
	protected $role=0;
	protected $info;
	protected $auth=array(2,5);
	protected $sid=0;
	protected $open_id='';
	function _initialize() {
		parent::_initialize();
		$this->Usermessage_model = M("message","sk_");
		$this->role=M("role_user")->where(array("user_id"=>$this->user_id,'role_id'=>array("in",$this->auth)))->count();
		$this->sid=I('post.sid',0,'intval');
		$this->open_id=I("post.open_id");
		if($this->role>0) {
			$this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" .$this->user_id)->getField("id,openid", true);//如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
			if(empty($this->info)){
				$this->info=array("");
			}
		}
	}
	function message_list(){
		$p=I('post.p',1,'intval');
		$limit=I('post.limit',20,'intval');
		if($this->role>0&&!array_key_exists($this->sid,$this->info)) {
			$this->ajaxReturn(array('status'=>99,'message'=>'没有权限访问'));
		}
		$formget['sendid'] =$this->sid;
		$formget['type']=2;
		$order=array("create_time"=>'desc');
		if(isset($_POST['title'])&&$_POST['title']!='')
			$formget['title']=array("like","%".I("post.title")."%");
		if(isset($_POST['title'])&&$_POST['title']!='')
			$formget['title']=array("like","%".I("post.title")."%");
		$list=$this->Usermessage_model->field("id mid,intro,create_time,status,(select wei_nickname from sk_member where open_id=rec) wei_nickname")->page("$p,$limit")->where($formget)->order($order)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'用户消息','value'=>$list));
	}

	function add_post(){
		if($this->role>0&&!array_key_exists($this->sid,$this->info)){
			$this->ajaxReturn(array('status'=>99,'message'=>'没有权限访问'));
		}
		$_POST['sendid']=$this->sid;
		$_POST['type']=2;
		$_POST['status']=0;
		$_POST['create_time']=time();
		if($data=$this->Usermessage_model->create()){
			$store_model=M("erweima","sk_");
			$user_model=M("member","sk_");
			$pid=$store_model->getFieldById($this->sid, "openid");
			if($this->Usermessage_model->rec==="0") {
				$this->Usermessage_model->recpid= $pid;
				if($this->Usermessage_model->add()!==false)
					$status=array('status'=>1,'message'=>'全部发送成功');
				else
					$status=array('status'=>3,'message'=>'全部发送失败');
				$this->ajaxReturn($status);
			}elseif($this->Usermessage_model->rec!=''){
				$rec=explode(",",$this->Usermessage_model->rec);
				$status=array();
				foreach($rec as $v){
					$count=$user_model->where(array("open_id"=>$v,"pid"=>$pid))->count();
					if($count==1){
						$data['rec']=$v;
						if($this->Usermessage_model->add($data)!==false) {
							$status[]=array('rec'=>$rec,'status' => 1, 'message' => '发送成功');
						}else{
							$status[]=array('rec'=>$rec,'status' => 3, 'message' => '发送失败');
						}
					}else{
						$status[]=array('rec'=>$rec,'status' => 4, 'message' => '用户不存在或不是盟友');
					}
				}
				$this->ajaxReturn(array('message'=>'多人发送','value'=>$status));
			}else{
				$this->ajaxReturn(array('status'=>2,'message'=>'rec为空'));
			}
		}else
			$this->ajaxReturn(array('status'=>5,'message'=>'非法数据'));
	}

	function appeal(){
		$add['openid'] = I("open_id");
		$add['wei_id'] = I("wei_nickname");
		$add['number'] = I("tel");
		$add['create_time'] = time();
		$appeal_order = M("appeal","sk_");
		if ($appeal_order->add($add)) {
			$this->ajaxReturn(array('status'=>1,'message'=>'提交成功'));
		}else{
			$this->ajaxReturn(array('status'=>2,'message'=>'提交失败'));
		}
    }
    function appeal_list(){
    	$map = array();
    	$this->open_id = 'oc4cpuBC-RLhADvsUE5xWwAkeJgk';
		$data = appeal_even($this->open_id,array(),$map);
		dump($data);exit;
		$rel = array();
		unset($data);
		$this->ajaxReturn(array('status'=>1,'message'=>'成功','value'=>$rel));
    }
    function seller_exits(){
       $user['rec'] = $this->open_id;
       $mall['rec'] = "";
       $seller['uid'] = $this->sid;
       $data['user'] = $this->Usermessage_model->field("intro,create_time")->where($user)->select();
       $data['mall'] = $this->Usermessage_model->field("intro,create_time")->where($mall)->select();
       $seller_message = D("Common/Message_read");
       $data['seller'] = $seller_message->join("left join hm_message as h on hm_message_read.mid=h.id")->field("h.add_time,h.content")->where($seller)->select();
       $this->ajaxReturn(array('status'=>1,'message'=>'商家消息','value'=>$data));
    }
}