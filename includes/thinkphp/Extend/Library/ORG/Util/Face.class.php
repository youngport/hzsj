<?php
class Face{
	private $images;		//ͼƬ��Դ
	private $width; 		//��ͼ���
	private $height;		//��ͼ�߶�
	private $left;			//��ͼСͼƬ�Ӵ�ͼƬ����߾���
	private $top;			//��ͼСͼƬ�Ӵ�ͼƬ���ϱ߾���
	private $proportion;	//ͼƬ����
	private $imagewidth;	//��ͼƬ�߶ȿ��,��ͼƬ��������,����ͼ�ο��Զ���height����
	public $saveimage;		
	public function __construct($images,$width,$height,$left,$top,$imagewidth,$saveimage){
		$this->images = $images;
		$this->width = $width;
		$this->height = $height;
		$this->left = $left;
		$this->top = $top;
		$this->imagewidth = $imagewidth;
		$this->saveimage = $saveimage;
		$this->calculation();
	}
	private function calculation(){
		//��ȡͼƬ��Դ�Ŀ�ȡ��߶ȡ�����
		list($yimagewidth, $yimageheight, $imageType) = getimagesize($this->images);
        
        $imageType = image_type_to_mime_type($imageType);
        
    	switch($imageType) {
    		case "image/gif":
    			$im=imagecreatefromgif($this->images); 
    			break;
    	    case "image/pjpeg":
    		case "image/jpeg":
    		case "image/jpg":
    			$im=imagecreatefromjpeg($this->images); 
    			break;
    	    case "image/png":
    		case "image/x-png":
    			$im=imagecreatefrompng($this->images); 
    			break;
      	}
        
		//�������
		$this->proportion = $this->imagewidth/$this->width;
		//$im = imagecreatefromjpeg($this->images);
		//������ͼƬ��С   
		$new_img_width  = ceil($this->width*$this->proportion); 
		$new_img_height = ceil($this->height*$this->proportion); 
		$newim = imagecreatetruecolor($this->width, $this->height);   
		$newims = imagecreatetruecolor($new_img_width, $new_img_height);  
		//������Դ
		imagecopyresampled($newim, $im, 0, 0, $this->left, $this->top, $yimagewidth, $yimageheight, $yimagewidth, $yimageheight);
		imagecopyresampled($newims, $newim, 0, 0, 0, 0, $this->imagewidth, $this->imagewidth, $this->width, $this->height);
		//header('Content-Type: image/jpeg');
		//imagejpeg($newims); 
        
		imagejpeg($newims,$this->saveimage,100); 
		imagedestroy($newim); 
		imagedestroy($newims); 
		imagedestroy($im);
        /**/
	}
}
//$im = new Face("backup/pscb.jpg",220,220,295,95,80,"upload_pic/test.jpg");
?>