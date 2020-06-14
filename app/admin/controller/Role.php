<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\logic\AdminMenu;
use app\logic\AdminRole;
use think\facade\View;

class Role extends AdminBaseController
{

    protected $middleware = ['adminAuth','Access'];

    /**
     * [AdminRoleList 获取角色组列表]
     */
    public function index()
    {
        $role = new AdminRole();
        $list = $role->where('delete_status', 0)->paginate(12);

        return view('', [
            'rolelist' => $list,
            'page' => $list->render(),
        ]);

    }

    /**
     * [add 添加管理员]
     */
    public function add()
    {
        if ($this->request->isPost())
        {
            $param                = $this->request->param();
            $role                 = new AdminRole();
            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($role->save($param))
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
            return view('');
        }
    }

    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
        $role  = new AdminRole();
        if ($this->request->isPost())
        {

            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($role->where('id', $param['id'])->save($param) !== false)
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

            return view('', [
                'editData' => $role->getAdminRoleInfo($param['id']),
            ]);

        }
    }

    /**
     * [delete 删除操作]
     * @return [type] [description]
     */
    public function del()
    {
        $id = $this->request->param('id', 0, 'intval');

        $role   = new AdminRole();
        $result = $role->update(['delete_status' => 1], ['id' => $id]);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }

    /**
     * [tree 权限树形结构图]
     * @return [type] [description]
     */
    public function tree()
    {
        $param = $this->request->param();

        $menu = new AdminMenu();

        if ($this->request->isPost())
        {
            if (isset($param['authids']))
            {

                $role = new AdminRole();
                if ($role->where('id', $param['id'])->save([
                    'role_auth' => implode(',', $param['authids']),
                ]))
                {
                    $this->success('操作成功');
                }

            }
            $this->error('操作失败');
        }
        else
        {
            View::assign('id', $param['id']);
            return View::fetch();
        }
    }

    //获取当前用户的tree权限
    public function getAuthTree()
    {

        $roleId = $this->request->param('id', 0, 'intval');
        $menu   = new AdminMenu();
        $tress  = $menu->getUserAuthTree($roleId);
        return $this->success('获取成功...', '', ['trees' => $tress]);

    }


}
