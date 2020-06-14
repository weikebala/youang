<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\Comment;
use think\facade\View;
use app\util\Tools;


class AdminComment extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];
    
	//
	public function index()
	{

		$param  = $this->request->param();
        $where = Tools::buildSearchWhere($param,[
            'u.nickname','co.title','c.content'],'c.');

		$comment = new Comment();
		$list = $comment->getCommentList($where);

		return view('', [
            'commentlist' => $list,
            'page' => $list->render(),
        ]);
	}



	    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
		$comment = new Comment();
        //
        if ($this->request->isPost())
        {
            if ($comment->where('id', $param['id'])->save($param))
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
            View::assign('editData', $comment->getCommentInfo($param['id']));
            return View::fetch();
        }
    }

    /**
     * [delete 删除操作]
     * @return [type] [description]
     */
    public function del()
    {
        $param = $this->request->param();

		$comment = new Comment();
        $result = $comment->update(['delete_status' => 1], ['id' => $param['id']]);
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