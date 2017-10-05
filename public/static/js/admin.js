jQuery('.page-sidebar').on('click', 'li > a', function (e) {
    if ($(this).next().hasClass('sub-menu') == false) {
        if ($('.btn-navbar').hasClass('collapsed') == false) {
            $('.btn-navbar').click();
        }
        return;
    }
    var parent = $(this).parent().parent();
    parent.children('li.open').children('a').children('.arrow').removeClass('open');
    parent.children('li.open').children('.sub-menu').slideUp(200);
    parent.children('li.open').removeClass('open');
    var sub = jQuery(this).next();
    if (sub.is(":visible")) {
        jQuery('.arrow', jQuery(this)).removeClass("open");
        jQuery(this).parent().removeClass("open");
        sub.slideUp(200);
    } else {
        jQuery('.arrow', jQuery(this)).addClass("open");
        jQuery(this).parent().addClass("open");
        sub.slideDown(200);
    }
    e.preventDefault();
});
class admin{
	constructor(){
		this.mainIfmWin=null;
	}
	add(url,title){
		if (url == '' && typeof(url) != 'string' ) {
			$.warn('网址不正确');
			return;
		}
		if ( title == '' && typeof(title) != 'string' ) {
			$.warn('标题错误');
			return;
		}		
		let	height=$(window).height()*0.95+'px';
		let width=$(window).width()*0.7+'px';
		layer.open({
			type:2,
			title:title,
			content:url,
			area: [width,height],
			shadeClose:true,
		})
	}
	closePage(){
		layer.closeAll();
	}
	PageGetData(){
		let ifm=$("#list");
		this.mainIfmWin=(typeof(ifm[0].contentWindow)=='object'?ifm[0].contentWindow:ifm[0].window);
		this.mainIfmWin.dataObj.get_data();
	}
	edit(url,title){
		if (url == '' && typeof(url) != 'string' ) {
			$.warn('网址不正确');
			return;
		}
		if ( title == '' && typeof(title) != 'string' ) {
			$.warn('标题错误');
			return;
		}

		let	height=$(window).height()*0.95+'px';
		let width=$(window).width()*0.7+'px';
		layer.open({
			type:2,
			title:title,
			content:url,
			area: [width,height],
			shadeClose:true,
		})		
	}
	deleteData(url){
		if (url == '' && typeof(url) != 'string' ) {
			$.warn('网址不正确');
			return;
		}
		layer.alert('你确定删除该数据吗？',{
			icon:0,
	        title:'提示',
	        btn:['确定','取消'],
	        yes:function(index){
	            layer.close(index);
	           	$.ajax({
	           		url:url,
	           		type:'post',
	           		error:function(){
	           			$.warn("服务器忙");
	           		},
	           		success:function(res){
	           			if (res.result=="SUCCESS") {
	           				$.suc(res.msg);
	           				this.PageGetData();
	           			}else{
	           				$.warn(res.msg);
	           			}
	           		}.bind(this)
	           	})
	        }.bind(this)
		})
	}
}