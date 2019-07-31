<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use Redis;
use think\Validate;
use think\facade\Session;
class Goods extends Common
{
   public function list()
   {
   	return $this->fetch();
   }
   public function action()
   {

    $redis = new Redis();
    $like=Request::post('like');
   	$arrr=Db::query("select * from class ");
    $ar=$this->getTree($arrr);
    $atr=Db::query("select * from brand");
    $redis->connect('127.0.0.1',6379);
      $zhanshi=$redis->ZREVRANGE('5028',0,4);
      
      $redis->hSetnx("key1","$like",0);
      $redis->hIncrBy("key1","$like",1);
      $count=$redis->hGet("key1","$like");
    if (empty($like)) {
      $mysqll="select gs.id,gs.gs_name,gs.desc,gs.ip,gs.money,gs.stock,br.id as br_id,br.brand_name ,cl.id as cl_id,cl.class_name from kjgoods as gs join brand as br on gs.b_id=br.id join class as cl on gs.class_id=cl.id";
      $arr=Db::query($mysqll);
      $arr=['yan'=>'1','status'=>$ar,'data'=>$arr,'atr'=>$atr,'aa'=>$zhanshi];
      $json=json_encode($arr);
      echo $json;
      die;
    }else{
      
      $redis->ZINCRBY('5028',$count,$like);
      $mysqll="select gs.id,gs.gs_name,gs.desc,gs.ip,gs.money,gs.stock,br.id as br_id,br.brand_name ,cl.id as cl_id,cl.class_name from kjgoods as gs join brand as br on gs.b_id=br.id join class as cl on gs.class_id=cl.id where gs.gs_name like'%$like%'";
       $arr1=Db::query($mysqll);
       $arr=json_encode($arr1);
       if ($count>10) {
          $redis->hset("key2","list",$arr);
          $stri=$redis->hGet("key2","list");
          $str=json_decode($stri);
          $arr=['yan'=>'1','status'=>$ar,'data'=>$str,'atr'=>$atr,'aa'=>$zhanshi];
       }else{
        $arr=['yan'=>'1','status'=>$ar,'data'=>$arr1,'atr'=>$atr,'aa'=>$zhanshi];
       }
      $json=json_encode($arr);
      echo $json;
    }
   }
   public function insert()
   {
   	
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
	public function add()
   {
   	$name=Request::post('name');
   	$many=Request::post('many');
   	$shf=Request::post('shf');
   	$b_id=Request::post('b_id');
   	$goods=Request::post('goods');
   	$c_id=Request::post('c_id');
   	$desc=Request::post('desc');
   	$mysq=Db::query("select id from kjgoods where gs_name= '$name'");
	if (empty($mysq)) {
   	$where=['gs_name'=>$name,'desc'=>$desc,'ip'=>$goods,'money'=>$many,'b_id'=>$b_id,'class_id'=>$c_id,'stock'=>$shf];
		$mysqli=Db::table('kjgoods')->insert($where);
		$arr=['yan'=>'1','status'=>'OK','data'=>"商品类型添加成功"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}else{
	   	$arr=['yan'=>'1','status'=>'error','data'=>"商品类型名已存在"];
   	 	$json=json_encode($arr);
	 	echo $json;
	   	}
   }
   public function src()
   {
   	$id=Request::get('id');
   	$arr=Db::query("select * from goods_img where goods_id= '$id'");
   	$this->assign('id',$id);
   	$this->assign('arr',$arr);
   	return $this->fetch();
   }
   public function upl(){
    $id=Request::post('id');
    $file = request()->file('file');
    foreach ($file as $key => $value) {
    $info = $value->move( '../public/static/imgs');
    if($info){
    	echo $getSaveName=str_replace("\\","/",$info->getSaveName());
    	$image = \think\Image::open('../public/static/imgs/'.$getSaveName);
   		$name= $info->getFilename();
   		$big=date("Ymd").'/'."in$name";
   		$middle=date("Ymd").'/'."im$name";
   		$slamm=date("Ymd").'/'."ig$name";
    	$image->thumb(150, 150)->save("../public/static/imgs/".$big);
        $image->thumb(100, 100)->save("../public/static/imgs/".$middle);
        $image->thumb(50, 50)->save("../public/static/imgs/".$slamm);
        $where=['big_img'=>$big,'middle_img'=>$middle,'small_img'=>$slamm,'goods_id'=>$id];
	   	$mysqli=Db::table('goods_img')->insert($where);
	  	$arr=['yan'=>'1','status'=>'error','data'=>"添加成功"];	 
		$json=json_encode($arr);
		echo $json;
		
    }else{
        // 上传失败获取错误信息
        echo $file->getError();
    }
}
}
	function del(){
		$id=Request::post('id');
		$arr=Db::query("select * from goods_img where id= '$id'");
		$big=$arr[0]["big_img"];
		$middle=$arr[0]["middle_img"];
		$slamm=$arr[0]["small_img"];
		unlink('static/imgs/'.$big);
		unlink('static/imgs/'.$middle);
		unlink('static/imgs/'.$slamm);
		$arr=Db::query("delete from goods_img where id= '$id'");
		$ar=['yan'=>'1','status'=>'error','data'=>"删除成功"];	 
		$json=json_encode($ar);
		echo $json;
	}
	 public function upt()
   {
   	$id=Request::get('id');
	$arr=Db::query("select * from kjgoods where id= '$id'");
	$this->assign('arr',$arr);
   	return $this->fetch();
   }
    public function updat()
   {
      $id=Request::post('id');
      $name=Request::post('name');
      $many=Request::post('many');
      $shf=Request::post('shf');
      $brand_id=Request::post('brand_id');
      $goods=Request::post('goods');
      $class_id=Request::post('class_id');
      $desc=Request::post('desc');
      $upd=['gs_name'=>$name,'desc'=>$desc,'ip'=>$goods,'money'=>$many,'b_id'=>$brand_id,'class_id'=>$class_id,'stock'=>$shf];
	  $mysqli=Db::table('kjgoods')->where('id',$id)->update($upd);
	  $ar=['yan'=>'1','status'=>'error','data'=>"修改成功"];	 
		$json=json_encode($ar);
		echo $json;
   }
   function dele(){
   	$id=Request::post('id');
   	$arr=Db::query("delete from kjgoods where id= '$id'");
   	$ar=['yan'=>'1','status'=>'error','data'=>"删除成功"];	 
		$json=json_encode($ar);
		echo $json;
   }
   public function attr()
   {
    $id=Request::get('id');
    $this->assign('id',$id);
    return $this->fetch();
   }
   public function gsattr()
   {
    $id=Request::get('id');
    $arr=Db::query("select * from kjgoods where id= '$id'");
    $attr_cat=$arr[0]['attr_cat'];
    $this->assign('attr_cat',$attr_cat);
    $this->assign('id',$id);
    return $this->fetch();
   }
   function show(){
      $id=Request::post('sel');
      $did=Request::post('id');
      $sta=Db::query("select attr_details_id from goods_attr where goods_id ='$did'");
      $arr=Db::query("select bu.id as bu_id,bu.name as bu_name,bu.attrcate_id as bca_id,de.id as de_id ,de.name as de_name from goods_attr join attribute as bu on goods_attr.attr_id=bu.id join attr_details as de on goods_attr.attr_details_id=de.id where goods_id='$did'");
        $newarr=[];
      foreach ($arr as $key => $value) {
         $newarr[$value['bu_name']][]=$value;
      }
    $ar=['yan'=>'1','sta'=>$sta,'data'=>$newarr];
    $json=json_encode($ar);
    echo $json;
      
   }
    public function goodsatt()
   {
    alert(reusult.data);
      // show()
    $price=Request::post('price');
    $number=Request::post('number');
    $str=Request::post('str');
    $tr=implode("-", $str);
    $sta=Db::query("select * from goodatt where goods_attr_id='$tr'");
    if (empty($sta)) {
      $where=['goods_id'=>$id,'goods_attr_id'=>$tr,'price'=>$price,'sn_number'=>$number];
    $mysqli=Db::table('goodatt')->insert($where);
    $ar=['yan'=>'1','sta'=>'0','data'=>"添加成功"];
    }else{
    $ar=['yan'=>'1','sta'=>'0','data'=>"不能重复"];
    }
    $json=json_encode($ar);
    echo $json;
   }
   public function goodsattr()
   {
    $id=Request::get('id');
    $this->assign('id',$id);
    return $this->fetch();
   }
   public function goodsaction()
   {
    $id=Request::post('id');
    $goods=Db::query("select * from goodatt where goods_id='$id'");
    $gsatt=[];
    for ($i=0; $i < count($goods) ; $i++) { 
        $newatt=[];
        $goods_attr_id=$goods[$i]['goods_attr_id'];
        $gsatt_id=explode("-", $goods_attr_id);
        //var_dump($gsatt_id);
        for ($j=0; $j <count($gsatt_id); $j++) { 
          $desc=$gsatt_id[$j];
         $attr_details=Db::query("select * from attr_details where id='$desc'");
         $newatt[]=$attr_details[0]['name'];
        }
        $attrs=implode("-",$newatt);
        $goods[$i]['name']=$attrs;
        // var_dump($attrs);
    }
     $ar=['yan'=>'1','sta'=>'0','data'=>$goods];
     $json=json_encode($ar);
     echo $json;
   }

}