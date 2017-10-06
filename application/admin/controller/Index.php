<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
class Index extends BaseAdmin{
    public function index(){
        $data['user']=session('staff_session_object');
        $data['menu']=$this->createMenu($data['user']['key']);
        return $this->fetch('index',$data);
    }
    public function loginout(){
        header("Cache-Control:no-cache,must-revalidate,no-store");
        header("Pragma:no-cache");
        header("Expires:-1");
        session('staff_session_object',null);        
        session_destroy();
        $this->redirect('admin/login/index');
    }
    public function welcome(){
        return 'welcome to administrator';
    }
    public function pwd(){
        return view();
    }
    public function save(){
        if ( Request::instance()->isAjax() ) {
            $old=input('post.old');
            $news=input('post.pwd');
            $pwd=input('post.newpwd');
            if ( empty( $old ) and empty($news) and empty($pwd) ) {
                return json(['result'=>'ERROR','msg'=>'密码不能为空']);
            }
            if ( !preg_match('/^[\w|_|\.]{5,12}$/',$pwd) ) {
                return json(['result'=>'ERROR','msg'=>'密码格式不正确']);
            }
            if ( $news !== $pwd ) {
                return json(['result'=>'ERROR','msg'=>'新密码不一致']);
            }
            $user=session('staff_session_object');
            db('staff')->where('id',$user['id'])->update(['pwd'=>md5($pwd)]);
            return json(['result'=>'SUCCESS','msg'=>'修改成功']);
        }
    }
    private function createMenu($func_data){
        $menu_data=$this->menuData();
        foreach ($func_data as $k => $v) {
            if ( empty($res_func[ $v['func_key'] ] ) ) {
                $res_func[ $v['func_key'] ]= [
                    'func_key'=>$v['func_key'],
                    'auth_key'=>[],
                ];
            }
            if ( !in_array( $v['auth_key'], $res_func[ $v['func_key'] ]['auth_key'] ) ) {
                $res_func[ $v['func_key'] ]['auth_key'][]=$v['auth_key'];
            }
        }
        $key_name=array_keys($res_func);
        foreach ($menu_data as $w => $d) {
            if ( in_array($w,$key_name) ) {
                $res[]=[
                    'menu_id'=>$d['menu_id'],
                    'key_val'=>$d['key_val'],
                    'func_val'=>$w,
                ];
            }
        }
        $ret=[];
        foreach ($res as $e => $t) {
            foreach ($t['menu_id'] as $c=>$r) {
                if (!in_array($r,$ret)) {
                    $ret[]=$r;
                }
            }
        }
        ksort($ret);
        $rets=db('menu')->field('id,url,named,icon,level,parent')->whereIn('id',$ret)->order('sort desc')->select();
        $parentList=[];
        foreach( $rets as $menu ){
            $parentList[ $menu['parent'] ][]=$menu;
        }
        $tree=$this->createTree($parentList,0);
        $menuList=$this->createList($tree);
        return $menuList;
    }
    private function createList($tree){
        ob_start();
        echo '<ul class="page-sidebar-menu">';
        foreach ($tree as $k => $v) {
            echo '<li>';
            echo '<a href="javascript:;" class="level">';
            echo '<img src="'.$v['icon'].'" class="menu-icon">';
            echo '<span class="title">'.$v['named'].'</span>';
            echo '<span class="arrow "></span>';
            echo '</a>';
            if (!empty($v['children'])) {
                echo '<ul class="sub-menu">';
                foreach ($v['children'] as $c => $d) {
                    echo '<li>';
                    echo '<a href="'.$d['url'].'" target="list" >';
                    echo '<img src="'.$d['icon'].'" class="menu-icon">';
                    echo '<span>'.$d['named'].'</span>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }
        }
        echo '</ul>';
        $ret=ob_get_contents();
        ob_clean();
        return $ret;
    }
    private function createTree(&$parentList,$pos){
        $ret=[];
        foreach( $parentList[$pos] as $k=>$v ){
            $ret[$v['id']]=$v;
            if( isset( $parentList[ $v['id'] ] ) ){
                $ret[$v['id']]['children']=$this->createTree($parentList,$v['id']);
            }
        }
        return $ret;
    }
    private function menuData(){
        $data=db('menu')->field(['id','screen_auth'])->select();
        foreach ($data as $k=>$v) {
            $tmp=json_decode($v['screen_auth'],true);
            foreach ($tmp as $e=>$c) {
                if ( empty($res[ $e ]) ) {
                    $res[ $e ]=[
                        'menu_id'=>[],
                        'key_val'=>[],
                    ];
                }
                if ( !in_array($v['id'],$res[$e]['menu_id']) ) {
                    $res[ $e ]['menu_id'][]=$v['id'];
                }
                if ( !in_array($c[0],$res[$e]['key_val']) ) {
                    $res[ $e ]['key_val'][]=$c[0];
                }
            }
        }
        return $res;
    }
}
