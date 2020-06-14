<?php
namespace app\user\controller;

use app\AdminBaseController;
use app\logic\User as UserLogic;
use app\util\Tools;
use think\facade\View;
use app\user\validate\AdminUser as AdminUserValidate;


class AdminUser extends AdminBaseController
{

    protected $middleware = ['adminAuth','Access'];

    public function index()
    {

        $param  = $this->request->param();
        $where = Tools::buildSearchWhere($param,[
            'mobile','nickname']);
        
        $user = new UserLogic();
        $list   = $user->getUserList($where);

        return view('', [
            'userlist' => $list,
            'page' => $list->render(),
        ]);

    }
    
    /**
     * [add 添加用户]
     */
    public function add()
    {
        $param = $this->request->param();

        //
        if ($this->request->isPost())
        {


            //验证数据
            $validate = new AdminUserValidate();
            if (!$validate->check($param))
            {
                $this->error($validate->getError());
            }

            $User = new UserLogic();
            $param['password'] = Tools::userMd5($param['password']);

            if ($User->save($param))
            {
                $this->success('操作成功');
            }
            else
            {
                $this->success('操做失败');
            }

            $User->save($data);

        }
        else
        {
            return View::fetch();
        }
    }

    /**
     * [edit 编辑展示页面]
     * @return [type] [description]
     */
    public function edit()
    {
        $param = $this->request->param();
        $User  = new UserLogic();
        //
        if ($this->request->isPost())
        {


            //验证数据
            $validate = new AdminUserValidate();
            if (!$validate->scene('edit')->check($param))
            {
                $this->error($validate->getError());
            }

            $param['password'] = !empty($param['password']) ? Tools::userMd5($param['password']) : 0;

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

            View::assign('editData', $User->getUserInfo($param['id']));
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

        $User = new UserLogic();

        $result = $User->update(['delete_status' => 1], ['id' => $param['id']]);
        if ($result)
        {
            return json(['code' => 1, 'msg' => '删除成功']);
        }
        else
        {
            return json(['code' => 0, 'msg' => '删除失败']);
        }
    }

    //拉黑
    public function block()
    {
        $param = $this->request->param();

        $User = new UserLogic();

        $user_status = $param['user_status'] == 0 ? 1:0;
        
        $result = $User->update([
            'user_status' => $user_status],
             ['id' => $param['id']
        ]);

        if ($result)
        {
            return json(['code' => 1, 'msg' => '操作成功']);
        }
        else
        {
            return json(['code' => 0, 'msg' => '操作失败']);
        }
    }

}
