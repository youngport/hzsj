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
class StoreadController extends ApiAdminbaseController {
	protected $store_model,$video_model;
	protected $role=0;
	protected $info;
	protected $auth=array(2,5);//用户归属组检查
	protected $ca=array();//权限不检查
	protected $open_id='';
	protected $sid=0;
	function _initialize() {
		parent::_initialize();
		$this->store_model = M("erweima","sk_");
		$this->video_model = M("video","sk_");
		$this->role=M("role_user")->where(array("user_id"=>$this->user_id,'role_id'=>array("in",$this->auth)))->count();
		$this->open_id=I("open_id");
		$this->sid=I("sid",0,"intval");
		if($this->role>0) {
			$this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" .$this->user_id)->getField("id,openid", true);//如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
			if(empty($this->info)){
				$this->info=array("");
			}
			if(!in_array(ACTION_NAME,$this->ca)) {
				if (($this->open_id==''||!in_array($this->open_id,$this->info))&&($this->sid==0||!array_key_exists($this->sid,$this->info))) {
					$this->ajaxReturn(array('status'=>99,'message'=>'没有权限访问'));
				}
			}
		}
	}
	function device_ad_list(){
		$mac=$this->store_model->getFieldById($this->sid,"mac");
		$where['vi_rec']=array(array("eq",""),array("eq",$mac),"or");
		$where['vi_youxiao']=1;
		$list=$this->video_model->field("vi_id,vi_name,vi_url,vi_paixu")->where($where)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'终端广告视频信息',"value"=>$list));
	}
	function device_ad_add(){
		$exts=array("flv","mp4","avi","rmvb","mkv");
		if($data=$this->video_model->create()){
			$data['sendid']=$this->sid;
			$data['rec']=$this->store_model->getFieldById($this->sid,"mac");
			$data['vi_youxiao']=0;
			if($data['vi_name']==''){
				$this->ajaxReturn(array('status'=>2,'message'=>'请填写视频名称'));
			}
			if($data['vi_url']==''){
				$this->ajaxReturn(array('status'=>3,'message'=>'请填写视频路径'));
			}
			$ext=trim(strrchr($data['vi_url'],"."),".");
			if(!in_array($ext,$exts)){
				$this->ajaxReturn(array('status'=>4,'message'=>'视频格式非法'));
			}
			if($data['rec']==''){
				$this->ajaxReturn(array('status'=>5,'message'=>'找不到该终端的mac地址'));
			}
			if($this->video_model->add($data)){
				$this->ajaxReturn(array('status'=>1,'message'=>'提交成功,审核成功后将会上线'));
			}else{
				$this->ajaxReturn(array('status'=>6,'message'=>'提交失败'));
			}
		}else{
			$this->ajaxReturn(array('status'=>98,'message'=>'数据对象错误'));
		}
	}
}