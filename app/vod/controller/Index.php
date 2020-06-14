<?php
namespace app\vod\controller;

use app\logic\Banner;
use app\logic\Course;
use app\logic\CourseCategory;
use app\logic\Nav;
use app\WebBaseController;
use think\facade\View;

class Index extends WebBaseController
{
    public function index()
    {
        $banner     = new Banner();
        $course     = new Course();
        
        return view('', [
            'bannerlist' => $banner->getBannerList(['type' => 1], 'link_url,image_url'),
            'linklist' => $banner->getBannerList(['type' => 2], 'link_url,image_url'),
            'newcourse' => $course->getNewCourse(), //新课程
            'recommendcourse' => $course->getRecommendCourse(), //推荐课程
            'hotcourse' => $course->getHotCourse(), //热门课程
            'conjecturecourse' => $course->getConjectureCourse(), // 猜你喜欢
        ]);
    }

    /**
     * [list 课程栏目]
     * @return [type] [description]
     */
    function list()
    {

        $param   = $this->request->param();
        $nav     = new Nav();
        $cat     = new CourseCategory();

        $navView = $nav->getNavView($param);
        $list    = $nav->getListData($param);
        $listInfo = $cat->getCourseCategoryInfo($param['list_id']);
        
        return view('', [
            'navview' => $navView,
            'listInfo' => $listInfo,
            'list' => $list,
            'page' => $list->render(),
        ]);
    }

    /**
     * [detail 获取详情]
     * @return [type] [description]
     */
    public function detail()
    {
        return View::fetch('detail');
    }

    /**
     * [search 获取搜索结果]
     * @return [type] [description]
     */
    public function search()
    {
        $param  = $this->request->param();
        $course = new Course();

        if (isset($param['keywords']))
        {
            $title = $param['keywords'];
            $list  = $course->baseQuery([['title', 'like', '%' . $title . '%']]);
        }
        else
        {
            $list = [];
        }

        return view('', [
            'list' => $list,
            'count' => count($list),
        ]);
    }

}
