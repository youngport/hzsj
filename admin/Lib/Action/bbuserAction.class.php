<?php
//BB商城会员列表
class bbuserAction extends  baseAction
{

	//会员列表
	public function index()
	{

		$huiyuan = M('bbuser', 'sk_')->select();
		$this->assign('huiyuan', $huiyuan);
		$this->display();

	}

	//会员修改视图
	public function edit()
	{
		$uid = $_GET['id'];
		$geren = M('bbuser', 'sk_')->where(array('id' => $uid))->find();
		$this->assign('geren', $geren);
		$this->display();
	}

	//会员信息修改处理
	public function editcheck()
	{


		$uid = $_POST['id'];
		$data['status'] = $_POST['status'];
		$data['lock'] = $_POST['lock'];

		$result = M('bbuser', 'sk_')->where(array('id' => $uid))->save($data);
		if ($result) {
			$this->success('修改成功!', U('bbuser/index'));

		} else {
			$this->error('修改失败!');
		}

	}


	//会员详情页
	public function details()
	{
		$res=array();
		if($_GET['id']){
			$res = M('bbuser','sk_')->field()->where('id='.$_GET['id'])->find();
		}
		$this->assign('res',$res);
		$this->display();
	}









}
?>
