class edit{
    constructor(){
        this.submiting=false;
        this.saveurl=null;
        $("[data-btn='submit']").bind('click',this.save.bind(this));
    }
    save(){
        if ( this.submiting ) {
            return;
        }
        let data=$("#edit_form").serialize();
        let url=$("#edit_form").attr('data-action');
        this.submiting=true;
        $.ajax({
            url:url,
            data:data,
            type:'post',
            error:function(){
                parent.$.warn('服务器忙,请重试');
                this.submiting=false;
            }.bind(this),
            success:function(res){
                if ( res.result == 'SUCCESS' ) {
                    parent.$.suc(res.msg);
                    window.location=this.saveurl.replace('{id}',res.id);
                    parent.adminObj.PageGetData()
                }else{
                    parent.$.warn(res.msg);
                    this.submiting=false;
                }
            }.bind(this)
        })
    }
    setForm(form,data){
        this.saveData=data;
        $.each(data,function(k,v){
            try{
                if( this.customFill.hasOwnProperty(k) ){
                    switch( typeof(this.customFill[k]) ){
                        case 'function':
                            var ret=this.customFill[k](v,data);
                            if( typeof(ret)=='undefined' )
                                return true;
                            else
                                v=ret;
                            break;
                        case 'string':
                        case 'number':
                            v=this.costom_fill[k];
                            break;
                        case 'object':
                            if( this.costom_fill[k]==null ){
                                return true;
                            }
                            break;
                    }
                }
            }
            catch(e){
                console.log(e);
            }
            var tag=form.find("[name='"+k+"']");
            if( tag.length==0 ){
                return true;
            }
            switch( tag[0].tagName.toLowerCase() ){
                case 'input':
                    switch( tag[0].type ){
                        case 'text':
                        case 'hidden':
                        case 'datetime-local':
                        case 'date':
                            tag.val(v);
                            break;
                        case 'checkbox':
                            if( parseInt(v)>0 ){
                                tag.attr('checked','checked');
                            }
                            break;
                        case 'radio':
                            tag.each(function(){
                                if($(this).attr('value')==v){
                                    $(this).attr('checked','checked');
                                }
                            });
                            break;
                    }
                    break;
                case 'select':
                    tag.val( v );
                    tag.trigger('change');
                    break;
                case 'TEXTAREA':
                    tag.html( v );
                    break;
                case 'textarea':
                    tag.html( v );
                    break;   

            }
        }.bind(this));
    }
    customFill(){

    }
    setFormStatus(set){
        switch( set ){
            case 'edit':
                $("[data-disabled]").attr('disabled','disabled');
                break;
            case 'browse':
                $("input,select,textarea").attr('disabled','disabled');
               $("[data-btn]").unbind('click').html('关闭').click(function(){
                    parent.adminObj.closePage();
                });
                break;
        }
    }
}