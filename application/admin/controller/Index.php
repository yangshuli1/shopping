<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
class Index extends Common
{
   public function index ()
   {
   	return view("index");
   }
   
}
?>