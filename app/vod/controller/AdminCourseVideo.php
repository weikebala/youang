<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\Chapter;
use app\logic\Course;
use app\logic\CourseVideo as CourseVideoLogic;
use app\util\Tools;
use app\vod\validate\AdminCourseVideo as AdminCourseVideoValidate;
use think\facade\View;

class AdminCourseVideo extends AdminBaseController
{
    protected $middleware = ['adminAuth', 'Access'];

    //视频列表
    public function index()
    {

        $param = $this->request->param();

        $where = Tools::buildSearchWhere($param, [
            'title', 'description']);

        $courseVideo = new CourseVideoLogic();
        $list        = $courseVideo->getVideoList($where);

        return view('', [
            'coursevideolist' => $list,
            'page' => $list->render(),
        ]);

    }

    //视频添加
    public function add($value = '')
    {

        $param                = $this->request->param();
        $param['show_status'] = !empty($param['show_status']) ? 1 : 0;

        $course      = new Course();
        $chapter     = new Chapter();
        $courseVideo = new CourseVideoLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdminCourseVideoValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            if ($courseVideo->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->error('操作失败');
            }

        }
        else
        {

            $course = new Course();
            return view('', [
                'courselist' => $course->field('id,title')->where('delete_status', 0)->select(),
                'chapterlist' => $chapter->field('id,title')->where('delete_status', 0)->select(),
            ]);

        }

    }

    //视频编辑
    public function edit()
    {
        $param = $this->request->param();

        $course      = new Course();
        $chapter     = new Chapter();
        $courseVideo = new CourseVideoLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdminCourseVideoValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            if ($courseVideo->where('id', $param['id'])->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->error('操作失败');
            }

        }
        else
        {

            $videoInfo   = $courseVideo->getCourseVideoInfo($param['id']);
            $videoParent = $courseVideo->getCourseOrChapter($param['id']);

            return view('', [
                'editData' => $videoInfo,
                'courselist' => $courseVideo->selectCourseList($videoParent),
                'chapterlist' => $courseVideo->selectChapterList($videoParent),
            ]);

        }

    }

    //视频删除
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $courseVideo = new CourseVideoLogic();

        $result = $courseVideo->update(['delete_status' => 1], ['id' => $id]);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }

    //
    public function getChapterList()
    {

        $param   = $this->request->param();
        $chapter = new Chapter();

        $data    = $chapter
            ->field('id,course_id,title')
            ->where(['delete_status' => 0, 'course_id' => $param['course_id']])
            ->select()->toArray();

        return json(['code' => 1, 'data' => $data]);
    }

}
