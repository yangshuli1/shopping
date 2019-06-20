<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Index
{
    public function index()
    {
    $mysql="select * from user";
    $arr1=Db::query($mysql);
 	var_dump($arr1);
	 // return view("index");
       
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
?>