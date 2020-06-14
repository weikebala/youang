<?php
namespace app\admin\validate;

use think\Validate;

class Administrator extends Validate
{
    protected $rule = [
        'nickname' => 'require',
        'password' => 'require',
        'role_id' => 'require',
        'mobile' => 'require|mobile',
    ];

    protected $message = [
        'nickname.require' => '用户名不能为空',
        'password.require' => '密码能为空',
        'mobile.require' => '手机号不能为空',
        'mobile.mobile' => '手机号不正确',
    ];
    
    protected $scene = [
        'edit' => ['nickname', 'role_id', 'mobile'],
        'editInfo' => ['nickname', 'mobile'],
    ];
}
