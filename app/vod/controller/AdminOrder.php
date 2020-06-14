<?php
namespace app\vod\controller;

use app\AdminBaseController;
use app\logic\Order as OrderLogic;
use think\facade\View;
use app\util\Tools;


class AdminOrder extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];
    
	//订单列表
	public function index()
    {

        $param  = $this->request->param();
        $where = Tools::buildSearchWhere($param,[
            'order_no']);

        $order = new OrderLogic();
        $list = $order->getOrderList($where);

        return view('', [
            'orderlist' => $list,
            'page' => $list->render(),
        ]);
    }


    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
        $order = new OrderLogic();
        //
        if ($this->request->isPost())
        {
            if ($order->where('id', $param['id'])->save($param) !== false)
            {
                $this->success('操作成功');
            }
            else
            {
                $this->success('操做失败');
            }

        }
        else
        {
            View::assign('editData', $order->getOrderInfo($param['id']));
            return View::fetch();
        }
    }

    /**
     * [delete 删除操作]
     * @return [type] [description]
     */
    public function del()
    {
        $param = $this->request->param();

        $order = new OrderLogic();

        $result = $order->update(['delete_status' => 1], ['id' => $param['id']]);
        if ($result)
        {
            return json(['code' => 1, 'msg' => '删除成功']);
        }
        else
        {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }
}