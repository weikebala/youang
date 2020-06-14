<?php
namespace app\vod\validate;

use think\Validate;

class AdminCourseVideo extends Validate
{
    protected $rule = [
        'course_id' => 'require',
        'chapter_id' => 'require',
        'title' => 'require',
        'video_url' => 'require',
    ];

    protected $message = [
        'course_id.require' => '请选择关联课程',
        'chapter_id.require' => '请选择关联章节',
        'title.require' => '请输入标题',
        'video_url.require' => '请上传视频',
    ];

}
