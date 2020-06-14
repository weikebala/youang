<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\admin\validate\Banner as BannerValidate;
use app\logic\Banner as BannerLogic;
use app\util\Tools;
use think\facade\View;

class Banner extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];

    public function index()
    {
        $param = $this->request->param();

        $banner = new BannerLogic();
        $where  = Tools::buildSearchWhere($param, [
            'title', 'description', 'link_url', 'image_url']);

        $list = $banner->getBannerList($where);

        return view('', [
            'bannerlist' => $list,
            'search_time' => isset($param['search_time']) ? $param['search_time'] : '',
            'page' => $list->render(),
        ]);
    }

    public function add()
    {

        $param  = $this->request->param();
        $banner = new BannerLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new BannerValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($banner->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->error('操作失败');
            }

        }
        else
        {
            $banner = new BannerLogic();
            return view('', ['menulist' => []]);
        }

    }

    public function edit()
    {

        $param  = $this->request->param();
        $banner = new BannerLogic();

        if ($this->request->isPost())
        {

            //验证数据
            $validate = new BannerValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $param['show_status'] = isset($param['show_status']) ? 1 : 0;

            if ($banner->where('id', $param['id'])->save($param))
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
            return view('', ['editData' => $banner->getBannerInfo($param['id'])]);
        }

    }

    /**
     * [delete 删除操作]
     * @return [type] [description]
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $banner = new BannerLogic();

        $result = $banner->update(['delete_status' => 1], ['id' => $id]);
        $result ? $this->success('删除成功') : $this->error('删除失败');

    }

}
