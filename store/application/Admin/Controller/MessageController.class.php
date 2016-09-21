<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class MessageController extends AdminbaseController {
	protected $message_model;
	protected $user_login;
	protected $role=0;
	protected $auth=array(2,5);
	
	function _initialize() {
		parent::_initialize();
		$this->message_model = D("Common/Message");
		$this->role=M("role_user")->where(array("user_id"=>get_current_admin_id(),'role_id'=>array("in",$this->auth)))->count();
		$this->user_login=M("Users")->getFieldByid(get_current_admin_id(),"user_login");
	}
	function index(){
		$my=I("my");
		$formget=array();
		if($my==1) {
			if($this->role>0)
				$formget['rec'] = get_current_admin_id();
			else
				$formget['rec']=array('neq',0);
			$join="left join hm_message_read on mid=id";
		}else {
			$formget['rec'] = 0;
			$join="left join hm_message_read on mid=id and uid=".get_current_admin_id();
		}
		$order=array("uid"=>'asc',"add_time"=>'desc');
		if(isset($_POST['title'])&&$_POST['title']!='')
			$formget['title']=array("like","%".I("title")."%");
		$count=$this->message_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->message_model->limit($page->firstRow . ',' . $page->listRows)->join($join)->where($formget)->order($order)->select();
		$this->assign("formget",I("post."));
		$this->assign("list",$list);
		$this->assign("Page", $page->show("Admin"));
		$this->assign("count",$count);
		$this->assign("my",$my);
		$this->display();
	}

	function add(){
		$this->display();
	}

	function add_post(){
		if(IS_POST){
			if($this->message_model->create()){
				if($this->message_model->add())
					$this->success("推送成功");
				else
					$this->error("推送失败");
			}else
				$this->error($this->message_model->getError());
		}
	}

	public function edit(){
		$id=intval(I("id"));
		$data=$this->message_model->find($id);
		$this->assign('data',$data);
		$this->display();
	}

	public function edit_post(){
		if(IS_POST){
			if($this->message_model->create()){
				$this->message_model->update_time=time();
				if($this->message_model->save())
					$this->success("修改成功",U("index"));
				else
					$this->error("修改失败");
			}else
				$this->error($this->message_model->getError());
		}
	}

	function delete(){
		$ids=I("id");
		$where['id']=array("in",$ids);
		$result=$this->message_model->where($where)->delete();
		if($result!==false)
			$this->success("删除成功");
		else
			$this->error("删除失败");
	}
	function info(){
		if(IS_GET) {
			$data = $this->message_model->find(I('id'));
			if(!empty($data)&&($data['rec']==0||$data['rec']==get_current_admin_id())) {
				$where=array('mid' => I('id'), 'uid' => get_current_admin_id());
				$count=M("message_read")->where($where)->count();
				$count>0||M("message_read")->add($where);
			}
			$this->assign("data", $data);
			$this->display();
		}
	}
}