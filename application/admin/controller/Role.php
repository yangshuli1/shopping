<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
use think\facade\Session;
class Role extends Common
{
   public function list()
   {
   	return $this->fetch();
   }
    public function insert()
   {
   	return $this->fetch();
   }
    public function show()
   {
   	$id=Request::post('id');
   	$mysqll="select p.id,p.name,p.path,p.description,p.category_id,pc.name as pc_name from permission as p join permission_category as pc on p.category_id=pc.id";
      $arr=Db::query($mysqll);
      $ar=Db::query("select * from role_permission where role_id = '$id'");
      $newarr=[];
      foreach ($arr as $key => $value) {
      	$newarr[$value['pc_name']][]=$value;
      }
    $ar=['yan'=>'1','sta'=>$ar,'data'=>$newarr];
   	$json=json_encode($ar);
	echo $json;
   }
    public function action()
   {
   	$mysqll="select * from role";
      $arr=Db::query($mysqll);
      $ar=['yan'=>'1','status'=>'OK','data'=>$arr];
   	  $json=json_encode($ar);
	 	  echo $json;
   }
    public function add()
   {
   	$name=Request::post('name');
   	$path=Request::post('path');
   	$data=Request::post();
   	 $validate = new \app\admin\validate\Permission;
	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
   	$mysq=Db::query("select id from role where name= '$name'");
	if (empty($mysq)) {
   		$pemid=Request::post('pemid');
	   	$arr=explode(',', $pemid);
	  	array_shift($arr);
	  	$perm=implode(',', $arr);
	    	$rbac=new Rbac;
	   	$rbac->createRole([
	    'name' => $name,
	    'description' => $path,
	    'status' => 1
		],$perm);
		$arr=['yan'=>'1','status'=>'OK','data'=>"角色添加成功"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}else{
	   	$arr=['yan'=>'1','status'=>'error','data'=>"角色名已存在"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}
   }
   function upt(){
   	$id=Request::get('id');
    $arr=Db::query("select * from role where id = '$id'");
     $this->assign('arr',$arr);
   	 return $this->fetch();
   }
   function updat(){
 $id=Request::post('id');
 $path=Request::post('path');
 $name=Request::post('name');
 $pemid=Request::post('pemid');
 $data=Request::post();
   	 $validate = new \app\admin\validate\Permission;
	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
   	$arr=Db::query("select * from role where name = 'name'");
   	if (empty($arr)||!empty($arr)&&$arr[0]['id']==$id) {
   		$mysql=Db::query("delete from role_permission where role_id ='$id'");
   		$where=['name'=>$name,'description'=>$path];
   		$mysqli=Db::table('role')->where('id','=',$id)->update($where);
   		$arrt=explode(',', $pemid);
	  	array_shift($arrt);

	  	foreach ($arrt as $key => $value) {
	  		$a="insert into `role_permission`(`role_id`,`permission_id`)value('$id','$value')";
	  		$arr= Db::query($a);
	  	}
	  	$arr=['yan'=>'1','status'=>'error','data'=>"修改成功"];
   	}else{
   		$arr=['yan'=>'1','status'=>'error','data'=>"角色名重复"];
   	}
   	$json=json_encode($arr);
	 	echo $json;
   }
   function dele(){
   	$id=Request::post('id');
   	$mysql=Db::query("delete from role where id ='$id'");
   	$arr=['yan'=>'1','status'=>'error','data'=>"删除成功"];
   }
}