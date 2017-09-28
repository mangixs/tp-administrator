$(function(){
	__loadcss();
	$.extend({suc:__jq_notice_success,warn:__jq_notice_warning,err:__jq_notice_error})
})
var __notice_time=3000;
var __notice_timeout;
var __end_fun=null;
function __loadcss(){
	var url='/static/css/jq_notice.css';
	var css=document.createElement( 'link' );
	css.rel='stylesheet';
	css.type='text/css';
	css.href=url;
	document.getElementsByTagName('head')[0].appendChild(css);	 
}
function __jq_notice_success(msg,fun){
	__show_notice(msg,'suc');
	__end_fun=fun;
}
function __jq_notice_warning(msg,fun){
	__show_notice(msg,'warn');
	__end_fun=fun;
}
function __jq_notice_error(msg,fun){
	__show_notice(msg,'err');
	__end_fun=fun;
}
function __show_notice(msg,type){
	__destroy_notice();
	var box=$('<div class="jq_notice" id="jq_notice"></div>');
	var str=$('<strong class="'+type+'"></strong>');
	str.html(msg);
	box.append(str);
	$('body').append(box);
	__notice_timeout=setTimeout(__notice_end,__notice_time);
}
function __notice_end(){
	__destroy_notice();
	if( typeof(__end_fun)=='function' ){
		__end_fun();
	}
	__end_fun=null;
}
function __destroy_notice(){
	$('#jq_notice').remove();
	clearTimeout( __notice_timeout );
}