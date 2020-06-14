<?php
namespace app\vod\controller;

use app\WebBaseController;
use app\util\Tools;
use app\logic\Chapter;
use app\logic\Course as CourseLogic;
use app\logic\CourseVideo;
use think\facade\View;

class Detail extends WebBaseController
{

    protected $middleware = ['auth'];
    
	/**
     * [index 获取课程详情]
     * @return [type] [description]
     */
    public function index()
    {

        $param = $this->request->param();

        $video   = new CourseVideo();
        $cours   = new CourseLogic();
        $chapter = new Chapter();

        //获取详情
        $coursInfo = $cours->getCourseInfo($param['course_id']);

        //查询用户权限
        if (!$cours->getCourseAuth($coursInfo)) {
            //跳转到支付页面
            return redirect('/');
        }
        //获取面包屑
        $breadcrumb = $cours->getBreadcrumb($param['course_id']);
        //获取最近更新s
        $recommendCourse = $chapter->getRecommendRoundCourse($coursInfo['category_id']);
        //获取章节
        $chapterList = $chapter->getChapter($param['course_id']);
        //获取视频数据
        $videoInfo = $video->getVideoInfo($param['id']);
        event('LogViewVideo',$param);

        //更新用户学习状态
        // $video->userStudyLog($param['course_id'],$param['id']);

        return view('', [
            'coursinfo' => $coursInfo,
            'videoinfo' => $videoInfo,
            'breadcrumb' => $breadcrumb,
            'chapterlist' => $chapterList,
            'recommend' => $recommendCourse,
        ]);
    }


}