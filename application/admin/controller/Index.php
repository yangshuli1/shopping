<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
class Index extends Common
{
   public function Index ()
   {
   	return view("Index");
   }
   
}
?>