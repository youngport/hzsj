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
class FeedbackController extends AdminbaseController {
	protected $Feedback_model;
	protected $auth=array(2,5);
	
	function _initialize() {
		parent::_initialize();
		$this->Feedback_model = M("Feedback");
	}
	function index(){
		$formget=array();
		$order=array("time"=>'desc');
		if(isset($_POST['status'])&&is_numeric($_POST['status']))
			$formget['status']=I("status");
		if(isset($_POST['type'])&&$_POST['type']!='')
			$formget['type']=I("type");
		if(isset($_POST['start_time'])&&$_POST['start_time']!='')
			$formget['time'][]=array("EGT",strtotime(I("start_time")));
		if(isset($_POST['end_time'])&&$_POST['end_time']!='')
			$formget['time'][]=array("ELT",strtotime(I("end_time")));
		$count=$this->Feedback_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->Feedback_model->limit($page->firstRow . ',' . $page->listRows)->join("left join hm_users b on uid=b.id")->field("hm_feedback.*,user_login,user_nicename")->where($formget)->order($order)->select();
		$this->assign("formget",I("post."));
		$this->assign("list",$list);
		$this->assign("Page", $page->show("Admin"));
		$this->assign("count",$count);
		$this->display();
	}

	function add(){
		$this->display();
	}

	function add_post(){
		if(IS_POST){
			if($this->Feedback_model->create()){
				$this->Feedback_model->uid=get_current_admin_id();
				$this->Feedback_model->time=time();
				if($this->Feedback_model->add())
					$this->success("反馈成功,工作人员稍后会通过消息或电话的形式联系您");
				else
					$this->error("反馈失败");
			}else
				$this->error($this->Feedback_model->getError());
		}
	}

	function delete(){
		$ids=I("id");
		$where['id']=array("in",$ids);
		$result=$this->Feedback_model->where($where)->delete();
		if($result!==false)
			$this->success("删除成功");
		else
			$this->error("删除失败");
	}
	function info(){
		if(IS_GET) {
			$data = $this->Feedback_model->join("hm_users on hm_feedback.uid=hm_users.id")->field("hm_feedback.*,hm_users.id uid,user_login,user_nicename")->where("hm_feedback.id=".I('id'))->find();
			$this->assign("data", $data);
			$this->display();
		}
	}
	function status_post(){
		if(IS_POST){
			$result=$this->Feedback_model->where("id=".I('id'))->setField("status",I("status"));
			if($result!==false)
				$this->success("修改成功",U("index"));
			else
				$this->error("修改失败");
		}
	}
}