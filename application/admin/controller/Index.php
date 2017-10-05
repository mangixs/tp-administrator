<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use \think\Request;
use BaseAdmin;
class Index extends BaseAdmin{
    public function index(){
        $data['user']=session('staff_session_object');
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
}
