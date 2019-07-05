<?php

namespace app\admin\validate;

use think\Validate;

class Permission extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name'  => 'require|max:50|min:1',
        'path'  => 'require|max:200|min:1',
         ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过50个字符',
        'name.min'     => '名称最少不能少于1个字符',
        'path.require' => '权限路径必须',
        'path.max'     => '权限路径最多不能超过200个字符',
        'path.min'     => '权限路径最少不能少于1个字符',
    ];
}
