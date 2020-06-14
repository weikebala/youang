<?php
namespace app\logic;

use app\model\CourseVideo as CourseVideoModel;

class CourseVideo extends CourseVideoModel
{
    //获取当前章节的课程ID&&章节ID
    public function getCourseOrChapter($videoId)
    {
        $chapterId = $this->getCourseVideoInfo($videoId, 'chapter_id');
        $courseId  = (new Chapter())->getChapterInfo($chapterId['chapter_id'], 'course_id');

        return [
            'chapter_id' => $chapterId['chapter_id'],
            'course_id' => $courseId['course_id'],
        ];

    }

    //获取分页列表
    public function getVideoList($where = [], $field = '*')
    {

        return $this->alias('c')
            ->field([
                'c.*',
                'co.title as course_title',
                'ch.title as chapter_title',
            ])
            ->join('course co', 'co.id = c.course_id')
            ->join('chapter ch', 'ch.id = c.chapter_id')
            ->order('c.create_time','desc')
            ->where([
                'c.delete_status' => 0, 
                'c.show_status' => 1,
                'co.delete_status' => 0,
                'co.show_status' => 1,
                'ch.delete_status' => 0,
                'ch.show_status' => 1,
            ])->paginate();
    }

    //
    public function selectCourseList($videoInfo)
    {

        $data = (new Course())->field('id,title')->where('delete_status', 0)->select()->toArray();

        foreach ($data as $key => $value)
        {

            if ($value['id'] == $videoInfo['course_id'])
            {
                $value['select'] = 1;
            }
            else
            {
                $value['select'] = 0;
            }
            $data[$key] = $value;
        }

        return $data;
    }

    //
    public function selectChapterList($videoInfo)
    {

        $data = (new Chapter())->field('id,title')->where('delete_status', 0)->select();
        foreach ($data as $key => $value)
        {

            if ($value['id'] == $videoInfo['chapter_id'])
            {
                $value['select'] = 1;
            }
            else
            {
                $value['select'] = 0;
            }
            $data[$key] = $value;
        }

        return $data;

    }

    public function baseQuery($where = [], $field = '*', $column = 'create_time', $desc = 'asc', $limit = '0')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0, 'show_status' => 1])
            ->order($column, $desc)
            ->limit($limit)->select();
    }

    //根据videoId获取详情
    public function getVideoInfo($videoId)
    {
        $video = $this->getCourseVideoInfo($videoId, 'id,chapter_id,course_id,title,description,seoTitle,seoKeywords,seoDescription,video_url,image_url,channel');

        $next = $this->field('id,title')
            ->where('id', '>', $videoId)
            ->where('course_id', $video['course_id'])
            ->order('create_time', 'asc')->find();

        $video['next_id']    = $next['id'];
        $video['next_title'] = $next['title'];
        return $this->getVideoUrl($video);
    }

    //获取视频详情
    public function getVideoUrl($video)
    {

        if ($video['channel'] == 'local')
        {

            $video['video_url'] = getDomain() . '/storage/' . str_replace('\\', '/', $video['video_url']);
            $video['suffix'] = pathinfo($video['video_url'], PATHINFO_EXTENSION);

        }
        else
        {

            $video_url          = (new File())->getPlayInfo($video['video_url']);
            $Auth               = (new File())->createVideoPlayAuth($video['video_url']);
            unset($video['url']);
            $video['suffix'] = pathinfo($video_url, PATHINFO_EXTENSION);
            $video['aliVideo'] = $Auth;


        }
        
        return $video;
    }


}
