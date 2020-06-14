<?php
namespace app\vod\controller;

use app\logic\Comment as CommentLogic;
use app\WebBaseController;

class Comment extends WebBaseController
{

    protected $middleware = ['auth'];

    //添加评论
    public function addComment()
    {

        $param   = $this->request->param();
        $comment = new CommentLogic();
        // $param['table_name']  = 'course';
        $result = $comment->saveComment($param);
        switch ($result)
        {
            case 0:
                $this->error('评论失败');
                break;
            case 1:
                $this->success('评论成功');
                break;
            case 2:
                $this->error('暂不能评论');
                break;
            default:
                $this->error('您输入的内容含敏感词，请修改后重新提交');
                break;
        }
        // if ($result) {
        //     return json(['code'=>1,'message'=>"评论成功"]);
        // }else{
        //     return json(['code'=>0,'message'=>"评论失败"]);
        // }
    }
}
