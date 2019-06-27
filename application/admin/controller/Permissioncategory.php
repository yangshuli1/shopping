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
   $name=Request::post('name');
   $marks=Request::post('marks');
   $rbac=new Rbac;
   $rbac->savePermissionCategory([
    'name' => $name,
    'description' => $marks,
    'status' => 1
	]);
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