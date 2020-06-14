<?php
namespace app\logic;

class Index
{ 	
	/**
	 * [getAdminCount 获取后台统计]
	 * @return [type] [description]
	 */
	public function getAdminCount()
	{
		$result  = [];
		$result['todayUser'] = (new User())->whereDay('create_time')->count();
		$result['todayVip'] = (new Order())
		->where(['order_type'=>2,'order_status'=>1])->whereDay('create_time')->count();
		$result['allUser'] = (new User())->count();
		$result['allVip'] = (new User())->where(['user_type'=>2])->whereTime('vip_expiration_time', '>=', time())->count();
		$result['todayComment'] = (new Comment())->where(['delete_status'=>0,'show_status'=>1])->whereDay('create_time')->count();
		$result['todayOrder'] = (new Order())
		->where(['order_status'=>1])->whereDay('create_time')->count();
		$result['todayVideo'] = (new CourseVideo())->where(['delete_status'=>0,'show_status'=>1])->whereDay('create_time')->count();
		$result['allCourse'] = (new Course())->where(['delete_status'=>0,'show_status'=>1])->count();
		return $result;
		//
	}
}