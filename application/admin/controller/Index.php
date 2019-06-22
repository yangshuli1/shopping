<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Index extends Common
{
    public function index()
    {
	 return view("index");
       
    }
 public function class()
    {
	return view("class");
       
    }
    public function list()
    {
	return view("list");
       
    }

    
}
?>