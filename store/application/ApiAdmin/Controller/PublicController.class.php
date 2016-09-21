<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
/**
 */
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class PublicController extends ApiAdminbaseController {

    function _initialize() {
		$post=I("post.");
		$key=$post['key'];
		unset($post['key']);
		if(!$this->_getSign($post,$key)){
			$this->ajaxReturn(array('status'=>0,'message'=>'KEY错误'));
		}
	}

    public function logout(){
    	session('ADMIN_ID',null);
    }

	public function dologin(){
		$data['username'] = I("post.username");
		if(empty($data['username'])){
			$this->ajaxReturn(array('status'=>2,'message'=>L('USERNAME_OR_EMAIL_EMPTY')));
		}
		$data['password'] = I("post.password");
		if(empty($data['password'])){
			$this->ajaxReturn(array('status'=>3,'message'=>L('PASSWORD_REQUIRED')));
		}
		$user = D("Common/Users");
		if(strpos($data['username'],"@")>0){//邮箱登陆
			$where['user_email']=$data['username'];
		}else{
			$where['user_login']=$data['username'];
		}

		$result = $user->where($where)->find();
		if(!empty($result) && $result['user_type']==1){
			if($result['user_pass'] == sp_password($data['password'])){

				$role_user_model=M("RoleUser");

				$role_user_join = C('DB_PREFIX').'role as b on a.role_id =b.id';

				$groups=$role_user_model->alias("a")->join($role_user_join)->where(array("user_id"=>$result["id"],"status"=>1))->getField("role_id",true);

				if( $result["id"]!=1 && ( empty($groups) || empty($result['user_status']) ) ){
					$this->ajaxReturn(array('status'=>6,'message'=>L('USE_DISABLED')));
				}
				$uid['uid'] = $result["id"];
				$role_store = M("RoleStore");
				$dp_num = $role_store->field("sid")->where($uid)->select();
				$info['dp_num'] = !empty($dp_num)?count($dp_num):0;
				//登入成功页面跳转
				$result['last_login_ip']=get_client_ip();
				$result['last_login_time']=time();
				$user->save($result);
				$info['user_id']=$result['id'];
				$info['user_nicename']=$result['user_nicename'];
				$this->ajaxReturn(array('status'=>1,'message'=>L('LOGIN_SUCCESS'),'value'=>$info));
			}else{
				$this->ajaxReturn(array('status'=>5,'message'=>L('PASSWORD_NOT_RIGHT')));
			}
		}else{
			$this->ajaxReturn(array('status'=>4,'message'=>L('USERNAME_NOT_EXIST')));
		}
	}
}