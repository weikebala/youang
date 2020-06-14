<?php
namespace app\model;

use think\Model;

class User extends Model
{

    protected $autoWriteTimestamp = true;
    protected $updateTime         = false;

    //获取详情
    public function getUserInfo($id, $fields = '*')
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

    //
    public function baseQuery($where = [], $field = '*', $column = 'create_time', $desc = 'asc', $limit = '0')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0])
            ->order($column, $desc)
            ->limit($limit)->select();
    }




}
