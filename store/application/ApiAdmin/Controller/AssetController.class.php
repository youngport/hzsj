<?php
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class AssetController extends ApiAdminbaseController {
	protected $asset_model;
	
	function _initialize() {
		parent::_initialize();
		$this->asset_model = D("Common/Asset");
	}

	function asset_list(){
		$formget['status']=1; 
		$order=array("time"=>'desc');
		$count=$this->asset_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->asset_model->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		$map['user_id'] = I("user_id");
		$download = D("Common/Download");
		foreach ($list as $key => $value) {
			$map['asset_id'] = $value['id'];
			$res = $download->where($map)->find();
			if (empty($res)) {
				$list[$key]['buff'] = '0';
			}else{
				$list[$key]['buff'] = "1";
			}
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'附件列表','data'=>$list));
	}

	function download(){
		$add['user_id'] = I("user_id");
		$add['asset_id'] = I("asset_id");
		$add['status'] = 1;
		$download = D("Common/Download");
		$res = $download->add($add);
		if ($res) {
			$this->ajaxReturn(array('status'=>1,'message'=>'状态已更改'));
		}else{
			$this->ajaxReturn(array('status'=>2,'message'=>'状态更改失败'));
		}
	}
	

	function upload_version(){
		$map['code'] = 0;
		$moder = D("Common/Version");
		$data = $moder->where($map)->field("code,message")->find();
		if (empty($data)) {
			$this->ajaxReturn(array('status'=>2,'message'=>'已经是最新版本'));
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'版本信息','data'=>$data));
	}

}