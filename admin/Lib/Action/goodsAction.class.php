<?php

Vendor('PHPExcel.PHPExcel');

class goodsAction extends baseAction
{

    public function index()
    {
        $items_mod = D('goods');
        $items_cate_mod = D('items_cate');
        //搜索
        $where = '1=1';
        $gys_where = '1=1';
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $pcode = isset($_GET['pcode']) && trim($_GET['pcode']) ? trim($_GET['pcode']) : '';
        $cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : '';
        $time_start = isset($_GET['time_start']) && trim($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = isset($_GET['time_end']) && trim($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $addtime_start = isset($_GET['addtime_start']) && trim($_GET['addtime_start']) ? trim($_GET['addtime_start']) : '';
        $addtime_end = isset($_GET['addtime_end']) && trim($_GET['addtime_end']) ? trim($_GET['addtime_end']) : '';
        $status = isset($_GET['status']) ? intval($_GET['status']) : '-1';
        $goodtype = isset($_GET['goodtype']) ? intval($_GET['goodtype']) : '-1';
        $order = isset($_GET['order']) && trim($_GET['order']) ? trim($_GET['order']) : '';
        $kucunr = isset($_GET['kucunr']) && trim($_GET['kucunr']) ? trim($_GET['kucunr']) : '';
        $sort = isset($_GET['sort']) && trim($_GET['sort']) ? trim($_GET['sort']) : 'desc';
        $kucun = isset($_GET['kucun']) && trim($_GET['kucun']) ? trim($_GET['kucun']) : '';
        $huodong_id = isset($_GET['huodong_id']) && trim($_GET['huodong_id']) ? trim($_GET['huodong_id']) : '';
        $cangku_bianhao = isset($_GET['cangku_bianhao']) && trim($_GET['cangku_bianhao']) ? trim($_GET['cangku_bianhao']) : '';
        $gys_name = isset($_GET['gys_name']) && trim($_GET['gys_name']) ? trim($_GET['gys_name']) : '';
        $cangku_name = isset($_GET['cangku_name']) && trim($_GET['cangku_name']) ? trim($_GET['cangku_name']) : '';
        $canyuhd = isset($_GET['canyuhd']) && trim($_GET['canyuhd']) ? trim($_GET['canyuhd']) : '-1';
        $is_hots = isset($_GET['is_hots']) && is_numeric($_GET['is_hots']) ? $_GET['is_hots'] + 0 : '-1';
        if ($huodong_id != "") {
            $where .= " AND (mp_id in(select canyuid from sk_huodong_bang where canyulei = 2 and huodongid = '$huodong_id') or gx_id in(select canyuid from sk_huodong_bang where canyulei = 3 and huodongid = '$huodong_id') or cate_id in(select canyuid from sk_huodong_bang where canyulei = 1 and huodongid = '$huodong_id'))";
            $goodtype = 0;
            $status = 1;
            $this->assign('huodong_id', $huodong_id);
            $canyuhd = 1;
        }
        if ($canyuhd >= 0) {
            $where .= " AND canyuhd = " . $canyuhd;
            $this->assign('canyuhd', $canyuhd);
        }
        $this->assign('canyuhd', $canyuhd);
        if ($is_hots >= 0) {
            $where .= " AND is_hots = " . $is_hots;
        }
        $this->assign('is_hots', $is_hots);

        if ($keyword) {
            $where .= " AND good_name LIKE '%" . $keyword . "%'";
            $this->assign('keyword', $keyword);
        }
        if ($pcode) {
            $where .= " AND pcode LIKE '%" . $pcode . "%'";
            $this->assign('pcode', $pcode);
        }
        if ($cate_id) {
            $where .= " AND sk_goods.cate_id=" . $cate_id;
            $this->assign('cate_id', $cate_id);
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
        if ($addtime_start) {
            $addtime_start_int = strtotime($addtime_start);
            $where .= " AND goods_addtime>='" . $addtime_start_int . "'";
            $this->assign('addtime_start', $addtime_start);
        }
        if ($addtime_end) {
            $addtime_end_int = strtotime($addtime_end);
            $where .= " AND goods_addtime<='" . $addtime_end_int . "'";
            $this->assign('addtime_end', $addtime_end);
        }
        if ($cangku_bianhao) {
            $cangku_bianhao_int = $cangku_bianhao;
            $gys_where .= " AND bang_cangku_id ='" . $cangku_bianhao_int . "'";
            $this->assign('cangku_bianhao', $cangku_bianhao);
        }
        if ($gys_name) {
            $gys_name_txt = strtotime($gys_name);
            $gys_where .= " AND gys_name like '%" . $gys_name_txt . "%'";
            $this->assign('gys_name', $gys_name);
        }
        if ($cangku_name) {
            $cangku_name_txt = strtotime($cangku_name);
            $gys_where .= " AND cangku_name like '%" . $cangku_name_txt . "%'";
            $this->assign('cangku_name', $cangku_name);
        }
        if ($cangku_bianhao || $gys_name || $cangku_name) {
            $huoqu_ckgoods = M('goods_gongying');
            $huoqu_ckgoods = $huoqu_ckgoods->join('RIGHT JOIN sk_goods_gongying_cangku on sk_goods_gongying.gys_bianma = sk_goods_gongying_cangku.gongying_bianhao')
                ->join('RIGHT JOIN sk_goods_cangku on sk_goods_gongying_cangku.cangku_bianhao = sk_goods_cangku.bang_cangku_id')->where($gys_where)->select();
            if (count($huoqu_ckgoods) > 0) {
                $where .= " AND id in(" . $huoqu_ckgoods[0]['bang_goods_id'];
                for ($i = 1; $i < count($huoqu_ckgoods); $i++) {
                    $where .= "," . $huoqu_ckgoods[$i]['bang_goods_id'];
                }
                $where .= ")";
            } else {
                $where .= " AND id in(null)"; //当查询仓库或者供应商没有数据时
            }
        }
        $status >= 0 && $where .= " AND status=" . $status;
        $this->assign('status', $status);
        if ($goodtype == 99)
            $where .= " AND sort_order in(1,2,3,4,5)";
        elseif ($goodtype == 1 || $goodtype == 2)
            $where .= " AND goodtype=" . $goodtype;
        elseif ($goodtype >= 0)
            $where .= " AND sort_order not in(1,2,3,4,5) AND goodtype=" . $goodtype;
        $this->assign('goodtype', $goodtype);
        //排序
        $order_str = 'add_time desc';
        if ($order) {
            $order_str = $order . ' ' . $sort;
        }
        if ($kucunr) {
            $kucun_str = $kucunr . ' ' . $kucun;
        }

        if (!empty($_GET['daochu'])) {//-> join("sk_items_cate on sk_items_cate.id = sk_goods.cate_id")
            $order_excel = $items_mod->join("sk_mingp mp on mp_id=mp.id")->where($where)->order($order_str)->select();
            foreach ($order_excel as $k => $val) {
                //$order_excel[$k]['key'] = ++$p->firstRow;
                $order_excel[$k]['items_cate'] = $items_cate_mod->field('name')->where('id=' . $val['cate_id'])->find();
            }
            $objPHPExcel = new PHPExcel();
            // 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
            $objPHPExcel
                ->getProperties()//获得文件属性对象，给下文提供设置资源
                ->setCreator("Maarten Balliauw")//设置文件的创建者
                ->setLastModifiedBy("Maarten Balliauw")//设置最后修改者
                ->setTitle("Office 2007 XLSX Test Document")//设置标题
                ->setSubject("Office 2007 XLSX Test Document")//设置主题
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")//设置备注
                ->setKeywords("office 2007 openxml php")//设置标记
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

            $excel_canshu->getColumnDimension('A')->setWidth(15);
            $excel_canshu->getColumnDimension('B')->setWidth(60);
            $excel_canshu->getColumnDimension('C')->setWidth(20);
            $excel_canshu->getColumnDimension('D')->setWidth(15);
            $excel_canshu->getColumnDimension('E')->setWidth(25);
            $excel_canshu->getColumnDimension('F')->setWidth(10);
            $excel_canshu->getColumnDimension('G')->setWidth(10);

            $this_biao->setCellValue('A1', '商品编号')
                ->setCellValue('B1', '商品名称')
                ->setCellValue('C1', '商品规格')
                ->setCellValue('D1', '所属分类')
                ->setCellValue('E1', '所属品牌')
                ->setCellValue('F1', '库存')
                ->setCellValue('G1', '利润分配价格')
                ->setCellValue('H1', '单价');
            for ($i = 0; $i < count($order_excel); $i++) {
                $this_biao->setCellValue('A' . ($i + 2), $order_excel[$i]['pcode'])
                    ->setCellValue('B' . ($i + 2), $order_excel[$i]['good_name'])
                    ->setCellValue('C' . ($i + 2), $order_excel[$i]['guige'])
                    ->setCellValue('D' . ($i + 2), $order_excel[$i]['items_cate']['name'])
                    ->setCellValue('E' . ($i + 2), $order_excel[$i]['name'])
                    ->setCellValue('F' . ($i + 2), $order_excel[$i]['kucun'])
                    ->setCellValue('G' . ($i + 2), $order_excel[$i]['fp_price'])
                    ->setCellValue('H' . ($i + 2), $order_excel[$i]['price']);
            }

            $objActSheet = $objPHPExcel->getActiveSheet();

            $objActSheet->setTitle('商品列表');

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="商品列表.xls"');
            header('Cache-Control: max-age=0');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } else {
            import("ORG.Util.Page");
            $count = $items_mod->where($where)->count();
            $p = new Page($count, 20);
            $items_list = $items_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order($order_str)->select();
            //$items_list = $items_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order($kucun_str)->select();
            foreach ($items_list as $k => $val) {
                $items_list[$k]['key'] = ++$p->firstRow;
                $items_list[$k]['items_cate'] = $items_cate_mod->field('name')->where('id=' . $val['cate_id'])->find();
            }
            $page = $p->show();
            $this->assign('page', $page);
            $cate_list = $items_cate_mod->get_list(5);
            $this->assign('cate_list', $cate_list['sort_list']);
            $this->assign('items_list', $items_list);
            $this->assign('order', $order);
            if ($sort == 'desc') {
                $sort = 'asc';
            } else {
                $sort = 'desc';
            }
            $this->assign('sort', $sort);
            $this->display();
        }
    }

    public function test()
    {
        //$items_mod = M('goods')->select();

        // $this->assign('list', $items_mod);
        $this->display();
    }

    public function test_post()
    {
        import("ORG.Util.Page");
        $model = M('goods');
        $count = $model->count();
        //echo $model->count()->getLastSql();exit;
        $pageSize=200;
        $p = new Page($count, $pageSize);
        $items_mod = $model->limit($p->firstRow . ',' . $p->listRows)->order("id desc")->select();
        $pageTotal=ceil($count/$pageSize);
        $this->ajaxReturn(array("data"=>$items_mod,"page_total"=>$pageTotal));
    }

    /**
     * webuploader 上传文件
     */
    public function ajax_upload()
    {
        // 根据自己的业务调整上传路径、允许的格式、文件大小
        ajax_upload('/data/goods_video/');
    }

    public function edit()
    {
        $items_mod = D('goods');
        //dump($items_mod);
        if (isset($_POST['dosubmit'])) {
            if ($_POST['id'] == 337 && $_SESSION['admin_info']['id'] != 1) {
                $node_id = M("node")->where(array('module' => MODULE_NAME, 'action' => "dpedit"))->getField('id');
                $rel = M("access")->where(array('node_id' => $node_id, 'role_id' => $_SESSION['admin_info']['role_id']))->count();
                if ($rel == 0) {
                    $this->error(L('_VALID_ACCESS_'));
                }
            }
            if ($data = $items_mod->create()) {
//                if ($_REQUEST['pcode'] == '') {
//                    $this->error('请填写商品编号');
//                }

                $data['good_name'] = addslashes($data['good_name']);
                if ($_REQUEST['good_name'] == '') {
                    $this->error('请填写商品标题');
                }
                if ($_REQUEST['pcate_id'] == '') {
                    $this->error('请填写一级分类');
                }
                if ($_REQUEST['cate_id'] == '') {
                    $this->error('请填写商品分类');
                }
                if ($_REQUEST['mp_id'] == '') {
                    $this->error('请填写商品品牌');
                }
                if ($_REQUEST['gx_id'] == '') {
                    $this->error('请填写商品功效');
                }
                if (empty($_REQUEST['mp_cate'])) {
                    $this->error('请选择商品品类');
                }

                if (empty($_REQUEST['nation_id'])) {
                    $this->error('请选择商品所属国籍');
                }
                //var_dump($items_mod->create()); die;
                $items_mod->sort_order = (int)$items_mod->sort_order;
                $items_mod->price = round((float)$items_mod->price, 2);
                $items_mod->orgprice = round((float)$items_mod->orgprice, 2);
                $items_mod->fp_price = round((float)$items_mod->fp_price, 2);
                $items_mod->last_time = time();
                /*            $content = $_REQUEST['good_name'];
                  if (is_array($content))	{
                  foreach ($content as $key=>$value){
                  $content[$key] = addslashes($value);
                  }
                  }else{
                  $content=addslashes($content);
                  }

                  $items_mod -> good_name = htmlspecialchars($content);; */

                if ($_FILES['img']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    //裁剪----------
                    $this->caijian($upload_list['0']['savename']);
                    $s_img = $this->s_imgurl;
                    //---------------结束
                    $items_mod->img = 'data/goods/m_' . $upload_list['0']['savename'];
                    $items_mod->simg = 'data/goods/s_' . $upload_list['0']['savename'];
                    $items_mod->bimg = $s_img;
                    //$items_mod->img = $items_mod->simg= $items_mod->bimg = $this->site_root . 'data/goods/m_' . $upload_list['0']['savename'];
                    //$items_mod->img = $items_mod->simg= $items_mod->bimg = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($_FILES['img2']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img2']);
                    $items_mod->img2 = $items_mod->simg = $items_mod->bimg = 'data/goods/m_' . $upload_list['0']['savename'];
                }

                //csb
                if ($_POST['video'] != '') {

                    $items_mod->img_video = $_POST['video'];
                }

                if ($_FILES['img3']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img3']);
                    $items_mod->img3 = 'data/goods/m_' . $upload_list['0']['savename'];
                }

                if ($_FILES['img4']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img4']);
                    $items_mod->img4 = 'data/goods/m_' . $upload_list['0']['savename'];
                }


                $gos_m = M('goods');
                $gos_gq = $gos_m->where('id=' . $_REQUEST['id'])->find();
                if ($gos_gq['status'] == 0 && $_REQUEST['status'] == 1)
                    $items_mod->add_time = time();

                $result = $items_mod->save();

                if (false !== $result) {
                    $this->success('修改商品信息成功', U('goods/index'));
                } else {
                    $this->error('修改商品信息失败');
                }
            } else {
                $this->error('修改商品信息失败');
            }
        } else {
            $items_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select'));
            $items_info = $items_mod->where('id=' . $items_id)->find();
            $items_cate_mod = D('items_cate');
//            $cate_list = $items_cate_mod->get_list(5);
//            $this->assign('cate_list', $cate_list['sort_list']);

            /* 新增的一级分类 */
            $items_cate = M("items_cate");
            $listed = $items_cate->field('id,name')->where("pid='5'")->select();
            /* echo $items_cate->getLastSql(); */
            $this->assign('listed', $listed);

            //商品分类
            $cate_list = $items_cate_mod->where("pid='" . $items_info['pcate_id'] . "'")->select();
            $this->assign('cate_list', $cate_list);

            //品牌
            $mingp = M('mingp');
            $mingp = $mingp->field('id,name')->where("cate_id='" . $items_info['pcate_id'] . "'")->select();
            $this->assign('mingp', $mingp);

            //商品品类
            $mp_cate_mod = M("mingp_cate");
            $mp_cate_list = $mp_cate_mod->field('id,name')->where("status='1' and cate_id='" . $items_info['pcate_id'] . "'")->select();
            $this->assign('mp_cate_list', $mp_cate_list);

            // 国籍选择
            $nat = M("nationality");
            $nat_list = $nat->where("cate_id='" . $items_info['pcate_id'] . "'")->select();
            $this->assign('nat_list', $nat_list);

            $gongx = M('gongx');
            $gongx = $gongx->select();
            $this->assign('gongx', $gongx);

            $this->assign('items', $items_info);
            $this->display();
        }
    }

    public function edit_kucun()
    {
        $items_mod = D('goods');
        if (isset($_POST['dosubmit'])) {
            if ($data = $items_mod->create()) {
//                if ($_REQUEST['pcode'] == '') {
//                    $this->error('请填写商品编号');
//                }
                if ($_REQUEST['good_name'] == '') {
                    $this->error('请填写商品标题');
                }
                if ($_REQUEST['cate_id'] == '') {
                    $this->error('请填写商品分类');
                }
                if ($_REQUEST['cate_id'] == '') {
                    $this->error('请填写商品分类');
                }
                if ($_REQUEST['mp_id'] == '') {
                    $this->error('请填写商品品牌');
                }
                if ($_REQUEST['gx_id'] == '') {
                    $this->error('请填写商品功效');
                }

                $items_mod->sort_order = (int)$items_mod->sort_order;
                $items_mod->price = round((float)$items_mod->price, 2);
                $items_mod->orgprice = round((float)$items_mod->orgprice, 2);
                $items_mod->fp_price = round((float)$items_mod->fp_price, 2);
                $items_mod->last_time = time();

                if ($_FILES['img']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img']);
                    $items_mod->img = $items_mod->simg = $items_mod->bimg = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($_FILES['img2']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['img2']);
                    $items_mod->img2 = $items_mod->simg = $items_mod->bimg = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                $result = $items_mod->save();
                if (false !== $result) {
                    $this->success('修改商品信息成功', U('goods/kucun'));
                } else {
                    $this->error('修改商品信息失败');
                }
            } else {
                $this->error('修改商品信息失败');
            }
        } else {
            $items_id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error(L('please_select'));
            $items_info = $items_mod->where('id=' . $items_id)->find();
            $items_cate_mod = D('items_cate');
            $cate_list = $items_cate_mod->get_list(5);
            $this->assign('cate_list', $cate_list['sort_list']);
            $mingp = M('mingp');
            $mingp = $mingp->select();
            $this->assign('mingp', $mingp);
            $gongx = M('gongx');
            $gongx = $gongx->select();
            $this->assign('gongx', $gongx);

            $this->assign('items', $items_info);
            $this->display();
        }
    }

    public function add()
    {
        if (isset($_POST['dosubmit'])) {
            $items_mod = D('goods');
            $items_cate_mod = D('items_cate');
            if (false === $items_mod->create()) {
                $this->error($items_mod->error());
            }
            $items_mod->good_name = addslashes($items_mod->good_name);
            if (empty($items_mod->good_name)) {
                $this->error('请填写商品标题');
            }
            if (empty($items_mod->pcate_id)) {
                $this->error('请填写一级分类');
            }
            if (empty($items_mod->cate_id)) {
                $this->error('请填写商品分类');
            }
            if (empty($items_mod->mp_id)) {
                $this->error('请选择商品品牌');
            }
            if (empty($items_mod->mp_cate)) {
                $this->error('请选择商品品类');
            }
            if (empty($items_mod->nation_id)) {
                $this->error('请选择商品所属国籍');
            }
            if (empty($items_mod->gx_id)) {
                $this->error('请选择商品功效');
            }
            if (empty($items_mod->fh_type)) {
                $this->error('请选择发货方式');
            }
            $items_mod->fh_type = $_POST['fh_type'];
            $items_mod->pcode = trim($items_mod->pcode);
            $items_mod->price = round((float)$items_mod->price, 2);
            $items_mod->orgprice = round((float)$items_mod->orgprice, 2);
            $items_mod->fp_price = round((float)$items_mod->fp_price, 2);
            if (empty($items_mod->pcode)) {
                $this->error('请填写商品编号');
            }
            $result = $items_mod->where("pcode='" . $items_mod->pcode . "'")->count();
            if ($result != 0) {
                $this->error('该商品编号已经存在');
            }
            if ($_REQUEST['status'] == 1)
                $items_mod->add_time = time();
            $items_mod->goods_addtime = time();
            $items_mod->hits = 0;
            //
            if ($_FILES['img']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img']);
                //裁剪----------
                $this->caijian($upload_list['0']['savename']);
                $s_img = $this->s_imgurl;
                //----------------结束
                //$items_mod->img = $this->site_root . 'data/goods/m_' . $upload_list['0']['savename'];
                $items_mod->img = 'data/goods/m_' . $upload_list['0']['savename'];
                $items_mod->simg = 'data/goods/s_' . $upload_list['0']['savename'];
                $items_mod->bimg = $s_imgurl;
                $upload_list = $this->_upload($_FILES['img2']);
                $items_mod->img2 = 'data/goods/m_' . $upload_list['0']['savename'];
            } else {
                $items_mod->img = $_POST['input_img'];
            }

            //csb---------------------
            if ($_FILES['img3']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img3']);
                $items_mod->img3 = 'data/goods/m_' . $upload_list['0']['savename'];

            } else {
                $items_mod->img = $_POST['input_img'];
            }
            //---------------------
            if ($_FILES['img4']['name'] != '') {
                $upload_list = $this->_upload($_FILES['img4']);
                $items_mod->img4 = 'data/goods/m_' . $upload_list['0']['savename'];

            } else {
                $items_mod->img = $_POST['input_img'];
            }
            //---------------------
            if ($_POST['video'] != '') {
                $items_mod->img_video = $_POST['video'];
            }


            $new_item_id = $items_mod->add();
            if ($new_item_id) {
                $items_cate_mod->setInc('item_nums', 1);
                $this->success("新增商品信息成功");
            } else {
                $this->error('新增商品信息失败');
            }
        } else {
//            $items_cate_mod = D('items_cate');
//            $cate_list = $items_cate_mod->get_list(5);
//            $this->assign('cate_list', $cate_list['sort_list']);
//            // 国籍选择
//            $nat = M("nationality");
//            $nat_list = $nat->select();
//            $this->assign('nat_list', $nat_list);

            $gongx = M('gongx');
            $gongx = $gongx->select();
            $this->assign('gongx', $gongx);
            $this->display();
        }
    }

    /**
     * 获取一级分类
     */
    function get_pcate()
    {

        $items_cate_mod = D('items_cate');
        $list = $items_cate_mod->field('id,name')->where("pid='5'")->select();
        echo json_encode($list);
    }

    /**
     * 获取二级分类
     */
    function get_cate()
    {
        $id = $_POST['id'];
        $items_cate_mod = D('items_cate');
        $data = $items_cate_mod->field(" id,name")->where("pid='" . $id . "' ")->select();
        echo json_encode($data);
    }

    /**
     * 获取品牌
     */
    function get_mp()
    {
        $id = $_POST['id'];
        $mingp = M('mingp');
        $mingps = $mingp->field('id,name')->where("is_enable='1' and cate_id='" . $id . "'")->select();
        echo json_encode($mingps);
    }

    /**
     * 获取品类
     */
    function get_mp_cate()
    {
        //商品品类
        $id = $_POST['id'];
        $mp_cate_mod = M("mingp_cate");
        $mp_cate_list = $mp_cate_mod->field('id,name')->where("status='1' and cate_id='" . $id . "'")->select();
        //$this->assign('mp_cate_list', $mp_cate_list);
        echo json_encode($mp_cate_list);
    }

    function nat_list()
    {
        // 国籍选择
        $id = $_POST['id'];
        $nat = M("nationality");
        $nat_list = $nat->field("id,nationality as name")->where("cate_id='" . $id . "'")->select();
        echo json_encode($nat_list);
    }

    function delete()
    {
        $items_mod = D('goods');
        $items_cate_mod = D('items_cate');
        if ((!isset($_GET['id']) || empty($_GET['id'])) && (!isset($_POST['id']) || empty($_POST['id']))) {
            $this->error('请选择要删除的商品！');
        }
        if (isset($_REQUEST['id'])) {
            $cate_ids = is_array($_REQUEST['id']) ? implode(',', $_REQUEST['id']) : intval($_REQUEST['id']);
            $items_mod->delete($cate_ids);
            $path = ROOT_PATH . "/data/goods";
            delDirFile($path, $_REQUEST['id']);
            $items_cate_mod->upCateNum($_REQUEST['id']);   //更新items_cate表里面的数据
        }
        $this->success(L('operation_success'));
    }

    public function _upload($imgage, $path = '', $isThumb = true)
    {
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize = 3292200;
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');

        if (empty($savePath)) {
            $upload->savePath = './data/goods/';
        } else {
            $upload->savePath = $path;
        }

        if ($isThumb === true) {
            $upload->thumb = true;
            $upload->imageClassPath = 'ORG.Util.Image';
            //设置前缀，逗号隔开
            $upload->thumbPrefix = 'm_,s_';
            //设置缩略图最大高度,多规格用，隔开
            $upload->thumbMaxWidth = '10240,227';
            $upload->thumbMaxHeight = '10240,234';
            $upload->saveRule = uniqid;
            $upload->thumbRemoveOrigin = true;
        }

        if (!$upload->uploadOne($imgage)) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        return $uploadList;
    }

    //----------增加一个裁剪图片-----------//
    function caijian($src_path)
    {

        //先设置缩放规格
        $dst_w = 300;
        $dst_h = 300;
        //创建原图的实例
        $src_path = 'data/goods/m_' . $src_path;
        $dst_w = 300;
        $dst_h = 300;
        //var_dump($src_path);exit;
        //创建原图的实例
        $src = imagecreatefromjpeg($src_path);
        list($src_w, $src_h) = getimagesize($src_path); // 获取原图尺寸
        $dst_scale = $dst_h / $dst_w; //目标图像长宽比 
        $src_scale = $src_h / $src_w; // 原图长宽比 
        if ($src_scale >= $dst_scale) {
            // 过高 
            $w = intval($src_w);
            $h = intval($dst_scale * $w);
            $x = 0;
            $y = ($src_h - $h) / 3;
        } else {
            // 过宽 
            $h = intval($src_h);
            $w = intval($h / $dst_scale);
            $x = ($src_w - $w) / 2;
            $y = 0;
        }
        // 先缩放比例 
        $scale = $dst_w / $w;
        $target = imagecreatetruecolor($dst_w, $dst_h);
        $final_w = intval($w * $scale);
        $final_h = intval($h * $scale);
        imagecopyresampled($target, $src, 0, 0, 0, 0, $final_w, $final_h, $w, $h);
        // 再剪裁白边，突出商品 
        $source = imagecreatefromjpeg($src_path);
        $croped = imagecreatetruecolor(227, 234);
        imagecopy($croped, $target, 0, 0, 35, 30, 227, 234);

        $s_imgurl = str_replace("m_", "b_", $src_path);
        imagejpeg($croped, $s_imgurl);
        return $s_imgurl;
    }

    //----------结束-----------//

    function status()
    {
        $id = intval($_REQUEST['id']);
        $type = trim($_REQUEST['type']);
        $items_mod = D('goods');
        $res = $items_mod->where('id=' . $id)->setField($type, array('exp', "(" . $type . "+1)%2"));
        $values = $items_mod->where('id=' . $id)->getField($type);
        $tim['add_time'] = time();
        if ($values[$type] == 1)
            $items_mod->where('id=' . $id)->save($tim);
        $this->ajaxReturn($values[$type]);
    }

    /*
     * 获取子分类至获取单级的分类
     */

    public function get_child_cates()
    {
        $items_cate_mod = $this->items_cate_mod;
        $parent_id = $this->_get('parent_id', 'intval');
        $cate_list = $items_cate_mod->field('id,name')->where(array('pid' => $parent_id))->order('ordid asc')->select();
        $content = "";
        foreach ($cate_list as $val) {
            $content .= "<option value='" . $val['id'] . "'>" . $val['name'] . "</option>";
        }
        echo $content;
        exit;
    }

    /*
     * 按搜索条件删除
     */

    function delete_search()
    {
        $items_mod = D('items');
        $items_cate_mod = D('items_cate');
        if (isset($_REQUEST['dosubmit'])) {
            if ($_REQUEST['isok'] == "1") {
                //搜索
                $where = '1=1';
                $keyword = trim($_POST['keyword']);
                $cate_id = trim($_POST['cate_id']);
                $time_start = trim($_POST['time_start']);
                $time_end = trim($_POST['time_end']);
                $status = trim($_POST['status']);
                $min_price = trim($_POST['min_price']);
                $max_price = trim($_POST['max_price']);

                if ($keyword != '') {
                    $where .= " AND title LIKE '%" . $keyword . "%'";
                }
                if ($cate_id != '') {
                    $where .= " AND cid=" . $cate_id;
                }
                if ($time_start != '') {
                    $time_start_int = strtotime($time_start);
                    $where .= " AND add_time>='" . $time_start_int . "'";
                }
                if ($time_end != '') {
                    $time_end_int = strtotime($time_end);
                    $where .= " AND add_time<='" . $time_end_int . "'";
                }
                if ($status != '') {
                    $where .= " AND status=" . $status;
                }
                if ($min_price != '') {
                    $where .= " AND price>=" . $min_price;
                }
                if ($max_price != '') {
                    $where .= " AND price<=" . $max_price;
                }

                $ids_list = $items_mod->where($where)->select();
                $ids = "";
                foreach ($ids_list as $val) {
                    $ids .= $val['id'] . ",";
                }
                $items_mod->where($where)->delete();

                //更新商品分类的数量
                $items_nums = $items_mod->field('cid,count(id) as cate_nums')->group('cid')->select();
                foreach ($items_nums as $val) {
                    $items_cate_mod->save(array('id' => $val['cid'], 'items_nums' => $val['cate_nums']));
                }

                $this->success('删除成功', U('goods/delete_search'));
            } else {
                $this->success('确认是否要删除？', U('goods/delete_search'));
            }
        } else {
            $cate_list = $items_cate_mod->get_list(5);
            $this->assign('cate_list', $cate_list['sort_list']);
            $this->display();
        }
    }

    public function kucun()
    {
        $items_mod = D('goods');
        $items_cate_mod = D('items_cate');
        //搜索
        $where = '1=1';
        $keyword = isset($_GET['keyword']) && trim($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $cate_id = isset($_GET['cate_id']) && intval($_GET['cate_id']) ? intval($_GET['cate_id']) : '';
        $time_start = isset($_GET['time_start']) && trim($_GET['time_start']) ? trim($_GET['time_start']) : '';
        $time_end = isset($_GET['time_end']) && trim($_GET['time_end']) ? trim($_GET['time_end']) : '';
        $status = isset($_GET['status']) ? intval($_GET['status']) : '-1';
        $order = isset($_GET['order']) && trim($_GET['order']) ? trim($_GET['order']) : '';
        $sort = isset($_GET['sort']) && trim($_GET['sort']) ? trim($_GET['sort']) : 'desc';
        if ($keyword) {
            $where .= " AND good_name LIKE '%" . $keyword . "%'";
            $this->assign('keyword', $keyword);
        }
        if ($cate_id) {
            $where .= " AND cate_id=" . $cate_id;
            $this->assign('cate_id', $cate_id);
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
        $where .= " AND kucun<=10";
        $status >= 0 && $where .= " AND status=" . $status;
        $this->assign('status', $status);
        //排序
        $order_str = 'add_time desc';
        if ($order) {
            $order_str = $order . ' ' . $sort;
        }
        import("ORG.Util.Page");
        $count = $items_mod->where($where)->count();
        $p = new Page($count, 20);
        $items_list = $items_mod->where($where)->limit($p->firstRow . ',' . $p->listRows)->order($order_str)->select();
        foreach ($items_list as $k => $val) {
            $items_list[$k]['key'] = ++$p->firstRow;
            $items_list[$k]['items_cate'] = $items_cate_mod->field('name')->where('id=' . $val['cate_id'])->find();
        }

        $page = $p->show();
        $this->assign('page', $page);
        $cate_list = $items_cate_mod->get_list(5);
        $this->assign('cate_list', $cate_list['sort_list']);
        $this->assign('items_list', $items_list);
        $this->assign('order', $order);
        if ($sort == 'desc') {
            $sort = 'asc';
        } else {
            $sort = 'desc';
        }
        $this->assign('sort', $sort);
        $this->display();
    }

    public function kucunc_bianiji()
    {
        $info_mod = M('goods');
        if (isset($_GET['id'])) {
            $id = isset($_GET['id']) && intval($_GET['id']) ? intval($_GET['id']) : $this->error('请选择要查看的链接');
        }
        $info = $info_mod->where('cate_id=' . $id)->find();
        $this->assign('info', $info);
        $this->display();
    }

    public function ss()
    {
        echo "<a href='{:U('goods/edit',array('id'=>1001))}'>3</a>";
    }

    public function search()
    {
        if ($this->isAjax()) {
            if (is_numeric($_POST['good'])) {
                $good = intval($_POST['good']);
                $where['id'] = $good;
            } else {
                $good = trim($_POST['good']);
                $where['good_name'] = array('like', '%' . $good . '%');
            }
            $status = 1;
            $info = 'ok';
            $data = M('goods')->where($where)->field('id,img')->find();
            if (empty($data)) {
                $status = 0;
                $info = 'no';
            }
            $this->ajaxReturn($data, $info, $status);
        }
    }

    function gs()
    {
        $where = ' where';
        if (isset($_GET['gid']) && $_GET['gid'] != "") {
            $where .= ' gs.gid=' . intval($_GET['gid']);
        }
        if (isset($_GET['good_name']) && $_GET['good_name'] != "") {
            $where == ' where' || $where .= ' and';
            $where .= " goods.good_name like '%" . trim($_GET['good_name']) . "%'";
        }
        if ($where == ' where')
            $where = '';
        $gs = M('gs');
        $gslist = $gs->query("select gs.*,goods.id gid,goods.good_name,goods.orgprice from sk_gs gs inner join sk_goods goods on gs.gid=goods.id" . $where . " order by id desc");
        import("ORG.Util.Page");
        $count = $gs->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        $this->assign('list', $gslist);
        $this->display();
    }

    function gs_cz()
    {
        $cz = $_REQUEST['cz'] ? trim($_REQUEST['cz']) : '';
        $gs = M('gs');
        $id = $_REQUEST['id'] ? trim($_REQUEST['id']) : 0;
        if ($this->isPost()) {
            if ($cz == 'status') {
                ($status = $gs->where('id=' . $id)->getField('status')) !== false || $this->error(false);
                $status = $status == 1 ? 0 : 1;
                $gs->where('id=' . $id)->setField('status', $status);
                $this->ajaxReturn($status);
            } else if ($cz == 'del' && $gs->delete(implode(',', $_POST['id'])))
                $this->success('删除成功');
            if ($gs->create()) {
                $gs->start_time = strtotime($gs->start_time);
                $gs->end_time = strtotime($gs->end_time);
                if ($_FILES['view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['view']);
                    $gs->view = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($_FILES['price_view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['price_view']);
                    $gs->price_view = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($cz == 'add') {
                    $data = $gs->where('gid=' . $gs->gid)->order('end_time desc')->getField('end_time');
                    if (isset($data['end_time']) && $data['end_time'] > time())
                        $this->error('已有该商品且还没过期');
                    else
                        if ($gs->add())
                            $this->success('添加成功', U('goods/gs'));
                } else if ($cz == 'edit' && $gs->save() !== false)
                    $this->success('修改成功', U('goods/gs'));
                $this->error('数据失败');
            }
        } else if ($cz == 'edit') {
            if (!$data = $gs->find($id))
                $this->error('没有此团购');
            $this->assign('data', $data);
        }
        $this->assign('cz', $cz);
        $this->display();
    }

    function sg()
    {
        $where = array();
        if (isset($_GET['gid']) && $_GET['gid'] != "") {
            $where['gid'] = intval($_GET['gid']);
        }
        if (isset($_GET['good_name']) && $_GET['good_name'] != "") {
            $where['good_name'] = array("like", "%" . trim($_GET['good_name']) . "%");
        }
        $sg = M('sg');
        //$sglist=$sg->query("select sg.*,goods.good_name,goods.orgprice from sk_sg sg inner join sk_goods goods on gs.gid=goods.id".$where." order by id desc");
        $sglist = $sg->field("sk_sg.*,g.id gid,good_name,orgprice")->join("sk_goods g on sk_sg.gid=g.id")->where($where)->order(array("id" => "desc"))->select();
        import("ORG.Util.Page");
        $count = $sg->where($where)->count();
        $Page = new Page($count, 15);
        $show = $Page->show();
        $this->assign('list', $sglist);
        $this->display();
    }

    function sg_cz()
    {
        $cz = $_REQUEST['cz'] ? trim($_REQUEST['cz']) : '';
        $sg = M('sg');
        $id = $_REQUEST['id'] ? trim($_REQUEST['id']) : 0;
        if ($this->isPost()) {
            if ($cz == 'status') {
                ($status = $sg->where('id=' . $id)->getField('status')) !== false || $this->error(false);
                $status = $status == 1 ? 0 : 1;
                $sg->where('id=' . $id)->setField('status', $status);
                $this->ajaxReturn($status);
            } else if ($cz == 'del' && $sg->delete(implode(',', $_POST['id'])))
                $this->success('删除成功');
            if ($sg->create()) {
                $sg->start_time = strtotime($sg->start_time);
                $sg->end_time = strtotime($sg->end_time);
                if ($_FILES['view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['view']);
                    $sg->view = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($_FILES['price_view']['name'] != '') {
                    $upload_list = $this->_upload($_FILES['price_view']);
                    $sg->price_view = 'data/goods/m_' . $upload_list['0']['savename'];
                }
                if ($cz == 'add') {
                    $data = $sg->where('gid=' . $sg->gid)->order('end_time desc')->getField('end_time');
                    if (isset($data['end_time']) && $data['end_time'] > time())
                        $this->error('已有该商品且还没过期');
                    else
                        if ($sg->add())
                            $this->success('添加成功', U('goods/sg'));
                } else if ($cz == 'edit' && $sg->save() !== false)
                    $this->success('修改成功', U('goods/sg'));
                $this->error('数据失败');
            }
        } else if ($cz == 'edit') {
            $data = $sg->find($id);
            if (empty($data))
                $this->error('没有此闪购');
            $this->assign('data', $data);
        }
        $this->assign('cz', $cz);
        $this->display();
    }

    function dp_price()
    {
        $dp = M('dp_price');
        $price = $dp->where('id=1')->field('id,dp_price')->find();
        $this->assign('price', $price['dp_price']);
        if (isset($_POST['dosubmit'])) {
            if (empty($_POST['dp_price'])) {
                $this->error('调整比例不能为空');
            }
            if (is_numeric($_POST['dp_price'])) {

                $dp_price = $_POST['dp_price'];
            } else {
                $this->error('填写的格式不对');
            }
            $data['dp_price'] = $dp_price;
            $res = $dp->where('id=1')->save($data);
            if ($res) {
                $this->success('调整成功！');
            } else {
                $this->error('调整失败');
            }
        }
        $this->display();
    }

}

?>