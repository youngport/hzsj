<?php

class orderAction extends baseAction{
	
	public function index() {
       $this->display();
	}

	public function upload(){
		if (! empty ( $_FILES ['file'] ['name'] )){
	  	  $info = $this->_upload('xls');
		  if (!$info) {
				 $this->error ( '上传失败' );
		  }
		  $info = substr($info, 2);
          define('SITE_PATH', dirname(__FILE__));
          $path = SITE_PATH;
          $path = str_replace(array("admin\Lib\Action"),"",$path);
          $path = str_replace(array("\\"),"/",$path);
          $url_path = $path.$info;
          $url_path = str_replace(array("/"),"\/",$url_path);
          $url_path = str_replace(array("/"),"",$url_path);

		  import("Org.Util.xlsList");    
		  $obj = new \Service();
		  $res = $obj->read($url_path);

		  import("Org.Util.http"); 
		  $httpClient = new \httpClient();
		  
		  $url = 'http://apisandbox.4px.com/api/service/woms/order/createDeliveryOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
		  $map = "";
		  foreach ($res as $key => $value) {
		       $map[$key] = '{"warehouseCode":"'.$value[0].'",
		                "referenceCode":"'.$value[1].'",
		                "carrierCode":"'.$value[2].'",
		                "insureType":"'.$value[3].'",
		                "insureMoney":"'.$value[4].'",
		                "sellCode":"'.$value[5].'",
		                "remoteArea":"'.$value[6].'",
		                "platformCode":"'.$value[7].'",
		                "description":"'.$value[8].'",
		                "consignee":{"state":"'.$value[9].'",
		                              "fullName":"'.$value[10].'",
		                              "email":"'.$value[11].'",
		                              "countryCode":"'.$value[12].'",
		                              "street":"'.$value[13].'",
		                              "city":"'.$value[14].'",
		                              "postalCode":"'.$value[15].'",
		                              "phone":"'.$value[16].'",
		                              "company":"'.$value[17].'",
		                              "doorplate":"'.$value[18].'",
		                              "cardId":"'.$value[19].'"},
		                "items":[{"sku":"'.$value[20].'",
		                          "quantity":"'.$value[21].'",
		                          "skuValue":"'.$value[22].'"},
		                          {"sku":"'.$value[23].'",
		                          "quantity":"'.$value[24].'",
		                          "skuValue":"'.$value[25].'"}]}';  
		       $httpClient->post($url, $map[$key]);
		       $res_[$key] = (Array)json_decode($httpClient->buffer);
		       if (!empty($res_[$key]['data']->errors)) {
		       	$this->error($res_[$key]['data']->errors[0]->codeNote);
		       }
		       $code[$key]['orderCode'] = $res_[$key]['data']->documentCode;

		  }
		  if (!empty($code)){
		    $order_code = M('Code',"order_");
		    foreach ($code as $key => $value) {
			      if (!empty($value['orderCode'])) {				        
				         if($order_code->add($code[$key])){		

				         }else{
				         	$resourse = false;
				         	$this->error("添加错误，在".$key."行中断");
				         }
			      }else{
	                  $this->error("xls数据错误");
			      }
		    }
		  }  
         if (!$resourse) {
        	 $this->success("添加成功");
         }
      } 


	}
    ###待添加订单
	public function add_list() {
	   $map['status'] = 0;	
	   $total = M('Code',"order_")->where($map)->count();
	   import("ORG.Util.Page"); // 导入分页类
       $page = new Page($total, 10);
       $show = $page->show();
       
	   if ($_GET['p'])
            $data = M('Code',"order_")->where($map)->page($_GET['p'].',10')->select();
        else
            $data = M('Code',"order_")->where($map)->limit('0,10')->select();
	   $this->assign("pageinfo",$show);
       $this->assign("data",$data);
       $this->display();
	}
	###添加订单数据
	public function add(){
		if (!empty($_GET['orderCode'])) {
			$url = 'http://apisandbox.4px.com/api/service/woms/order/getDeliveryOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
            $result = '{ "orderCode": "'.$_REQUEST['orderCode'].'"}';
            import("Org.Util.http"); 
		    $httpClient = new \httpClient();
		    $httpClient->post($url, $result);
		    $res = (Array)json_decode($httpClient->buffer);
			$data = M("list","order_")->where($_GET)->field("orderCode")->select();
			$shipWeightActual = !empty($res['data']->shipWeightActual)?$res['data']->shipWeightActual:0;
			
			if (empty($data)) {
				$recourse = array();
	            $sql_2 = array();
				$sql_ = "INSERT INTO `order_list` VALUES ('".$res['data']->orderCode."','".$res['data']->status."',".$res['data']->weight.",'".$res['data']->warehouseCode."','".$res['data']->referenceCode."','".$res['data']->carrierCode."','".$res['data']->shippingNumber."','".strtotime($res['data']->createTime)."','".$res['data']->isOda."','".strtotime($res['data']->shippingTime)."','".$res['data']->interceptStatus."',".$shipWeightActual.",'".$res['data']->packageTypeActual."','".$res['data']->orderSign."','".$res['data']->ramStatus."',".$res['data']->shipWeightPredict.",'".$res['data']->shipWeightVolumn."')";
	            $recourse[0] = M()->query($sql_);
	            $sql_1 = "INSERT INTO `order_user` VALUES ('".$res['data']->orderCode."','".$res['data']->objConsigneeReponseVo->state."','".$res['data']->objConsigneeReponseVo->fullName."','".$res['data']->objConsigneeReponseVo->countryCode."','".$res['data']->objConsigneeReponseVo->city."','".$res['data']->objConsigneeReponseVo->postalCode."','".$res['data']->objConsigneeReponseVo->street."','".$res['data']->objConsigneeReponseVo->email."','".$res['data']->objConsigneeReponseVo->doorplate."','".$res['data']->objConsigneeReponseVo->phone."','".$res['data']->objConsigneeReponseVo->company."','".$res['data']->objConsigneeReponseVo->cardId."')";
	            $recourse[1] = M()->query($sql_1);
	            $a = count($res['data']->lsOrderDetails);	
				for ($i=0; $i < $a; $i++) { 
					$sql_2[$i] = "INSERT INTO `order_goods` VALUES ('".$res['data']->orderCode."','".$res['data']->lsOrderDetails[$i]->sku."','".$res['data']->lsOrderDetails[$i]->skuId."','".$res['data']->lsOrderDetails[$i]->quantity."','".$res['data']->lsOrderDetails[$i]->skuName."','".$res['data']->lsOrderDetails[$i]->quality."')";
			    $recourse[2][$i] = M()->query($sql_2[$i]);
	            }
	            if($recourse!==false){
	            	$save['status'] = '1';
	            	$bool = M("code","order_")->where($_GET)->save($save);
	            	if ($bool) {
	            		$this->success("添加成功");
	            	}        	
	            }else{
	            	$this->error("添加失败");
	            }
			}
				

		}
	}
 ####订单列表   
    public function order_list(){
        $total =  M("List","order_")->join("left join order_user as u on order_list.orderCode=u.orderCode")->count();
        if ($_GET['p'])
            $data = M("List","order_")->join("left join order_user as u on order_list.orderCode=u.orderCode")->page($_GET['p'].',10')->select();
        else
            $data = M("List","order_")->join("left join order_user as u on order_list.orderCode=u.orderCode")->limit('0,10')->select();
        
        import("ORG.Util.Page"); // 导入分页类
        $page = new Page($total, 10);
        $show = $page->show();
        $this->assign("pageinfo",$show);

    	
    	$code = array();
    	foreach ($data as $key => $value) {
    		$code['orderCode'] = $value['orderCode'];
    		$goods = M("goods","order_")->where($code)->select();
    		foreach ($goods as $k => $v) {
    			if ($v['orderCode']==$value['orderCode']) {
	    			$data[$key]['goods'][$k] = $goods[$k];
	    		}
    		} 		
    	}
    	$this->assign("data",$data);
    	$this->display();
    }
    ###待更新订单
    public function edit_list(){
    	$map['status'] = 1;	
	    $total = M('Code',"order_")->where($map)->count();
	   if ($_GET['p'])
            $data = M('Code',"order_")->where($map)->page($_GET['p'].',10')->select();
        else
            $data = M('Code',"order_")->where($map)->limit('0,10')->select();
	   import("ORG.Util.Page"); // 导入分页类
       $page = new Page($total, 10);
       $show = $page->show();
       $this->assign("pageinfo",$show);

       $this->assign("data",$data);
       $this->display();
    }
    ###单个订单更新
    public function edit(){
    	$url = 'http://apisandbox.4px.com/api/service/woms/order/getDeliveryOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
        $result = '{ "orderCode": "'.$_REQUEST['orderCode'].'"}';
        import("Org.Util.http"); 
	    $httpClient = new \httpClient();
	    $httpClient->post($url, $result);
	    $res = (Array)json_decode($httpClient->buffer);
		$data = M("order")->where($_GET)->field("orderCode")->select();
    	$edit = "UPDATE `order` SET `status`='".$res['data']->status."',`shippingTime`='".strtotime($res['data']->shippingTime)."' WHERE orderCode='".$res['data']->orderCode."'";
        $recourse = M()->execute($edit);
        if($recourse!==false){	      
        	$this->success("更新成功");
        }else{
        	$this->error("更新失败");
        }
    }
    ###多个订单更新
    public function edits(){
    	if (!empty($_GET)) {
    		$orderCode = substr($_GET['orderCode'],0,strlen($_GET['orderCode'])-1);
    		$arr = array();
    		$arr = explode(',', $orderCode);
    		$url = 'http://apisandbox.4px.com/api/service/woms/order/getDeliveryOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
    		import("Org.Util.http"); 
	    	$httpClient = new \httpClient();
    		foreach ($arr as $key => $value) {
    			$result[$key] = '{ "orderCode": "'.$arr[$key].'"}';
    			$httpClient->post($url, $result[$key]);
	            $res[$key] = (Array)json_decode($httpClient->buffer);
	            $save['status'] = $res['data']->status;
	            $save['shippingTime'] = strtotime($res['data']->shippingTime);
	            $map['orderCode'] = $res[$key]['data']->orderCode;
	            $bool[$key] = M('list',"order_")->where($map)->save($save);
    		}
    		 if ($bool!==false) {
            	echo '更新成功';
            }else{
            	echo '更新失败';
            }
    		
    	}
    }

	public function _empty(){
		
	}
}
