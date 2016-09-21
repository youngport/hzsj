function red_num(obj){
	var $buyDom = $(obj).parent().next().children().eq(0);
	buynum = parseInt($buyDom.val());
	buynum = buynum - 1;
	if(buynum<0) buynum = 0;
	$buyDom.val(buynum);
}
function closeGuan(obj){
	$(obj).parent().remove();
}