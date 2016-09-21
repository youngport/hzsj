<?php

class inventoryAction extends baseAction {
    public function index(){
    	$map['fid'] = 0;
    	$this->data = M("Name","goods_")->where($map)->select();
        $this->display();
    }
    public function good_name(){
    	if(!empty($_GET)){
    		$map['fid'] = $_GET['code'];

            $data = M("Name","goods_")->where($map)->select();
			echo json_encode($data);
		}
    }
    public function good_names(){
    	if(!empty($_GET)){
    	     $url = 'http://apisandbox.4px.com/api/service/woms/item/getItemCategory?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
		     $map = '{ "categoryParentCode": "'.$_GET['code'].'" }';
		     import("Org.Util.http"); 
		     $httpClient = new \httpClient();
		     $httpClient->post($url, $map);
		     $res = (Array)json_decode($httpClient->buffer);
		     $data = array();
		     foreach ($res['data'] as $key => $value) {
		     	$data[$key]['categoryCode'] = $res['data'][$key]->categoryCode;
		     	$data[$key]['categoryName'] = $res['data'][$key]->categoryName;
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
		  
		  $url = 'http://apisandbox.4px.com/api/service/woms/item/createItem?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
		  $map = "";
		  $code = array();
		  foreach ($res as $key => $value) {
		        $map[$key] = '{ "categoryCode":"'.$_POST['three'].'",
						        "sku": "'.$value[1].'", 
						        "itemName": "'.$value[0].'", 
						        "specification": "'.$value[7].'", 
						        "unitPrice": '.$value[14].', 
						        "units": "'.$value[9].'", 
						        "weight": '.$value[10].', 
						        "length": '.$value[11].', 
						        "width": '.$value[12].',
						        "height": '.$value[13].', 
						        "declare": "'.$value[15].'", 
						        "referenceCode": "", 
						        "isRelease":"", 
						        "description": "", 
						        "businessType": "",
						        "valuable": "'.$value[5].'",
						        "constantTemprature": "'.$value[6].'", 
						        "originCountry": "'.$value[8].'", 
						        "dangerSign": "'.$value[4].'",
						        "upc":"'.$value[16].'" }';
		                         //print_r($map[$key]);exit;   
		       $httpClient->post($url, $map[$key]);
		       $res_[$key] = (Array)json_decode($httpClient->buffer);
		       if (!empty($res_[$key]['data']->errors)) {
		       	$this->error($res_[$key]['data']->errors[0]->codeNote);
		       }
		       $code[$key]['sku'] = $res_[$key]['data']->documentCode;
		       $code[$key]['name'] = $res_[$key]['data']->documentId;

		  }

		  if (!empty($code)){
		    $goods_sku = M('Sku',"goods_");
		    foreach ($code as $key => $value) {
			      if (!empty($value['sku'])) {  
				         if($goods_sku->add($code[$key])){
				         	
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

    public function add(){
    	$map['status'] = 0;
        $data = M("Sku","goods_")->where($map)->field("sku")->select();
        if (empty($data)) {
        	$this->success("没有可加载货品");
        }
    	$str = "";
    	$str_ = "";
    	foreach ($data as $key => $value) {
    		$str_ .=  $value['sku'].',';
    		$str .= '"'.$value['sku'].'",';
    	}
    	$str = substr($str,0,strlen($str)-1);
    	$str_ = substr($str_,0,strlen($str_)-1);
    	import("Org.Util.http"); 
	    $httpClient = new \httpClient();
	   
	    $url = 'http://apisandbox.4px.com/api/service/woms/item/getItemList?customerId=100800&token=oDuCfVi88b40oOuMYQUOcTh2b/T+uJdDBsJ+VOrlG6Q=1&language=en_US';
	    $json = '{ "lstSku": ['.$str.'] }';
	    $httpClient->post($url, $json);
		$res = (Array)json_decode($httpClient->buffer);
		$add = array();
		foreach ($res['data'] as $k => $v) {
			$add[$k]['categoryCode'] = $res['data'][$k]->categoryCode;
            $add[$k]['sku'] = $res['data'][$k]->sku;
            $add[$k]['itemName'] = $res['data'][$k]->itemName;
            $add[$k]['specification'] = $res['data'][$k]->specification;
            $add[$k]['unitPrice'] = $res['data'][$k]->unitPrice;
            $add[$k]['units'] = $res['data'][$k]->units;
            $add[$k]['weight'] = $res['data'][$k]->weight;
            $add[$k]['length'] = $res['data'][$k]->length;
            $add[$k]['width'] = $res['data'][$k]->width;
            $add[$k]['height'] = $res['data'][$k]->height;
            $add[$k]['declare'] = $res['data'][$k]->declare;
            $add[$k]['referenceCode'] = $res['data'][$k]->referenceCode;
            $add[$k]['description'] = $res['data'][$k]->description;
            $add[$k]['imBarNo'] = $res['data'][$k]->imBarNo;
            $add[$k]['imBarCode'] = $res['data'][$k]->imBarCode;
            $bool[$k] = M("Message","goods_")->add($add[$k]);
		}
		if ($bool!==false) {
			$save['status'] = '1';
			$save_['sku'] = array("in",$str_);
			$res_ = M("Sku","goods_")->where($save_)->save($save);
            if ($res_) {
            	$this->success("加载成功");
            }		
		}else{
			$this->success("加载失败");
		}
        
    }
   
    public function it_goods(){
	    $total = M('Message',"goods_")->count();
	    import("ORG.Util.Page"); // 导入分页类
        $page = new Page($total, 10);
        $show = $page->show();

	    if ($_GET['p'])
            $data = M('Message',"goods_")->page($_GET['p'].',10')->select();
        else
            $data = M('Message',"goods_")->limit('0,10')->select();
        
	    $this->assign("pageinfo",$show);
        $this->assign("data",$data);
        $this->display();
    }


    public function _empty(){
		
	}


}
