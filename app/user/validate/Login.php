<?php
namespace app\user\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'nickname' => 'require|max:25|min:6',
        'password' => 'require|max:25|min:8',
        'repassword' => 'require|max:25|min:8',
        'clause' => 'require',
        'mobile' => 'require|mobile|token',
        'smscode' => 'require',
        // 'mobile' => 'mobile',
    ];

    protected $message = [
        'nickname.require' => '用户名不能为空',
        'nickname.max' => '用户名不能超过25个字符',
        'nickname.min' => '用户名不能小于6个字符',
        'password.require' => '密码能为空',
        'password.max' => '密码不能超过25个字符',
        'password.min' => '密码不能小于8个字符',
        'repassword.max' => '密码不能超过25个字符',
        'repassword.min' => '密码不能小于8个字符',
        'repassword.require' => '请重复输入密码',
        'clause.require' => '请选择用户条款',
        'mobile.require' => '手机号不能为空',
        'mobile.mobile' => '手机号不正确',
        'mobile.token' => '请刷新页面后重试',
        'smscode.require' => '验证码不能为空',
    ];

    protected $scene = [
        'register' => ['nickname', 'password', 'repassword'],
        'forget' => ['mobile', 'password', 'smscode'],
        'mobileLogin' => ['mobile', 'smscode'],
        'userLogin' => ['nickname', 'password'],
        'sms' => ['mobile'],
        'setting' => ['nickname'],
    ];
}
