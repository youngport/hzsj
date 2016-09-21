<?php

class couponAction extends baseAction {

    public function index() {
        $coupon = M('coupon');
        if (isset($_GET['rec']) && $_GET['rec'] != '') {
            $where['rec'] = $this->_get('rec');
        }
        if (isset($_GET['id']) && $_GET['id'] != '') {
            $where['id'] = $this->_get('id');
        }
        if (isset($_GET['start_time']) && $_GET['start_time'] != '') {
            $where['start_time'] = array('EGT', strtotime($this->_get('start_time')));
        }
        if (isset($_GET['end_time']) && $_GET['end_time'] != '') {
            $where['end_time'] = array('ELT', strtotime($this->_get('end_time')));
        }

        //导出优惠券使用情况

        if (!empty($_GET['daochu'])) {

            Vendor('PHPExcel.PHPExcel');

            $field = 'id,rec,xz,js,start_time,end_time,sk_coupon.add_time couponaddtime,sk_coupon.status status,order_sn,buyer_name,goods_amount,sk_orders.status dingdanzhuangtai,reason,sk_orders.add_time xiadantime,payment_name,payment_code,wei_nickname';
            $join1 = 'LEFT JOIN sk_orders ON sk_coupon.id=sk_orders.couponid LEFT JOIN sk_member ON sk_coupon.rec=sk_member.open_id';
            $excel = M('coupon')->join($join1)->field($field)->where($where)->select();
            $objPHPExcel = new PHPExcel();
            // 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
            $objPHPExcel
                    ->getProperties()  //获得文件属性对象，给下文提供设置资源
                    ->setCreator("Maarten Balliauw")                 //设置文件的创建者
                    ->setLastModifiedBy("Maarten Balliauw")          //设置最后修改者
                    ->setTitle("Office 2007 XLSX Test Document")    //设置标题
                    ->setSubject("Office 2007 XLSX Test Document")  //设置主题
                    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
                    ->setKeywords("office 2007 openxml php")        //设置标记
                    ->setCategory("Test result file");                //设置类别
            // 位置aaa  *为下文代码位置提供锚
            // 给表格添加数据
            $this_biao = $objPHPExcel->setActiveSheetIndex(0);             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
            $excel_canshu = $objPHPExcel->getActiveSheet();
            $excel_canshu->getStyle('A')->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('H')->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('F')->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $excel_canshu->getStyle('I')->getNumberFormat()
                    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

            $excel_canshu->getColumnDimension('A')->setWidth(10);
            $excel_canshu->getColumnDimension('B')->setWidth(15);
            $excel_canshu->getColumnDimension('C')->setWidth(35);
            $excel_canshu->getColumnDimension('D')->setWidth(15);
            $excel_canshu->getColumnDimension('E')->setWidth(10);
            $excel_canshu->getColumnDimension('F')->setWidth(20);
            $excel_canshu->getColumnDimension('G')->setWidth(20);
            $excel_canshu->getColumnDimension('H')->setWidth(20);
            $excel_canshu->getColumnDimension('I')->setWidth(20);
            $excel_canshu->getColumnDimension('J')->setWidth(10);
            $excel_canshu->getColumnDimension('K')->setWidth(10);
            $excel_canshu->getColumnDimension('L')->setWidth(10);
            $excel_canshu->getColumnDimension('M')->setWidth(10);
            $excel_canshu->getColumnDimension('N')->setWidth(20);
            $excel_canshu->getColumnDimension('O')->setWidth(20);

            $this_biao->setCellValue('A1', '优惠券ID')
                    ->setCellValue('B1', '微信昵称')
                    ->setCellValue('C1', '优惠券用户')
                    ->setCellValue('D1', '优惠信息')
                    ->setCellValue('E1', '优惠券状态')
                    ->setCellValue('F1', '优惠券开始时间')
                    ->setCellValue('G1', '优惠券结束时间')
                    ->setCellValue('H1', '优惠券添加时间')
                    ->setCellValue('I1', '订单流水号')
                    ->setCellValue('J1', '购买人')
                    ->setCellValue('K1', '商品总价')
                    ->setCellValue('L1', '订单状态')
                    ->setCellValue('M1', '支付方式')
                    ->setCellValue('N1', '支付订单号')
                    ->setCellValue('O1', '下单时间');

            $i = 2;
            foreach ($excel as $k => $v) {

                if ($v['dingdanzhuangtai'] == 0)
                    $dingdanzhuangtai = "未付款";
                else if ($v['dingdanzhuangtai'] == 1)
                    $dingdanzhuangtai = "已付款";
                else if ($v['dingdanzhuangtai'] == 2)
                    $dingdanzhuangtai = "清关中";
                else if ($v['dingdanzhuangtai'] == 3)
                    $dingdanzhuangtai = "已发货";
                else if ($v['dingdanzhuangtai'] == 4)
                    $dingdanzhuangtai = "已完成";
                else if ($v['dingdanzhuangtai'] == 6)
                    $dingdanzhuangtai = "退换货申请中";
                else if ($v['dingdanzhuangtai'] == 7)
                    $dingdanzhuangtai = "退换货审核通过";
                else if ($v['dingdanzhuangtai'] == 8)
                    $dingdanzhuangtai = "退换货物流中";
                else if ($v['dingdanzhuangtai'] == 9)
                    $dingdanzhuangtai = "退换货已完成";
                else if ($v['dingdanzhuangtai'] == 10)
                    $dingdanzhuangtai = "退换货审核不通过";
                else if ($v['dingdanzhuangtai'] == 98)
                    $dingdanzhuangtai = "已拒绝";
                else if ($v['dingdanzhuangtai'] == 99)
                    $dingdanzhuangtai = "已关闭";

                if ($v['status'] == 1) {
                    $status = '未使用';
                } else if ($v['status'] == 2) {
                    $status = '已使用';
                } else if ($v['status'] == 3) {
                    $status = '转让中';
                } else if ($v['status'] == 0) {
                    $status = '停用';
                }
                if ($v['xiadantime']) {
                    $xiadantime = date("Y-m-d H:i:s", $v['xiadantime']);
                } else {
                    $xiadantime = '';
                }
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, $v['id'])
                        ->setCellValue('B' . $i, $v['wei_nickname'])
                        ->setCellValue('C' . $i, $v['rec'])
                        ->setCellValue('D' . $i, $v['xz'] . '-' . $v['js'])
                        ->setCellValue('E' . $i, $status)
                        ->setCellValue('F' . $i, date("Y-m-d", $v['start_time']))
                        ->setCellValue('G' . $i, date("Y-m-d", $v['end_time']))
                        ->setCellValue('H' . $i, date("Y-m-d H:i:s", $v['couponaddtime']))
                        ->setCellValue('I' . $i, $v['order_sn'])
                        ->setCellValue('J' . $i, $v['buyer_name'])
                        ->setCellValue('K' . $i, $v['goods_amount'])
                        ->setCellValue('L' . $i, $dingdanzhuangtai)
                        ->setCellValue('M' . $i, $v['payment_name'])
                        ->setCellValue('N' . $i, $v['payment_code'])
                        ->setCellValue('O' . $i, $xiadantime);
                $i++;
            }


            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('优惠券使用情况');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="优惠券使用情况.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } else {
            import("ORG.Util.Page");
            $count = $coupon->where($where)->count();
            $p = new Page($count, 20);
            $field = 'id,rec,xz,js,start_time,end_time,add_time,type,status,wei_nickname';
            $join = 'LEFT JOIN `sk_member` on sk_coupon.rec=sk_member.open_id';
            $list = $coupon->join($join)->order('add_time desc')->where($where)->limit($p->firstRow . ',' . $p->listRows)->select();
            $page = $p->show();
            $this->assign('page', $page);
            $this->assign('list', $list);
            $this->assign('get', $_GET);
            $this->display();
        }
    }

    function add() {
        if ($_POST) {
            $coupon = M("coupon");
            $message = M("message");
            if ($coupon->create()) {
                $coupon->xz = intval($coupon->xz);
                $coupon->js = intval($coupon->js);
                if ($coupon->xz < $coupon->js) {
                    $this->error("优惠减免不能大于限额!");
                }
                $coupon->start_time = strtotime($coupon->start_time);
                $coupon->end_time = strtotime($coupon->end_time);
                $coupon->add_time = time();
                $coupon->sn = coupon_sn();
                $coupon->reason = $coupon->reason;
                $coupon->add_name = $_SESSION['admin_info']['user_name'];
                $add_time = date('Y-m-d', $coupon->start_time);
                $end_time = date('Y-m-d', $coupon->end_time);
                $coupon->rec= trim($coupon->rec);
                $rec =$coupon->rec;
                $coupon->js=  trim($coupon->js);
                $js = $coupon->js;
                $res = $coupon->add();
                if ($res) {
                    //$text = "恭喜您获取一张" . $js . "元的优惠券，有效时间为" . $add_time . "到" . $end_time . "，快去购物吧";
                    $text=" 优惠券已进口袋啦！也可以分享给你身边的“剁手”好友哦，独乐不如众乐，独“剁”不如众“剁”！";
                    $this->weixintishi($rec, $text);
                    $message->query("insert into sk_message(intro,create_time,rec,type) value ('$text','" . time() . "','$rec',2)");
                    $this->success("添加成功");
                }
            }
        } else {
            $this->display();
        }
    }

    function edit() {
        $coupon = M("coupon");
        if ($_POST) {
            if ($coupon->create()) {
                $coupon->xz = floatval($coupon->xz);
                $coupon->js = floatval($coupon->js);
                if ($coupon->xz < $coupon->js) {
                    $this->error("优惠减免不能大于限额!");
                }
                $coupon->start_time = strtotime($coupon->start_time);
                $coupon->end_time = strtotime($coupon->end_time);
                $coupon->reason = $coupon->reason;
                $coupon->rec=  trim($coupon->rec);
                if (($coupon->save())!==false) {
                    $this->success("修改成功");
                }
            }
        } else {
            $id = intval($_GET['id']);
            $info = $coupon->find($id);
            $this->assign("info", $info);
            $this->display();
        }
    }

    function delete() {
        $coupon = M('coupon');
        if (isset($_POST['id']) && is_array($_POST['id'])) {
            $ids = implode(',', $_POST['id']);
            $coupon->delete($ids);
            $this->success(L('operation_success'));
        }
    }

    function weixintishi($openidd, $weixintishi_text) {
        $weixin_token = M('weixin_token');
        $token_txt = $weixin_token->select();
        $token_url = $token_txt[0]['token'];
        if ((time() - $token_txt[0]['time']) > 7100) {//数据库里 微信token 已过期  需要跟新
            $ch = curl_init();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx8b17740e4ea78bf5&secret=bbd06a32bdefc1a00536760eddd1721d";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $return = curl_exec($ch);
            curl_close($ch);
            $output_array = json_decode($return, true);
            $token_url = $output_array['access_token'];
            $whe['id'] = $token_txt[0]['id'];
            $data_up['time'] = time();
            $data_up['token'] = $token_url;
            $weixin_token->where($whe)->save($data_up);
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $token_url;
        $data = "{
                        \"touser\": \"" . $openidd . "\",
                        \"msgtype\": \"text\",
                        \"text\": {
                            \"content\": \"" . htmlspecialchars($weixintishi_text) . "\"
                        }
                    }";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $return = curl_exec($ch);
        curl_close($ch);
        $output_array = json_decode($return, true);
    }

    function situation() {
        $couponid = $_GET['id']; //优惠券ID
        $result = M('orders')->where(array('couponid' => $couponid))->select(); //查找使用优惠券的订单
        $this->assign('result', $result);
        $this->display();
    }

}

?>