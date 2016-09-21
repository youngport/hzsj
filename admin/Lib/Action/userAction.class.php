<?php
set_time_limit(0);
Vendor('PHPExcel.PHPExcel');

class userAction extends baseAction {

    public function index() {
        //echo ini_get("memory_limit");
        ini_set("memory_limit","256M");
        //echo ini_get("memory_limit");
        $mod = D("member");
        $pagesize = 20;
        import("ORG.Util.Page");
        $time_start = isset($_GET['addtime_start']) && trim($_GET['addtime_start']) ? trim($_GET['addtime_start']) : '';
        $time_end = isset($_GET['addtime_end']) && trim($_GET['addtime_end']) ? trim($_GET['addtime_end']) : '';
        $addtime_start = isset($_GET['dltime_start']) && trim($_GET['dltime_start']) ? trim($_GET['dltime_start']) : '';
        $addtime_end = isset($_GET['dltime_end']) && trim($_GET['dltime_end']) ? trim($_GET['dltime_end']) : '';
        $spid = isset($_GET['spid']) ? trim($_GET['spid']) : '';
        $xpid = isset($_GET['xpid']) ? trim($_GET['xpid']) : '';
        $order = array();

        $ddcs = isset($_GET['ddcs']) ? intval($_GET['ddcs']) : '-1';
        $this->assign('ddcs', $ddcs);

        $flag = isset($_GET['flag']) ? intval($_GET['flag']) : '-1';
        $this->assign('flag', $flag);
        if ($flag == 3)
            $where['_string'] = 'jointag=1 or jointag=2';
        else if ($flag >= 0)
            $where['jointag'] = $flag;

        $open_id = isset($_GET['open_id']) && trim($_GET['open_id']) ? trim($_GET['open_id']) : '';
        if ($open_id) {
            $where['_string'] = " open_id = '$open_id'";
            $this->assign('open_id', $open_id);
        }
        if ($time_start) {
            //$time_start_int = strtotime($time_start);
            $where['_string'] = " login_time >= '$time_start'";
            $this->assign('addtime_start', $time_start);
        }
        if ($time_end) {
            //$time_start_int = strtotime($time_end);
            $where['_string'] = " login_time <= '$time_end'";
            $this->assign('addtime_end', $time_end);
        }
        if ($time_end && $time_start) {
            $where['_string'] = " login_time <= '$time_end' and login_time >= '$time_start'";
        }
        if ($addtime_start) {
            $time_start_int = strtotime($addtime_start);
            $where['_string'] = " last_login >= '$time_start_int'";
            $this->assign('dltime_start', $addtime_start);
        }
        if ($addtime_end) {
            $time_start_intq = strtotime($addtime_end);
            $where['_string'] = " last_login <= '$time_start_intq'";
            $this->assign('dltime_end', $addtime_end);
        }
        if ($addtime_start && $addtime_end) {
            $where['_string'] = " last_login <= '$time_start_intq' and last_login >= '$time_start_int'";
        }
        if ($addtime_start && $addtime_end) {
            $where['_string'] = " last_login <= '$time_start_intq' and last_login >= '$time_start_int'";
        }
        if ($spid) {
            $where['_string'] = " open_id=(select pid from sk_member where open_id='$spid')";
            $this->assign('spid', $spid);
            $this->assign('xpid', '');
        }
        if ($xpid) {
            $where['_string'] = " open_id=(select pid from sk_member where open_id='$xpid') or pid='$xpid'";
            $this->assign('xpid', $xpid);
            $this->assign('spid', '');
            $data = $mod->where("pid='$xpid'")->field('open_id,jointag')->select();
            $count = utcount($data);

            if (!empty($count)) {
                $count['mcount']+=0;
                $count['mper'] = round(($count['mcount'] / $count['count']) * 100);
                $count['ycount'] = count($data);
                $count['ymcount'] = $mod->where("pid='$xpid' and jointag=1")->count() + 0;
                $count['ymper'] = round(($count['ymcount'] / $count['ycount']) * 100);
                $this->assign("count", $count);
            }
        }

        $keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
        if (!empty($keyword)) {
            $this->assign('keyword', $keyword);
            $where['_string'] = " real_name like '%$keyword%'";
        }
        $wei_name = isset($_REQUEST['wei_name']) ? $_REQUEST['wei_name'] : '';
        if (!empty($wei_name)) {
            $this->assign('wei_name', $wei_name);
            $where['_string'] = " wei_nickname like '%$wei_name%'";
        }
        if (isset($_GET['order']) && $_GET['order'] != '') {
            $order[$this->_get('order')] = 'desc';
            $this->assign('order', $this->_get('order'));
        } else
            $order['last_login'] = 'desc';
        if (!empty($_GET['daochu'])) {
            if ($ddcs == 0) {
                $order_excel = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,im_qq,email,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop,(SELECT count(order_id) from  sk_orders WHERE sk_orders.buyer_id=sk_member.open_id and (sk_orders.`status`=1 or  sk_orders.`status`=2 or sk_orders.`status`=3 or sk_orders.`status`=4 or sk_orders.`status`=5)) ordernum')->order("logins desc")->limit($p->firstRow . ',' . $p->listRows)->select();
                file_put_contents('excle.log', date("Y-m-d H:i:s") . " sql--" .$mod->getLastSql()."---". PHP_EOL, FILE_APPEND | LOCK_EX); //记录日志
                
                foreach ($order_excel as $key => $v) {
                    $xpid = $v['open_id'];
                    $cc = $mod->where("pid='$xpid'")->field('open_id,jointag')->select();
                    $count = uscount($cc, $key);
                    $order_excel[$key]['mcount'] = $count['mcount'];
                }
            } elseif ($ddcs == 1) {
                $order_excel = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,im_qq,email,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop,(SELECT count(order_id) from  sk_orders WHERE sk_orders.buyer_id=sk_member.open_id and (sk_orders.`status`=1 or  sk_orders.`status`=2 or sk_orders.`status`=3 or sk_orders.`status`=4 or sk_orders.`status`=5)) ordernum')->order("logins asc")->select();
               
                foreach ($order_excel as $key => $v) {
                    $xpid = $v['open_id'];
                    $cc = $mod->where("pid='$xpid'")->field('open_id,jointag')->select();
                    $count = uscount($cc, $key);
                    $order_excel[$key]['mcount'] = $count['mcount'];
                }
            } else {
                $order_excel = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,im_qq,email,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop,(SELECT count(order_id) from  sk_orders WHERE sk_orders.buyer_id=sk_member.open_id and (sk_orders.`status`=1 or  sk_orders.`status`=2 or sk_orders.`status`=3 or sk_orders.`status`=4 or sk_orders.`status`=5)) ordernum')->order($order)->select();
                //echo $mod->getLastSql();
                foreach ($order_excel as $key => $v) {
                    $xpid = $v['open_id'];
                    $cc = $mod->where("pid='$xpid'")->field('open_id,jointag')->select();
                    $count = uscount($cc, $key);
                    $order_excel[$key]['mcount'] = $count['mcount'];
                }
            }
            //exit();
            //$order_excel = $items_mod->table('wei_exchange ')->where($where)->select();
            //$order_excel = $items_mod->table('wei_exchange  exc')->join(' sk_member men on exc.openid=men.open_id')->where($where)-> field('exc.*,men.wei_username,men.wei_nickname,men.pay_code,men.yin_username,men.yin_code,men.brank,men.brank_adda,men.brank_addb,men.brank_zhi')->order($order_str)->select();
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
            $excel_canshu->getColumnDimension('B')->setWidth(35);
            $excel_canshu->getColumnDimension('C')->setWidth(20);
            $excel_canshu->getColumnDimension('D')->setWidth(35);
            $excel_canshu->getColumnDimension('E')->setWidth(10);
            $excel_canshu->getColumnDimension('F')->setWidth(30);
            $excel_canshu->getColumnDimension('G')->setWidth(30);
            $excel_canshu->getColumnDimension('J')->setWidth(20);
            $excel_canshu->getColumnDimension('H')->setWidth(20);
            $excel_canshu->getColumnDimension('I')->setWidth(20);
            $excel_canshu->getColumnDimension('O')->setWidth(20);
            $excel_canshu->getColumnDimension('P')->setWidth(20);
            $this_biao->setCellValue('A1', 'ID')
                    ->setCellValue('B1', '微信昵称')
                    ->setCellValue('C1', '登录账户')
                    ->setCellValue('D1', '真实姓名')
                    ->setCellValue('E1', '联系电话')
                    ->setCellValue('F1', 'QQ')
                    ->setCellValue('G1', '电子邮箱')
                    ->setCellValue('H1', '盟友人数')
                    ->setCellValue('I1', '总会员人数')
                    ->setCellValue('J1', '总收益')
                    ->setCellValue('K1', '登录次数')
                    ->setCellValue('L1', '账号注册时间')
                    ->setCellValue('M1', '加入会员时间')
                    ->setCellValue('N1', '会员最近登陆时间状态')
                    ->setCellValue('O1', '会员状态')
                    ->setCellValue('P1', '订单数')
                    ->setCellValue('Q1', 'mac地址');
            for ($i = 0; $i < count($order_excel); $i++) {
                if ($order_excel[$i]['jointag'] == 0)
                    $order_excel_zt = "普通用户";
                else if ($order_excel[$i]['jointag'] == 1)
                    $order_excel_zt = "普通会员";
                else if ($order_excel[$i]['jointag'] == 2)
                    $order_excel_zt = "店铺会员";

                $this_biao->setCellValue('A' . ($i + 2), $order_excel[$i]['usrid'])
                        ->setCellValue('B' . ($i + 2), $order_excel[$i]['wei_nickname'])
                        ->setCellValue('C' . ($i + 2), $order_excel[$i]['open_id'])
                        ->setCellValue('D' . ($i + 2), $order_excel[$i]['real_name'])
                        ->setCellValue('E' . ($i + 2), $order_excel[$i]['phone_mob'] . " / " . $order_excel[$i]['phone_tel'])
                        ->setCellValue('F' . ($i + 2), $order_excel[$i]['im_qq'])
                        ->setCellValue('G' . ($i + 2), $order_excel[$i]['email'])
                        ->setCellValue('H' . ($i + 2), $order_excel[$i]['pidnum'])
                        ->setCellValue('I' . ($i + 2), $order_excel[$i]['mcount'])
                        ->setCellValue('J' . ($i + 2), $order_excel[$i]['sumpop'] . " ")
                        ->setCellValue('K' . ($i + 2), $order_excel[$i]['logins'])
                        ->setCellValue('L' . ($i + 2), $order_excel[$i]['login_time'])
                        ->setCellValue('M' . ($i + 2), $order_excel[$i]['join_time'])
                        ->setCellValue('N' . ($i + 2), date('Y-m-d H:i:s', $order_excel[$i]['last_login']))
                        ->setCellValue('O' . ($i + 2), $order_excel_zt)
                        ->setCellValue('P' . ($i + 2), $order_excel[$i]['ordernum'])
                        ->setCellValue('Q' . ($i + 2), $order_excel[$i]['mac_address']);
                 //file_put_contents('logfile.log', date("Y-m-d H:i:s") . " " . "A--B--C--D--E--F--G--H--I--J--K--L--M--N--O"  . PHP_EOL, FILE_APPEND | LOCK_EX); //记录日志
                
            }

            //得到当前活动的表,注意下文教程中会经常用到$objActSheet
            $objActSheet = $objPHPExcel->getActiveSheet();
            // 位置bbb  *为下文代码位置提供锚
            // 给当前活动的表设置名称
            $objActSheet->setTitle('用户信息');

            /* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
              $objWriter->save('ola.xlsx'); *///直接生成
            //ob_end_clean();
            // 生成2003excel格式的xls文件
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="用户信息.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }else {
            $count = $mod->where($where)->count();
            
            $p = new Page($count, $pagesize);
            if ($ddcs == 0) {
                $list = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop')->order("logins desc")->limit($p->firstRow . ',' . $p->listRows)->select();
                
            } elseif ($ddcs == 1) {
                $list = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop')->order("logins asc")->limit($p->firstRow . ',' . $p->listRows)->select();
            } else {
                $list = $mod->where($where)->field('usrid,wei_nickname,usrname,real_name,phone_mob,phone_tel,pid,open_id,login_time,last_login,last_ip,jointag,logins,sk_member.mac_address,(select count(usrid) from sk_member a where a.pid=sk_member.open_id ) pidnum,(select sum(pop) p from wei_pop where wei_pop.openid=sk_member.open_id) sumpop,(select count(*) from sk_member a where a.pid=sk_member.open_id and a.jointag >=1) uesrnum,(SELECT count(*) from  sk_orders WHERE sk_orders.buyer_id=sk_member.open_id and (sk_orders.`status`=1 or  sk_orders.`status`=2 or sk_orders.`status`=3 or sk_orders.`status`=4 or sk_orders.`status`=5)) ordernum')->order($order)->limit($p->firstRow . ',' . $p->listRows)->select();
//                echo $mod->getLastSql();      
            }

            $page = $p->show();
            $this->assign('list', $list);
            $this->assign('page', $page);
            $this->display();
        }
    }
    /**
     * 显示盟友会员的数量
     */
    function showhycount(){
        $xpid = $_GET['openId'];
        $mod = D("member");
        $cc = $mod->where("pid='$xpid'")->field('open_id,jointag')->select();
        $count = uscount($cc, $key);
        $count=$count['mcount'];
        $this->assign('count',$count);
        $this->display();
    }

    function edit() {
        if (isset($_POST['dosubmit'])) {
            $info_mod = D('member');
            $info_data = $info_mod->create();
            $usrpwd = trim($_POST['usrpwd']);
            if (!empty($usrpwd)) {
                if (strlen($usrpwd) < 6 || strlen($usrpwd) > 20) {
                    $this->error('密码长度错误，应在6到20位之间');
                }
                $info_mod->usrpwd = md5($usrpwd);
            } else {
                $userInfo = $info_mod->where("usrid=" . $info_data['usrid'])->find();
                $info_mod->usrpwd = $userInfo['usrpwd'];
            }
            if (empty($info_mod->birthday))
                $info_mod->birthday = 0;
            else
                $info_mod->birthday = strtotime($info_mod->birthday);
            $email = $info_mod->email;
            if (!empty($email) && !is_email($email)) {
                $this->error('电子邮箱错误');
            }
            $result_info = $info_mod->save($info_data);

            if (false !== $result_info) {
                $this->success('修改会员信息成功');
            } else {
                $this->success('修改会员信息成功');
            }
        } else {
            $info_mod = M('member');
            if (isset($_GET['id'])) {
                $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
            }
            $user = $info_mod->where('usrid=' . $id)->find();
            $this->assign('info', $user);
            $this->display();
        }
    }

    public function delete() {
        if (!isset($_POST['usrid']) || empty($_POST['usrid'])) {
            $this->error('请选择要删除的数据！');
        }
        $user_mod = D('member');
        $ids = $_POST['usrid'];
        if (is_array($ids)) {
            foreach ($ids as $val) {
                $user_mod->delete($val);
            }
            $this->success("删除成功");
        }
    }

    public function status() {
        $id = intval($_REQUEST['id']);
        $type = trim($_REQUEST['type']);
        $items_mod = D('member');
        $res = $items_mod->where('usrid=' . $id)->setField($type, array('exp', "(" . $type . "+1)%2"));
        $values = $items_mod->where('usrid=' . $id)->getField($type);
        $this->ajaxReturn($values[$type]);
    }

    public function checkusr() {
        $usrname = trim($_REQUEST['usrname']);
        if (empty($usrname))
            $this->ajaxReturn('', '参数为空', -1);
        $items_mod = D('user_info');
        $usr = $items_mod->where(array('usrname' => $usrname))->find();
        if (!empty($usr))
            $this->ajaxReturn('', '已被占用', 0);
        else
            $this->ajaxReturn('', '未被占用', 1);
    }

    function geren_dianpu() {
        if (isset($_GET['openid'])) {
            $tj['openid'] = $_GET['openid'];
        }
        $tj['status'] = 4;


        if (isset($_GET['status']) && $_GET['status'] != -1) {
            $tj['shenhe'] = $_GET['status'];
        }
        if (isset($_GET['tex']) && $_GET['tex'] != "") {
            $tj['_string'] = "dianpname like '%" . $_GET['tex'] . "%'";
            $tx = $_GET['tex'];
        }
        //dump($_GET['openid']);die;
        $dianp = M('erweima');
        $count = $dianp->where($tj)->join('sk_orders on sk_erweima.dingdan=sk_orders.order_id')->count(); // 查询满足要求的总记录数
        if ($_GET['p'])
            $dianp = $dianp->where($tj)->join('sk_orders on sk_erweima.dingdan=sk_orders.order_id')->page($_GET['p'] . ',20')->select();
        else
            $dianp = $dianp->where($tj)->join('sk_orders on sk_erweima.dingdan=sk_orders.order_id')->limit('0,20')->select();

        import("ORG.Util.Page"); // 导入分页类
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $this->assign('page', $show); // 赋值分页输出

        if ($tj['shenhe'] == "")
            $this->assign('status', -1);
        else
            $this->assign('status', $tj['shenhe']);
        $this->assign('keyword', $tx);
        $this->assign('openid', $tj['openid']);
        $this->assign('items_cate_list', $dianp);
        $this->display();
    }

    function geren_dpshenhe() {
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
        }
        $whe['id'] = $id;
        $dianpu = M('erweima');
        $dianpu = $dianpu->where($whe)->join('sk_member on sk_member.open_id = sk_erweima.openid')->select();
        $this->assign('items_cate_list', $dianpu);

        $this->display();
    }

    function dianpuchakan() {
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的详情链接');
        }
        $whe['id'] = $id;
        $dianpu = M('erweima');
        $dianpu = $dianpu->where($whe)->join('sk_member on sk_member.open_id = sk_erweima.openid')->select();
        $this->assign('dianpu', $dianpu);

        $this->display();
    }

    function dianpu_shenheget() {
        if ($_FILES['img']['name'] != '') {
            $upload_list = $this->_upload($_FILES['img']);
            $name['dianp_img'] = $upload_list;
        }
        $dianpu = M("erweima");
        $where['id'] = $_GET['id'];
        $name['dianpname'] = $_POST['dianpname'];
        $name['dianp_lxfs'] = $_POST['dianp_lxfs'];
        $name['zuobiao'] = $_POST['zuobiao'];
        $name['xxdizhi'] = $_POST['xxdizhi'];
        $name['shenhe'] = $_POST['shenhe'];

        if ($dianpu->where($where)->save($name))
            $this->error('修改成功');
        else
            $this->error('修改失败');
    }

    function xiaoxi() {
        $info_mod = M('member');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要编辑的链接');
        }
        $user = $info_mod->where('usrid=' . $id)->find();
        $this->assign('info', $user);
        $this->display();
    }

    function xiaoxiget() {

        $id = $_POST['id'];
        $neirong = $_POST['neirong'];
        $biaoti = $_POST['biaoti'];

        $xiaoxi = M("xiaoxi"); // 实例化User对象
        $data['openid'] = $id;
        $data['biaoti'] = $biaoti;
        $data['neirong'] = $neirong;
        /* $data['openid'] = 'oc4cpuKadXviOYYPCRwrrQ2Xvfds';
          $data['biaoti'] = '4444';
          $data['neirong'] = '55555555555555555'; */
        $data['sanchu'] = 0;

        $data['shijian'] = time();
        $dianpu = $xiaoxi->add($data);
        $this->ajaxReturn($dianpu);
    }

    function shouyi() {
        if (isset($_GET['openid'])) {
            $pop = M('pop', 'wei_');
            $where['openid'] = $this->_get('openid');
            $name = M("member")->getFieldByOpen_id($this->_get('openid'), "wei_nickname");
            $order['shijianc'] = 'desc';
            if (!empty($_GET['daochu'])) {
                $order_excel = $pop->where($where)->field("b.order_sn,openid,pop,shijianc,buyer_id,buyer_name,order_amount,order_id")->join("sk_orders b on wei_pop.order_sn=b.order_sn")->order($order)->select();
                $objPHPExcel = new PHPExcel();
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
                $this_biao = $objPHPExcel->setActiveSheetIndex(0);
                $excel_canshu = $objPHPExcel->getActiveSheet();
                $excel_canshu->getStyle('A')->getNumberFormat()
                        ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $excel_canshu->getStyle('C')->getNumberFormat()
                        ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                $excel_canshu->getStyle('E')->getNumberFormat()
                        ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

                $excel_canshu->getColumnDimension('A')->setWidth(20);
                $excel_canshu->getColumnDimension('B')->setWidth(35);
                $excel_canshu->getColumnDimension('C')->setWidth(20);
                $excel_canshu->getColumnDimension('D')->setWidth(35);
                $excel_canshu->getColumnDimension('E')->setWidth(10);

                $this_biao->setCellValue('A1', '订单流水号')
                        ->setCellValue('B1', '购买人')
                        ->setCellValue('C1', '订单总价')
                        ->setCellValue('D1', '付款时间')
                        ->setCellValue('E1', '收益');
                for ($i = 0; $i < count($order_excel); $i++) {
                    $this_biao->setCellValue('A' . ($i + 2), $order_excel[$i]['order_sn'])
                            ->setCellValue('B' . ($i + 2), $order_excel[$i]['buyer_name'])
                            ->setCellValue('C' . ($i + 2), $order_excel[$i]['order_amount'])
                            ->setCellValue('D' . ($i + 2), date("Y-m-d H:i:s", $order_excel[$i]['shijianc']))
                            ->setCellValue('E' . ($i + 2), $order_excel[$i]['pop']);
                }
                $objActSheet = $objPHPExcel->getActiveSheet();
                $objActSheet->setTitle($name . '的收益统计');
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $name . '的收益统计.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
            }
            if ($_GET['buyer_name'] && $_GET['buyer_name'] != '') {
                $where['buyer_name'] = $this->_get('buyer_name');
                $this->assign('buyer_name', $this->_get('buyer_name'));
            }
            if ($_GET['buyer_id'] && $_GET['buyer_id'] != '') {
                $where['buyer_id'] = $this->_get('buyer_id');
                $this->assign('buyer_id', $this->_get('buyer_id'));
            }
            if ($_GET['order_sn'] && $_GET['order_sn'] != '') {
                $where['wei_pop.order_sn'] = $this->_get('order_sn');
                $this->assign('order_sn', $this->_get('order_sn'));
            }
            if (isset($_GET['start_time']) && $_GET['start_time'] != '') {
                $where['shijianc'][] = array("EGT", strtotime($this->_get('start_time')));
                $this->assign("start_time", $this->_get('start_time'));
            }
            if (isset($_GET['end_time']) && $_GET['end_time'] != '') {
                $where['shijianc'][] = array("ELT", strtotime($this->_get('end_time')));
                $this->assign("end_time", $this->_get('end_time'));
            }
            if ($_GET['order'] && $_GET['order'] != '') {
                $order[$this->_get('order')] = 'desc';
                $this->assign('order', $this->_get('order'));
            }
            import("ORG.Util.Page");
            $count = $pop->where($where)->count();
            $p = new Page($count, 20);
            $list = $pop->where($where)->field("b.order_sn,openid,pop,shijianc,buyer_id,buyer_name,order_amount,order_id")->join("sk_orders b on wei_pop.order_sn=b.order_sn")->limit($p->firstRow . ',' . $p->listRows)->order($order)->select();
            $this->assign('list', $list);
            
            $this->assign("openid", $where['openid']);
            $this->assign("page", $p->show());
            $this->display();
        }
    }

    function count() {
        $member = M('member');
        $where = '';
        $order = ' order by ';
        //$sql = "select a.time,a.count,b.rcount,b.pcount,c.mcount,(SELECT count(*) time FROM sk_member a inner join sk_orders b on a.open_id=b.buyer_id where (erm=2 or is_member=1) and status=1 and jointag=1 and FROM_UNIXTIME(add_time,'%Y-%m-%d')=a.time and FROM_UNIXTIME(unix_timestamp(login_time),'%Y-%m-%d')=a.time) jmcount from sk_member_count a left join (SELECT FROM_UNIXTIME(unix_timestamp(login_time),'%Y-%m-%d') time,count(*) rcount,sum(if(pid!='',1,0)) pcount FROM `sk_member` group by time) b on a.time=b.time left join (select FROM_UNIXTIME(add_time,'%Y-%m-%d') time,count(*) mcount from sk_orders  where (erm=2 or is_member =1) and status=1 group by time) c on a.time=c.time";
        //$sql="select a.time,a.count,b.rcount,b.pcount,c.mcount from sk_member_count a left join (SELECT FROM_UNIXTIME(unix_timestamp(login_time),'%Y-%m-%d') time,count(*) rcount,sum(if(pid!='',1,0)) pcount FROM `sk_member` group by time) b on a.time=b.time left join (select FROM_UNIXTIME(add_time,'%Y-%m-%d') time,count(distinct(buyer_id)) mcount from sk_orders  where (erm=2 or is_member =1) and status!=0 group by time) c on a.time=c.time";
        $sql="select a.time,a.count,b.rcount,b.pcount,c.mcount from sk_member_count a left join (SELECT FROM_UNIXTIME(unix_timestamp(login_time),'%Y-%m-%d') time,count(*) rcount,sum(if(pid!='',1,0)) pcount FROM `sk_member` group by time) b on a.time=b.time left join (select FROM_UNIXTIME(add_time,'%Y-%m-%d') time,count(distinct(buyer_id)) mcount from sk_orders  where (erm=2 or is_member =1) and status IN(1,2,3,4,9) group by time) c on a.time=c.time";
        $sql2 = "select count(*) count from sk_member_count a left join (SELECT FROM_UNIXTIME(unix_timestamp(login_time),'%Y-%m-%d') time,count(*) rcount,sum(if(pid!='',1,0)) pcount FROM `sk_member` group by time) b on a.time=b.time left join (select FROM_UNIXTIME(add_time,'%Y-%m-%d') time,count(*) mcount from sk_orders  where (erm=2 or is_member =1)  and status=1 group by time) c on a.time=c.time";
        if (isset($_GET['start_time']) && $_GET['start_time'] != '') {
            if ($where == "")
                $where.=" where";
            else
                $where.="and";
            $where.=" unix_timestamp(a.time)>='" . strtotime($this->_get('start_time')) . "'";
            $this->assign("start_time", $this->_get('start_time'));
        }
        if (isset($_GET['end_time']) && $_GET['end_time'] != '') {
            if ($where == "")
                $where.=" where";
            else
                $where.="and";
            $where.=" unix_timestamp(a.time)<'" . strtotime($this->_get('end_time')) . "'";
            $this->assign("end_time", $this->_get('end_time'));
        }
        if ($_GET['order'] && $_GET['order'] != '') {
            $order.=$this->_get('order') . ' desc ';
            $this->assign('order', $this->_get('order'));
        } else
            $order.="time desc";
        import("ORG.Util.Page");
        $count = $member->query($sql2 . $where);
        $p = new Page($count[0]['count'], 20);
        $list = $member->query($sql . $where . $order . " limit " . $p->firstRow . ',' . $p->listRows);
        $this->assign("page", $p->show());
        $this->assign('list', $list);
        $this->display();
    }


    public function search(){
        
        if(!empty($_POST)){
            
            $openid = trim($_POST['openid']);
            if(!$openid){
                $this->error('openid不存在!');
            }
            
            $mac = M('member','sk_')->where(array('open_id'=>$openid))->field('mac_address')->find();
            $mac = $mac['mac_address'];
            $parentId = $this->getPartent($openid);
            $store = M('erweima','sk_');
            $data = $store->where(array('openid'=>$parentId,'mac'=>$mac))->field('dianpname')->find();
            if(!$data['dianpname']){
                exit('没有数据!');
            }
            echo '店铺名:' . $data['dianpname'];
            echo '<br/>';
            echo 'mac地址:' . $mac;
            exit(); 
            
        }
        
        $this->display();
    }
    
    /*
     * 递归统计顶级ID
     * @param $openid 
     * @return string 顶级ID
     */
    private function getPartent($openid){
        

        $pid = M('member','sk_')->where(array('open_id'=>$openid))->field('open_id,pid')->find();

//         echo M('member','sk_')->getLastSql();
        if( $pid['pid'] ){
            $pids = $pid['pid'];
            $npids = $this->getPartent( $pid['pid'] );
            
            if(isset($npids)){
                
                $pids = $npids;
            }
                
        }
        return $pids;
        
    }
    
 }
