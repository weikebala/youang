<?php
namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'username' => 'require|token',
        'password' => 'require',
        'verifycode' => 'require|captcha',
    ];

    protected $message = [
        'username.require' => '用户名不能为空',
        'password.require' => '密码能为空',
        'verifycode.require' => '验证码不能为空',
        'verifycode.captcha' => '验证码有误',
        'username.token' => '请刷新页面后重试',
    ];

}
