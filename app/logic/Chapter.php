<?php
namespace app\logic;

use app\model\Chapter as ChapterModel;

class Chapter extends ChapterModel
{

    //获取后台首页数据
    public function getChapterList($where = [], $field = '*')
    {

        return $this->alias('ch')
            ->field([
                'ch.*',
                'co.title as course_title',
            ])
            ->join('course co', 'ch.course_id = co.id')
            ->where([
                'ch.delete_status' => 0,
                'ch.show_status' => 1,
                'co.delete_status' => 0,
                'co.show_status' => 1,
            ])
            ->where($where)
            ->paginate();

    }

    public function baseQuery($where = [], $field = '*', $column = 'create_time', $desc = 'asc', $limit = '0')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0, 'show_status' => 1])
            ->order($column, $desc)
            ->limit($limit)->select();
    }

    //获取章节
    public function getChapter($id)
    {

        $result = $this->baseQuery(['course_id' => $id], 'id,course_id,title,description');

        foreach ($result as $key => $value)
        {

            $value['video'] = (new CourseVideo())
                ->baseQuery(['chapter_id' => $value['id']], 'id,chapter_id,course_id,title')->each(function ($item) use ($value)
            {
                $item['study_status'] = $this->getUserStudyStatus($item);
                return $item;
            });
            // $value['study_status'] = $this->getUserStudyStatus($value);
            $result[$key] = $value;
        }
        // print_r($result->toArray());exit();
        return $result;
    }

    //获取当前用户的章节学习状态
    public function getUserStudyStatus($value)
    {
        $log = (new RecordLog())->getRecordLogInfo([
            'user_id' => getUserInfoData(0, 'id'),
            'name' => $value['course_id'],
            'value' => $value['id'],
            'category' => 'studyCourse',
        ]);

        return empty($log) ? 0 : 1;
    }

    //获取推荐课程
    public function getRecommendRoundCourse($categoryId)
    {

        $result = $this->alias('ch')
            ->field([
                'ch.title as chapter_title',
                'ch.id as chapter_id',
                'co.title as course_title',
                'co.id as course_id',
                'co.description',
                'co.sell_price',
                'co.cource_image_url',
            ])
            ->join('course co', 'ch.course_id = co.id')
            ->join('course_category cat', 'cat.id = co.category_id')
            ->where([
                'ch.delete_status' => 0,
                'ch.show_status' => 1,
                'co.delete_status' => 0,
                'co.show_status' => 1,
            ])
            ->orderRaw('rand()')
            ->limit(6)
            ->group('chapter_id')
            ->select();

        return $result;

    }

}
