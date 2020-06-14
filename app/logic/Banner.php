<?php
namespace app\logic;

use app\model\Banner as BannerModel;

class Banner extends BannerModel
{

	//
	public function getBannerList($where = [], $field = '*')
    {
        return $this->field($field)->where($where)
        ->where(['delete_status'=>0,'show_status'=>1])
        ->paginate();
    }
    
}