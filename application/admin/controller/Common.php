<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use think\facade\Session; 
class Common extends Controller
{
   public function initialize()
    {
	$name=Session::get('name');
	if (empty($name)) {
		$this->redirect('login/login');
	}else{
		$this->assign('name',$name);
	}
    }
    // public function gettoken(){
    //     $token = $this->request->token('__token__', 'sha1');
    //     $json=['code'=>'0','status','error'=>'ok','data'=>$token];
    //     echo json_encode($json);
    //     Session::set('token',$token);
    // }
}
?>