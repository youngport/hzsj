<?php

class warehouseAction extends baseAction {
    public function index(){
        $this->display();
    }

    public function ware_ps(){
    	if(!empty($_GET)){
    	     $url = 'http://apisandbox.4px.com/api/service/woms/order/getOrderCarrier?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
		     $map = '{ "warehouseCode": "'.$_GET['code'].'" }';
		     import("Org.Util.http");
		     $httpClient = new \httpClient();
		     $httpClient->post($url, $map);
		     $res = (Array)json_decode($httpClient->buffer);
		     $data = array();
		     foreach ($res['data'] as $key => $value) {
		     	$data[$key]['code'] = $res['data'][$key]->carrierCode;
		     	$data[$key]['name'] = $res['data'][$key]->carrierName;
		     }
		     echo json_encode($data);
		}
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
		  
		  $url = 'http://apisandbox.4px.com/api/service/woms/receiving/createReceivingOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
		  $map = "";
		  //dump($res);exit;
		  $code = array();
		  foreach ($res as $key => $value) {
		         $map[$key] = '{"warehouseCode":"'.$_POST['ware'].'",
						        "carrierCode": "HKEMS", 
						        "insureMoney": "", 
						        "insureType": "'.$_POST['baoxian'].'", 
						        "dutypayType": "'.$_POST['guanshui'].'", 
						        "referenceCode": "", 
						        "description": "", 
						        "customsType": "'.$_POST['baoguan'].'", 
						        "lstItem": [{
						        "sku": "'.$value[1].'", 
						        "quantity": '.$value[2].', 
						        "packageType": "'.$value[3].'", 
						        "boxNumber":'.$value[0].', 
						        "goodsType": "'.$value[4].'", 
						        "validDate": "'.$value[5].'"}]}';  
		       $httpClient->post($url, $map[$key]);
		       $res_[$key] = (Array)json_decode($httpClient->buffer);
		       if (!empty($res_[$key]['data']->errors)) {
		       	$this->error($res_[$key]['data']->errors[0]->codeNote);
		       }
		       $code[$key]['receivingCode'] = $res_[$key]['data']->documentCode;
		  }
          //dump($code);exit;
		  if (!empty($code)){
		    $wh_code = M('Code',"wh_");
		    foreach ($code as $key => $value) {
			      if (!empty($value['receivingCode'])) {  
				         if($wh_code->add($code[$key])){
				         	
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

    public function edit(){
        if (!empty($_GET['receivingCode'])) {
         	$url = 'http://apisandbox.4px.com/api/service/woms/receiving/getReceivingOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
            $result = '{ "receivingCode": "'.$_REQUEST['receivingCode'].'"}';
            import("Org.Util.http"); 
		    $httpClient = new \httpClient();
		    $httpClient->post($url, $result);
		    $res = (Array)json_decode($httpClient->buffer);
		    $count = count($res['data']->lstItem);
		    $map['receivingCode'] = $_GET['receivingCode'];
		    $data = M("Message","wh_")->where($map)->field("receivingCode")->find();
			if (empty($data)) {
			    $message['receivingCode'] = $_GET['receivingCode'];
			    $message['status'] = $res['data']->status;
			    $message['createDate'] = (string)strtotime($res['data']->createDate);
			    $message['warehouseCode'] = $res['data']->warehouseCode;
			    $message['shippingCode'] = $res['data']->shippingCode;
			    $bool[0] = M("Message","wh_")->add($message);
			    
			    
			    for ($i=0; $i < $count; $i++) { 
			    	$goods[$i]['id'] = $i;
			    	$goods[$i]['receivingCode'] = $_GET['receivingCode'];
			    	$goods[$i]['sku'] = $res['data']->lstItem[$i]->sku;
			    	$goods[$i]['receivedDate'] = empty($res['data']->lstItem[$i]->receivedDate)?$res['data']->lstItem[$i]->receivedDate:(string)strtotime($res['data']->lstItem[$i]->receivedDate);
			    	$goods[$i]['quantity'] = $res['data']->lstItem[$i]->quantity;
			    	$goods[$i]['boxNumber'] = $res['data']->lstItem[$i]->boxNumber;
			    	$goods[$i]['acceptedQuantity'] = $res['data']->lstItem[$i]->acceptedQuantity;
			    	$goods[$i]['destAcceptedQuantity'] = $res['data']->lstItem[$i]->destAcceptedQuantity;
			    	$bool[1][$i] = M("Goods","wh_")->add($goods[$i]);
			    }
			    if($bool!==false){
	            	$this->success("添加成功");
	            }else{
	            	$this->error("添加失败");
	            }	    
			}else{
			    $message['status'] = $res['data']->status;
			    $bool[0] = M("message","wh_")->where($map)->save($message);
			    $bool[1] = M("code","wh_")->where($map)->save($message);

			    for ($i=0; $i < $count; $i++) { 
			    	$map_[$i]['receivingCode'] = $_GET['receivingCode'];
			    	$map_[$i]['id'] = $i;
			    	$goods[$i]['receivedDate'] = empty($res['data']->lstItem[$i]->receivedDate)?$res['data']->lstItem[$i]->receivedDate:(string)strtotime($res['data']->lstItem[$i]->receivedDate);
			    	$goods[$i]['boxNumber'] = $res['data']->lstItem[$i]->boxNumber;
			    	$goods[$i]['acceptedQuantity'] = $res['data']->lstItem[$i]->acceptedQuantity;
			    	$goods[$i]['destAcceptedQuantity'] = $res['data']->lstItem[$i]->destAcceptedQuantity;
			    	$bool[2][$i] = M("Goods","wh_")->where($map_[$i])->save($goods[$i]);
			    }
			    if($bool!==false){
	            	$this->success("更新成功");
	            }else{
	            	$this->error("更新失败");
	            }	
            
			}            	
        }     	
			
    }

    public function del(){
        if (!empty($_GET['receivingCode'])) {
        	$data = M("Message","wh_")->where($_GET)->field("receivingCode")->select();
        	if (!empty($data)) {
        		$url = 'http://apisandbox.4px.com/api/service/woms/receiving/cancelReceivingOrder?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
	            $result = '{ "receivingCode": "'.$_REQUEST['receivingCode'].'"}';
	            import("Org.Util.http"); 
			    $httpClient = new \httpClient();
			    $httpClient->post($url, $result);
			    $res = (Array)json_decode($httpClient->buffer);
			    //print_r($res);exit;
			    if ($res['data']->ack == "Y") {
			    	$map['receivingCode'] = $_GET['receivingCode'];
				    $message['status'] = "X";
				    $bool[0] = M("Message","wh_")->where($map)->save($message);
				    $bool[1] = M("Code","wh_")->where($map)->save($message);
			    }else{
			    	$this->error("已取消单号");
			    }
			    if($bool!==false){
	            	$this->success("取消成功");
	            }else{
	            	$this->error("取消失败");
	            }
        	}
			
		}	
			
    }

    public function ware_index(){
    	$total = M('Code',"wh_")->count();
    	import("ORG.Util.Page"); // 导入分页类
        $page = new Page($total, 10);
        $show = $page->show();

	    if ($_GET['p'])
            $data = M('Code',"wh_")->page($_GET['p'].',10')->select();
        else
            $data = M('Code',"wh_")->limit('0,10')->select();
        
	    $this->assign("pageinfo",$show);
        $this->assign("data",$data);
        $this->display();
    }
   
    public function ware_message(){
    	$total = M("message","wh_")->join("left join wh_goods as g on wh_message.receivingCode=g.receivingCode")->count();
    	import("ORG.Util.Page"); // 导入分页类
        $page = new Page($total, 10);
        $show = $page->show();

	    if ($_GET['p'])
            $data = M("message","wh_")->join("left join wh_goods as g on wh_message.receivingCode=g.receivingCode")->page($_GET['p'].',10')->select();
        else
            $data = M("message","wh_")->join("left join wh_goods as g on wh_message.receivingCode=g.receivingCode")->limit('0,10')->select();
        
	    $this->assign("pageinfo",$show);
        $this->assign("data",$data);
        $this->display();
    }

    public function _empty(){
		
	}


}
