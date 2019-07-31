<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
use think\facade\Session;
class Permission extends Common
{
   public function list()
   {

   	return $this->fetch();
   }
   public function action()
   {
   	$mysqll="select p.id,p.name,p.path,p.description,pc.name as pc_name from permission as p join permission_category as pc on p.category_id=pc.id";
      $ar=Db::query($mysqll);
      $arr=['yan'=>'1','status'=>'OK','data'=>$ar];
   	$json=json_encode($arr);
	 	echo $json;
	 }
	 public function insert()
   {
   	return $this->fetch();
   }
   public function add()
	{ 
	  $data=Request::post('');
	  $validate = new \app\admin\validate\Permission;
	  if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
	 	echo $json;
	 	die;
        }	 
	 $validate =new  \app\admin\validate\Permission;
	$rbac=new Rbac;
	 $name=$rbac->getPermission([['name','=',$data['name']]]);
	 $path=$rbac->getPermission([['path','=',$data['path']]]);
	 if (empty($name)&&empty($name)) {
	 	$rbac->createPermission([
    'name' => $data['name'],
    'description' =>$data['description'] ,
    'status' => 1,
    'type' => 1,
    'category_id' =>$data['sel'],
    'path' => $data['path'],
	]);
	 $arr=['yan'=>'1','status'=>'OK','data'=>"添加成功"];
   	 $json=json_encode($arr);
	 	echo $json;
	 }else{
	 $arr=['yan'=>'1','status'=>'error','data'=>"权限或者权限路径已存在"];
   	 $json=json_encode($arr);
	 	echo $json;
	 }
	
	}
	public function dele()
   {
   	$data=Request::post('id');
   	$rbac=new Rbac;
   	$rbac->delPermission([$data]);
   	$arr=['yan'=>'0','status'=>'error','data'=>"删除成功"];
   	 $json=json_encode($arr);
	 	echo $json;
   }
   function updatet(){
 $data=Request::get('id');
 $mysql="select * from permission where id='$data' ";
  $arr=Db::query($mysql);
  $this->assign('arr',$arr);
   	return $this->fetch();
   }
   function updat(){
    $tok= Session::get('token');
    $token=Request::post('token');
   	$id=Request::post('id');
   	$name=Request::post('name');
   	$path=Request::post('path');
   	$description=Request::post('description');
   	$sel=Request::post('sel');
    $data=Request::post('');
    if ($token!=$tok) {
      $arr=['yan'=>'1','status'=>'OK','data'=>"令牌无效"];
    $json=json_encode($arr);
    echo $json;
      die;
    }
    $validate = new \app\admin\validate\Permission;
    if (!$validate->check($data)) {
        $arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
    echo $json;
    die;
        } 
  	$arr=Db::query("select id from permission where name='$name' or path='$path'");
  	if (empty($arr)) {
  		$data=['name'=>$name,'path'=>$path,'description'=>$description,'category_id'=>$sel];
  		Db::table('permission')->where('id','=',$id)->update($data);
  		$arr=['yan'=>'1','status'=>'OK','data'=>"修改成功"];
 		$json=json_encode($arr);
	 	echo $json;
	 	die;
  	}else{
  		foreach ($arr as $key => $value) {
		  	if ($value['id']!=$id) {
		   	$arr=['yan'=>'0','status'=>'error','data'=>"类名或路径已存在"];
		   	 $json=json_encode($arr);
			 	echo $json;
			 	die;
		   }
  		}
  		$data=['name'=>$name,'path'=>$path,'description'=>$description,'category_id'=>$sel];
  		Db::table('permission')->where('id','=',$id)->update($data);
  	}
   }
  
	 public function gettoken(){
        $token = $this->request->token('__token__', 'sha1');
        $json=['code'=>'0','status','error'=>'ok','data'=>$token];
        echo json_encode($json);
        Session::set('token',$token);
   }
	
}