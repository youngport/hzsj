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
class CouponController extends AdminbaseController {
	protected $Coupon_model;
	protected $store_model;
	protected $user_model;
	protected $role=0;
	protected $info;
	protected $auth=array(2,5);
	
	function _initialize() {
		parent::_initialize();
		$this->store_model = M("erweima","sk_");
		$this->Coupon_model = M("coupon","sk_");
		$this->user_model = M("member","sk_");
		$this->role=M("role_user")->where(array("user_id"=>get_current_admin_id(),'role_id'=>array("in",$this->auth)))->count();
		if($this->role>0) {
			$this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" . get_current_admin_id())->getField("id", true);//如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
			if(empty($this->info)){
				$this->info=array("");
			}
		}
	}
	function index(){
		$formget=array();
		if($this->role>0)
			$formget['sendid']=array("in",$this->info);
		$order=array("add_time"=>'desc');
		$count=$this->Coupon_model->where($formget)->count();
		$page = $this->page($count, 20);
		$list=$this->Coupon_model->field("*,(select dianpname from sk_erweima where id=sendid) dianpname,(select wei_nickname from sk_member where open_id=rec) wei_nickname")->limit($page->firstRow . ',' . $page->listRows)->where($formget)->order($order)->select();
		$this->assign("formget",I("post."));
		$this->assign("list",$list);
		$this->assign("Page", $page->show("Admin"));
		$this->assign("count",$count);
		$this->display();
	}

	public function edit(){
		$id=intval(I("id"));
		$data=$this->Coupon_model->find($id);
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
			if($this->Coupon_model->create()){
				if($this->Coupon_model->rec=='') {
					$this->Coupon_model->recpid = $this->store_model->getFieldById($this->Coupon_model->sendid, "openid");
					$this->Coupon_model->rec='';
				}else
					$this->Coupon_model->recpid='';
				$this->Coupon_model->update_time=time();
				if($this->Coupon_model->save())
					$this->success("修改成功",U("index"));
				else
					$this->error("修改失败");
			}else
				$this->error($this->Coupon_model->getError());
		}
	}

	function delete(){
		$ids=I("id");
		$where['id']=array("in",$ids);
		if($this->role>0)
			$formget['sendid']=array("in",array_values($this->info));
		$result=$this->Coupon_model->where($where)->delete();
		if($result!==false)
			$this->success("删除成功");
		else
			$this->error("删除失败");
	}
}