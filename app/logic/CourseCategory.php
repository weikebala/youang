<?php
namespace app\logic;
use app\model\CourseCategory as CourseCategoryModel;
use app\util\Tools;
use app\util\Menu;



class CourseCategory extends CourseCategoryModel
{


	public function getCategoryList()
	{
		$category = $this->where('delete_status',0)->order('sort', 'desc')->select()->toArray();
        return Tools::formatTree(Tools::listToTree($category, 'id', 'parent_id'),0,'title');

	}
}