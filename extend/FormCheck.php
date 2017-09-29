<?php
class FormCheck{
	private $__set=[];
	private $__default_null=true;
	private $__default_preg=[
		':mobile'=>'/^[1]{1}[0-9]{10}$/',
		':email'=>'/^([\w|_|-]+)@([\w|.]+).([com|cn]{1})$/',
		':tel'=>'/^(([1]{1}[0-9]{10}){1})|((([0-9]{3,4}[-|_]?)?[0-9]{7}){1})$/',  
		':phone'=>'/^[1]{1}[0-9]{10}$/',  
		':notnull'=>'/^.+$/',
		':ch'=>'/^[\u4e00-\u9fa5|\s]+$/',
		':ch_no_space'=>'/^[\u4e00-\u9fa5]+$/',
		':en'=>'/^[\w|\s]+$/',
		':en_no_space'=>'/^\w+$/',
		':number'=>'/^[0-9]+$/',
		':login_name'=>'/^\w{4,18}$/',
		':password'=>'/^\w{6,18}$/',
		':over_zero'=>'/^([1-9]{1}[0-9]*(\.[0-9]+)?)|(0\.[0-9]+[1-9]{1})$/', 
		':image'=>'/^.+\.(jpg|png|jpeg|gif){1}$/i', 
		':date'=>'/^[0|1]?[0-9]{3}[-|_|.|\/]?$/i',
		':amount'=>'/^([0-9]+(\.[0-9]+)?)|(0\.[0-9]+[1-9]{1})$/',
	];
	private $__php_corrected_preg=[
		'/^[\u4e00-\u9fa5|\s]+$/'=>'/^[\x{4e00}-\x{9fa5}|\s]+$/u',
		'/^[\u4e00-\u9fa5]+$/'=>'/^[\x{4e00}-\x{9fa5}]+$/u',
	];
	public function __construct($set=[]){
		$this->set_check($set);
	}
	protected function set_check($sets){
		foreach( $sets as $skey=>$set ){
			$tmp=[];
			foreach($set as $k=>$v){
				if( empty( $v['name'] ) or empty( $v['preg'] ) ){
					continue;
				}
				$tmp[ $v['name'] ]=$v;
				if( !isset( $tmp[ $v['name'] ]['not_null'] ) ){
					$tmp[ $v['name'] ]['not_null']=$this->__default_null;
				}
				if( empty( $tmp[ $v['name'] ]['notice'] ) ){
					$tmp[ $v['name'] ]['notice']=$v['name']."格式错误";
				}
				if( array_key_exists(  $tmp[ $v['name'] ]['preg'],$this->__default_preg ) ){
					$tmp[ $v['name'] ]['preg']=$this->__default_preg[ $tmp[ $v['name'] ]['preg'] ];
				}
			}
			$this->__set[ $skey ]=$tmp;
		}		
	}
	public  function checkFrom($data,$item){
		if( empty( $this->__set[$item] ) ){
			return ['result'=>'WRONG_FORM','msg'=>'输入表单值错误!'];
		}
		$sets=$this->__set[$item];
		foreach( $sets as $k=>$set ){
			$val=@$data[ $k ];
			$check=true;
			if( empty($val) and $set['not_null']===false ){
				$check=false;
			}
			if( array_key_exists($set['preg'],$this->__php_corrected_preg) ){
				$set['preg']=$this->__php_corrected_preg[ $set['preg'] ];
			}
			if( $check and !preg_match_all($set['preg'],$val) ){
				return ['result'=>'CHECK_WRONG','msg'=>$set['notice'],'row'=>$k];
			}
		}
		return ['result'=>'CHECK_PASS','msg'=>'验证通过'];				
	}
}