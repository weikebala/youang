<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\CourseCategory as CatLogin;
use think\facade\View;

class AdminCourseCategory extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];
    
    //分类首页
    public function index()
    {
        $category = new CatLogin();

        return view('', [
            'categorylist' => $category->getCategoryList(),
        ]);

    }

    //添加
    public function add()
    {

        $param    = $this->request->param();
        $category = new CatLogin();

        $param['show_status'] = !empty($param['show_status']) ? 1 : 0;
        if ($this->request->isPost())
        {
            if ($category->save($param))
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
                'category' => $category->getCategoryList(),
            ]);

        }

    }

    //编辑
    public function edit()
    {

        $param    = $this->request->param();
        $category = new CatLogin();

        $param['show_status'] = !empty($param['show_status']) ? 1 : 0;

        if ($this->request->isPost())
        {
            if ($category->where('id', $param['id'])->save($param) !== false)
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
                'editData' => $category->getCourseCategoryInfo($param['id']),
                'categorylist' => $category->getCategoryList(),
            ]);

        }

    }

    //删除
    public function del()
    {
        $param    = $this->request->param();
        $category = new CatLogin();
        $result   = $category->update(['delete_status' => 1], ['id' => $param['id']]);
        if ($result)
        {
            return json(['code' => 1, 'msg' => '删除成功']);
        }
        else
        {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

}
