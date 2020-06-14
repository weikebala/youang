<?php
namespace app\admin\validate;

use think\Validate;

class Banner extends Validate
{
    protected $rule = [
        'title' => 'require',
        'link_url' => 'checkUrl:require',
        'image_url' => 'require',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'link_url.require' => '链接地址能为空',
        'image_url.require' => '图片不能为空',
    ];


    // 验证链接地址
    protected function checkUrl($value, $rule, $data=[])
    {
        return checkUrl($value) ? true : '请输入正确的链接地址';
    }



}
