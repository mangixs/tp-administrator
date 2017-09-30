function adminGetData(seting){
	this.requiring=false;
	this.data_key='data';
	this.data=null;
	this.model_set='flush';
	this.ready=false;
	this.timeout=null;
	this.searchBox=null;
	this.searchType='JSON';
	this.config(seting);
}

$.extend(adminGetData.prototype,{
	config:function(config){
		if( typeof(config)!='object' ){
			alert('设置未定义!');
			return;
		}
		if( typeof(config.box)!='object' && config.box.length!=1 ){
			alert('数据显示区为定义!');
			return;
		}
		if( typeof(config.url)!='string' ){
			alert('数据地址未定义！');
			return;
		}
		if( typeof(config.tpl)!='string' && $('#'+config.tpl).length!=1 ){
			alert('数据渲染未定义！');
			return;
		}
		this.url=config.url;
		this.box=config.box;
		this.tpl=config.tpl;
		this.ready=true;
		this.debug=typeof(config.debug)=='boolean'?config.debug:false;
		this.searchType=typeof(config.searchType)=='string'?config.searchType:'JSON';
	},
	get_data:function(){
		if( this.requiring==true && !this.ready ){
			return;
		}
		this.requiring=true;
		$.extend(this.suc_callback.prototype,{
			dataObj:this,
		});
		var data=this.getCondition();
		var config={
			url:encodeURI(this.url),
			data:data,
			success:this.suc_callback.bind(this),
			error:this.err_callback.bind(this),
		}
		var ajax=$.ajax(config);
	},
	suc_callback:function(res,xhr){
		this.requiring=false;
		if(this.debug){
			console.log( res );
			return;
		}
		if( typeof( res[this.data_key] )=='undefined' ){
			parent.$.err('未能获取到有效的数据!');
			return;
		}
		this.handle(res[this.data_key]);
		if( this.pageObj ){
			this.pageObj.update( res.page );
		}
	},
	err_callback:function(){
		this.requiring=false;
		parent.$.err('获取数据错误!');
	},
	handle:function(datas){
		var tpl=this.tpl;
		var box=this.box;
		var self=this;
		switch( this.model_set ){
			case 'after':
				$.each( datas,function(k,v){
					v=self.dataBeforeHandle(v);
					box.append( template.render(tpl,v) );
				} );			
			break;
			case 'before':
				$.each( datas,function(k,v){
					v=self.dataBeforeHandle(v);
					box.prepend( template.render(tpl,v) );
				} );			
			break;
			case 'flush':
			default:
				box.html('');
				$.each( datas,function(k,v){
					v=self.dataBeforeHandle(v);
					box.append( template.render(tpl,v) );
				} );
			break;
		}
		if( typeof( this.successReturn )=='function' ){
			this.successReturn(datas);
		}
	},
	dataBeforeHandle:function(v){
		return v;
	},
	successReturn:null,
	errorReturn:null,
	pageObj:null,
	selectObj:null,
	condition:{},
	baseCondition:{},
	getCondition:function(){
		var arr=[];
		for( k in this.baseCondition){
			arr.push( k+'='+this.baseCondition[k] );
		}
		for( k in this.condition){
			arr.push( k+'='+this.condition[k] );
		}
		return arr.join('&');
	},
	setCondition:function(set){
		if( typeof(set)=='object' ){
			for( k in set){
				this.condition[k]=set[k];
			}			
		}
		return this;
	},
	resetCondition:function(){
		this.condition={};
		return this;
	},
	setPage:function(obj){
		this.pageObj=obj;
		obj.dataObj=this;
		return this;
	},
	model:function(set){
		if( set ){
			this.model_set=set;
		}		
		return this;
	},
	first:function(){
		if( this.pageObj ){
			this.pageObj.change(1);
		}
		return this;
	},
	setDebug:function(){
		this.debug=true;
		return this;
	},
	setSearch:function(box,act){
		if(box.length==0){
			return;
		}
		this.searchBox=box;
		if( act ){
			box.find("input[type='text'][name]").keyup( this.searchStart.bind(this) );
			//box.find("input[type='text'][name]").keyup( this.searchStart.bind(this) );
			box.find("select[name]").change( this.searchStart.bind(this) );
		}
		box.find('button').click( this.handleSearch.bind(this) );
	},
	searchStart:function(){
		clearInterval( this.timeout );
		this.timeout=setTimeout(this.handleSearch.bind(this),1000);
	},
	handleSearch:function(){
		var set=this.searchBox.serializeArray();
		switch( this.searchType ){
			case 'JSON':
			case 'json':
				var arr={};
				for(k in set){
					arr[ set[k].name ]=set[k].value;
				}
				var str=JSON.stringify(arr);
				this.setCondition({search:str});
				break;
			case 'GET':
			case 'get':
				var arr={};
				for(k in set){
					arr[ set[k].name ]=set[k].value;
				}
				this.setCondition(arr);
				break;
		}
		this.first();
	},
	setBaseCondition:function(set){
		if( typeof(set)=='object' ){
			for( k in set){
				this.baseCondition[k]=set[k];
			}
		}
		return this;
	},
	removeBaseCondition:function(k){
		if( this.baseCondition.hasOwnProperty(k) ){
			delete this.baseCondition[k];
		}
	},
	removeCondition:function(k){
		if( this.condition.hasOwnProperty(k) ){
			delete this.condition[k];
		}
	},
	screenData:function(res){
		this.handle(res[this.data_key]);
		if( this.pageObj ){
			this.pageObj.update( res.page );
		}
	},
})