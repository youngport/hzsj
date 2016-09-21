<?php
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class TassetController extends ApiAdminbaseController {
	protected $tasset_model;

	function _initialize() {
		parent::_initialize();
		$this->tasset_model = D("Common/tasset");
	}

	function tasset_list(){
		$formget['status']=1;
		$order=array("time"=>'desc');
		$count=$this->tasset_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->tasset_model->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'培训资源列表','data'=>$list));
	}

	function video_list(){
		$formget['status']=1; 
		$formget['cate']=3;
		$order=array("time"=>'desc');
		$count=$this->tasset_model->where($formget)->count();
		$page = $this->page($count, 6);
		$list=$this->tasset_model->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		if (empty($list)) {
			$this->ajaxReturn(array('status'=>2,'message'=>'视频列表为空'));
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'视频列表','data'=>$list));
	}

}