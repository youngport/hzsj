<?php
	//BB商城商品列表
	class bbgoodsAction extends baseAction{

    public function index() {
        $items_mod = D('goods');
        $items_cate_mod = D('items_cate');
        //搜索
    	//以下这些都是从商品表复制过来的代码
   
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
            //是否BB商城商品查询条件,0不上架 1上架
       		$where['bbshop'] = '1';
      
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

    public function edit(){
        $id = $_GET['id'];
        $this->assign('id',$id);//将商品id传到编辑页

        $this->display();

    }
    public function editchuli(){
        $bid = $_POST['bid'];

        $result = M('goods','sk_')->where(array('id'=>$bid))->save(array('bbshop'=>'0'));//BB商城商品下架
        
       if($result){
        $this->success("下架成功!",U('bbgoods/index'));
    }else{
        $this->error("下架失败!");
    }
        
    }
}

?>