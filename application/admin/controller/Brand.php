<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use gmars\rbac\Rbac;
use Request;
use think\Validate;
use think\facade\Session;
class Brand extends Common
{
   public function list()
   {
   	return $this->fetch();
   }
    public function action()
   {
  $ar=Db::query("select * from brand");
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
	   	$url=Request::post('url');
	   	$brand_name=Request::post('brand_name');
	   	$description=Request::post('description');
   	  $file = request()->file('logo');
    // 移动到框架应用根目录/uploads/ 目录下
   		$data=Request::post();
   		// var_dump($data);die;
   	 $validate = new \app\admin\validate\brand;
   	   if (!$validate->check($data)) {
   	  	$arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
   	  	$json=json_encode($arr);
		echo $json;
	 	die;
        }
        	$mysq=Db::query("select id from brand where brand_name= '$brand_name'");
			if (empty($mysq)) {
				if ($file) {
				$info = $file->move( '../public/static/img');       	
				    if($info){
				      $getSaveName=str_replace("\\","/",$info->getSaveName());
					    $where=['brand_name'=>$brand_name,'desc'=>$description,'url'=>$url,'logo'=>$getSaveName];
					   	$mysqli=Db::table('brand')->insert($where);
					  	$arr=['yan'=>'1','status'=>'error','data'=>"添加成功"];	 
						$json=json_encode($arr);
						echo $json;
						die;
				    }else{
				        // 上传失败获取错误信息
				        echo $file->getError();
				    }
				}else{
					$arr=['yan'=>'1','status'=>'error','data'=>"文件不能为空"];	 
						$json=json_encode($arr);
						echo $json;
						die;
					}
			}else{
				$arr=['yan'=>'1','status'=>'error','data'=>"用户名已存在"];
		   	  	$json=json_encode($arr);
				echo $json;
			 	die;
			}
   }
   public function dele()
   {
   	$id=Request::post('id');
    $logo=Request::post('logo');
    unlink('static/img/'.$logo);
   	Db::query("delete from brand where id='$id'");
   	$arr=['yan'=>'0','status'=>'error','data'=>"删除成功"];
   	$json=json_encode($arr);
	   echo $json;
   }
    function upt(){
   	$id=Request::get('id');
    $arr=Db::query("select * from brand where id = '$id'");
     $this->assign('arr',$arr);
   	 return $this->fetch();
   }
   function updat(){
	 $id=Request::post('id');
	 $logode=Request::post('logode');
   $url=Request::post('url');
	 $brand_name=Request::post('brand_name');
	 $description=Request::post('description');
    $file = request()->file('chooseImage');
	 $data=Request::post();
    $validate = new \app\admin\validate\brand;
    if (!$validate->check($data)) {
        $arr=['yan'=>'1','status'=>'error','data'=>$validate->getError()];
        $json=json_encode($arr);
    echo $json;
    die;
        }  
    // $info = $file->move( '../public/static/img');
    $arr=Db::query("select id from brand where brand_name='$brand_name' or url='$url'");
    if (empty($arr)||!empty($arr)&&$arr[0]['id']==$id) {
      if ($file) {
        $info = $file->move( '../public/static/img'); 
        if ($info) {
      $getSaveName=str_replace("\\","/",$info->getSaveName());
      $where=['brand_name'=>$brand_name,'desc'=>$description,'url'=>$url,'logo'=>$getSaveName];
      $mysqli=Db::table('brand')->where('id','=',$id)->update($where);
      $arr=['yan'=>'1','status'=>'error','data'=>"修改成功"];
        }else{
           // 上传失败获取错误信息
            echo $file->getError();
        }
      } else{
        $arr=['yan'=>'1','status'=>'error','data'=>"文件不能为空"];  
            $json=json_encode($arr);
            echo $json;
            die;
      } 
    }else{
      $arr=['yan'=>'1','status'=>'error','data'=>"角色名重复"];
    }
    $json=json_encode($arr);
    echo $json;
   	
   }
			
}