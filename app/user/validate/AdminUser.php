<?php
namespace app\user\validate;

use think\Validate;

class AdminUser extends Validate
{
    protected $rule = [
        'nickname' => 'require',
        'password' => 'require',
        'avatar_url' => 'require',
        'mobile' => 'require|mobile',
    ];

    protected $message = [
        'nickname.require' => '用户名不能为空',
        'password.require' => '密码能为空',
        'mobile.require' => '手机号不能为空',
        'mobile.mobile' => '手机号不正确',
        'avatar_url.require' => '头像不能为空',
    ];
    
    protected $scene = [
        'edit' => ['nickname','mobile','avatar_url'],
    ];

}
