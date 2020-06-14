<?php
namespace app\admin\controller;

use app\AdminBaseController;
use app\admin\validate\Administrator as AdministratorValidate;
use app\logic\AdminRole as Role;
use app\logic\AdminUser;
use app\util\Tools;
use think\facade\View;

class Administrator extends AdminBaseController
{
    protected $middleware = ['adminAuth','Access'];
    
    
    /**
     * [AdminUserList 管理员列表]
     */
    public function index()
    {

        $param = $this->request->param();
        $where = Tools::buildSearchWhere($param, [
            'a.mobile', 'a.nickname']);

        $User = new AdminUser();
        $list = $User->getAdminUserList($where);
        return view('', [
            'userlist' => $list,
            'page' => $list->render(),
        ]);
    }

    /**
     * [add 添加管理员]
     */
    public function add()
    {
        $param = $this->request->param();

        //
        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdministratorValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $User  = new AdminUser();
            $exsit = $User->where('nickname', $param['nickname'])
                ->whereOr('mobile', $param['mobile'])->find();
            if ($exsit)
            {
                $this->error('用户名或手机号已存在');
            }

            $param['password']    = Tools::userMd5($param['password']);
            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if ($User->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->error('操做失败');
            }

            $User->save($data);

        }
        else
        {

            $role = new Role();

            return view('', [
                'rolelist' => $role->where('delete_status', 0)->select(),
            ]);

        }
    }

    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
        $User  = new AdminUser();
        $role  = new Role();

        //
        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdministratorValidate();
            if (!$validate->scene('edit')->check($param))
            {
                $this->error($validate->getError());
            }

            $User  = new AdminUser();
            $exsit = $User->where('mobile', $param['mobile'])
                ->where('id', '<>', $param['id'])->find();

            if ($exsit)
            {
                $this->error('手机号重复');
            }

            $param['password']    = !empty($param['password']) ? Tools::userMd5($param['password']) : 0;
            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if (!$param['password'])
            {
                unset($param['password']);
            }
            if ($User->where('id', $param['id'])->save($param) !== false)
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
                'rolelist' => $role->where('delete_status', 0)->select(),
                'editData' => $User->getAdminUserInfo($param['id']),
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

        $User   = new AdminUser();
        $result = $User->update(['delete_status' => 1], ['id' => $id]);
        $result ? $this->success('删除成功') : $this->error('删除失败');
    }

    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */

    public function editInfo()
    {
        $param = $this->request->param();
        $User  = new AdminUser();
        //
        if ($this->request->isPost())
        {

            //验证数据
            $validate = new AdministratorValidate();
            if (!$validate->scene('editInfo')->check($param))
            {
                $this->error($validate->getError());
            }

            $User  = new AdminUser();
            $exsit = $User->where('mobile', $param['mobile'])
                ->where('id', '<>', $param['id'])->find();

            if ($exsit)
            {
                $this->error('手机号重复');
            }

            $param['password']    = !empty($param['password']) ? Tools::userMd5($param['password']) : 0;
            $param['show_status'] = isset($param['show_status']) ? $param['show_status'] : 0;

            if (!$param['password'])
            {
                unset($param['password']);
            }
            if ($User->where('id', $param['id'])->save($param) !== false)
            {
                $this->success('操作成功');
            }
            else
            {
                $this->error('操做失败');
            }

        }
        else
        {

            return view('', [
                'editData' => $User->getAdminUserInfo($param['id']),
            ]);
        }

    }

}
