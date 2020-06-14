<?php
namespace app\admin\validate;

use think\Validate;

class Menu extends Validate
{
    protected $rule = [
        'title' => 'require',
        'url' => 'require',
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'url.require' => '路径能为空',
    ];

}
