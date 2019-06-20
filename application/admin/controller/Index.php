<?php
namespace app\admin\controller;

class Index
{
    public function index()
    {
	echo"123";
       
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
?>