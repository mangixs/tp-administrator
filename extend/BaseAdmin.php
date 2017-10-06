<?php
use \think\Controller;
use \think\db;
class BaseAdmin extends Controller{
	public function __construct(){
		parent::__construct();
		if ( empty( session('staff_session_object') ) ) {
			$this->redirect('admin/login/index');
		}
	}
	protected function hasAuth($id){
		$res=db('admin_job_auth')->where('admin_job_id',$id)->select();
		$ret=[];
		foreach ($res as $v) {
			$ret[ $v['func_key'] ][]=$v['auth_key'];
		}
		return $ret;
	}
	protected function funcAuth(){
        $func=$this->func_all();
        $auth=$this->auth_all();
        $tmp=[];
        foreach( $auth as $v ){
            $tmp[ $v['func_key'] ][]=$v;
        }
        $ret=[];
        foreach( $func as $k=>$v ){
            $ret[ $v['key'] ]=$v;
            if( array_key_exists( $v['key'],$tmp ) ){
                $ret[ $v['key'] ]['auth']=$tmp[ $v['key'] ];
            }
            else{
                $ret[ $v['key'] ]['auth']=[];
            }
        }
        return $ret;
    }
    private function func_all(){
        return db('background_func')->select();
    }
    private function auth_all(){
        return db('func_auth')->select();
    }
    protected function allMenu(){
        $res=db('menu')->field('id,named,level,parent')->select();
        $data=[];
        foreach ($res as $k => $v) {
            $data[ $v['parent'] ][ $v['id'] ]=$v;
        }
        $tree=[];
        $this->treeMenu($data,0,$tree);
        return $tree;
    }
    private function treeMenu(&$data,$pid,&$tree){
        if( isset( $data[ $pid ] ) ){
            foreach( $data[ $pid ]  as $k=>$v){
                $tree[ $k ]=$v;
                $this->treeMenu($data,$k,$tree);
            }
        }
    }
}