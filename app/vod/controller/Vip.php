<?php
namespace app\vod\controller;

use app\WebBaseController;
use think\facade\View;
use app\util\Tools;
use app\logic\Setting;

class Vip extends WebBaseController
{
	//vip开通页面
	public function index()
	{
		$set = new Setting();
		$vip = $set->baseQuery(['category'=>'vipConfig']);
		return view('', [
            'vip' => $vip,
        ]);
	}
}