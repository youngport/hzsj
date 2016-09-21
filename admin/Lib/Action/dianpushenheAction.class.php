<?php

class dianpushenheAction extends baseAction
{
	public function index()
	{
		$erweima = M('erweima_shenhe');
		if(isset($_GET['dianpname'])&&$_GET['dianpname']!=''){
			$where['dianpname']=array("like","%".$this->_get('dianpname')."%");
		}
		if(isset($_GET['xxdizhi'])&&$_GET['xxdizhi']!=''){
			$where['xxdizhi']=array("like","%".$this->_get('xxdizhi')."%");
		}
		if(isset($_GET['openid'])&&$_GET['openid']!=''){
			$where['openid']=$this->_get('openid');
		}
		if(isset($_GET['start_time'])&&$_GET['start_time']!=''){
			$where['start_time']=array('EGT',strtotime($this->_get('start_time')));
		}
		if(isset($_GET['end_time'])&&$_GET['end_time']!=''){
			$where['end_time']=array('ELT',strtotime($this->_get('end_time')));
		}
		if(isset($_GET['status'])&&is_numeric($_GET['status'])){
			$where['status']=intval($_GET['status']);
		}
		import("ORG.Util.Page");
		$count = $erweima->where($where)->count();
		$p = new Page($count,20);
		$list = $erweima->where($where)->limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		$this->assign('page',$page);
		$this->assign('list',$list);
		$this->assign('get',$_GET);
		$this->display();
	}

	public function info(){
		$erweima=M('erweima_shenhe');
		$data=$erweima->find(intval($_GET['id']));
		$this->assign('data',$data);
		$this->display();
	}
	public function edit(){
		$erweima=M('erweima_shenhe');
		$id=intval($_POST['id']);
		$status=$_POST['status'];
		$data=$erweima->field("sid id,weixin_account,zuobiao,xxdizhi,dianpname,dianp_lxfs,dianp_img,rmac,zd_bianhao")->find($id);
		if(!empty($data)&&$data['status']==0){
			if($status==1){
				if($data['dianp_img']=='')unset($data['dianp_img']);
				$result=M('erweima')->save($data);
				if($result!==false){
					$erweima->where("id=$id")->setField('status',1);
				}
			}elseif($status==2){
				$erweima->where("id=$id")->setField('status',2);
			}
			$this->success("审核修改成功");
		}else{
			$this->error("数据错误");
		}
	}
	function delete()
    {
		$coupon = M('erweima_shenhe');
		if(isset($_POST['id'])&&is_array($_POST['id'])){
			$ids = implode(',',$_POST['id']);
			$coupon->delete($ids);
			$this->success(L('operation_success'));
		}
    }

}
?>