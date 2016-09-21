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
class UsermessageController extends AdminbaseController {
	protected $Usermessage_model;
	protected $store_model;
	protected $user_model;
	protected $role=0;
	protected $info;
	protected $auth=array(2,5);
	
	function _initialize() {
		parent::_initialize();
		$this->store_model = M("erweima","sk_");
		$this->Usermessage_model = M("message","sk_");
		$this->user_model = M("member","sk_");
		$this->role=M("role_user")->where(array("user_id"=>get_current_admin_id(),'role_id'=>array("in",$this->auth)))->count();
		if($this->role>0) {
			$this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" . get_current_admin_id())->getField("id,openid", true);//如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
			if(empty($this->info)){
				$this->info=array("");
			}
		}
	}
	function index(){
		$formget=array();
		if($this->role>0)
			$formget['sendid']=array("in",array_keys($this->info));
		$order=array("create_time"=>'desc');
		if(isset($_POST['title'])&&$_POST['title']!='')
			$formget['title']=array("like","%".I("title")."%");
		$count=$this->Usermessage_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->Usermessage_model->field("*,(select dianpname from sk_erweima where id=sendid) dianpname,(select wei_nickname from sk_member where open_id=rec) wei_nickname")->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		$this->assign("formget",I("post."));
		$this->assign("list",$list);
		$this->assign("Page", $page->show("Admin"));
		$this->assign("count",$count);
		$this->display();
	}

	function add(){
		$where['shenhe']=1;
		if($this->role>0)
			$where['id']=array("in",array_keys($this->info));
		$store=$this->store_model->field("id,dianpname")->where($where)->select();
		$this->assign("store",$store);
		$this->display();
	}
	function partner($id=0){
		$id=$id==0?I("id",0,"intval"):$id;
		if($this->role>0&&!array_key_exists($id,$this->info)){
			$this->error("非法访问");
		}
		$where['pid']=$this->store_model->getFieldById($id,"openid");
		$where['wei_nickname']=array('neq','');
		$data=$this->user_model->field("open_id,wei_nickname")->where($where)->select();
		if(IS_AJAX)
			echo empty($data)?0:json_encode($data);
		else
			return empty($data)?0:$data;
	}
	function add_post(){
		if(IS_POST){
			if($this->role>0&&!array_key_exists(I("sendid"),$this->info)){
				$this->error("非法访问");
			}
			if($this->Usermessage_model->create()){
				if($this->Usermessage_model->rec=='')
					$this->Usermessage_model->recpid=$this->store_model->getFieldById($this->Usermessage_model->sendid, "openid");
				$this->Usermessage_model->create_time=time();
				if($this->Usermessage_model->add())
					$this->success("推送成功");
				else
					$this->error("推送失败");
			}else
				$this->error($this->Usermessage_model->getError());
		}
	}

	public function edit(){
		$id=intval(I("id"));
		$data=$this->Usermessage_model->find($id);
		if($this->role>0&&!array_key_exists($data['sendid'],$this->info)){
			$this->error("非法访问");
		}
		$where['shenhe']=1;
		$store=$this->store_model->field("id,dianpname")->where($where)->select();
		$this->assign("store",$store);
		$rec=$this->partner($data['sendid']);
		$this->assign("rec",$rec);
		$this->assign('data',$data);
		$this->display();
	}

	public function edit_post(){
		if(IS_POST){
			if($this->role>0&&!array_key_exists(I("sendid"),$this->info)){
				$this->error("非法访问");
			}
			if($this->Usermessage_model->create()){
				if($this->Usermessage_model->rec=='') {
					$this->Usermessage_model->recpid = $this->store_model->getFieldById($this->Usermessage_model->sendid, "openid");
					$this->Usermessage_model->rec='';
				}else
					$this->Usermessage_model->recpid='';
				$this->Usermessage_model->update_time=time();
				if($this->Usermessage_model->save())
					$this->success("修改成功",U("index"));
				else
					$this->error("修改失败");
			}else
				$this->error($this->Usermessage_model->getError());
		}
	}

	function delete(){
		$ids=I("id");
		$where['id']=array("in",$ids);
		if($this->role>0)
			$formget['sendid']=array("in",array_values($this->info));
		$result=$this->Usermessage_model->where($where)->delete();
		if($result!==false)
			$this->success("删除成功");
		else
			$this->error("删除失败");
	}
	function info(){
		if(IS_GET) {
			$data = $this->Usermessage_model->find(I('id'));
			if($this->role>0&&!array_key_exists($data['sendid'],$this->info)){
				$this->error("非法访问");
			}
			$this->assign("data", $data);
			$this->display();
		}
	}
}