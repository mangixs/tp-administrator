<?php
namespace app\admin\controller;
use \think\Controller;
use \think\View;
use BaseAdmin;
class Staff extends BaseAdmin{
    public function index(){
        return view();
    }
    public function add(){
    	return $this->fetch();
    }
}
