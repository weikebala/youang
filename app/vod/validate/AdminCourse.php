<?php
namespace app\vod\validate;

use think\Validate;

class AdminCourse extends Validate
{
    protected $rule = [
        'category_id' => 'require',
        'title' => 'require',
        // 'content' => 'checkContent:require',
        'cource_image_url' => 'require',
    ];

    protected $message = [
        'category_id.require' => '请选择分类',
        'title.require' => '请输入标题',
        // 'content.require' => '请输入内容',
        'cource_image_url.require' => '请上传图片',
    ];


    // 验证内容
    // protected function checkContent($value, $rule, $data=[])
    // {
    //     return strip_tags($value) ? true : '请输入内容';
    // }


}
