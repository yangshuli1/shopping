<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use Request;
use think\Validate;
use think\facade\Session;
class Goodsattr extends Common
{
   public function list()
   {
   	return $this->fetch();
   }
   public function action()
   {
         $id=Request::post('id');
         $arr=Db::query("select attr_cat from kjgoods where id ='$id' ");
         $attr_cat=$arr[0]['attr_cat'];
         $mysq=Db::query("select * from attr_category");
         $arr=['yan'=>'1','dete'=>$attr_cat,'data'=>$mysq];
         $json=json_encode($arr);
         echo $json;
   }
   public function bute()
   {
      $id=Request::get('id');
      $arr=Db::query("select * from attribute where attrcate_id='$id'");
      $this->assign('arr',$arr);
      return $this->fetch();
   }
   public function details()
   {
      $id=Request::get('id');
      $arr=Db::query("select * from attr_details where attr_id='$id'");
      $this->assign('arr',$arr);
      return $this->fetch();
   }
   public function inse()
   {
      $name=Request::post('name');
      $where=['name'=>$name];
      $mysqli=Db::table('attr_category')->insert($where);
      $arr=['yan'=>'1','status'=>'OK','data'=>"添加成功"];
      $json=json_encode($arr);
         echo $json;
   }
   public function inser()
   {
      $id=Request::post('id');
      $name=Request::post('name');
      $where=['name'=>$name,'attrcate_id'=>$id];
      $mysqli=Db::table('attribute')->insert($where);
      $arr=['yan'=>'1','status'=>'OK','data'=>"添加成功"];
      $json=json_encode($arr);
         echo $json;
   }
    public function det()
   {
      $arr=Db::query("select bu.id as bu_id,bu.name as bu_name,de.id as de_id ,de.name as de_name from  attribute as bu  join attr_details as de on bu.id=de.attr_id where bu.attrcate_id='$id'");

     $id=Request::post('sel');
      $ar=Db::query("select * from attribute where attrcate_id='$id'");
      $arr=['yan'=>'1','status'=>'OK','data'=>$ar];
      $json=json_encode($arr);
      echo $json;
   }
    public function tail()
   {
      $id=Request::post('ect');
      $name=Request::post('name');
      $where=['name'=>$name,'attr_id'=>$id];
      $mysqli=Db::table('attr_details')->insert($where);
      $arr=['yan'=>'1','status'=>'OK','data'=>"添加成功"];
      $json=json_encode($arr);
         echo $json;
   }
   function show(){
      $id=Request::post('sel');
      $did=Request::post('id');
      //echo $id;
      $sta=Db::query("select attr_details_id from goods_attr where goods_id ='$did'");
      $arr=Db::query("select bu.id as bu_id,bu.name as bu_name,de.id as de_id ,de.name as de_name from  attribute as bu  join attr_details as de on bu.id=de.attr_id where bu.attrcate_id='$id'");
        $newarr=[];
      foreach ($arr as $key => $value) {
         $newarr[$value['bu_name']][]=$value;
      }
    $ar=['yan'=>'1','sta'=>$sta,'data'=>$newarr];
    $json=json_encode($ar);
    echo $json;
      
   }
   function add(){
      $id=Request::post('id');
      $sel=Request::post('sel');
      $pemid=Request::post('pemid');//把数组接过来
      if (empty($pemid)) {
         $upd=['attr_cat'=>''];
         $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
      }else{
         $upd=['attr_cat'=>$sel];
         $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
      }
      $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
      Db::query("delete from goods_attr where goods_id='$id'");
      $arr=explode(',', $pemid);//接过来第一个是空值和逗号，去除第一个字符串逗号
      array_shift($arr);//去除第一值
      //$perm=implode(',', $arr);//用逗号来拆分数组
      
      //$sql[]='';
      foreach ($arr as $key => $value) {
      $ar=Db::query("select * from attr_details where id='$value'");
      $sql=$ar[0]["attr_id"];
      $where=['goods_id'=>$id,'attr_details_id'=>$value,'attr_id'=>$sql];
      $mysqli=Db::table('goods_attr')->insert($where);   
      }
       
   }
  
}