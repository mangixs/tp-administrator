function managerPage(box){
    this.page=1;
    this.every_set=[10,25,50,100];
    this.every=this.every_set[0];
    this.box=box;
    this.init();
}
$.extend(managerPage.prototype,{
    set:function(){
        return {page:this.page,every:this.every};
    },
    update:function(page){
        if( this.page>1 && this.page>page.page_count ){
            this.change( page.page_count );
            return;
        }
        var start=page.count_all==0?0:(page.page-1)*page.every+1;
        $('#data_start').html(start);
        var end=page.page*page.every;
        var es=end<page.count_all?end:page.count_all;
        $('#data_end').html(es);
        $('#data_count').html(page.count_all);
        var num=parseInt( page.page );
        var pcou=parseInt( page.page_count );
        var s=(num-3)>1?(num-3):1;
        var ov=pcou-(num+3);
        if( ov<0 ){
            s+=ov;
            if( s<1 ){
                s=1;
            }
        }
        var arr=[];
        var i=0;
        do{
            arr.push(s);
            i++;
            s++;
        }while( i<7 && s<=pcou );
        var show=$('.page_show');
        show.html('');
        if( num>1 ){
            show.append( '<button onclick="pageObj.change(1)"><<</button>' );
            show.append( '<button onclick="pageObj.change('+(num-1)+')"><</button>' );
        }

        for( i=0;i<arr.length;i++ ){
            var but=$('<button onclick="pageObj.change('+arr[i]+')">'+arr[i]+'</button>');
            if( arr[i]==num ){
                but.addClass('active');
            }
            show.append(but);
        }
        if( num<pcou ){
            show.append( '<button onclick="pageObj.change('+(num+1)+')">></button>' );
            show.append( '<button onclick="pageObj.change('+pcou+')">>></button>' );
        }
    },
    change:function(page,model){
        if( typeof(page)=='number' ){
            this.page=page;
        }
        dataObj.setCondition({page:this.page,every:this.every}).model(model).get_data();
    },
    init:function(){
        var box=this.box;
        if( box.length==0 ){
            return;
        }
        box.append('<div class="data_show">显示第<span id="data_start"></span>至<span id="data_end"></span>项结果，共<span id="data_count"></span>项</div>');
        box.append('<div class="page_show"></div>');
        var eveBox=$('<div class="every_show"><span>每页:</span></div>');
        var eveSel=$('<select></select>');
        for( i=0;i<this.every_set.length;i++ ){
            eveSel.append('<option value="'+this.every_set[i]+'">'+this.every_set[i]+'</option>');
        }
        eveSel.change(function(evt){
            var self=typeof( evt.target )=='object' ?$(evt.target):$(evt.srcElement);
            this.every=self.val();
            this.change(1);
        }.bind(this));
        eveBox.append(eveSel);
        box.append(eveBox);
    },
    dataObj:null,
})
