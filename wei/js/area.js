function selectArea(place1, place2){
	var city = $("#"+place1).val();
	if(city == "北京"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='北京' selected>北京</option>");
		/*var cityareaname = new Array('东城区','西城区','崇文区','宣武区','朝阳区','海淀区','丰台区','石景山');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "深圳"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='深圳' selected>深圳</option>");
		/*var cityareaname = new Array('罗湖','福田','南山','盐田','宝安','龙岗');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "上海"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='上海' selected>上海</option>");
		/*var cityareaname = new Array('宝山','金山','南市','长宁','静安','青浦','崇明','卢湾','松江','奉贤','浦东','杨浦','虹口','普陀','闸北','黄浦','闵行','徐汇','嘉定','南汇');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "重庆"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='重庆' selected>重庆</option>");
		/*var cityareaname = new Array('渝中','江北','沙坪坝','南岸','九龙坡','大渡口');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "天津"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='天津' selected>天津</option>");
		/*var cityareaname = new Array('和平','河北','河西','河东','南开','红桥','塘沽','汉沽','大港','东丽','西青','津南','北辰','武清','滨海');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "广东"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='广东' selected>广东</option>");
		/*var cityareaname = new Array('广州','珠海','中山','佛山','东莞','清远','肇庆','阳江','湛江','韶关','惠州','河源','汕尾','汕头','梅州');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}*/
	}else if(city == "河北"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('石家庄','唐山','秦皇岛','邯郸','邢台','张家口','承德','廊坊','沧州','保定','衡水');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "山西"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('太原','大同','阳泉','朔州','长治','临汾','晋城');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "内蒙古"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('呼和浩特','包头','乌海','临河','东胜','集宁','锡林浩特','通辽','赤峰','海拉尔','乌兰浩特');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "辽宁"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('沈阳','大连','鞍山','锦州','丹东','盘锦','铁岭','抚顺','营口','辽阳','阜新','本溪','朝阳','葫芦岛');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "吉林"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('长春','吉林','四平','辽源','通化','白山','松原','白城','延边');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "黑龙江"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('哈尔滨','齐齐哈尔','牡丹江','佳木斯','大庆','伊春','黑河','鸡西','鹤岗','双鸭山','七台河','绥化','大兴安岭');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "江苏"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择</option>");
		var cityareaname = new Array('南京','苏州','无锡','常州','镇江','连云港 ','扬州','徐州 ','南通','盐城','淮阴','泰州','宿迁');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "浙江"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('杭州','湖州','丽水','温州','绍兴','舟山','嘉兴','金华','台州','衢州','宁波');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "安徽"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('合肥','芜湖','蚌埠','滁州','安庆','六安','黄山','宣城','淮南','宿州','马鞍山','铜陵','淮北','阜阳','池州','巢湖','亳州');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "福建"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('福州 ','厦门 ','泉州 ','漳州 ','龙岩 ','南平 ','宁德 ','莆田 ','三明');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "江西"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('南昌','景德镇','九江','萍乡','新余','鹰潭','赣州','宜春','吉安','上饶','抚州');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "山东"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('济南','青岛','淄博','德州','烟台','潍坊','济宁','泰安','临沂','菏泽','威海','枣庄','日照','莱芜','聊城','滨州','东营');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "河南"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('郑州','开封','洛阳','平顶山','安阳','鹤壁','新乡','焦作','濮阳','许昌','漯河','三门峡','南阳','商丘','周口','驻马店','信阳','济源');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "湖北"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('武汉','黄石','十堰','荆州','宜昌','襄樊','鄂州','荆门','孝感','黄冈','咸宁','恩施','随州','仙桃','天门','潜江','神农架');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "湖南"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('长沙','株州','湘潭','衡阳','邵阳','岳阳','常德','郴州','益阳','永州','怀化','娄底','湘西 ');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "广西"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('南宁','柳州','桂林','梧州','北海','防城港','钦州','贵港','玉林','贺州','百色','河池');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "海南"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('海口','三亚','通什','琼海','琼山','文昌','万宁','东方','儋州');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "四川"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('成都','自贡','攀枝花','泸州','德阳','绵阳','广元','遂宁','内江','乐山','南充  ','宜宾','广安','达川','巴中','雅安','眉山  ','阿坝 ','甘孜 ','凉山 ');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "贵州"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('贵阳 ','六盘水','遵义','铜仁','毕节','安顺','黔西南 ','黔东南','黔南');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "云南"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('昆明','东川','曲靖','玉溪','昭通','思茅','临沧','保山','丽江','文山 ','红河 ','西双版纳 ','楚雄 ','大理 ','德宏 ','怒江','迪庆');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "西藏"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('拉萨','那曲','昌都','山南','日喀则','阿里','林芝');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "陕西"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('西安','铜川','宝鸡','咸阳','渭南','延安','汉中','榆林','商洛','安康');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "甘肃"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('兰州','金昌','白银','天水','嘉峪关','定西','平凉','庆阳','陇南','武威','张掖','酒泉','甘南 ','临夏');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "青海"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('西宁','海东',' 海北 ','黄南','海南','果洛','玉树','海西');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "宁夏"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('银川','石嘴山','银南','固原');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "新疆"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('乌鲁木齐','克拉玛依','石河子','吐鲁番','哈密','和田','阿克苏','喀什','克孜勒苏','巴音郭楞','昌吉','博尔塔拉','伊犁');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}else if(city == "港澳台"){
		$("#"+place2).children().remove();
		$("#"+place2).append("<option value='' selected>请选择...</option>");
		var cityareaname = new Array('港澳台');
		for(var i=0;i<cityareaname.length;i++){
			$("#"+place2).append("<option value='"+cityareaname[i]+"'>"+cityareaname[i]+"</option>");
		}
	}
}