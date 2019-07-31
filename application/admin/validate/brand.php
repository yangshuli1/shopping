<?php

namespace app\admin\validate;

use think\Validate;

class brand extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'brand_name'  => 'require|max:50|min:1',
        'url'  => 'require|max:200|min:1',
        // 'logo'  => 'require|max:200|min:1',
         ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'brand_name.require' => '名称必须',
        'brand_name.max'     => '名称最多不能超过50个字符',
        'brand_name.min'     => '名称最少不能少于1个字符',
        'url.require' => '连接地址必须',
        'url.max'     => '连接地址最多不能超过200个字符',
        'url.min'     => '连接地址最少不能少于1个字符',
        // 'logo.require' => 'logo必须',
        // 'logo.max'     => 'logo最多不能超过200个字符',
        // 'logo.min'     => 'logo最少不能少于1个字符',
    ];
}
