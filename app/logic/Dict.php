<?php
namespace app\logic;

use app\model\Dict as DictModel;

class Dict extends DictModel
{
	//
	public function getDictList($where = [], $field = '*')
	{
		return $this->field($field)->where($where)
            ->where(['delete_status' => 0, 'show_status' => 1])->paginate();
	}

	//
	public function saveDict($data)
	{
		$value = $data['category']  ? $data['value'] : '';
		$category = $data['category']  ? $data['category'] : $data['value'];
		return $this->save([
			'category'=>$category,
			'value'=>$value,
			'show_status'=>$data['show_status'],
			'type'=>$data['category'] ? 0:1,
		]);
	}
}