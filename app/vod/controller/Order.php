<?php
namespace app\vod\controller;

use app\logic\Order as OrderLogic;
use app\WebBaseController;
use think\facade\View;

class Order extends WebBaseController
{
    protected $middleware = ['auth'];

    public function createOrder()
    {
        
        $param = $this->request->param();

        $order = new OrderLogic();
        $info  = $order->getCommodityInfo($param);

        return view('', [
            'info' => $info,
        ]);
    }

}
