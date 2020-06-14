<?php
namespace app\vod\controller;

use app\logic\Chapter;
use app\logic\Course as CourseLogic;
use app\logic\CourseVideo;
use app\logic\Comment;
use app\WebBaseController;
use think\facade\View;

class Course extends WebBaseController
{

    /**
     * [index 课程首页]
     * @return [type] [description]
     */
    public function index()
    {
        $param = $this->request->param();

        $course   = new CourseLogic();
        $chapter = new Chapter();

        //获取详情
        $coursInfo = $course->getCourseInfo($param['id']);

        $buystatus = $course->getCourseAuth($coursInfo);

        //获取面包屑
        $breadcrumb = $course->getBreadcrumb($param['id']);

        //获取最近更新
        $recommendCourse = $chapter->getRecommendRoundCourse($coursInfo['category_id']);
        
        event('LogViewCourse',$param);
        
        return view('', [
            'coursinfo' => $coursInfo,
            'buystatus' => $buystatus,
            'breadcrumb' => $breadcrumb,
            'recommend' => $recommendCourse,
        ]);

    }

    /**
     * [chapter 课程章节]
     * @return [type] [description]
     */
    public function chapter()
    {

        $param = $this->request->param();

        $course   = new CourseLogic();
        $chapter = new Chapter();

        //获取详情
        $coursInfo = $course->getCourseInfo($param['id']);
        //查看当前用户的购买状态
        $buystatus = $course->getCourseAuth($coursInfo);

        //获取面包屑
        $breadcrumb = $course->getBreadcrumb($param['id']);

        //获取推荐课程
        $recommendCourse = $chapter->getRecommendRoundCourse($coursInfo['category_id']);

        //获取章节
        $chapterList = $chapter->getChapter($param['id']);

        return view('', [
            'coursinfo' => $coursInfo,
            'buystatus' => $buystatus,
            'breadcrumb' => $breadcrumb,
            'chapterlist' => $chapterList,
            'recommend' => $recommendCourse,
        ]);
    }

    /**
     * [articleList 课程关联文章]
     * @return [type] [description]
     */
    public function articleList()
    {
        return View::fetch('articleList');
    }

    /**
     * [commentList 课程评论]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function comment()
    {
        $param = $this->request->param();

        $course   = new CourseLogic();
        $chapter = new Chapter();
        $comment = new Comment();


        //获取详情
        $coursInfo = $course->getCourseInfo($param['id']);
        $buystatus = $course->getCourseAuth($coursInfo);

        //获取面包屑
        $breadcrumb = $course->getBreadcrumb($param['id']);
        //获取最近更新
        $recommendCourse = $chapter->getRecommendRoundCourse($coursInfo['category_id']);
        //获取评论
        $commentList = $comment->getDetailCommentList('course',$param['id']);

        return view('', [
            'coursinfo' => $coursInfo,
            'buystatus' => $buystatus,
            'breadcrumb' => $breadcrumb,
            'recommend' => $recommendCourse,
            'commentlist' => $commentList,
            'page' => $commentList->render(),
        ]);
    }



}
