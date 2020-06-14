<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\Chapter;
use app\logic\Course as CourseLogic;
use app\logic\CourseCategory;
use app\util\Tools;
use app\vod\validate\AdminCourse as AdminCourseValidate;
use think\facade\View;

class AdminCourse extends AdminBaseController
{
    protected $middleware = ['adminAuth', 'Access'];

    /**
     * [courselist 课程列表/章节列表]
     * @return [type] [description]
     */
    public function index()
    {
        $param = $this->request->param();

        $course = new CourseLogic();

        $where = Tools::buildSearchWhere($param, [
            'title', 'description']);

        $list = $course->getCourseList($where);

        return view('', [
            'courslist' => $list,
            'page' => $list->render(),
        ]);

    }

    //添加课程
    public function add()
    {

        $param  = $this->request->param();
        $course = new CourseLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdminCourseValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($course->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->success('操做失败');
            }

        }
        else
        {

            $category = new CourseCategory();
            return view('', [
                'categorylist' => $category->getCategoryList(),
            ]);
        }
    }

    //编辑课程
    public function edit()
    {

        $param    = $this->request->param();
        $course   = new CourseLogic();
        $category = new CourseCategory();
        if ($this->request->isPost())
        {

            unset($param['file']);
            //验证数据
            $validate = new AdminCourseValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($course->where('id', $param['id'])->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->success('操做失败');
            }

        }
        else
        {
            return view('', [
                'categorylist' => $category->getCategoryList(),
                'editData' => $course->getCourseInfo($param['id']),
            ]);

        }
    }

    /**
     * [delete 删除课程操作]
     * @return [type] [description]
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $chapter = new Chapter();
        //查看课程下是否有章节
        $chapterInfo = $chapter->getChapterInfo([
            'delete_status' => 0,
            'show_status' => 1,
            'course_id' => $id,
        ], 'id');

        if (empty($chapterInfo))
        {

            $course = new CourseLogic();
            $result = $course->update(['delete_status' => 1], ['id' => $id]);
            $result ? $this->success('删除成功') : $this->error('删除失败');
        }
        else
        {
            $this->error('请先删除课程下的章节');
        }

    }

    //操作
    public function operation()
    {

        $param = $this->request->param();

        $key   = isset($param['hot_status']) ? 'hot_status' : 'recommend_status';
        $value = isset($param['hot_status']) ? $param['hot_status'] : $param['recommend_status'];

        $course = new CourseLogic();
        $result = $course->update([$key => ($value ? 0 : 1)], ['id' => $param['id']]);
        $result ? $this->success('操作成功') : $this->error('操作失败');

    }

}
