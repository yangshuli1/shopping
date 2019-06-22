<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\index\model\User;
use think\captcha\Captcha;
use think\facade\Session;
class Login extends Controller
{
    public function Login()
    {
	return view("login");
    }
     public function logi()
    { 

	 $name=input('get.name');
	 $pass=input('get.pass');
    $mysql="select * from user where name='$name'and password='$pass' ";
      $arr=Db::query($mysql);
	  	// $where=['name'=>$name,'password'=>$pass];
	  	// echo  $res=Db::table('user')->where($where)->find();
	  	if (empty($arr)) {
	  	echo "no";
	  	}else{
	  	echo "OK";
	  	}
    }
    

    
}
?>