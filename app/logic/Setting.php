<?php
namespace app\logic;

use app\model\Banner;
use app\model\Setting as SettingModel;

class Setting extends SettingModel
{
    /**
     * [getSettingList 获取基础列表]
     * @return [type] [description]
     */
    public function getSettingCategoryList($category)
    {
        $data = [];
        $result = $this->field('category_name,content,category')->where([
            'delete_status' => 0,
            'show_status' => 1,
            'category' => $category,
        ])->select()->toArray();
        foreach ($result as $key => $value) {
            $data[$value['category_name']] = $value;
        }
        return $data;
    }

    /**
     * [getSettingList 获取基础列表]
     * @return [type] [description]
     */
    public function getSettingList($base = 'base')
    {

        // return
        $result = $this->field('id,title,category,category_name')
            ->where([
                'delete_status' => 0,
                'show_status' => 1,
                'category' => $base,
            ])->select();
        //     ->each(function ($item)
        // {
        //     $item['child'] = $this->getSettingCategoryList($item['category_name']);
        // });

        return $result;

        // print_r($result);exit();
    }

    /**
     * [saveSettingPost description]
     * @param  [type] $param [description]
     * @return [type]        [description]
     */
    public function saveSettingPost($param)
    {
        // print_r($param);exit();
        foreach ($param as $key => $value)
        {
            $this->update(['content' => $value], ['category' => $param['type'], 'category_name' => $key]);
        }
    }

    /**
     * [getBannerList 获取banner图列表]
     * @return [type] [description]
     */
    public function getBannerList($base = '')
    {
        $banner    = new Banner();
        $type      = ['banner' => 1, 'link' => 2];
        $whereType = [];
        if ($base)
        {
            $whereType = ['type' => $type[$base]];
        }
        return $banner
            ->where(array_merge($whereType, ['delete_status' => 0]))
            ->select()->toArray();
    }

    //
    public function getSettingContent($category_name)
    {
        $result = $this->getSettingInfo(['category_name'=>$category_name],'content');
        return $result['content'];
    }
}
