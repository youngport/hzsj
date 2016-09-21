
/*
*  @ 图片延迟加载	
*           
*/	
function lazy(options){
	var defaultOptions = {
			//id : null,  //指定那些id下的img元素使用延迟显示
			src : null, //指定图片路径    
	} 
		this.options = $.extend(defaultOptions,options);
		//this.scroll(); //绑定滚动事件
		return this ;
}	
/*获取所有的图片元素 并将所有的图片设置为指定的loading图片*/
lazy.prototype.setImgs = function(){
	//var imgObj = $("#"+this.options.id).find("img");
	var imgObj = $("#dingshen").find("img")
	var len = imgObj.length;
	var src = this.options.src ? this.options.src : '';
	for(var i=0; i<len; i++){
		var idbox = $(imgObj).eq(i);
		var _imgs;
		if(idbox){
			for(var t=0 ; t < idbox.length ; t++){
				   lazy.imgs.push(idbox[t]);
			  }
		}
	}
	
}
/*获取元素的位置*/
lazy.prototype.location = function(obj){
	 var top = 0;
		top = $(obj).offset().top;
	 return top;
}	
/*绑定滚动事件*/
lazy.prototype.scroll = function(){
	this.setImgs();
	var _this = this;
	var height = $(window).height();
	
	//页面初始化
	var initTop = $(document).scrollTop();
	for(var i=0 ; i < lazy.imgs.length; i++){
			var _initTop = _this.location(lazy.imgs[i]);
			if(_initTop >= initTop && _initTop < initTop + height){
				var _src =$(lazy.imgs[i]).attr('data-src');
				   //如果图片已经显示，则取消赋值
				  if(lazy.imgs[i].src !== _src){					
					lazy.imgs[i].src = _src;
					//商城第二套皮肤的布局
					var h = $("ul[data-layoutList]").find("li:first").find("img").height();
					var num = $("ul[data-layoutList]").find("li:first").attr("data-layout");
					$("ul[data-layoutList]").find("li:first").height(h);
					var setH  =  h /num;
					$("ul[data-layoutList]").find("li").not(":first").find("a").height(setH);
			   }
			 
			}
			
	}
	
	$(document).bind("scroll",function(){  //滚动加载
		var top = $(this).scrollTop();
		for(var i=0 ; i < lazy.imgs.length; i++){
			var _top = _this.location(lazy.imgs[i]);
			if(_top > top && _top < top + height){
				var _src =$(lazy.imgs[i]).attr('data-src');
				   //如果图片已经显示，则取消赋值
				  if(lazy.imgs[i].src !== _src){					
					lazy.imgs[i].src = _src;
					//商城第二套皮肤的布局
					var h = $("ul[data-layoutList]").find("li:first").find("img").height();
					var num = $("ul[data-layoutList]").find("li:first").attr("data-layout");
					$("ul[data-layoutList]").find("li:first").height(h);
					var setH  =  h /2;
					$("ul[data-layoutList]").find("li").not(":first").find("a").height(setH);
			   }
			} 
		}
	})
	var load = new Image();
		load.src =this.options.src;
		load.onload = function(){
			$(document).scroll();
	 };
}
lazy.imgs =[];	 //图片列表
$(function(){
	var IMGLAZYLOADING = new lazy();
	IMGLAZYLOADING.scroll();
})
function kaishi(){
	for(var i=0 ; i < lazy.imgs.length; i++){
		var _top = _this.location(lazy.imgs[i]);
		if(_top > top && _top < top + height){
			var _src =$(lazy.imgs[i]).attr('data-src');
			   //如果图片已经显示，则取消赋值
			  if(lazy.imgs[i].src !== _src){					
				lazy.imgs[i].src = _src;
				//商城第二套皮肤的布局
				var h = $("ul[data-layoutList]").find("li:first").find("img").height();
				var num = $("ul[data-layoutList]").find("li:first").attr("data-layout");
				$("ul[data-layoutList]").find("li:first").height(h);
				var setH  =  h /2;
				$("ul[data-layoutList]").find("li").not(":first").find("a").height(setH);
		   }
		} 
	}	
}
///////////////////////////////////////////////
	
	