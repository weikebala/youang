<?php
namespace app\vod\validate;

use think\Validate;

class AdminChapter extends Validate
{
    protected $rule = [
        'course_id' => 'require',
        'title' => 'require',
    ];

    protected $message = [
        'category_id.require' => '请选择关联课程',
        'title.require' => '请输入标题',
    ];

}
