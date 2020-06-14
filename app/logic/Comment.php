<?php
namespace app\logic;

use app\model\Comment as CommentModel;
use app\util\Tools;
class Comment extends CommentModel
{
	//添加评论
	public function saveComment($param)
	{

        if (strtolower($param['table_name']) == 'course') {

            $course= new Course();
            $courseInfo = $course->getCourseInfo($param['source_id']);

            $isBuy = $course->getCourseAuth($courseInfo);

            if ($courseInfo['sell_status']) {
                if (!$isBuy) {
                    return 2;
                }
            }

            $content = Tools::badWordsFilter($param['content']);
            if ($content) {
                return 3;
            }

        }

		$result = $this->save([
			'user_id'=>getUserInfoData(),
			'source_id'=> $param['source_id'],
			'content'=> $param['content'],
			'url'=>$param['url'],
			'table_name'=> strtolower($param['table_name']),
			'create_time'=>time(),
		]);

        (new RecordLog())->baseSave(
        	'comment',
        	getUserInfoData(),
        	$this->id,
        	$param['table_name']
        );
        
		return $result;
		
	}

	//获取评论列表
	public function getDetailCommentList($table_name,$id)
	{

		$result = $this->alias('c')
            ->field([
            	'co.id',
            	'u.nickname',
            	'u.avatar_url',
            	'u.id',
                'c.content',
            	'c.user_id',
            	'c.create_time',
            ])
            ->join('course co', 'c.source_id = co.id')
            ->join('user u', 'u.id = c.user_id')
            ->order('c.create_time','desc')
            ->where('c.source_id',$id)
            ->where('c.table_name',$table_name)
            ->where(['c.show_status'=>1,'c.delete_status'=>0])
            ->paginate(['query' => ['id' => $id], 'list_rows' => 8]);


        return $result;
	}



    //获取评论列表
    public function getCommentList($where= [])
    {

        $result = $this->alias('c')
            ->field([
                'c.id',
                'u.nickname',
                'u.avatar_url',
                'u.id as user_id',
                'co.title',
                'c.content',
                'c.url',
                'c.create_time',
            ])
            ->join('course co', 'c.source_id = co.id')
            ->join('user u', 'u.id = c.user_id')
            ->order('c.create_time','desc')
            ->where($where)
            ->where(['c.show_status'=>1,'c.delete_status'=>0])
            ->paginate();


        return $result;
    }


}