<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/3/27
 * Time: 13:01
 */
Vendor('PHPExcel.PHPExcel');

class ordersAction extends  baseAction {
    public  function  index(){       
        $items_mod = D('orders');
        //搜索
        $where = '1=1';
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $invoice_no = isset($_GET['invoice_no']) && trim($_GET['invoice_no']) ? trim($_GET['invoice_no']) : '';
        $time_start = isset($_GET['time_start']) && trim($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = isset($_GET['time_end']) && trim($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $status = isset($_GET['status']) ? intval($_GET['status']) : '-1';
        $fenlei = isset($_GET['fenlei']) ? intval($_GET['fenlei']) : '-1';
        $order = isset($_GET['order']) && trim($_GET['order']) ? trim($_GET['order']) : '';
        $sort = isset($_GET['sort']) && trim($_GET['sort']) ? trim($_GET['sort']) : 'desc';
        $buyer_id = isset($_GET['openid_txt']) && trim($_GET['openid_txt']) ? trim($_GET['openid_txt']) : '';
        $buyer_name = isset($_GET['goumairen']) && trim($_GET['goumairen']) ? trim($_GET['goumairen']) : '';
        if ($keyword) {
            $where .= " AND order_sn LIKE '%" . $keyword . "%'";
            $this->assign('keyword', $keyword);
        }
        if ($buyer_id) {
            $where .= " AND buyer_id LIKE '%" . $buyer_id . "%'";
            $this->assign('openid_txt', $buyer_id);
        }
        if ($buyer_name) {
            $where .= " AND buyer_name LIKE '%" . $buyer_name . "%'";
            $this->assign('goumairen', $buyer_name);
        }
        if ($invoice_no) {
            $where .= " AND invoice_no LIKE '%" . $invoice_no . "%'";
            $this->assign('invoice_no', $invoice_no);
        }
        if ($time_start) {
            $time_start_int = strtotime($time_start);
            $where .= " AND add_time>='" . $time_start_int . "'";
            $this->assign('time_start', $time_start);
        }
        if ($time_end) {
            $time_end_int = strtotime($time_end);
            $where .= " AND add_time<='" . $time_end_int . "'";
            $this->assign('time_end', $time_end);
        }
        $status >= 0 && $where .= " AND status=" . $status;
        $this->assign('status', $status);
        if($fenlei >= 0&& $fenlei < 3){
            $where .= " AND erm=".$fenlei;
        }elseif($fenlei==3){
            $where .= " AND jifendh = 1";
        }
        $this->assign('fenlei', $fenlei);
        //排序
        $order_str = 'add_time desc';
        if ($order) {
            $order_str = $order . ' ' . $sort;
        }
        if(!empty($_GET['daochu'])){
            $order_excel = $items_mod->where($where)->join("sk_order_extm on sk_orders.order_id = sk_order_extm.order_id ")->join("sk_order_goods on sk_orders.order_id = sk_order_goods.order_id")->order($order_str)->select();
            //dump($order_excel);die;
            
            $objPHPExcel = new PHPExcel();
            $uphtml = D('uphtml');
            // 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
            $objPHPExcel
            ->getProperties()  //获得文件属性对象，给下文提供设置资源
            ->setCreator( "Maarten Balliauw")                 //设置文件的创建者
            ->setLastModifiedBy( "Maarten Balliauw")          //设置最后修改者
            ->setTitle( "Office 2007 XLSX Test Document" )    //设置标题
            ->setSubject( "Office 2007 XLSX Test Document" )  //设置主题
            ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
            ->setKeywords( "office 2007 openxml php")        //设置标记
            ->setCategory( "Test result file");                //设置类别
            // 位置aaa  *为下文代码位置提供锚
            // 给表格添加数据
            $this_biao = $objPHPExcel->setActiveSheetIndex(0);             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
            $excel_canshu = $objPHPExcel->getActiveSheet();
            $excel_canshu->getStyle('A')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('G')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('F')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('H')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $excel_canshu->getColumnDimension('A')->setWidth(20);
            $excel_canshu->getColumnDimension('C')->setWidth(10);
            $excel_canshu->getColumnDimension('D')->setWidth(12);
            $excel_canshu->getColumnDimension('F')->setWidth(18);
            $excel_canshu->getColumnDimension('G')->setWidth(40);
            $excel_canshu->getColumnDimension('H')->setWidth(20);
            $excel_canshu->getColumnDimension('I')->setWidth(40);
            $excel_canshu->getColumnDimension('J')->setWidth(10);
            $excel_canshu->getColumnDimension('L')->setWidth(20);
            $excel_canshu->getColumnDimension('N')->setWidth(20);
            $excel_canshu->getColumnDimension('O')->setWidth(30);
            $excel_canshu->getColumnDimension('P')->setWidth(60);
            
            $this_biao->setCellValue( 'A1', '订单流水号' )
                      ->setCellValue( 'B1', '订单总价（单位：元）' )
                      ->setCellValue( 'C1', '订单状态' )
                      ->setCellValue( 'D1', '支付方式' )
                      ->setCellValue( 'E1', '购买人' )
                      ->setCellValue( 'F1', '联系电话' )
                      ->setCellValue( 'G1', '收货地址' )
                      ->setCellValue( 'H1', '下单时间' )
                      ->setCellValue( 'I1', '商品名称' )
                      ->setCellValue( 'J1', '商品数量' )
                      ->setCellValue( 'K1', '商品规格' )
                      ->setCellValue( 'L1', '价格（单位：元）' )
                      ->setCellValue( 'M1', '商品编号' )
                      ->setCellValue( 'N1', '快递单号' )
                      ->setCellValue( 'O1', '身份证号' )
                      ->setCellValue( 'P1', '买家附言' );
            $tong_id = "";   
            $tong_id_y = 2;          
            for($i=0; $i<count($order_excel); $i++){
                 $this_biao->setCellValue( 'I'.($i+2), $uphtml -> uphtml_f($order_excel[$i]['goods_name']) )
                           ->setCellValue( 'J'.($i+2), $order_excel[$i]['quantity'] )
                           ->setCellValue( 'K'.($i+2), $order_excel[$i]['specification']." " )
                           ->setCellValue( 'L'.($i+2), $order_excel[$i]['price']." * ".$order_excel[$i]['quantity']." = ".($order_excel[$i]['quantity']*$order_excel[$i]['price']) )
                           ->setCellValue( 'M'.($i+2), $order_excel[$i]['goods_code']." " );
                 //if( $i == 0){
                 if(($tong_id != $order_excel[$i]['order_id'])||($i==0)||($tong_id == $order_excel[$i]['order_id'] && $i == count($order_excel)-1)){

                     if($order_excel[$i]['status']==0)
                         $order_excel_zt = "未付款";
                     else if($order_excel[$i]['status']==1)
                         $order_excel_zt = "已付款";
                     else if($order_excel[$i]['status']==2)
                         $order_excel_zt = "备货中";
                     else if($order_excel[$i]['status']==3)
                         $order_excel_zt = "已发货";
                     else if($order_excel[$i]['status']==4)
                         $order_excel_zt = "已完成";
                     else if($order_excel[$i]['status']==6)
                         $order_excel_zt = "申请退换货中";
                     else if($order_excel[$i]['status']==7)
                         $order_excel_zt = "退换货审核通过";
                     else if($order_excel[$i]['status']==8)
                         $order_excel_zt = "退换货物流中";
                     else if($order_excel[$i]['status']==9)
                         $order_excel_zt = "退换货已完成";
                     else if($order_excel[$i]['status']==10)
                         $order_excel_zt = "退换货审核不通过";
                     else if($order_excel[$i]['status']==98)
                         $order_excel_zt = "已拒绝";
                     else if($order_excel[$i]['status']==99)
                         $order_excel_zt = "已关闭";
                     $this_biao->setCellValue( 'A'.($i+2), $order_excel[$i]['order_sn']." ")
                               ->setCellValue( 'B'.($i+2), $order_excel[$i]['order_amount'] )
                               ->setCellValue( 'C'.($i+2), $order_excel_zt )
                               ->setCellValue( 'D'.($i+2), $order_excel[$i]['payment_name'] )
                               ->setCellValue( 'E'.($i+2), $order_excel[$i]['buyer_name'] )
                               ->setCellValue( 'F'.($i+2), $order_excel[$i]['phone_tel']." " )
                               ->setCellValue( 'G'.($i+2), $order_excel[$i]['address'] )
                               ->setCellValue( 'H'.($i+2), date('Y-m-d H:i:s',$order_excel[$i]['add_time']) )
                               ->setCellValue( 'O'.($i+2), $order_excel[$i]['shenfenz']." " )
                               ->setCellValue( 'P'.($i+2), $order_excel[$i]['postscript']." " )
                               ->setCellValue( 'N'.($i+2), $order_excel[$i]['invoice_no']." " );
                     
                     
                     //if($i == count($order_excel)-1){
                     $i_ji = ($i+1);
                     //}else
                         //$i_ji = ($i+1);
                    if($i > 0 && ($tong_id != $order_excel[$i]['order_id'] || $i == count($order_excel)-1)){
                        //$excel_canshu->mergeCells("A5:A8");//合并
                        $excel_canshu->mergeCells("A".$tong_id_y.":A".$i_ji);//合并
                        $objStyleA1 = $excel_canshu->getStyle("A".$tong_id_y);
                        $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("B".$tong_id_y.":B".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("B".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("C".$tong_id_y.":C".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("C".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("D".$tong_id_y.":D".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("D".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("E".$tong_id_y.":E".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("E".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("F".$tong_id_y.":F".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("F".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("G".$tong_id_y.":G".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("G".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("H".$tong_id_y.":H".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("H".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中
                         $excel_canshu->mergeCells("N".$tong_id_y.":N".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("N".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中 
                         $excel_canshu->mergeCells("O".$tong_id_y.":O".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("O".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中 
                         $excel_canshu->mergeCells("P".$tong_id_y.":P".$i_ji);//合并
                         $objStyleA1 = $excel_canshu->getStyle("P".$tong_id_y);
                         $objAlignA1 = $objStyleA1 ->getAlignment();
                         $objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);  //上下居中 
                     }
                     $tong_id_y = $i+2;
                 }
                 $tong_id = $order_excel[$i]['order_id'];
            }
            
            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('订单信息');
            
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="订单信息.xls"');
            header('Cache-Control: max-age=0');
            
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }else{
            import("ORG.Util.Page");
            $count = $items_mod->where($where)->join(" sk_dpgou on sk_dpgou.orderid = sk_orders.order_id")->count();
            $p = new Page($count, 20);
            $items_list = $items_mod->where($where)->join(" sk_dpgou on sk_dpgou.orderid = sk_orders.order_id")->limit($p->firstRow . ',' . $p->listRows)->order($order_str)->select();
            $page = $p->show();
            $this->assign('page', $page);
            $this->assign('items_list', $items_list);
            $this->assign('order', $order);
            if ($sort == 'desc') {
                $sort = 'asc';
            } else {
                $sort = 'desc';
            }
            $this->assign('sort', $sort);
            $this->assign('addtime_start', $time_start);
            $this->assign('addtime_end', $time_end);
            $this->display();
            
        }
        
        
    }

    public  function  edit(){
        if (isset($_POST['dosubmit'])) {
            $info_mod = D('orders');
            $info_data = $info_mod->create();
            $usrpwd=trim($_POST['usrpwd']);
            if (!empty($usrpwd)) {
                if (strlen($usrpwd) < 6 || strlen($usrpwd) > 20) {
                    $this->error('密码长度错误，应在6到20位之间');
                }
                $info_mod->usrpwd=md5($usrpwd);
            }
            else
            {
                $userInfo=$info_mod->where("usrid=" . $info_data['usrid'])->find();
                $info_mod->usrpwd=$userInfo['usrpwd'];
            }
            $email=$info_mod->email;
            if (!empty($email)&&!is_email($email))
            {
                $this->error('电子邮箱错误');
            }
            $info_mod->birthday=strtotime($info_mod->birthday);
            $result_info = $info_mod->save();
            if (false !== $result_info) {
                $this->success('修改会员信息成功');
            } else {
                $this->success('修改会员信息成功');
            }
        } else {
            $info_mod = D('orders');
            $good_mod = D('goods');
            $logs_mod = D('order_log');
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
            }
            //$info = $info_mod->join('sk_kuaidi on sk_kuaidi.name = sk_orders.shipping_name and sk_kuaidi.danhao = sk_orders.invoice_no')->where('order_id=' . $id)->find();
            $info = $info_mod->where('order_id=' . $id)->find();
            $kd=M("order_goods")->join("sk_kuaidi b on shipping_name=b.name and invoice_no=danhao")->field("count(*) count,sum(if(zhuangtai=3,'1',0)) kcount")->where('order_id=' . $id)->find();
            $goods=$good_mod->query('select *  from  sk_order_goods WHERE order_id='.$id);
            $logs=$logs_mod->query('select *  from  sk_order_log WHERE order_id='.$id.' order by log_time desc');
            $this->assign('info', $info);
            $this->assign('kd', $kd);
            $this->assign('goods', $goods);
            $this->assign('logs', $logs);
            $this->display();
        }
    }
    public function  detail()
    {
        $info_mod = D('orders');
        $good_mod = D('goods');
        $logs_mod = D('order_log');
        $extm_mod = D('order_extm');
        $dianpu = D('dpgou');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
        }
        $thhuo=M("thhuo")->where("ordersid=$id")->find();
        $info = $info_mod->where('order_id=' . $id)->find();
        $goods = $good_mod->query('select *  from  sk_order_goods WHERE order_id=' . $id);
        $logs = $logs_mod->query('select *  from  sk_order_log WHERE order_id=' . $id .' order by log_time desc');
        $pops = $logs_mod->query("SELECT p.*  ,m.`wei_nickname`,m.`wei_username` FROM  wei_pop p ,sk_member m WHERE p.`openid`=m.`open_id`  and p.order_sn='" . $info['order_sn'] ."' order by p.poptime desc");
        $orderinfo=$extm_mod->where(array('order_id'=>$id))->find();
        //$dianpu=$dianpu->where("orderid=".$info['order_id'])->find();
        $dianpu = $dianpu -> where("orderid=".$info['order_id']) -> join(" sk_orders on sk_dpgou.orderid = sk_orders.order_id") -> join("sk_member on sk_member.open_id = sk_orders.buyer_id") -> find();
        $this->assign("dianpu",$dianpu);
        $this->assign('info', $info);
        $this->assign('goods', $goods);
        $this->assign('logs', $logs);
        $this->assign("thhuo",$thhuo);

        $this->assign('pops', $pops);
        $this->assign('extinfo', $orderinfo);
        $this->display();
    }
    public function  shenheimg()
    {
    	
        $info_mod = D('orders');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的链接');
        }
        $info = $info_mod->where('order_id=' . $id)->find();
        $this->assign('info', $info);
		$arr_url['url'] = 'http://'.$_SERVER['HTTP_HOST'];
        $this->assign('info_url',$arr_url) ;
		
        $this->display();
    }
    public function  erweima()
    {
    	
        //$info_mod = D('orders');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的链接');
        }
        $mod = M();
        $erweima = $mod->query("select er.id oi,er.true_false tf,er.dizhi dz,g.img img from sk_erweima er join sk_goods g on g.id = er.shangpin where dingdan=".$id);

        //$info = $info_mod->where('order_id=' . $id)->find();
        $this->assign('erweima', $erweima);
		$arr_url = 'http://'.$_SERVER['HTTP_HOST'];
        $this->assign('url',$arr_url) ;
        $this->assign('idq',$id) ;
        $this->display();
    }

    public function status()
    {
        
        $id = isset($_POST['id']) && intval($_POST['id']) ? intval($_POST['id']) : 0;
        $action = isset($_POST['action']) && (!empty($_POST['action'])) ? $_POST['action'] : "";

        if (empty($id) || empty($action))
            $this->ajaxReturn('', '参数错误', -1);
        $order_mod=D('orders');
        $orders=$order_mod->where('order_id='.$id)->find();
        if(empty($orders))
            $this->ajaxReturn('', '操作的订单无记录', -1);
        $admin_info=$_SESSION['admin_info'];
        switch ($action) {
            case 'aduit':
                $order_mod->where('order_id='.$id)->setField('status',1);
                $this->addlog($id,'已审核','aduit','已审核','用户名:'.$admin_info['user_name'].' (ID:'.$admin_info['id'].')');
                $this->ajaxReturn('', '订单状态已变更为已审核',1);
                break;
            case 'refuse':
                $refuse_info=$_POST['refuse_info'];
                $data=array(
                    'refuse_info'=>$refuse_info,
                    'status'=>99
                );
                $order_mod->where('order_id='.$id)->save($data);
                $this->addlog($id,'已拒绝','refuse','已拒绝','用户名:'.$admin_info['user_name'].'(ID:'.$admin_info['id'].'),原因:'.$refuse_info);
                $this->ajaxReturn('', '订单状态已变更为已拒绝',1);
                break;
            case 'sending':
                $enh = $order_mod->where('order_id='.$id)->select();
                if($enh[0]['shenfenz']=="")
                    $this->ajaxReturn('','该订单尚未填写身份证信息，备货失败',1);
                else{
                    $order_mod->where('order_id='.$id)->setField('status',2);
                    $this->addlog($id,'备货中','sending','备货中','用户名:'.$admin_info['user_name'].' (ID:'.$admin_info['id'].')');
                    $this->ajaxReturn('', '订单状态已变更为备货中',1);
                }
                break;
            case 'close':
                $close_info = $_POST['close_info'];
                $data = array(
                    'close_info' => $close_info,
                    'status' => 98
                );
                $order_mod->where('order_id=' . $id)->save($data);
                $this->addlog($id, '已关闭', 'close', '已关闭', '用户名:' . $admin_info['user_name'] . ' (ID:' . $admin_info['id'] . '),原因:' . $close_info);
                $this->ajaxReturn('', '订单状态已变更为已关闭', 1);
                break;
            case 'sended':
                $invoice_no=trim($this->_post('invoice_no'));
                $shipping_name=trim($this->_post('shipping_name'));
                $kuaidigongsi=$_POST['kuaidigongsi'];
                $data=array(
                    'invoice_no'=>$invoice_no,
                    'shipping_name'=>$shipping_name,
                    'status'=>3
                );
                $order_mod->where('order_id='.$id)->save($data);
                $this->addlog($id,'已发货','sended','变更为已发货','用户名:'.$admin_info['user_name'].' (ID:'.$admin_info['id'].')');

                $order_openid = $order_mod->join('sk_member on sk_member.open_id=sk_orders.buyer_id')->where('order_id='.$id)->select();
                $weixintxt=" 亲爱的".$order_openid[0]['wei_nickname']."，您购买的宝贝已经发货,商品正搭着 ".$kuaidigongsi." （快递单号 ".$data['invoice_no']."）星夜向您奔来，洗洗干净，在家里等着我~";
                $this -> weixintishi($order_openid[0]['buyer_id'],$weixintxt);
                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$order_openid[0]['buyer_id']."',2)");
                //$this -> weixintishi($order_openid[0]['buyer_id'],"您的商城订单已发货,快递公司 ".$kuaidigongsi." 快递单号 ".$data['invoice_no']);
                
                $this->ajaxReturn('', '订单状态已变更为已发货',1);
                break;
            case 'tradefinished':
                $data=array(
                    'finish_time'=>time(),
                    'status'=>4
                );
                //收益分配-----------------------------------------------------------------------
                
                $totalPrice = 0;//商品分成的价格
                $nullmodel = new Model();
                $sql = "select kd.zhuangtai,o.buyer_id,o.status,o.order_sn from sk_orders o join sk_kuaidi kd on kd.name = o.shipping_name and kd.danhao = o.invoice_no where o.order_id='$id'";
                $sql="select a.buyer_id,a.status,a.order_sn,sum(if(c.zhuangtai!=3,1,0)) kd from sk_orders a left join sk_order_goods b on a.order_id=b.order_id left join sk_kuaidi c on b.shipping_name=c.name and b.invoice_no=c.danhao where a.order_id='$id'";
                $arr_kdzt = $nullmodel -> query($sql);

                $data_sj['open_id'] = $arr_kdzt[0]['buyer_id'];
                $out_trade_no = $arr_kdzt[0]['order_sn'];
                if($arr_kdzt[0]['kd']==0 && $arr_kdzt[0]['status']==3){

                    $jifen = M('jifen');
                    $jifen_data['jifen'] = 3;
                    $jifen_data['laiyuan'] = 9;
                    $jifen_data['shijian'] = time();
                    $jifen_data['openid'] = $arr_kdzt[0]['buyer_id'];
                    $jifen -> add($jifen_data);
                    $memb = M('member');
                    $memb_where['open_id'] = $arr_kdzt[0]['buyer_id'];
                    $jifen -> where($memb_where) -> setInc('jifen',3);
                    
                    $sql = "select k.quantity,k.profit as fp_price,g.goodtype,o.buyer_id,o.order_sn from sk_orders o left join sk_order_goods k on k.order_id=o.order_id left join sk_goods g on g.id=k.goods_id where o.order_id='$id'";
                    $resuArr = $nullmodel -> query($sql);
                     
                    for($k=0;$k<count($resuArr);$k++){
                        if($resuArr[$k]['goodtype'] == 0 || $resuArr[$k]['goodtype'] == 2){
                            if(floatval($resuArr[$k]['fp_price'])>0)
                                $totalPrice += floatval($resuArr[$k]['fp_price'])*intval($resuArr[$k]['quantity']);
                        }if($resuArr[$k]['goodtype'] == 2){//该购买的商品不是LED产品就算上去——————————
                            $havaDaili = true;//只要代理产品了，即修改客户身份
                            $dataDaili['jointag'] = 2;
                        }
				    }
                    $i = 1;
                    $meArr = M('member');
                    $resultArr = $meArr -> where($data_sj) ->select();//获取上级资料
                    
                    if(count($resultArr)==0) break;
                    $shangopenid = $resultArr[0]["pid"];
                    
                    if($havaDaili){//是否加盟会店家
                        if($resultArr[0]['jointag']==0)//之前是否已经是会员
                            $dataDaili['join_time'] = date('Y-m-d H:i:s',time());//修改加入会员时间
                        $meArr -> where($data_sj) -> save($dataDaili);//修改成为店铺会员
                    }
                    
                    if($shangopenid != ""){
                        
                        $tru_fal = false;
                        $cunchu = new Model();//调用存储过程
                        $cunchu->query("call sk_member(@return_012,@openid,'".$arr_kdzt[0]['buyer_id']."');");
                        $cunchu = $cunchu->query("select @return_012 as joingat,@openid pid;");

                        if($cunchu[0]['pid'] == "" || $cunchu[0]['joingat'] != 2){
                            $tru_fal = true;
                        }
                        $cunchu[0]['wei_nickname']=M("member")->getFieldByOpen_id($cunchu[0]['pid'],"wei_nickname");
                        $arr_kdzt[0]['wei_nickname']=M("member")->getFieldByOpen_id($arr_kdzt[0]['buyer_id'],"wei_nickname");
                        while($i <= 3){
                            $data_sj['open_id'] = $shangopenid;
                            $resultArr = $meArr -> where($data_sj) ->select();
                            if(count($resultArr)==0) break;
                            $shangopenid = $resultArr[0]["pid"];
                            $fenpei = 0;
                            /* if($tru_fal){
                                if($i == 1){
                                    $fenpei = $totalPrice * 0.7;
                                }else if($i == 2){
                                    $fenpei = $totalPrice * 0.2;
                                }else if($i == 3){
                                    $fenpei = $totalPrice * 0.1;
                                }
                            }else{ */
                                if($i == 1){
                                    $fenpei = $totalPrice * 0.5;
                                }else if($i == 2){
                                    $fenpei = $totalPrice * 0.2;
                                }else if($i == 3){
                                    $fenpei = $totalPrice * 0.1;
                                }
                            /* } */
                            if(!$tru_fal && $i == 1 && ($totalPrice * 0.2) > 0 && $cunchu[0]['pid'] != $arr_kdzt[0]['buyer_id']){//店铺商家占有利益分成
                                $sql = "insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$cunchu[0]['pid']."','".($totalPrice * 0.2)."' ,now(),'2',".time().")";
                                $nullmodel -> execute($sql);
                                $sql = "update sk_member set shouyi = shouyi + ".($totalPrice * 0.2)." where open_id='".$cunchu[0]['pid']."'";
                                $nullmodel -> execute($sql);

                                $weixintxt="亲爱的 ".$cunchu[0]['wei_nickname']."，".$arr_kdzt[0]['wei_nickname']." 购买了商城宝贝，您获取了店家收益 ".($totalPrice * 0.2)." 元，分享美好的事物与朋友，得到的喜悦胜于佣金，然并卵，佣金也是美好的事物（小编醉了）";
                                $this -> weixintishi($cunchu[0]['pid'],$weixintxt);
                                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$cunchu[0]['pid']."',2)");

                                $agent=M("role_store")->field("agentid,dianpname")->join("sk_erweima on pid=openid")->where("pid='".$cunchu[0]['pid']."'")->find();//查看是否有代理人
                                if(!empty($agent)){
                                    M()->query("insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$agent['agentid']."',1,now(),'2',".time().")");
                                    $weixintxt="您所代理的'".$agent['dianpname']."'成交了一笔订单,获得收益1元";
                                    $this -> weixintishi($agent['agentid'],$weixintxt);
                                    M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$agent['agentid']."',2)");
                                    M()-> query("update sk_member set shouyi = shouyi +1 where open_id='".$agent['agentid']."'");
                                }
                            }
                            
                            $sql = "select id from wei_pop where order_sn='$out_trade_no' and openid='".$resultArr[0]["open_id"]."'";
                            $havaArr = $nullmodel -> query($sql);
                            $dp_fencheng = 1;//普通三级盟友分成占一份
                            if($resultArr[0]["open_id"]==$cunchu[0]['pid']){
                                $dp_fencheng = 2;//店铺商家在三级盟友内  分成占两份
                            }
                             
                            if(count($havaArr) < $dp_fencheng && $fenpei > 0 && ($resultArr[0]["jointag"]=="1"||$resultArr[0]['jointag']==2)){//判断上级是否有资格参与分成 且上级还未参与该订单的分成
                                $sql = "insert into wei_pop (order_sn,openid,pop,poptime,duitag,shijianc) values ('$out_trade_no','".$resultArr[0]["open_id"]."','$fenpei' ,now(),'2',".time().")";
                                $nullmodel -> execute($sql);
                                $sql = "update sk_member set shouyi = shouyi + ".$fenpei." where open_id='".$resultArr[0]["open_id"]."'";
                                $nullmodel -> execute($sql);

                                $weixintxt="亲爱的 ".$resultArr[0]["wei_nickname"]."， ".$arr_kdzt[0]['wei_nickname']."  购买了商城宝贝，您获取会员收益  ".$fenpei." 元。有人问我，成为和众会员并获得收益是种怎样的感受？我只想说，you can you up ，no can 问客服~";
                                $this -> weixintishi($resultArr[0]["open_id"],$weixintxt);
                                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$resultArr[0]["open_id"]."',2)");
                            }else{
                                $i = 100;//跳出循环
                            }
                            $i++;
                        }
                    }
                //收益分配-----------------------------------------------------------------

                $order_mod->where('order_id='.$id)->save($data);
                $order_mod->table('wei_pop')->where(array('order_sn'=>$orders['order_sn']))->setField('duitag',0);
                $this->addlog($id,'已完成','tradefinished','变更为已完成','用户名:'.$admin_info['user_name'].' (ID:'.$admin_info['id'].')');
                $this->ajaxReturn('', '订单状态已变更为已完成',1);
                }
                break;
        }


    }
    private  function  addlog($order_id,$operator,$order_status,$chaged_status,$remark){

        if(!empty($order_id)) {
            $log_mode = D('order_log');
            $data=array(
                'order_id'=>$order_id,
                'operator'=>$operator,
                'order_status'=>$order_status,
                'chaged_status'=>$chaged_status,
                'remark'=>$remark,
                'log_time'=>time()
            );
            $log_mode->add($data);
        }

    }

    public  function  pop(){
        $items_mod = M();
        //搜索
        $where = '1=1';
       // $order_sn = isset($_GET['order_sn']) && trim($_GET['order_sn']) ? trim($_GET['order_sn']) : '';
        //$pop = isset($_GET['pop']) ? floatval($_GET['pop']) : 0;
        $time_start = isset($_GET['time_start']) && trim($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = isset($_GET['time_end']) && trim($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $audit_tag = isset($_GET['audit_tag']) ? intval($_GET['audit_tag']) : '-1';
        $order = isset($_GET['order']) && trim($_GET['order']) ? trim($_GET['order']) : '';
        $sort = isset($_GET['sort']) && trim($_GET['sort']) ? trim($_GET['sort']) : 'desc';
//        if ($order_sn) {
//            $where .= " AND order_sn LIKE '%" . $order_sn . "%'";
//            $this->assign('order_sn', $order_sn);
//        }
//        if ($pop) {
//            $where .= " AND pop LIKE '%" . $pop . "%'";
//            $this->assign('invoice_no', $pop);
//        }
        if (!empty($time_start)&&strtotime($time_start)>0) {
            $where .= " AND exc.submit>='" . $time_start . "'";
            $this->assign('time_start', $time_start);
        }
        if (!empty($time_start)&&strtotime($time_end)>0) {
            $where .= " AND exc.submit<='" . $time_end . "'";
            $this->assign('time_end', $time_end);
        }
        $audit_tag >= 0 && $where .= " AND audit_tag=" . $audit_tag;
        $this->assign('audit_tag', $audit_tag);
        //排序
        $order_str = 'exc.submit desc';
        if ($order) {
            $order_str = $order . ' ' . $sort;
        }
        
        if(!empty($_GET['daochu'])){

            //$order_excel = $items_mod->table('wei_exchange ')->where($where)->select();
            $order_excel = $items_mod->table('wei_exchange  exc')->join(' sk_member men on exc.openid=men.open_id')->where($where)-> field('exc.*,men.wei_username,men.wei_nickname,men.pay_code,men.yin_username,men.yin_code,men.brank,men.brank_adda,men.brank_addb,men.brank_zhi')->order($order_str)->select();
            $objPHPExcel = new PHPExcel();
        
            // 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
            $objPHPExcel
            ->getProperties()  //获得文件属性对象，给下文提供设置资源
            ->setCreator( "Maarten Balliauw")                 //设置文件的创建者
            ->setLastModifiedBy( "Maarten Balliauw")          //设置最后修改者
            ->setTitle( "Office 2007 XLSX Test Document" )    //设置标题
            ->setSubject( "Office 2007 XLSX Test Document" )  //设置主题
            ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
            ->setKeywords( "office 2007 openxml php")        //设置标记
            ->setCategory( "Test result file");                //设置类别
            // 位置aaa  *为下文代码位置提供锚
            // 给表格添加数据
            $this_biao = $objPHPExcel->setActiveSheetIndex(0);             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
            /* $this_biao->setCellValue( 'A1', 'Hello' );         //给表的单元格设置数据
             $this_biao->setCellValue( 'B1', '999' );         //给表的单元格设置数据
             $this_biao->setCellValue( 'C1', '啦啦啦all');         //给表的单元格设置数据
             $this_biao->setCellValue( 'D1', 77 );         //给表的单元格设置数据
             $this_biao->setCellValue( 'E1', iconv('gbk', 'utf-8', 77) );         //给表的单元格设置数据
             $this_biao->setCellValue( 'B2', 'world!' );      //数据格式可以为字符串
             //->setCellValue( 'C1', 12)            //数字型
             $this_biao->setCellValue( 'D2', 12);            //
             $this_biao->setCellValue( 'D3', true );           //布尔型
            $this_biao->setCellValue( 'D4', '=SUM(C1:D2)' );//公式 */
            $excel_canshu = $objPHPExcel->getActiveSheet();
            $excel_canshu->getStyle('A')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('C')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('E')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        
            $excel_canshu->getColumnDimension('A')->setWidth(20);
            $excel_canshu->getColumnDimension('C')->setWidth(20);
            $excel_canshu->getColumnDimension('E')->setWidth(30);
            $excel_canshu->getColumnDimension('J')->setWidth(20);
            $excel_canshu->getColumnDimension('H')->setWidth(20);
        
            $this_biao->setCellValue( 'A1', '姓名' )
            ->setCellValue( 'B1', '微信昵称' )
            ->setCellValue( 'C1', '支付宝账号' )
            ->setCellValue( 'D1', '收款银行用户名' )
            ->setCellValue( 'E1', '收款银行账号' )
            ->setCellValue( 'F1', '收款银行' )
            ->setCellValue( 'G1', '提现金额' )
            ->setCellValue( 'H1', '提现方式' )
            ->setCellValue( 'I1', '审核标记' )
            ->setCellValue( 'J1', '提现时间' );
            for($i=0; $i<count($order_excel); $i++){
                if($order_excel[$i]['audit_tag']==0)
                    $order_excel_zt = "提交申请中";
                else if($order_excel[$i]['audit_tag']==1)
                    $order_excel_zt = "兑换成功";
                else if($order_excel[$i]['audit_tag']==2)
                    $order_excel_zt = "拒绝兑换";
                
                if($order_excel[$i]['wy_zfb']==1)
                    $order_excel_zf = "支付宝";
                else 
                    $order_excel_zf = "银行卡";
        
                $this_biao->setCellValue( 'A'.($i+2), $order_excel[$i]['wei_username'])
                ->setCellValue( 'B'.($i+2), $order_excel[$i]['wei_nickname'] )
                ->setCellValue( 'C'.($i+2), $order_excel[$i]['pay_code'] )
                ->setCellValue( 'D'.($i+2), $order_excel[$i]['yin_username'] )
                ->setCellValue( 'E'.($i+2), $order_excel[$i]['yin_code']." " )
                ->setCellValue( 'F'.($i+2), $order_excel[$i]['brank'] )
                ->setCellValue( 'G'.($i+2), $order_excel[$i]['totpop'] )
                ->setCellValue( 'H'.($i+2), $order_excel_zf )
                ->setCellValue( 'I'.($i+2), $order_excel_zt )
                ->setCellValue( 'J'.($i+2), $order_excel[$i]['submit'] );
            }
        
            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('提现信息');
        
            /* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
             $objWriter->save('ola.xlsx'); *///直接生成
            //ob_end_clean();
            // 生成2003excel格式的xls文件
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="提现信息.xls"');
            header('Cache-Control: max-age=0');
        
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }else{
            import("ORG.Util.Page");
            $count = $items_mod->table('wei_exchange ')->where($where)->count();
    
            $p = new Page($count, 20);
            $items_list = $items_mod->table('wei_exchange  exc')->join(' sk_member men on exc.openid=men.open_id')->where($where)-> field('exc.*,men.wei_username,men.wei_nickname,men.pay_code,men.yin_username,men.yin_code,men.brank,men.brank_adda,men.brank_addb,men.brank_zhi')->limit($p->firstRow . ',' . $p->listRows)->order($order_str)->select();
            $page = $p->show();
            $this->assign('page', $page);
            $this->assign('items_list', $items_list);
            $this->assign('order', $order);
            if ($sort == 'desc') {
                $sort = 'asc';
            } else {
                $sort = 'desc';
            }
            $this->assign('sort', $sort);
            $this->assign('addtime_start', $time_start);
            $this->assign('addtime_end', $time_end);
            $this->display();
        }
    }

    public  function  extstatus(){
        $id=isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        $acton=isset($_REQUEST['act'])&&trim($_REQUEST['act'])?trim($_REQUEST['act']):'';
        if(empty($id)||empty($acton))
            $this->ajaxReturn('','参数为空',-1);
        $result=false;
        $info='';
        switch($acton)
        {
            case "agree":
                $mod=M();
                $info='执行同意申请提现操作';
                $result= $mod->table('wei_exchange')->where(array('id'=>$id))->setField('audit_tag',3);
                $mod->table('wei_pop')->where(array('exchangeid'=>$id))->setField('duitag',2);
                $quopenid = $mod->table('wei_exchange')->join('sk_member on sk_member.open_id=wei_exchange.openid')->where(array('id'=>$id))->select();
                $weixintxt="亲爱的 ".$quopenid[0]['wei_nickname']."，您于 ".date('Y-m-d H:i:s',time())." 在和众商城申请提现 ".$quopenid[0]['totpop']." 元，已通过审核";
                $this -> weixintishi($quopenid[0]['openid'],$weixintxt);
                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$quopenid[0]['openid']."',2)");
                //$this -> weixintishi($quopenid[0]['openid'],"恭喜 ".date('Y-m-d H:i:s',time())." 提现 ".$quopenid[0]['totpop']."元，提现成功");
                break;
            case "disagree":
                $mod=M();
                $info='执行拒绝申请提现操作';
                $result=$mod->table('wei_exchange')->where(array('id'=>$id))->setField('audit_tag',2);
                $quopenid = $mod->table('wei_exchange')->join('sk_member on sk_member.open_id=wei_exchange.openid')->where(array('id'=>$id))->select();
                
                $whe['open_id'] = $quopenid[0]['openid'];
                $tixianb = $mod -> table('sk_member') -> where($whe)->setInc('shouyi',$quopenid[0]['totpop']);
                $weixintxt="亲爱的 ".$quopenid[0]['wei_nickname']."，很遗憾的告诉您，您 ".date('Y-m-d H:i:s',time())." 提现 ".$quopenid[0]['totpop']." 元，提现失败。您的现金已经被小怪兽吃掉了，赶快联系工作人员去看看马尼（money）去哪了~";
                $this -> weixintishi($quopenid[0]['openid'],$weixintxt);
                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$quopenid[0]['openid']."',2)");
                //$this -> weixintishi($quopenid[0]['openid'],date('Y-m-d H:i:s',time())." 提现 ".$quopenid[0]['totpop']."元，提现失败");
                break;
            case "money":
            $mod=M();
            $info='执行提现操作';
            $result= $mod->table('wei_exchange')->where(array('id'=>$id))->setField('audit_tag',1);
            $mod->table('wei_pop')->where(array('exchangeid'=>$id))->setField('duitag',1);
            $quopenid = $mod->table('wei_exchange')->join('sk_member on sk_member.open_id=wei_exchange.openid')->where(array('id'=>$id))->select();
            $weixintxt="亲爱的 ".$quopenid[0]['wei_nickname']."，恭喜您于 ".date('Y-m-d H:i:s',time())." 从和众世纪提现 ".$quopenid[0]['totpop']." 元，提现成功。聚少成多，金山银海也不过如此，每一分收益都是汗水浇灌的，所以。。。大神你该洗洗澡了orz";
            $this -> weixintishi($quopenid[0]['openid'],$weixintxt);
            M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$quopenid[0]['openid']."',2)");
            break;
        }
        if($result)
            $this->ajaxReturn('',$info.'成功',1);
        else
            $this->ajaxReturn('',$info.'失败',0);

    }
    public function order_beiz(){
    	$order = M('orders');
    	$map['order_id'] = array('eq',$_POST['id']);
    	$map_x['order_notes'] = $_POST['order_beiz_text'];
    	$data = $order -> where($map) -> save($map_x);
    	echo $data;
    }
    public function order_shenfenz(){
    	$order = M('orders');
    	$map['order_id'] =intval($_POST['id']);
    	$map_x['buyer_name'] = trim($this->_post('xingmz'));
    	$map_x['shenfenz'] = trim($this->_post('shenfzh'));
        $where['open_id']=$order->getFieldByOrder_id($map['order_id'],"buyer_id");
        $data = $order -> where($map) -> save($map_x);
        if($data!==false)
            M("member")->where($where)->setField("wei_shousfz",$map_x['shenfenz']);
    	echo $data;
    }
    public function poprmb(){
    	$items_mod = M();
    	$id['id'] = array('eq',$_GET['id']);
    	//dump($id);
    	$items_mod = $items_mod -> table('wei_exchange ') -> where($id) -> find();
    	//dump($items_mod);
		$this->assign('rmb', $items_mod);
    	$this -> display();
    }
    public function poprmb_upd(){
    	$order = M();
    	$map['id'] = array('eq',$_POST['id']);
    	$map_x['rmb_beiz'] = $_POST['order_beiz_text'];
    	$data = $order -> table('wei_exchange ') -> where($map) -> save($map_x);
    	echo $data;
    }
    public function shenhe(){
    	$order = M();
    	$map['order_id'] = array('eq',$_POST['id']);
    	$map_x['shenhe_jiez'] = $_POST['state'];
    	$wc = $order -> table('sk_orders') ->field('status') -> where($map) ->select();
    	if($wc[0]['status']==5||$wc[0]['status']==1||$wc[0]['status']==2||$wc[0]['status']==3||$wc[0]['status']==4){
	    	$data = $order -> table('sk_orders ') -> where($map) -> save($map_x);
		    if($data !== false){
	    		echo  $map_x['shenhe_jiez'];
		    }
    	}else
    		echo 3;
    }
    public function serweima(){    	
    	$order = M();
    	$map['id'] = array('eq',$_POST['oi']);
    	$ai = $_POST['tf'];
    	$map_x['true_false'] = $_POST['tf'];
    	//$wc = $order -> table('sk_erweima') ->field('status') -> where($map) ->select();
    	$data = $order -> table('sk_erweima ') -> where($map) -> save($map_x);
    	echo $ai;
    }
    public function tuihh_sq(){
    	$order = M('orders');
    	$map['order_id'] = array('eq',$_GET['id']);
    	$data = $order -> join('sk_thhuo on sk_thhuo.ordersid = sk_orders.order_id') -> where($map) -> select();
    	$this -> assign('th',$data);
    	$this -> display();
    }
    public function tongguo(){    	
    	$order = M();
    	$map['order_id'] = array('eq',$_POST['id']);
    	if($_POST['tg']==1)
    	   $map_x['status'] = 7;
    	else
            $map_x['status'] = 4;//不通过则变回完成状态
    	$order -> table('sk_orders ') -> where($map) -> save($map_x);
    	echo 0;
    }
    public function tuihh_wl(){    	
    	$order = M('orders');
    	$map['order_id'] = array('eq',$_GET['id']);
    	$data = $order -> join('sk_thhuo on sk_thhuo.ordersid = sk_orders.order_id') -> where($map) -> select();
    	$this -> assign('th',$data);
    	$this -> display();
    }
    public function wlwc(){    	
    	$order = M();
    	$map['order_id'] = array('eq',$_POST['id']);
    	$map_x['status'] = 9;
    	$order -> table('sk_orders ') -> where($map) -> save($map_x);
    	echo 0;
    }  
    function dingyue(){
        if($_POST['name']&&$_POST['danhao']){
            $post_data = array();
            $post_data["schema"] = 'json' ;
            
            //callbackurl请参考callback.php实现，key经常会变，请与快递100联系获取最新key
            //$post_data["param"] = '{"company":"zhongtong", "number":"363826593543","from":"", "to":"", "key":"BYeHdgdK8102", "parameters":{"callbackurl":"http://www.yourdmain.com/kuaidi"}}';
            $post_data["param"] = '{"company":"'.$_POST['name'].'", "number":"'.$_POST['danhao'].'","from":"", "to":"", "key":"BYeHdgdK8102", "parameters":{"callbackurl":"http://m.hz41319.com/wei/callback.php"}}';
            
            
            $url='http://www.kuaidi100.com/poll';
            
            $o="";
            foreach ($post_data as $k=>$v)
            {
                $o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
            }
            
            $post_data=substr($o,0,-1);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $result = curl_exec($ch);		//返回提交结果，格式与指定的格式一致（result=true代表成功）
            curl_close($ch);
            
            //$result = json_decode($result, true);
            //$this->ajaxReturn(0,$result['returnCode'],0);
            //$this->ajaxReturn(0,$result,0);
        }else 
            $this->ajaxReturn(0,"00",0);
    } 
    function weixintishi($openidd,$weixintishi_text){
        $weixin_token = M('weixin_token');
        $token_txt = $weixin_token -> select();
        $token_url = $token_txt[0]['token'];
        if((time() - $token_txt[0]['time'])>7100){//数据库里 微信token 已过期  需要跟新
            $ch = curl_init();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $return = curl_exec ( $ch );
            curl_close ( $ch );
            $output_array = json_decode($return,true);
            $token_url = $output_array['access_token'];
            $whe['id'] = $token_txt[0]['id'];
            $data_up['time'] = time();
            $data_up['token'] = $token_url;
            $weixin_token -> where($whe) -> save($data_up);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token_url;
        $data = "{
                        \"touser\": \"".$openidd."\",
                        \"msgtype\": \"text\",
                        \"text\": {
                            \"content\": \"".htmlspecialchars($weixintishi_text)."\"
                        }
                    }";
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        $return = curl_exec ( $ch );
        curl_close ( $ch );
        $output_array = json_decode($return,true);
    }
    function jiamengshenhe(){
        $order_id['orderid'] = isset($_GET['order_id']) && trim($_GET['order_id']) ? trim($_GET['order_id']) : '';
        $sk_dpgou = M('dpgou');
        $sk_dpgou = $sk_dpgou -> where($order_id) -> join(" sk_orders on sk_dpgou.orderid = sk_orders.order_id") -> join("sk_member on sk_member.open_id = sk_orders.buyer_id") -> select();
        $this->assign('items_cate_list', $sk_dpgou);
        $this -> display();
    }
    function jiamengshenhe_get(){
        $dianpu = M("dpgou");
        $where['dpgou_id'] = $_GET['dpgou_id'];
        $name['jiamengshenhe'] = $_POST['jiamengshenhe'];

        $tr = $dianpu->where($where)->find();
        if($tr['jiamengshenhe']==0){
            $open_id=$this->_post("open_id");
            $nickname=$this->_post("wei_nickname");
            if($dianpu->where($where)->save($name)){
                /* $dianpu = $dianpu->where($where) -> join(" sk_orders on sk_dpgou.orderid = sk_orders.order_id") -> select();
                 if($dianpu[0]['jiamengshenhe'] == 1){
                 $user = M('member');
                 $user_where['open_id'] = $dianpu[0]['buyer_id'];
                 $user_data['jointag'] = '2';
                 $user_data['join_time'] = date('Y-m-d H:i:s',time());
                 $user -> where($user_where) -> save($user_data);
                 } */
                if($name['jiamengshenhe']==1) {
                    $weixintxt="亲爱的 " . $nickname . "，您于 " . date('Y-m-d日', time()) . " 在洋仆淘跨境商城申请线上店铺已通过审核，请到订单中支付";
                    $this->weixintishi($open_id,$weixintxt);
                }elseif($name['jiamengshenhe']==2) {
                    $weixintxt="亲爱的 " . $nickname . "，您于 " . date('Y-m-d日', time()) . " 在洋仆淘跨境商城申请线上店铺未通过审核，请重新申请或联系客服";
                    $this->weixintishi($open_id,$weixintxt);
                }
                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$open_id."',2)");
                $this->success('审核成功',U('index'));
            }else {
                $weixintxt="亲爱的 ".$nickname."，您于 ".date('Y-m-d日',time())." 在洋仆淘跨境商城申请线上店铺未通过审核，请重新申请或联系客服";
                $this -> weixintishi($open_id,$weixintxt);
                M()->query("insert into sk_message(intro,create_time,rec,type) value ('$weixintxt','".time()."','".$open_id."',2)");
                $this->error('审核失败');
            }
        }else
            $this->success('该订单已审核',U('index'));
    }
    function editkd(){
        if($this->isPost()){
            $orders_goods=M('order_goods');
            if($orders_goods->create()){
                if($orders_goods->save()!==false)
                    echo 1;
                else
                    echo 0;
            }
            $where['order_id']=$this->_post("oid");
            $where['shipping_name|invoice_no']='';
            $count=$orders_goods->where($where)->count();
            $status=M('orders')->where("order_id=".$where['order_id'])->getField('status');
            if($count==0&&$status==2){
                M('orders')->where("order_id=".$where['order_id'])->setField("status",3);
            }elseif($count>0&&$status==3){
                M('orders')->where("order_id=".$where['order_id'])->setField("status",2);
            }
        }
    }
    function count(){
        $orders=M("orders");
        $order_goods=M("order_goods");
        $where=array();
        $order=array();
        if(isset($_GET['openid'])&&$_GET['openid']!='') {
            $where['buyer_id'] = $this->_get('openid');
            $this->assign("openid",$this->_get('openid'));
        }
        if(isset($_GET['buyer_name'])&&$_GET['buyer_name']!='') {
            $where['buyer_name'] = $this->_get('buyer_name');
            $this->assign("buyer_name",$this->_get('buyer_name'));
        }
        if(isset($_GET['start_time'])&&$_GET['start_time']!='') {
            $where['add_time'][] = array("EGT", strtotime($this->_get('start_time')));
            $this->assign("start_time",$this->_get('start_time'));
        }
        if(isset($_GET['end_time'])&&$_GET['end_time']!='') {
            $where['add_time'][] = array("ELT",strtotime($this->_get('end_time')));
            $this->assign("end_time",$this->_get('end_time'));
        }
        if(isset($_GET['end_time'])&&$_GET['end_time']!='') {
            $where['add_time'][] = array("ELT",strtotime($this->_get('end_time')));
            $this->assign("end_time",$this->_get('end_time'));
        }
        if(isset($_GET['order'])&&$_GET['order']!=''){
            $order[$this->_get('order')]='desc';
            $this->assign('order',$this->_get('order'));
        }else
            $order['add_time']='desc';//默认时间排序
        if($_GET['daochu']&&$_GET['daochu']!=''){
            $subQuery = $orders->field('buyer_id,buyer_name,order_amount,status,add_time')->order("add_time desc")->buildSql();//因为下面要分组,所以要先生成排序好时间的表做成子查询
            $list = $orders->table($subQuery . ' a')->where($where)->group('buyer_id')->join("left join sk_member_important b on b.openid=a.buyer_id")->field("buyer_id,buyer_name,type,count(*) order_count,sum(if(status=4,order_amount,0)) money,sum(if(status=4,'1',0)) order_ycount,sum(if(status=1,'1',0)) order_wcount,sum(if(status>4,'1',0)) order_tcount")->order($order)->select();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()
                ->setCreator( "Maarten Balliauw")
                ->setLastModifiedBy( "Maarten Balliauw")
                ->setTitle( "Office 2007 XLSX Test Document" )
                ->setSubject( "Office 2007 XLSX Test Document" )
                ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords( "office 2007 openxml php")
                ->setCategory( "Test result file");
            $this_biao = $objPHPExcel->setActiveSheetIndex(0);
            $excel_canshu = $objPHPExcel->getActiveSheet();
            $excel_canshu->getStyle('A')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('C')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('E')->getNumberFormat()
                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $excel_canshu->getColumnDimension('A')->setWidth(60);
            $excel_canshu->getColumnDimension('B')->setWidth(40);
            $excel_canshu->getColumnDimension('C')->setWidth(20);
            $excel_canshu->getColumnDimension('D')->setWidth(15);
            $excel_canshu->getColumnDimension('E')->setWidth(10);
            $excel_canshu->getColumnDimension('F')->setWidth(10);
            $excel_canshu->getColumnDimension('G')->setWidth(10);
            $this_biao->setCellValue( 'A1', '购买人ID' )
                ->setCellValue( 'B1', '购买人' )
                ->setCellValue( 'C1', '已完成订单总价' )
                ->setCellValue( 'D1', '订单总量' )
                ->setCellValue( 'E1', '已完成订单总量' )
                ->setCellValue( 'F1', '未付款订单总量' )
                ->setCellValue( 'G1', '退货订单' );
            for($i=0; $i<count($list); $i++){
                $this_biao->setCellValue( 'A'.($i+2), $list[$i]['buyer_id'])
                    ->setCellValue( 'B'.($i+2), $list[$i]['buyer_name'] )
                    ->setCellValue( 'C'.($i+2), $list[$i]['money'] )
                    ->setCellValue( 'D'.($i+2), $list[$i]['order_count'] )
                    ->setCellValue( 'E'.($i+2), $list[$i]['order_ycount'] )
                    ->setCellValue( 'F'.($i+2), $list[$i]['order_wcount'] )
                    ->setCellValue( 'G'.($i+2), $list[$i]['order_tcount'] );
            }

            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setTitle('订单统计');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="订单统计.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }else {
            import("ORG.Util.Page");
            $count = count($orders->where($where)->group('buyer_id')->field('buyer_id')->select());
            $p = new Page($count, 30);
            $page = $p->show();
            $subQuery = $orders->field('buyer_id,buyer_name,order_amount,status,add_time')->order("add_time desc")->buildSql();//因为下面要分组,所以要先生成排序好时间的表做成子查询
            $list = $orders->table($subQuery . ' a')->where($where)->group('buyer_id')->join("left join sk_member_important b on b.openid=a.buyer_id")->field("buyer_id,buyer_name,type,count(*) order_count,sum(if(status=4,order_amount,0)) money,sum(if(status=4,'1',0)) order_ycount,sum(if(status=1,'1',0)) order_wcount,sum(if(status>4,'1',0)) order_tcount")->limit($p->firstRow . ',' . $p->listRows)->order($order)->select();
            //分组后统计各个订单的数量,并且左连接重点用户表,那个表有他的id,就是重点用户
            //type 有值即为重点用户
            //money 已完成总订单
            //order_count 总订单
            //order_ycount 已完成订单
            //order_wcount 为付款订单
            //order_tcount 退货订单
            $this->assign('list', $list);
            $this->assign("page", $page);
            $this->display();
        }
    }
    function important(){
        if($_POST){
			$important=M('member_important');
            $data['openid']=$this->_post('id');
            $type=$_POST['cz']+0;
			$count=$important->where($data)->count();
			if($type==0){
				$count>0||$important->add($data);
				echo 1;
			}else{
				$count<=0||$important->where($data)->delete();
				echo 2;
			}
        }
    }
    function splitgoods(){
        if($this->isPost()){
            $order_goods=M("order_goods");
            $where['order_id']=$this->_post("oid");
            $where['rec_id']=$this->_post("rec_id");
            $number=intval($this->_post("number"));
            $where['quantity']=array("GT",$number);
            $count=$order_goods->where($where)->count();
            if($count>0&&$number>0){
                $data=$order_goods->where($where)->field("rec_id",true)->find();
                $data['quantity']=$number;
                if($order_goods->add($data)) {
                    echo 1;
                    $order_goods->where($where)->setDec("quantity",$number);
                }else
                    echo 2;
            }
        }
    }
} 