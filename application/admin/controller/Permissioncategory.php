<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
class Permissioncategory extends Common
{

   public function list()
   {
   	return $this->fetch();
   }
    public function add()
   {
   $data=Request::post('');
   // var_dump($data);
   // die;
   	 $validate = new \app\admin\validate\permiss;
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

   
}
?>