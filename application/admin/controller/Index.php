<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
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
}
