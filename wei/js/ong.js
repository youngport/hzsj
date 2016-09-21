/*
**初始化
*/
var isScroll = true;
(function(w){
	var hastouch = "ontouchstart" in window?true:false,  //判断是在移动端还是PC端
		tapstart = hastouch?"touchstart":"click";        //如果是移动端 则用touchstart事件，如果是PC端 则用click事件
			
	//搜索界面	
	$(".search_text").bind(tapstart,function(){
		var moduleType = $("#moduleType").val();
		//if(moduleType == 1){
			$("div[data-storeMain],ul[data-storeMain]").hide();
		//}
		$(this).css({width:"85%",float:'left'});
		$(this).siblings("a").show();
		$(this).attr("disabled",false);
		$("#keyword").show();
		$("a[data-searchNo]").show();
	})
	//取消搜索
	//$("a[data-searchNo]").live(tapstart,function(){
//		$("div[data-storeMain],ul[data-storeMain]").show();
//		$(".search_text").css({width:'100%'});
//		$(this).siblings(".search_text").attr("disabled",true);
//		$(this).siblings(".search_text").val("");
//		$(this).siblings(".search_text").blur();
//		$(this).hide();
//		$("#keyword").hide();
//	});
	// banner 滚动
	//$.gallery({swapId:"banner"}).reload().setIsAuto(true).start(2000);
	//$.gallery({swapId:"shopping_banner"}).reload().setIsAuto(true).start(3000);
	$(window).resize(function(){
		//$.gallery({swapId:"banner"}).reload().setIsAuto(true).start(2000);
		//$.gallery({swapId:"shopping_banner"}).reload().setIsAuto(true).start(3000);
	})
	var wH= $(window).height();
	var dH = $(document).height();
	var time = dH /3;
		var top = wH;
		$(document).scroll(function(){
			if($(document).scrollTop()>=top){
				$("div[data-goto='top']").show()
			}else{
				$("div[data-goto='top']").hide()
			}
		})
		
		
		//返回顶部
	//	$("div[data-goto='top']").live("touchstart",function(event){
//			event.stopPropagation();
//			event.preventDefault();
//			$('body').animate({
//				scrollTop:0
//			},time)
//		})
	
	//我要开店步骤
	/*$("button[data-step]").live("click",function(){  //同意条款进去填写资料页
		var companyId = $(this).attr("data-companyId");
		window.location.href="open/openStore.action?requestType=1";
		
	})
	$("button[data-search]").live("click",function(){  //查询进度
		window.location.href="open/openStore.action?requestType=2";
		
	})
	$("a[data-back='clause']").live("click",function(){ //返回至条款
		var boxId = $(this).attr("data-back");
		$(this).parents(".setUp_step").hide();
		$("div[data-box='"+boxId+"']").show();
	})
	$("button[data-success='setUp']").bind("click",function(){  //成功提交至提示
		if($("#china_name").val() == ''){ 
			$("#account").text("请填写姓名");
			return;
		} 
		var spellName = $("#spell_name").val();
		if(spellName == ''){
			$("#account").text("请填写商城账号");
			return;
		}
		if($("#contact_phone").val() == ''){
			$("#account").text("请填写电话");
			return;
		}
		if(strlen(spellName) < 4){
			$("#account").text("商城账号不能少于4个字符");
			return;
		}
		if(strlen(spellName) > 20){
			$("#account").text("商城账号不能超过20个字符");
			return;
		}
		var obj = this;
		$.ajax({
			url : "json/open/applyOpenStore.action",
			type : "post",
			data : {
				"openStore.applyName" : $("#china_name").val(),
				"openStore.spellName" : $("#spell_name").val(),
			    "openStore.contactPhone" : $("#contact_phone").val(),
			    "openStore.applyReason" : $("#apply_reason").val(),
			    "relationId" : $("input[name='relationId']").val(),
			    "relationType" : $("input[name='relationType']").val()
			},
			async: false, 
			dataType : "json",
			success : function(data){
				if(data.messages == 'ok'){
					$("#china_name").val("");
					$("#spell_name").val("");
					$("#contact_phone").val("");
					$("#apply_reason").val("");
					$(obj).parents(".box").hide();
					$("div[data-successTips]").fadeIn();
					$(obj).parents(".setUp_step").hide().siblings().show();
				}else if(data.messages == 'error'){
					alert("申请失败!");
				}else{
					$("#account").text(data.messages);
				}
			}
		})
		return false;
	});*/
	
	
	function strlen(str) {
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            var c = str.charCodeAt(i);
            //单字节加1 
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
                len++;
            }
            else {
                len += 2;
            }
        }
        return len;
    }
	
	
	//价格变动
	/*price();
	$(".add_num").die(tapstart).live(tapstart,function(){
		var val = parseInt($(this).siblings("input[data-num]").val());
		$(this).siblings(".reduce_num").removeClass("no");
		var newVal = val + 1;
		$(this).siblings("input[data-num]").val(newVal);
		if($(this).parents("li").attr("data-num")){
			$(this).parents("li").attr("data-num",newVal);
		}
		price();

	});
	$(".reduce_num").die(tapstart).live(tapstart,function(){
		var val = parseInt($(this).siblings("input[data-num]").val());
		$(this).siblings(".add_num").removeClass("no");
		var newVal = val - 1;
		if(newVal == 0 ){
			$(this).addClass("no");
			return;
		}
		$(this).siblings("input[data-num]").val(newVal);
		if($(this).parents("li").attr("data-num")){
			$(this).parents("li").attr("data-num",newVal);
		}
		price();
	});
	$("input[data-num]").bind("blur",function(event){
		var val = $(this).val();
		var reg = /^\d+$/;	
		 if(!reg.test(val)){
			$.tipBox({
				title:'数量超出范围~',
			})
			$(this).val("1");
		}
		
	});*/
	//商品图高度跟随宽度变化而变化
	
	var imgBoxW = $("div[data-storeimg],span[data-storeimg]").width();
	$("div[data-storeimg],span[data-storeimg]").css({
		height:imgBoxW,
	});
	$("div[data-storeimg],span[data-storeimg]").find("img").each(function(){
		$(this).load(function(){
			var imgW = $(this).width();
			var imgH = $(this).height();
			if(imgW > imgH){
				$(this).css({
					'max-width':'inherit',
					'max-height':'100%',
				})
				var imgTW = $(this).width();
				var mLeft = -(imgTW - imgBoxW)/2;
				$(this).css({
					"margin-left":mLeft,
				})
			}
			if(imgW < imgH){
				$(this).css({
					'max-height':'inherit',
					'max-width':'100%',
				});
				var imgTH = $(this).height();
				var mTop = -(imgTH - imgBoxW)/2;
				$(this).css({
					"margin-top":mTop,
				})
			}
		});
	})
	$(window).resize(function(){
		var imgBoxW = $("div[data-storeimg],span[data-storeimg]").width();
		  $("div[data-storeimg],span[data-storeimg]").css({
			  height:imgBoxW,
		});
		
	})
	
//	//关闭产品弹出层
//	$("div[data-dele]").live("click",function(){
//		
//		$(this).parents(".standard_content").animate({bottom:"-500px"},300,function(){
//			$(this).parents("#standard").hide();
//		});
//		$("div[data-shopDate]").css({
//			webkitTransform:"scale(1)",
//			webkitTransformOrigin:"50% 50%",
//		})
//	});
	
	//显示产品规格弹出层
	/*$("a[data-boxs],div[data-boxs]").live("click",function(){
		var id = $(this).attr("data-boxs");
		if($(this).attr('data-shoppimg')){
			var src = $(this).parent().siblings(".comm_img").find("img").attr("src");
			$("#"+id).find(".stan_img").find("img").attr("src",src);
		}
		
		var _this = $(this);
		$("#"+id).show();
		
		if($("#"+id).hasClass("cart_standard")){
			$("#"+id).find(".standard_content").animate({bottom:"0px"},500);
			cardStandBox(_this);
		}else{
			$("#"+id).find(".standard_content").animate({bottom:"61px"},500);
		}
		$("input[data-addClass='btn']").addClass("isProductShow");
		$("div[data-shopDate]").css({
			webkitTransform:"scale(0.9)",
			webkitTransformOrigin:"50% 50%",
		})
	});*/
	// 点点居中
	var ico_w = $("div[data-centent]").width();
	$("div[data-centent]").css({
		left:'50%',
		marginLeft: -ico_w/2,
	});
	
	//购物车编辑商品
	/*$("a[data-edit='cart']").toggle(function(){
		$(this).parents("li").find("span[data-shopedit]").removeClass("hide");
		$(this).parents("li").find("span[data-shopedit]").siblings("span[data-commdoity]").addClass("hide");
		$(this).text("完成");
	},function(){
		var _this = $(this);
		$(this).parents("li").find("span[data-shopedit]").addClass("hide");
		$(this).parents("li").find("span[data-shopedit]").siblings("span[data-commdoity]").removeClass("hide");
		$(this).text("编辑");
		var detailId = $(this).parents("li").attr("data-detailid");
		var Num = $(this).parents("li").attr("data-num");
		var stPrice = $(this).parents("li").attr("data-price");
		var id =$(this).parents("li").attr("data-id");
		var desc = $(this).parents("li").attr("data-desc");
		$.post("json/mall/updateCartProduct.action",{
			"shoppingCart.id":id,
			"shoppingCart.productDetailId":detailId,
			"shoppingCart.num":Num,
		},function(data){
			if(data.messages =="ok"){
				var obj = _this.parents("li");
				obj.find(".com_txt").find("p").text(desc);
				obj.find("span[data-standNum]").text(Num);
				obj.find("span[data-standPrice]").text(stPrice);
				obj.find("span[data-allPrice='stand']").text(Math.round(parseFloat(stPrice)*parseInt(Num) * 100.0) / 100.0);
				calculate();
			}
		})
	});*/
	
	
	
	//列表加载
	/*$(document).bind("scroll",function(){
		if(!isScroll){ 
			return;
		}else{
			isScroll = false;
			var DH = $(this).height();
			var WH = $(window).height();
			var DT = $(this).scrollTop();
			if(DT >= DH - WH){
				$(this).find("div[data-loadlist]").show();
				var val = parseInt($("#currentPage").val()) + 1;
				$("#currentPage").val(val)
				if(typeof(getanotherProduct)!="undefined"){
					getanotherProduct();
					isScroll = true;
				}
			}else{
				isScroll = true;
				//$(this).find("div[data-loadlist]").hide();
			}
		}
	})
	
	//顶部高亮状态
	var foot_index = $("#mall_ultype").val()-1;
	$("#foot_nav li").eq(foot_index).find("a").addClass("active");
	$("#foot_nav li").eq(foot_index).siblings().find("a").removeClass("active");*/
})(window);
/*价格*/	
function price(){
	var oldPrice = parseFloat($("*[data-oldprice]").text());
	var num = parseInt($("input[data-num]").val());
	var newPrice = parseFloat(oldPrice * num);
	if(newPrice&&newPrice!="0"){
		$("*[data-newprice]").text(Number(newPrice).toFixed(2));
	}
}