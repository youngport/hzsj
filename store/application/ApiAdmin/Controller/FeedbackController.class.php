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
class FeedbackController extends ApiAdminbaseController {
	protected $Feedback_model;
	
	function _initialize() {
		parent::_initialize();
		$this->Feedback_model = M("Feedback");
	}

	function add_post(){
		if(IS_POST){
			if($this->Feedback_model->create()){
				if(!(is_numeric($this->Feedback_model->type)&&in_array($this->Feedback_model->type,array(0,1,2,3)))){
					$this->ajaxReturn(array('status'=>2,'message'=>"类型错误"));
				}
				if($this->Feedback_model->content==''){
					$this->ajaxReturn(array('status'=>3,'message'=>"内容不能为空"));
				}
				if($this->Feedback_model->tel==''){
					$this->ajaxReturn(array('status'=>3,'message'=>"手机号不能为空"));
				}
				$this->Feedback_model->uid=$this->user_id;
				$this->Feedback_model->time=time();
				if($this->Feedback_model->add())
					$this->ajaxReturn(array('status'=>1,'message'=>"反馈成功,工作人员稍后会通过消息或电话的形式联系您"));
				else
					$this->ajaxReturn(array('status'=>4,'message'=>"反馈失败,请稍后再试"));
			}else
				$this->ajaxReturn(array('status'=>98,'message'=>"数据对象错误"));
		}
	}
}