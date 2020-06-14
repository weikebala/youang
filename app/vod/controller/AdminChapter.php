<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\Chapter as ChapterLogic;
use app\logic\Course;
use app\logic\CourseVideo;
use app\util\Tools;
use app\vod\validate\AdminChapter as AdminChapterValidate;
use think\facade\View;

class AdminChapter extends AdminBaseController
{
    protected $middleware = ['adminAuth', 'Access'];

    //首页
    public function index()
    {

        $param = $this->request->param();

        $where = Tools::buildSearchWhere($param, [
            'ch.title', 'ch.description', 'co.title'], 'ch');

        $chapter = new ChapterLogic();
        $list    = $chapter->getChapterList($where);

        return view('', [
            'chapterlist' => $list,
            'page' => $list->render(),
        ]);

    }

    //添加
    public function add()
    {

        $param   = $this->request->param();
        $chapter = new ChapterLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdminChapterValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            if ($chapter->save($param))
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
                'courselist' => $course->baseQuery([], 'id,title'),
            ]);

        }

    }

    //编辑操作
    public function edit()
    {

        $param   = $this->request->param();
        $chapter = new ChapterLogic();

        if ($this->request->isPost())
        {

            //验证数据
//            $validate = new AdminChapterValidate();
//            if (!$validate->check($param))
//            {
//                $this->error($validate->getError());
//            }

            $param['show_status'] = !empty($param['show_status']) ? 1 : 0;

            if ($chapter->where('id', $param['id'])->save($param) !== false)
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

            return view('', [
                'editData' => $chapter->getChapterInfo($param['id']),
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

        //查看章节下是否有视频
        $video     = new CourseVideo();
        $videoInfo = $video->getCourseVideoInfo([
            'delete_status' => 0,
            'show_status' => 1,
            'chapter_id' => $id,
        ], 'id');
        if (empty($videoInfo))
        {
            $chapter = new ChapterLogic();
            $result  = $chapter->update(['delete_status' => 1], ['id' => $id]);
            $result ? $this->success('删除成功') : $this->error('删除失败');
        }
        else
        {
            $this->error('请先删除章节下的视频');
        }

    }

}
