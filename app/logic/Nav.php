<?php
namespace app\logic;

use app\model\Nav as NavModel;
use app\util\Tools;

class Nav extends NavModel
{

    //获取导航列表
    public function getNavlist()
    {

        $nav = $this->alias('a')
            ->field([
                'a.*',
                'c.title as category_title',
            ])
            ->leftJoin('course_category c', 'a.category_id = c.id')
            ->where(['a.show_status' => 1, 'a.delete_status' => 0])
            ->select()->toArray();

        // print_r($nav);exit();
        // $nav = $this->where('delete_status',0)->order('sort', 'desc')->select()->toArray();

        return Tools::formatTree(Tools::listToTree($nav, 'id', 'parent_id'), 0, 'title');
    }

    //获取前端展示
    public function getNavView($param)
    {
        $data = $this->alias('a')
            ->field([
                'a.parent_id',
                'a.category_id',
                'a.id',
                'a.title',
                'c.seoTitle',
                'c.seoKeywords',
                'c.seoDescription',
            ])
            ->join('course_category c', 'a.category_id = c.id')
            ->where(['a.show_status' => 1, 'a.delete_status' => 0])
            ->select();

        $lv = Tools::formatTree(
            Tools::listToTree($data->toArray(), 'id', 'parent_id'), 0, 'title'
        );

        if (isset($param['list_id']))
        {
            $sonIds    = Tools::getUpTree($lv, $param['list_id']);
            $parentIds = Tools::getOnTree($lv, $param['list_id']);
            $allIds    = array_filter(array_merge($parentIds, $sonIds));

            $checkLv = [];
            foreach ($lv as $key => $value)
            {

                if (in_array($value['id'], $parentIds))
                {
                    $value['active'] = 1;
                }
                else
                {
                    $value['active'] = 0;
                }
                if (in_array($value['parent_id'], $allIds) || $value['parent_id'] == 0)
                {

                    $checkLv[$key] = $value;
                }

            }
            $lv = $checkLv;

        }

        $nav = Tools::dataGroup($lv, 'lv');
        return $nav;

    }

    //获取筛选数据
    public function getListData($param)
    {

        $whereIn = [];
        $cat     = new CourseCategory();
        if (!empty($param))
        {

            $data = $this->alias('a')
                ->field([
                    'a.parent_id',
                    'a.category_id',
                    'a.id',
                    'a.title',
                    'c.seoTitle',
                    'c.seoKeywords',
                    'c.seoDescription',
                ])
                ->join('course_category c', 'a.category_id = c.id')
                ->where([
                    'a.show_status' => 1,
                    'a.delete_status' => 0,
                    'c.show_status' => 1,
                    'c.delete_status' => 0,
                ])
                ->select();

            $lv = Tools::formatTree(
                Tools::listToTree($data->toArray(), 'id', 'parent_id'), 0, 'title'
            );

            $listId = Tools::getUpTree($lv, $param['list_id']);
            $sonIds = $this->getIdsByParentId($listId);

            $sonIds = $this->getCategoryIdsByNavIds($sonIds, $param['list_id']);

            $ids     = implode(',', $sonIds);
            $whereIn = [
                ['cat.id', 'in', $ids],
            ];

        }

        $result = $cat->alias('cat')
            ->field([
                'co.title',
                'co.description',
                'co.sell_price',
                'co.sell_status',
                'co.cource_image_url',
                'co.id',
            ])
            ->join('course co', 'co.category_id = cat.id')
            ->where([
                'co.show_status' => 1,
                'co.delete_status' => 0,
            ])
            ->where($whereIn)
            ->paginate(['query' => ['list_id' => $param['list_id']], 'list_rows' => 15]);

        return $result;
    }

    //
    public function getCategoryIdsByNavIds($sonIds, $listId)
    {

        if (!empty($sonIds))
        {
            $ids     = implode(',', $sonIds);
            $whereIn = [
                ['id', 'in', $ids],
            ];
        }
        else
        {
            $whereIn = [
                ['id', 'in', $listId],
            ];
        }

        $category   = $this->field('category_id')->where($whereIn)->select();
        $categoryId = [];
        foreach ($category as $key => $value)
        {
            $categoryId[$key] = $value['category_id'];
        }

        return $categoryId;

    }

    //根据当前选择获取所有子id
    public function getIdsByParentId($sonIds)
    {

        // $cat   = new CourseCategory();
        $idArr = [];
        foreach ($sonIds as $key => $value)
        {
            $info  = $this->baseQuery(['parent_id' => $value], 'id,parent_id');
            $ids   = $this->dataToIds($info);
            $idArr = array_merge($idArr, $ids);
        }
        return array_merge($idArr, $sonIds);
    }

    //根据数据获取当前所有id
    public function dataToIds($data)
    {
        $ids = [];
        foreach ($data as $key => $value)
        {
            $ids[$key] = $value['id'];
        }
        return $ids;
    }

}
