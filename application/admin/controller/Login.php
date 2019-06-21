<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use app\index\model\User;
use think\captcha\Captcha;
class Login
{
    public function login()
    {
	return view("login");
    }
     public function log()
    { 
	  $name=input('get.name');
	  $pass=input('get.pass');
	  $yan=input('get.yan');
	  $captcha = new \think\captcha\Captcha();
	  if (!$captcha->check($yan)) {
	  	$arr=['yan'=>'1','status'=>'error','message'=>"验证码错误"];
	  }else{
	  	$where=['name'=>$name,'password'=>$pass];
	  	$res=Db::table('user')->where($where)->find();
	  	if (empty($res)) {
	  	$arr=['name'=>'2','status'=>'error','message'=>"账号或码错误"];
	  	}else{
	  		$arr=['name'=>'0','status'=>'OK','message'=>"登录成功"];
	  	}
	  }
	  $json=json_encode($arr);
	  echo $json;
    }
    

    
}
?>