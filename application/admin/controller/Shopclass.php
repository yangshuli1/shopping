<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
use think\facade\Session;
class Shopclass extends Common
{
   public function list()
   {
   	$arr=Db::query("select * from class");
 	$arrr=$this->getTree($arr);
 	$this->assign('arrr',$arrr);
   	return $this->fetch();
   }
   private function getTree($array, $pid =0, $level = 0){
	    static $list = [];
	    foreach ($array as $key => $value){
	        if ($value['pid'] == $pid){
	            $flg = str_repeat('|--',$level);
	            $value['class_name'] = $flg.$value['class_name'];
	            // echo $value['class_name']."<br/>";
	            $list[] = $value;
	            unset($array[$key]);
	            $this->getTree($array, $value['id'], $level+1);
	        }
	}
    return $list;
	}
	public function insert()
   {
   	return $this->fetch();
   }
    public function action()
   {
   	$arrr=Db::query("select * from class ");
    $arr=$this->getTree($arrr);
 	 $ar=['yan'=>'1','status'=>'OK','data'=>$arr];
 	  $json=json_encode($ar);
 	  echo $json;
   }
  public function add()
   {
   $name=Request::post('class_name');
   	$id=Request::post('id');
   	$description=Request::post('description');
   	$data=Request::post();
   	$mysq=Db::query("select id from class where class_name= '$name'");
	if (empty($mysq)) {
   		$where=['class_name'=>$name,'describe'=>$description,'pid'=>$id];
		$mysqli=Db::table('class')->insert($where);
		$arr=['yan'=>'1','status'=>'OK','data'=>"商品类型添加成功"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}else{
	   	$arr=['yan'=>'1','status'=>'error','data'=>"商品类型名已存在"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}
   }
   function dele(){
    $id=Request::post('id');
    $mysql=Db::query("delete from class where id ='$id'");
    $arr=$this->get($id);
    echo json_encode($arr);

   }
    public function get($id){
     $arr=Db::query("select * from class where pid ='$id'");
     if (empty($arr)) {
      $arr=['yan'=>'1','status'=>'error','data'=>"删除成功"];
      $json=json_encode($arr);
      echo $json;
     }else{
      foreach ($arr as $key => $value){
      $id=$value['id'];
      $mysql=Db::query("delete from class where id ='$id'");
      $this->get($value['id']);
     }    
     }
     // $this->list();
    }
}