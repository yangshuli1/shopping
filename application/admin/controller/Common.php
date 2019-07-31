<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use Request;
use Redis;
use gmars\rbac\Rbac;
use think\facade\Session; 
class Common extends Controller
{
   public function __construct(){
        parent::__construct();
             $redis = new Redis();
     // $redis->connect('127.0.0.1',6379);
 
     //    // incr() 对指定的key的值加1
     //    // decr()对指定的key的值减1
     //    // incrBy() 将第二个参数的值加到key的值上
     //    // decrBy() 将第二个参数的值加到key的值上
     //    // incrByFloat() 自增一个浮点类型的值
        
     //    echo $redis->incr('shenzhen')."<br/>";//1
     //    echo $redis->incr('shenzhen')."<br/>";//2
     //    echo $redis->incrBy('shenzhen',6)."<br/>";//8
     //    echo $redis->decr('shenzhen')."<br/>";//7
     //    echo $redis->decr('shenzhen')."<br/>";//6
     //    echo $redis->decrBY('shenzhen',3)."<br>"; //3
     //    echo $redis->incrByFloat('shenzhen',0.88);//3.88

            //验证是否登录
    	$name=Session::get('name');
    	if (empty($name)) {
    		$this->redirect('login/login');
    	}else{
    		$this->assign('name',$name);
    	}
     // 验证是否有权限
        $module=Request::module();//请求当前模块
        $class=Request::controller();//请求当前控制器
        $action=Request::action();//请求当前方法
        $arr_class=['Permission','Permissioncategory','Role','User'];
        $arr_action=['add','updat','dele','action'];
        if (in_array($class,$arr_class)) {
            if (in_array($action,$arr_action)) {
                        // echo "123";die;
               $str="admin/$class/$action";
               //$str=strtolower($str);//把首字母转换为小写
               $rbac=new Rbac;
               $bool=$rbac->can($str);//如果有权限返回true如果没有权限返回false
               if ($bool==false) {
                    $arr=['code'=>'0','status'=>'error','data'=>"没有权限"];
                    $json=json_encode($arr);
                    echo $json;
                    die;
                   }

                 }
        }
    }


}
?>