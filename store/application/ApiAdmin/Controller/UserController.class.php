<?php
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class UserController extends ApiAdminbaseController{
	protected $users_model;
	
	function _initialize() {
		parent::_initialize();
		$this->users_model = D("Common/Users");
	}

	function userinfo(){
		$user=$this->users_model->field("user_nicename")->where(array("id"=>$this->user_id))->find();
		$this->ajaxReturn(array('status'=>1,'message'=>'用户信息','value'=>$user));
	}
	
	function userinfo_post(){
		$_POST['id']=$this->user_id;
		if ($this->users_model->create()) {
			if ($this->users_model->field("id,user_nicename")->save()!==false) {
				$this->ajaxReturn(array('status'=>1,'message'=>'修改成功'));
			} else {
				$this->ajaxReturn(array('status'=>2,'message'=>'修改失败'));
			}
		} else {
			$this->ajaxReturn(array('status'=>98,'message'=>'数据对象错误'));
		}
	}
	function paypass_pst(){
		$pass=I("pass");
		$len=strlen($pass);
		if($pass==''||$len<6||$len>10){
			$this->ajaxReturn(array('status'=>2,'message'=>'支付密码至少6个到10个字符'));
		}
		$data['id']=$this->user_id;
		$data['user_paypass']=array("neq",'');
		$count=$this->users_model->where($data)->count();
		if($count>0){
			$this->ajaxReturn(array('status'=>3,'message'=>'已经设置过支付密码'));
		}
		$data['user_paypass']=sp_password($pass);
		$result=$this->users_model->save($data);
		if($result!==false){
			$this->ajaxReturn(array('status'=>1,'message'=>'设置成功'));
		}else{
			$this->ajaxReturn(array('status'=>4,'message'=>'设置失败'));
		}
	}
	function paypass_edit_post(){
		$old_pass=I("old_pass");
		$new_pass=I("new_pass");
		$data['id']=$this->user_id;
		$data['user_paypass']=sp_password($old_pass);
		$count=$this->users_model->where($data)->count();
		if($count<1){
			$this->ajaxReturn(array('status'=>2,'message'=>'密码错误'));
		}
		$len=strlen($new_pass);
		if($new_pass==''||$len<6||$len>10){
			$this->ajaxReturn(array('status'=>3,'message'=>'支付密码至少6个到10个字符'));
		}
		$data['user_paypass']=sp_password($new_pass);
		$result=$this->users_model->save($data);
		if($result!==false){
			$this->ajaxReturn(array('status'=>1,'message'=>'修改成功'));
		}else{
			$this->ajaxReturn(array('status'=>4,'message'=>'修改失败'));
		}
	}
	function wallet(){
		$waller_model=M("wallet");
		$pass=I("pass");
		if($pass==''){
			$this->ajaxReturn(array('status'=>2,'message'=>'请填写密码'));
		}
		$paypass=$this->users_model->getFieldById($this->user_id,"user_paypass");
		if($paypass==''){
			$this->ajaxReturn(array('status'=>3,'message'=>'请先设置密码'));
		}elseif($paypass!=sp_password($pass)){
			$this->ajaxReturn(array('status'=>4,'message'=>'密码错误'));
		}
		$field=array("bank","bank_name","bank_number","alipay","update_time");
		$where['user_id']=$this->user_id;
		$type=I("type",0,"intval");
		if($type==1)
			$field=array("bank","bank_name","bank_number");
		elseif($type==2)
			$field=array("alipay");
		$data=$waller_model->where($where)->field($field)->find();
		$this->ajaxReturn(array('status'=>1,'message'=>'钱包信息','value'=>$data));
	}

	function wallet_edit(){
		$waller_model=M("wallet");
		$type=I("type",0,"intval");$pass=I("pass");
		$pass=I("pass");
		if($pass==''){
			$this->ajaxReturn(array('status'=>2,'message'=>'请填写密码'));
		}
		$paypass=$this->users_model->getFieldById($this->user_id,"user_paypass");
		if($paypass==''){
			$this->ajaxReturn(array('status'=>3,'message'=>'请先设置密码'));
		}elseif($paypass!=sp_password($pass)){
			$this->ajaxReturn(array('status'=>4,'message'=>'密码错误'));
		}
		if($waller_model->create()){
			if($type==1) {
				$field=array("bank","bank_name","bank_number","update_time");
			}elseif($type==2) {
				$field=array("alipay","update_time");
			}else{
				$field=array("bank","bank_name","bank_number","alipay","update_time");
			}
			if($type==1||$type==0) {
				if ($waller_model->bank == "")
					$this->ajaxReturn(array('status' => 5, 'message' => '银行不能为空'));
				if ($waller_model->bank_name == "")
					$this->ajaxReturn(array('status' => 6, 'message' => '银行卡持有人不能为空'));
				if ($waller_model->bank_number == "")
					$this->ajaxReturn(array('status' => 7, 'message' => '银行卡卡号不能为空'));
			}
			if($type==2||$type==0) {
				if ($waller_model->alipay == "")
					$this->ajaxReturn(array('status' => 8, 'message' => '支付宝账号不能为空'));
			}
			$where['user_id']=$this->user_id;
			$waller_model->update_time=time();
			if($waller_model->where($where)->count()>0){
				$result=$waller_model->field($field)->where($where)->save();
			}else{
				$field[]="user_id";
				$result=$waller_model->field($field)->add();
			}
			if($result!==false){
				$this->ajaxReturn(array('status'=>1,'message'=>'修改成功'));
			}else{
				$this->ajaxReturn(array('status'=>9,'message'=>'修改失败'));
			}
		}else{
			$this->ajaxReturn(array('status'=>98,'message'=>'数据对象错误'));
		}
	}
	function password_post(){
		if (IS_POST) {
			if(empty($_POST['old_password'])){
				$this->error("原始密码不能为空！");
			}
			if(empty($_POST['password'])){
				$this->error("新密码不能为空！");
			}
			$user_obj = D("Common/Users");
			$uid=get_current_admin_id();
			$admin=$user_obj->where(array("id"=>$uid))->find();
			$old_password=$_POST['old_password'];
			$password=$_POST['password'];
			if(sp_password($old_password)==$admin['user_pass']){
				if($_POST['password']==$_POST['repassword']){
					if($admin['user_pass']==sp_password($password)){
						$this->error("新密码不能和原始密码相同！");
					}else{
						$data['user_pass']=sp_password($password);
						$data['id']=$uid;
						$r=$user_obj->save($data);
						if ($r!==false) {
							$this->success("修改成功！");
						} else {
							$this->error("修改失败！");
						}
					}
				}else{
					$this->error("密码输入不一致！");
				}

			}else{
				$this->error("原始密码不正确！");
			}
		}
	}
}