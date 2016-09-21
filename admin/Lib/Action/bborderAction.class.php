<?php
//BB商城订单管理
class bborderAction extends  baseAction {
	
	//订单列表
	public function index(){
		$where = '1=1';
		if($_POST['rec']){
			$receiver = $_POST['rec'];
			$where.=" AND receiver LIKE '%" . $receiver . "%'";
			$this->assign('rec',$receiver);
		}
		if($_POST['tel']){
			$tel = $_POST['tel'];
			$where.=" AND tel LIKE '%" . $tel . "%'";
			$this->assign('tel',$tel);
		}
		if ($_POST['invoice_no']) {
			$invoice_no = $_POST['invoice_no'];
			$where .= " AND invoice_no LIKE '%" . $invoice_no . "%'";
			$this->assign('invoice_no', $invoice_no);
		}
		if ($_POST['ordersn']) {
			$ordersn = $_POST['ordersn'];
			$where .= " AND ordersn LIKE '%" . $ordersn . "%'";
			$this->assign('ordersn', $ordersn);
		}
		if ($_POST['time_start']) {
			$time_start_int = strtotime($_POST['time_start']);
			$where .= " AND add_time>='" . $time_start_int . "'";
			$this->assign('time_start', $time_start_int);
		}
		if ($_POST['time_end']) {
			$time_end_int = strtotime($_POST['time_end']);
			$where .= " AND add_time<='" . $time_end_int . "'";
			$this->assign('time_end', $time_end_int);
		}
		if ($_POST['status']>0){
			$status = intval($_POST['status']);
			$where .= " AND sk_bborders.status='" . $status . "'";
			$this->assign('status', $status);
		}



		$field="bu.phone,sk_bborders.*";
		$join="LEFT JOIN sk_bbuser bu ON sk_bborders.userid=bu.id";
		$count = M('bborders','sk_')->join($join)->where($where)->field($field)->order('add_time DESC')->count();
		import("ORG.Util.Page");
		$p = new Page($count, 20);
		$page = $p->show();
		$result = M('bborders','sk_')->join($join)->field($field)->where($where)->order('add_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

		/*导出excel表格*/
		if($_POST['excel']=='搜索结果导出Excel'){
			$resu = M('bborders','sk_')->join($join)->field($field)->where($where)->select();
			if( empty($resu) || !is_array($resu) ){
				$this->error('无数据可以导出！');
			}else{
				$str = "订单流水号\t订单所属账号\t购买人\t订单总价\t订单状态\t支付方式\t下单时间\t支付时间\t快递单号\t备注\t订单完成时间\n";
				$str = iconv ( 'utf-8', 'gbk', $str );
				foreach ($resu as $res){
					$order_status = '';
					switch ($res['status']){
						case '1';
							$order_status = '未付款';
							break;
						case '2';
							$order_status = '已付款';
							break;
						case '3';
							$order_status = '已发货';
							break;
						case '4';
							$order_status = '已完成';
							break;
					}
					$str .= iconv('utf-8','gbk',$res['ordersn'])."\t";
					$str .= iconv('utf-8','gbk',$res['phone'])."\t";
					$str .= iconv('utf-8','gbk',$res['receiver']).";\t";
					$str .= number_format($res['goodprice'],2)."\t";
					$str .= $order_status."\t";
					$str .= iconv('utf-8','gbk',$res['payment_name']).";\t";
					$str .= date('Y-m-d H:i:s',$res['add_time'])."\n";
					$str .= date('Y-m-d H:i:s',$res['pay_time'])."\n";
					$str .= iconv('utf-8','gbk',$res['invoice_no']).";\t";
					$str .= iconv('utf-8','gbk',$res['remark']).";\t";
					$str .= date('Y-m-d H:i:s',$res['finaltime'])."\n";
				}
				$filename = iconv('utf-8','gbk', 'BB商城订单信息').date ( 'Y-m-d_H-i-s' ). '.xls';
				echo exportExcel ( $filename, $str );
			}
		}

		$this->assign('page',$page);
		$this->assign('result',$result);
		$this->display();

	}
	//订单修改
	public function edit(){
		$id = $_GET['id'];//获取订单id
		$join = 'LEFT JOIN sk_goods g ON sk_bborder_good.goodid = g.id';
		$field = 'sk_bborder_good.*,g.id gid,g.good_name,g.img,g.price,g.pcode,g.guige';
		$goods = M('bborder_good','sk_')->join($join)->field($field)->where(array('sk_bborder_good.orderid'=>$id))->select();//获取订单中的商品列表
		$info = M('bborders','sk_')->where(array('id'=>$id))->find();

		$this->assign('goods',$goods);
		$this->assign('info',$info);
		$this->display();

	}
	//订单修改表单处理
	public function editchuli(){
		$res_in = '';
		$res_sh = '';



		if($_POST['goods_invoice_no']){
			$invoice_no = array_combine($_POST['goodsid'],$_POST['goods_invoice_no']);
			foreach ($invoice_no as $key=>$value){
				$where = array('id'=>$key);
				$data = array('invoice_no'=>trim($value));
				if($value != ''){
					$res_in = M('bborder_good','sk_')->where($where)->save($data);
				}
			}
		}

		if($_POST['goods_shipping_name']){
			$shipping_name = array_combine($_POST['goodsid'],$_POST['goods_shipping_name']);
			foreach ($shipping_name as $k=>$v){
				$res_sh = M('bborder_good','sk_')->query("UPDATE sk_bborder_good SET shipping_name = '$v' WHERE id='$k'");
			}
		}


		$orderid = $_POST['oid'];
		$data['status'] = $_POST['status'];
		$data['shipping_name'] = $_POST['shipping_name'];
		$data['invoice_no'] = $_POST['invoice_no'];
		$data['remark'] = $_POST['remark'];



		$result = M('bborders','sk_')->where(array('id'=>$orderid))->save($data);
		if($result || !$res_sh || $res_in){
			$this->success('订单修改成功');
		}else{
			$this->error('订单修改失败');
		}
	}
	//订单详情查看
	public function show(){
		$id = $_GET['id'];//获取订单id
		$join = 'LEFT JOIN sk_goods g ON sk_bborder_good.goodid = g.id';
		$field = 'sk_bborder_good.*,g.id gid,g.good_name,g.img,g.price,g.pcode,g.guige';
		$goods = M('bborder_good','sk_')->join($join)->field($field)->where(array('sk_bborder_good.orderid'=>$id))->select();//获取订单中的商品列表
		
		$join1 = "LEFT JOIN sk_bbuser bu ON sk_bborders.userid=bu.id";
		$field1 = "sk_bborders.*,bu.phone";
		$info = M('bborders','sk_')->join($join1)->where(array('sk_bborders.id'=>$id))->field($field1)->find();//获取订单详细信息

		$this->assign('goods',$goods);
		$this->assign('info',$info);
		$this->display();
	}
}
?>
