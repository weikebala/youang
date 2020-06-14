<?php
namespace app\model;

use think\Model;

class Banner extends Model
{

    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    //获取详情
    public function getBannerInfo($id, $fields = '*')
    {
        if (is_array($id))
        {
            return $this->field($fields)->where($id)->where('delete_status', 0)->find();
        }
        else
        {
            return $this->field($fields)->where('id', $id)->where('delete_status', 0)->find();
        }

    }

}
