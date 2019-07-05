<?php

namespace app\admin\validate;

use think\Validate;

class user extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'user_name'  => 'require|max:50|min:1',
        // 'description'  => 'require|max:200|min:1',
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
        // 'description.require' => '角色路径必须',
        // 'description.max'     => '角色路径最多不能超过200个字符',
        // 'description.min'     => '角色路径最少不能少于1个字符',
    ];
}