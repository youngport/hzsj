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
class MessageController extends ApiAdminbaseController {
	protected $message_model;
	protected $user_login;
	protected $role=0;
	protected $auth=array(2,5);
	
	function _initialize() {
		parent::_initialize();
		$this->message_model = D("Common/Message");
		$this->role=M("role_user")->where(array("user_id"=>get_current_admin_id(),'role_id'=>array("in",$this->auth)))->count();
	}

	function message_list(){
		$formget=array();
		$formget['_string']="rec=0 or rec='".$this->user_id."'";
		$join="left join hm_message_read on mid=id and uid=".$this->user_id;
		$order=array("add_time"=>'desc');
		$list=$this->message_model->field('id mid,title,excerpt,add_time,update_time,if(ISNULL(uid),0,1) is_read')->join($join)->where($formget)->order($order)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'商家消息','value'=>$list));
	}

	function message_info(){
		$where['id']=I('post.mid');
		$where['_string']="rec=0 or rec='".$this->user_id."'";
		$data = $this->message_model->field("add_time,title,content,update_time")->where($where)->find();
		if(!empty($data)&&($data['rec']==0||$data['rec']==$this->user_id)) {
			$where=array('mid' => I('id'), 'uid' =>$this->user_id);
			$count=M("message_read")->where($where)->count();
			$count>0||M("message_read")->add($where);
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'商家消息详情','value'=>$data));
	}
	function personal(){
		$moder = M("message","sk_");
		$map['rec']=I("rec");
		$map['type']=I("type");
		$data = $moder->where($map)->field("create_time,intro")->select();
		if (empty($data)) {
			$this->ajaxReturn(array('status'=>2,'message'=>'没有消息'));
		}else{
			$this->ajaxReturn(array('status'=>1,'message'=>'获取成功','value'=>$data));
		}
	}

	function send_message(){
		$member = M("member","sk_");
		$message = M("message","sk_");
		$map['pid'] = I('open_id');
		$add['intro'] = I('intro');
		$add['type'] = 2;
		$add['create_time'] = time();
		$user = I('user');
		$user_ = explode(",",$user); 
		$data = $member->where($map)->field("wei_nickname")->select();
		foreach ($user_ as $key => $value) {
			if (!in_array($value, $data)) {
				$this->ajaxReturn(array('status'=>2,'message'=>'盟友填写错误'));
			}else{
				$map_1['wei_nickname'] = $value;
				$data_ = $member->where($map_1)->field("open_id")->find();              
                $add['rec'] = $data_['open_id'];
				$data_[$key] = $message->add(); 
			}
		}
		if ($data) {
			$this->ajaxReturn(array('status'=>1,'message'=>'发送成功'));
		}else{
			$this->ajaxReturn(array('status'=>3,'message'=>'发送失败'));
		}	
	}
	function erweima(){
		// $uid['uid'] = I('user_id');
		// $role_store = M("RoleStore");
		// $sid = $role_store->field("sid")->where($uid)->select();
		// if (empty($sid)) {
		// 	$this->ajaxReturn(array('status'=>2,'message'=>'该用户没有店铺'));
		// }else{
		// 	foreach ($sid as $key => $value) {
		// 		$sid_ .= $value['sid'].',';
		// 	}	
		// 	$sid_ = substr($sid_, 0,strlen($sid_)-1);    
		// $id['id'] = array("in",$sid_);
	    $id['id'] = I('sid');
	    
		$moder_er = M("erweima","sk_");
		$data = $moder_er->field("dianpname,dianp_lxfs,dizhi")->where($id)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'店铺信息','value'=>$data));		
	}

}