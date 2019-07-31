<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
use think\facade\Session;
class User extends Common
{
   public function list()
   {
   	return $this->fetch();
   }
    public function insert()
   {
   	return $this->fetch();
   }
    public function action()
   {
   	$mysqll="select u.id,u.user_name,u.password,u.mobile,u.last_login_time,u.create_time,r.name from user as u join user_role as us on u.id=us.user_id join role as r on us.role_id=r.id";
      $arr=Db::query($mysqll);
      $ar=['yan'=>'1','status'=>'OK','data'=>$arr];
   	  $json=json_encode($ar);
	 	  echo $json;
   }
    public function add()
   {
   	$sel=Request::post('sel');
   	$name=Request::post('name');
   	$description=Request::post('description');
   	$path=Request::post('path');
   	$data=Request::post();
   	 $validate = new \app\admin\validate\Permission;
	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
   	$mysq=Db::query("select id from user where user_name= '$name'");
	if (empty($mysq)) {
	   Db::query("insert into `user`(`user_name`,`password`,`mobile`)value('$name','$path','$description')");
	   $mys=Db::query("select id from user where user_name= '$name'");
	   $sql=$mys[0]['id'];
	   $rbac=new Rbac;
	   $id[]=$sel;
	   $rbac->assignUserRole($sql, $id);
		$arr=['yan'=>'1','status'=>'OK','data'=>"管理员添加成功"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}else{
	   	$arr=['yan'=>'1','status'=>'error','data'=>"管理员名已存在"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}
   }
    function dele(){
   	$id=Request::post('id');
   	$mysql=Db::query("delete from user where id ='$id'");
   	$mysq=Db::query("delete from user_role where user_id ='$id'");
   	$arr=['yan'=>'1','status'=>'error','data'=>"删除成功"];
   	$json=json_encode($arr);
	 	echo $json;
   }
   function updat(){
   	$id=Request::get('id');
    $arr=Db::query("select u.id,u.user_name,u.password,u.mobile,us.role_id from user as u join user_role as us on u.id=us.user_id where u.id='$id'");
      $this->assign('arr',$arr);
   	 return $this->fetch();
   }
function upt(){
	 $id=Request::post('id');
	 $path=Request::post('path');
	 $name=Request::post('name');
	 $description=Request::post('description');
	 $sel=Request::post('sel');
	 $data=Request::post();
   	 $validate = new \app\admin\validate\Permission;
	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
	 $arr=Db::query("select * from user where user_name = '$name'");
   	  if (empty($arr)||!empty($arr)&&$arr[0]['id']==$id) {
   	  	$where=['user_name'=>$name,'password'=>$path,'mobile'=>$description];
   		$mysqli=Db::table('user')->where('id','=',$id)->update($where);
   		$rbac=new Rbac;
	   $tid[]=$sel;
	   $rbac->assignUserRole($id, $tid);
	  $arr=['yan'=>'1','status'=>'error','data'=>"修改成功"];
   	 }else{
   		$arr=['yan'=>'1','status'=>'error','data'=>"角色名重复"];
   	 }
   	$json=json_encode($arr);
	 	echo $json;
   }
}