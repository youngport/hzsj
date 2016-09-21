<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tuolaji <479923197@qq.com>
// +----------------------------------------------------------------------
namespace ApiAdmin\Controller;
use Common\Controller\ApiAdminbaseController;
class StoreController extends ApiAdminbaseController {
	protected $store_model;
	protected $role=0;
	protected $info;
	protected $auth=array(2,5);//用户归属组检查
	protected $ca=array("store_list");//权限不检查
	protected $open_id='';
	protected $sid=0;
	function _initialize() {
		parent::_initialize();
		$this->store_model = M("erweima","sk_");
		$this->role=M("role_user")->where(array("user_id"=>$this->user_id,'role_id'=>array("in",$this->auth)))->count();
		$this->open_id=I("open_id");
		$this->sid=I("sid",0,"intval");
		if($this->role>0) {
			$this->info = M("role_store")->join("left join sk_erweima on id=sid")->where("uid=" .$this->user_id)->getField("id,openid", true);//如果是用户组的话就统计出他的终端,下面的方法在访问前会检查此终端是否在这组数据里,不在你懂的
			if(empty($this->info)){
				$this->info=array("");
			}
			if(!in_array(ACTION_NAME,$this->ca)) {
				if (($this->open_id==''||!in_array($this->open_id,$this->info))&&($this->sid==0||!array_key_exists($this->sid,$this->info))) {
					$this->ajaxReturn(array('status'=>99,'message'=>'没有权限访问'));
				}
			}
		}
	}
	function store_list(){
		$formget['shenhe']=1;
		if($this->sid==0&&$this->role>0) {
			$formget['id'] = array("in",array_keys($this->info));
		}elseif($this->sid>0&&($this->role==0||array_key_exists($this->sid,$this->info))){
			$formget['id'] = $this->sid;
		}elseif($this->role>0){
			$this->ajaxReturn(array('status'=>99,'message'=>'没有权限访问'));
		}
		$data['info']=array('shouyi'=>0,'partners'=>0,'members'=>0,'orders'=>0);
		$data['list']=$this->store_model->field('id sid,openid,dianpname,dianp_img,(select shouyi from sk_member where open_id=openid) shouyi')->where($formget)->select();
		if(!empty($data['list'])) {
			foreach ($data['list'] as $k => $v) {
				$data['info']['shouyi'] += $v['shouyi'];
				unset($data['list'][$k]['shouyi']);
				$count = utcount($v['openid']);
				$data['info']['partners'] += $count['total']['zcount'];
				$data['info']['members'] += $count['total']['zmcount'];
				$count = otcount($v['openid']);
				$data['info']['orders'] += $count['total']['xd'] + $count['total']['fd'];
			}
			$this->ajaxReturn(array('status' => 1, 'message' => '店铺信息', 'value' => $data));
		}else{
			$this->ajaxReturn(array('status' =>2, 'message' => '没有该店铺'));
		}
	}

	function info(){
		$info=$this->store_model->join("sk_member b on sk_erweima.openid=b.open_id")->field("wei_nickname,headimgurl,dianp_lxfs,bianhao,dianpname,sk_erweima.dizhi,xxdizhi,weixin_account,rmac")->find($this->sid);
		if(empty($info)){
			$this->ajaxReturn(array('status' =>2, 'message' => '没有该店铺'));
		}else{
			$this->ajaxReturn(array('status' => 1, 'message' => '店铺信息', 'value' =>$info));
		}
	}
	function info_edit_post(){
		$store_model=D('Common/Erweima');
		$_POST['id']=$this->sid;
		$data=$store_model->create();
		if(empty($data)){
			$error=explode('.',$store_model->getError());
			if(count($error)==1){
				array_unshift($error,13);
			}
			$this->ajaxReturn(array('status'=>$error[0],'message'=>$error[1]));
		}else{
			$store_shenhe_model=M('ErweimaShenhe','sk_');
			$where['uid']=$this->user_id;
			$where['sid']=$data['id'];
			$where['time']=array('between',array(strtotime(date('Y-m-d')),strtotime(date('Y-m-d'.'+1 day'))));
			if($store_shenhe_model->where($where)->count()>=3){
				$this->ajaxReturn(array('status'=>15,'message'=>'当日申请已超过3次'));
			}
			$data['sid']=$data['id'];
			unset($data['id']);
			$data['time']=time();
			if(isset($_FILES['dianp_img'])&&!empty($_FILES['dianp_img'])){
				$img=upload('dianp_img');
				if($img!==false)$data['dianp_img']=$img;
			}
			$result=$store_shenhe_model->add($data);
			if($result!==false){
				$this->ajaxReturn(array('status'=>1,'message'=>'申请成功'));
			}else{
				$this->ajaxReturn(array('status'=>14,'message'=>'申请失败'));
			}
		}
	}
	function upload($name){
		$upload=new \Think\Upload();
		$upload->rootPath="../";
		$upload->savePath="/wei/up_zhang/";
		$upload->maxSize='4194304';
		$upload->exts=array('jpg', 'gif', 'png', 'jpeg');
		$upload->autoSub=false;
		$upload->saveName=md5(date("YmdHis").mt_rand(1,1000));
		$info=$upload->uploadOne($_FILES[$name]);
		if($info){
			return $info['savepath'].$info['savename'];
		}else {
			return false;
		}
	}
	function partner(){
		$p=I('post.p',1,'intval');
		$limit=I('post.limit',20,'intval');
		$data=utcount($this->open_id,array(),array("open_id","jointag",'wei_nickname','login_time','last_login'),0,'info');
		$pdata=array();
		if(!empty($data)) {
			foreach ($data as $key => $row) {
				$login_time[$key] = $row['login_time'];
			}
			array_multisort($login_time, SORT_DESC, $data);
			$p=$p<=0?1:$p;
			$p = ($p-1) * $limit;
			$pdata = array_slice($data, $p, $limit);
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'总盟友信息列表','value'=>$pdata));
	}

	function onepartner(){
		$p=I('post.p',1,'intval');
		$limit=I('post.limit',20,'intval');
		$where['pid']=$this->open_id;
		$this->user_model = M("member","sk_");
		$data=$this->user_model->field("open_id,jointag,wei_nickname,headimgurl")->where($where)->page("$p,$limit")->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'一级盟友列表','value'=>$data));
	}

	function income(){
		$pop_model=M("pop","wei_");
		$formget['openid']=$this->open_id;
		$p=I('post.p',1,'intval');
		$limit=I('post.limit',20,'intval'); 
		$order['shijianc']='desc';
		//$count=$pop_model->join("left join sk_orders b on wei_pop.order_sn=b.order_sn")->where($formget)->count();
		$list=$pop_model->join("left join sk_orders b on wei_pop.order_sn=b.order_sn")->field("b.order_sn,(select wei_nickname from sk_member where open_id=b.buyer_id) wei_nickname,order_amount,pop,shijianc")->page("$p,$limit")->where($formget)->order($order)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'收益信息','value'=>$list));
	}

	function order(){
		$p=I('post.p',1,'intval');
		$limit=I('post.limit',20,'intval');
		$field=array("order_sn","buyer_id","status","add_time","finish_time","goods_amount","order_amount");
		$data=otcount($this->open_id,array(),$field,'info');
		$pdata=array();
		if(!empty($data)) {
			foreach ($data as $key => $row) {
				$add_time[$key] = $row['add_time'];
			}
			array_multisort($add_time, SORT_DESC, $data);
			$p=$p<=0?1:$p;
			$p = ($p-1) * $limit;
			$pdata = array_slice($data, $p, $limit);
		}
		$this->ajaxReturn(array('status'=>1,'message'=>'订单信息','value'=>$pdata));
	}
	function goods_action(){
		$formget=array();
		$data = goods_even($this->open_id,array(),$formget);
		$rel = array();
		foreach ($data as $key => $value) {
			$rel[$key]['id'] = $value['id'];
			$rel[$key]['name'] = $value['good_name'];
			$rel[$key]['r_num'] = $value['r_count'];
			$rel[$key]['l_num'] = $value['l_count'];
		}
		unset($data);
		$this->ajaxReturn(array('status'=>1,'message'=>'成功','value'=>$rel));
	}
	function router_count(){
		$info=$this->store_model->field("dianpname,rmac")->find(intval(I("id")));
		import('@.Common.Router');
		$router=new \router();
		$time1['startTime']=date("Y-m-d",strtotime("-1 day"));//昨日
		$time1['endTime']=date("Y-m-d H:i:s",strtotime($time1['startTime']." +1 day")-1);
		$time2['startTime']=date("Y-m-d H:i:s",strtotime("-1 week Monday"));//这个周
		$time2['endTime']=date("Y-m-d H:i:s",strtotime("+0 week Monday")-1);
		$time3['startTime']=date("Y-m");//这个月
		$time3['endTime']=date('Y-m-d', strtotime(date('Y-m-01') . ' +1 month -1 day'));
		$time4['startTime']=date("Y-m-d H:i:s",strtotime("2016-1-1"));//总
		$time4['endTime']=date("Y-m-d H:i:s",strtotime("+1 day")-1);
		$post=array("apMac"=>$info['rmac'],"Timeslot"=>array($time1,$time2,$time3,$time4));
		$post=json_encode($post);
		$data['all']=$router->GetAllVistors($post);
		$data['old']=$router->GetOldVistors($post);
		$data['new']=$router->GetNewVistors($post);
		$this->assign("info",$info);
		$this->assign("data",$data);
		$this->display();
	}

	function order_count(){
		$formget=array();
		if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
		$formget['add_time'][]=array("EGT",date("Y-m-d H:i:s",I("start_time")));
		else
			$formget['add_time'][]=array("EGT",date("Y-m-d",strtotime("-7 day")));
		if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
			$formget['add_time'][]=array("ElT",date("Y-m-d H:i:s",I("end_time")));
		else
		$formget['add_time'][]=array("ElT",date("Y-m-d 11:59",strtotime("-1 day")));
		
		$orderinfo=otcount_days($this->open_id,$formget);
		if (!$orderinfo) {
			$this->ajaxReturn(array('status'=>2,'message'=>'没有下级盟友'));
		}
		unset($orderinfo['total']); 
		$chart=array();
		foreach($orderinfo as $k=>$v){
			$chart['key'][]=$k;
			$chart['xd'][]=$v['xd'];
			$chart['fd'][]=$v['fd'];
		}
		$chart=json_encode($chart);
		$this->assign("chart",$chart);
		$this->display();
	}
	function action_count(){
		$formget=array();
		if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
			$formget['time'][]=array("EGT",I("start_time"));
		else
			$formget['time'][]=array("EGT",strtotime(date("Y-m-d",strtotime("-7 day"))));
		if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
			$formget['time'][]=array("ElT",I("end_time"));
		else
			$formget['time'][]=array("ElT",strtotime(date("Y-m-d 11:59",strtotime("-1 day"))));
		$data=atcount($this->open_id,array(),$formget);
		//$this->ajaxReturn($data);
		$i=1;
		$chart=array("key"=>array(0),"count"=>array(0));
		if(!empty($data)) {
			$chart=array();
			foreach ($data as $k => $v) {
				$chart['key'][] = "TOP" . $i;
				$chart['count'][] = $v['count'];
				unset($data[$k]);
				$data["TOP" . $i] = $v;
				$i++;
			}
		}
		$chart=json_encode($chart);
		$this->assign("chart",$chart);
		$this->display();
	}

	function partner_count(){
		$formget=array();
		$judgement = I('ment');	
		if ($judgement=='') {
			if (strlen($_REQUEST['start_time'])==10&&strlen($_REQUEST['end_time'])==10) {
				//$this->ajaxReturn($_REQUEST['start_time']);
				if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
				$formget['login_time'][]=array("EGT",date("Y-m-d H:i:s",I("start_time")));
				else
					$formget['login_time'][]=array("EGT",date("Y-m-d",strtotime("-7 day")));
				if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
					$formget['login_time'][]=array("ElT",date("Y-m-d H:i:s",I("end_time")));
				else
				$formget['login_time'][]=array("ElT",date("Y-m-d 11:59",strtotime("-1 day")));				
			}elseif (strlen($_REQUEST['start_time'])==13&&strlen($_REQUEST['end_time'])==13) {
				$start = round($_REQUEST['start_time']/1000);
				$end = round($_REQUEST['end_time']/1000);	
				if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
				$formget['login_time'][]=array("EGT",date("Y-m-d H:i:s",$start));
				else
					$formget['login_time'][]=array("EGT",date("Y-m-d",strtotime("-7 day")));
				if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
					$formget['login_time'][]=array("ElT",date("Y-m-d H:i:s",$end));
				else
				$formget['login_time'][]=array("ElT",date("Y-m-d 11:59",strtotime("-1 day")));
			}
			//$this->ajaxReturn($formget);	
			$orderinfo=utcount_days($this->open_id,$formget,1);
            
			$count['total']=$orderinfo['total'];
			$count['total']['mper']=round(($count['total']['zmcount']/$count['total']['zcount'])*100);
			unset($orderinfo['total']);
			$count['now']=$orderinfo['now'];
			$count['now']['mper']=round(($count['now']['nmcount']/$count['now']['ncount'])*100);
			unset($orderinfo['now']);
			$chart=array();
			foreach($orderinfo as $k=>$v){
				$chart['key'][]=$k;
				$chart['zcount'][]=$v['zcount'];
				$chart['zmcount'][]=$v['zmcount'];
				$chart['ncount'][]=$v['ncount'];
				$chart['nmcount'][]=$v['nmcount'];
			}

			$chart=json_encode($chart);
			$this->assign("chart",$chart);//$this->ajaxReturn($chart);
			$this->display();
		}elseif ($judgement==1) {
			if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
			$formget['add_time'][]=array("EGT",date("Y-m-d H:i:s",I("start_time")));
			else
				$formget['add_time'][]=array("EGT",date("Y-m-d",strtotime("-7 day")));
			if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
				$formget['add_time'][]=array("ElT",date("Y-m-d H:i:s",I("end_time")));
			else
			$formget['add_time'][]=array("ElT",date("Y-m-d 11:59",strtotime("-1 day")));
			$orderinfo=otcount_days($this->open_id,$formget);
			unset($orderinfo['total']);
			$chart=array();
			foreach($orderinfo as $k=>$v){
				$chart['key'][]=$k;
				$chart['xd'][]=$v['xd'];
				$chart['fd'][]=$v['fd'];
			}
			$chart=json_encode($chart);
			$this->assign("chart",$chart);
			$this->display("order_count");				
		}elseif ($judgement==2) {
			if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
			$formget['time'][]=array("EGT",I("start_time"));
			else
				$formget['time'][]=array("EGT",strtotime(date("Y-m-d",strtotime("-7 day"))));
			if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
				$formget['time'][]=array("ElT",I("end_time"));
			else
			$formget['time'][]=array("ElT",strtotime(date("Y-m-d 11:59",strtotime("-1 day"))));
			$data=atcount($this->open_id,array(),$formget);
			$i=1;
			$chart=array("key"=>array(0),"count"=>array(0));
			if(!empty($data)) {
				$chart=array();
				foreach ($data as $k => $v) {
					$chart['key'][] = "TOP" . $i;
					$chart['count'][] = $v['count'];
					unset($data[$k]);
					$data["TOP" . $i] = $v;
					$i++;
				}
			}
			$chart=json_encode($chart);
			$this->assign("chart",$chart);
			$this->display("action_count");
		}elseif ($judgement==3) {
			
		}		
	}
    
    function income_count(){
    	$formget=array();
		if(isset($_REQUEST['start_time'])&&$_REQUEST['start_time']!='')
			$formget['shijianc'][]=array("EGT",I("start_time"));
		else
			$formget['shijianc'][]=array("EGT",strtotime(date("Y-m-d",strtotime("-7 day"))));
		if(isset($_REQUEST['end_time'])&&$_REQUEST['end_time']!='')
			$formget['shijianc'][]=array("ElT",I("end_time"));
		else
			$formget['shijianc'][]=array("ElT",strtotime(date("Y-m-d 11:59",strtotime("-1 day"))));
		$data=income_days($this->open_id,$formget);    
		if(!empty($data)) {
			$chart=array();
			foreach ($data as $k => $v) {
				$chart['key'][] = $k;
				$chart['id'][] = $v['id'];
				unset($data[$k]);
				$chart['pop'][] = $v['pop'];
				$chart['order_amount'][] = $v['order_amount'];
			}
		}
		$chart=json_encode($chart);
		$this->assign("chart",$chart);
		$this->display();
    }
	function comment(){
		$dppinglun=M("dppinglun","sk_");
		$formget['dianpid']=$this->sid;
		$formget['jiancha']=1;
		$formget['is_jx']=0;
		$order['shijian']='desc';
		$data['common']=$dppinglun->join("sk_member on open_id=openid")->where($formget)->order($order)->field("id,sk_dppinglun.pid,neirong,openid,wei_nickname,headimgurl,shijian,zan,content,hf_name")->select();
		$formget['is_jx']=1;
		$data['jx']=$dppinglun->join("sk_member on open_id=openid")->where($formget)->order($order)->field("id,sk_dppinglun.pid,neirong,openid,wei_nickname,headimgurl,shijian,zan,content,hf_name")->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'评论列表','value'=>$data));
	}
	function is_jx(){
		$dppinglun=M('dppinglun','sk_');
		$formget['id']=I("cid");
		$formget['dianpid']=$this->sid;
		$cid=$dppinglun->field("id,is_jx")->where($formget)->find();
		if(!empty($cid)){
			$result=$dppinglun->where($formget)->setField(array('is_jx'=>($cid['is_jx']==1?0:1)));			
			if($result!==false){
				$this->ajaxReturn(array('status'=>1,'message'=>'修改成功'));
			}else{
				$this->ajaxReturn(array('status'=>3,'message'=>'修改失败'));
			}
		}else{
			$this->ajaxReturn(array('status'=>98,'message'=>'数据对象错误'));
		}
	}
	function reply(){
		$dppinglun=M('dppinglun','sk_');
		$formget['id']=I("cid");
		$formget['dianpid']=$this->sid;
		if($dppinglun->where($formget)->count()>0){
			$data['pid']=$formget['id'];
			$data['dianpid']=$formget['dianpid'];
			$data['neirong']=I("neirong");
			$data['jiancha']=1;
			$data['shijian']=time();
			$data['openid']=$this->store_model->getFieldById($this->sid,"openid");
			if(empty($data['neirong'])){
				$this->ajaxReturn(array('status'=>3,'message'=>'内容不存在'));
			}
			if($dppinglun->add($data)!==false) {
				$this->ajaxReturn(array('status' => 1, 'message' => '回复成功'));
			}else{
				$this->ajaxReturn(array('status' => 4, 'message' => '回复失败'));
			}
		}else{
			$this->ajaxReturn(array('status'=>2,'message'=>'评论ID非法'));
		}
	}
	function mcoupon_list(){
		$time=time();
		$where['open_id']=$this->store_model->getFieldById($this->sid,"openid");
		$where['money']=array("EGT",0);
		$where['status']=1;
		$where['start_time']=array("ELT",$time);
		$where['end_time']=array("EGT",$time);
		$data=M("mcoupon","sk_")->field("id,money,start_time,end_time")->where($where)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'现金券列表','value'=>$data));
	}
	function coupon_list(){
		$where['sendid']=$this->sid;
		$data=M("coupon","sk_")->field("id,sn,(select wei_nickname from sk_member where open_id=rec) wei_nickname,rec,xz,js,start_time,end_time,add_time")->where($where)->select();
		$this->ajaxReturn(array('status'=>1,'message'=>'商家发放优惠券列表','value'=>$data));
	}
	function cfcoupon_post(){
		$mcoupon_model=M("mcoupon","sk_");
		$coupon_model=M("coupon","sk_");
		$time=time();
		if($data=$coupon_model->create()){
			$data['sendid']=$this->sid;
			$this->open_id=$this->store_model->getFieldById($this->sid,"openid");
			$where['id']=I('mid');
			$where['open_id']=$this->open_id;
			$where['money']=array("EGT",0);
			$where['status']=1;
			$where['start_time']=array("ELT",$time);
			$where['end_time']=array("EGT",$time);
			$mdata=$mcoupon_model->where($where)->find();
			if(!empty($mdata)){
				if($data['xz']>$mdata['money']){
					$this->ajaxReturn(array('status'=>3,'message'=>'超出现金券持有金额'));
				}
				if($data['js']==0||$data['js']>$mdata['money']||$data['xz']<=$data['js']){
					$this->ajaxReturn(array('status'=>4,'message'=>'金额为0或超出限制金额'));
				}
				if($data['start_time']==''||$data['end_time']==''){
					$this->ajaxReturn(array('status'=>5,'message'=>'时间设置错误'));
				}
				$rec=explode(",",$data['rec']);
				foreach($rec as $v) {
					$data['rec']=$v;
					if (M("member", "sk_")->where(array("pid" => $this->open_id, "open_id" => $data['rec']))->count() <= 0) {
						$info[]=array('rec'=>$data['rec'],'status' => 6, 'message' => '用户不存在或不是下级');
						continue;
					}
					$data['sn'] = substr(md5("hz" . time().mt_rand(1000,9999)), 0, 5);
					$data['add_time'] = time();
					if ($coupon_model->add($data) !== false) {
						$info[]=array('rec'=>$data['rec'],'status' => 1, 'message' => '优惠券发送成功');
						$mdata['money'] -= I("js");
						$mcoupon_model->save($mdata);
					}else{
						$info[]=array('rec'=>$data['rec'],'status' => 7, 'message' => '优惠券发送失败');
					}
				}
				$this->ajaxReturn(array('message' => '优惠券发送详情','value'=>$info));
			}else{
				$this->ajaxReturn(array('status'=>2,'message'=>'优惠券不存在或已过期'));
			}
		}else{
			$this->ajaxReturn(array('status'=>98,'message'=>'数据对象错误'));
		}
	}
	
}