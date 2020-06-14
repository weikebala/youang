<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\logic\Index as indexLogic;
use think\facade\View;

class Index extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];

    public function index()
    {
        $index = new indexLogic();
        //获取基础信息
        return view('', [
            'basecount' => $index->getAdminCount(),
        ]);
    }
}
