<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
class Permissioncategory extends Common
{

   public function list()
   {
   	return $this->fetch();
   }
    public function add()
   {
   $data=Request::post('');
   	  $validate = new \app\admin\validate\Permissioncategory;
   	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
   $rbac=new Rbac;
   $name=$rbac->getPermissionCategory([['name','=',$data['name']]]);
   if (empty($name)) {
   	$rbac->savePermissionCategory([
    'name' => $data['name'],
    'description' =>$data['description'],
    'status' => 1

	]);
	$arr=['yan'=>'1','status'=>'OK','data'=>"添加成功"];
   	 $json=json_encode($arr);
	 	echo $json;
   }else{
   	$arr=['yan'=>'1','status'=>'error','data'=>"类名已存在"];
   	 $json=json_encode($arr);
	 	echo $json;
	 	
   }
   }
   public function insert()
   {
   	return $this->fetch();
   }
   public function action()
   {
   	$rbac=new Rbac;
   	$ar=array();
   	$arr=$rbac->getPermissionCategory($ar);
   	// var_dump($arr);
   	$arrr=['message'=>$arr];
   	 $json=json_encode($arrr);
	 echo $json;
   }
   public function dele()
   {
   	$data=Request::post('id');
   	$rbac=new Rbac;
   	$rbac->delPermissionCategory([$data]);
   	$arr=['yan'=>'0','status'=>'error','data'=>"删除成功"];
   	 $json=json_encode($arr);
	 	echo $json;
   }
   public function mydel()
   {
   	$id=Request::post('id');
   	if (empty($id)) {
   		$arr=['yan'=>'0','status'=>'error','data'=>"请选择要删除的内容"];
   	 $json=json_encode($arr);
	 	echo $json;
   	}else{
   	$data=explode(',', $id);
   	array_shift($data);
   
   	$rbac=new Rbac;
   	$rbac->delPermissionCategory($data);
   	$arr=['yan'=>'0','status'=>'error','data'=>"删除成功"];
   	 $json=json_encode($arr);
	 	echo $json;
   	}
   
   }
   public function updat()
   {
   	$data=Request::post('name');
   	$id=Request::post('id');

   $rbac=new Rbac;
   $name=$rbac->getPermissionCategory([['name','=',$data]]);
   if (empty($name)) {
   	 $mysql="update permission_category set name='$data' where id='$id'";
     $arr=Db::query($mysql);
	$arr=['yan'=>'1','status'=>'OK','data'=>"修改成功"];
 	$json=json_encode($arr);
	 	echo $json;
	die;
   }else{
   	if ($name[0]['id']!=$id) {
   	$arr=['yan'=>'0','status'=>'error','data'=>"类名已存在"];
   	 $json=json_encode($arr);
	 	echo $json;
	 	die;
   }
    $mysql="update permission_category set name='$data' where id='$id'";
     $arr=Db::query($mysql);
	$arr=['yan'=>'1','status'=>'OK','data'=>""];
 	$json=json_encode($arr);
	 	echo $json;
   // 	$arr=['yan'=>'0','status'=>'error','data'=>"类名已存在"];
   // 	 $json=json_encode($arr);
	 	// echo $json;
	 	//echo "23456";
   }
   }
   
}
?>