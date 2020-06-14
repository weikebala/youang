<?php
namespace app\logic;

use app\model\Course as CourseModel;
use app\util\Tools;

class Course extends CourseModel
{

    //
    public function getCourseList($where = [], $field = '*')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0, 'show_status' => 1])->paginate();
    }

    //
    public function baseQuery($where = [], $field = '*', $column = 'create_time', $desc = 'asc', $limit = '0')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0, 'show_status' => 1])
            ->order($column, $desc)
        // ->order($column, 'asc')
            ->limit($limit)->select();
    }

    //获取最新课程
    public function getNewCourse()
    {
        $field = 'id,category_id,title,cource_image_url,sell_price,study_num,cource_tag,level_status,sell_status,views,create_time';
        return $this->baseQuery([], $field, 'create_time', '', 4)->each(function ($item)
        {
            $item['cource_image_url'] = getUrlPath($item['cource_image_url']);
            return $item;
        });
    }

    //推荐课程
    public function getRecommendCourse()
    {

        $field = 'id,category_id,title,cource_image_url,sell_price,level_status,study_num,cource_tag,sell_status,views,create_time';

        return $this->baseQuery(['recommend_status'=>1], $field, 'create_time', 'asc', 3)->each(function ($item)
        {
            $item['cource_image_url'] = getUrlPath($item['cource_image_url']);
            return $item;
        });

    }

    //热门课程
    public function getHotCourse()
    {
        $field = 'id,category_id,title,cource_image_url,sell_price,level_status,study_num,cource_tag,sell_status,views,create_time';

        return $this->baseQuery(['hot_status'=>1], $field, 'create_time', 'desc', 4)->each(function ($item)
        {
            $item['cource_image_url'] = getUrlPath($item['cource_image_url']);
            return $item;
        });

    }

    //猜你喜欢
    public function getConjectureCourse()
    {

        $field = 'id,category_id,title,cource_image_url,sell_price,level_status,study_num,cource_tag,sell_status,views,create_time';

        return $this->baseQuery([], $field, 'create_time', 'asc', 4)->each(function ($item)
        {
            $item['cource_image_url'] = getUrlPath($item['cource_image_url']);
            return $item;
        });
    }

    //根据课程ID获取面包屑
    public function getBreadcrumb($id)
    {
        $info = $this->getCourseInfo($id);

        $parent = (new CourseCategory())->field('parent_id')->where('id', $info['category_id'])->find();
        $data   = (new CourseCategory())->field('id,title,parent_id')
            ->where(['delete_status' => 0, 'show_status' => 1])->select()->toArray();

        $result = Tools::getBreadcrumb($data, $parent['parent_id']);
        return $result;
    }

    //获取当前课程播放权限
    public function getCourseAuth($courseInfo)
    {
        if ($courseInfo['sell_status'])
        {

            //查看当前用户是否为会员
            if (getUserInfoData(0, 'user_type') == 2 && getUserInfoData(0, 'vip_expiration_time') > time())
            {

                return 1;
            }
            //查看当前用户是否已经订购课程
            $order = (new Order())->where([
                'commodity_id' => $courseInfo['id'],
                'user_id' => getUserInfoData(0, 'id'),
                'order_status' => 1,
                'order_type' => 1,
            ])->find();

            return empty($order) ? 0 : 1;
        }
        return 1;
    }

}
