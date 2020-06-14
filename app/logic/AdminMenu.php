<?php
namespace app\logic;

use app\model\AdminMenu as AdminMenuModel;
use app\util\Menu;
use app\util\Tools;

class AdminMenu extends AdminMenuModel
{


    public $menuConfig = ['add'=>'添加','edit'=>'编辑','del'=>'删除'];
    public $rabcConfig = ['add', 'del', 'edit'];


    /**
     * [getMenuList 获取当前目录列表]
     * @return [type] [description]
     */
    public function getMenuList($data = [])
    {

        if (empty($data))
        {
            $data = $this->where('delete_status', 0)->where('type','in','1,2')->order('sort', 'asc')->select()->toArray();
        }
        else
        {
            $data = $data->toArray()['data'];
        }
        // print_r($data);exit();
        return Tools::formatTree(Tools::listToTree($data, 'id', 'parent_id'), 0, 'title');
    }

    //获取当前权限的选中状态
    public function getActionCheck($edit)
    {
        $data = $edit->toArray();
        $uriPath    = explode('/', $data['url']);

        $rbac = [];
        foreach ($this->rabcConfig as $key => $value) {
            
            $rbacAction = $uriPath[0] . '/' . $uriPath[1] . '/' . $value;
            $rbac[$value] = 0;
            if ($this->getAdminMenuInfo(['url' => $rbacAction,'show_status'=>1])) {
                $rbac[$value] = 1;
            }

        }
        return $rbac;
    }

    /**
     * [addMenu 添加目录]
     */
    public function addMenu($param)
    {

        $parentExist = $this->getAdminMenuInfo(['url' => $param['url']]);
        if ($parentExist)
        {
            return 2;
        }

        $data = [
            'parent_id' => $param['parent_id'],
            'type' => $param['menuType'],
            'title' => $param['title'],
            'url' => trim($param['url']),
            'icon' => isset($param['icon']) ? $param['icon'] : 'circle',
            'remark' => $param['remark'],
            'show_status' => !empty($param['show_status']) ? 1 : 0,
        ];

        switch ($param['menuType'])
        {

            case 2: //作为默认菜单
                $result = $this->addChildMenu($data, $param);
                break;
            default:
                $result = $this->save($data);
                break;
        }
        return $result;        
    }



    //添加子菜单
    public function addChildMenu($data, $param = [])
    {


        $this->startTrans();
        
        $result  = $this->save($data);
        $lastId  = $this->id;
        $rbacArr = $this->rbacToArr($data, $param);
        if (!empty($rbacArr) && is_array($rbacArr))
        {

            //执行权限操作
            foreach ($rbacArr as $key => $value)
            {

                $urlArr = explode('/', $value);
                $exist = $this->getAdminMenuInfo(['url' => $value]);
                if ($exist)
                {

                    //更改为可用状态
                    $this->update(
                        ['delete_status' => 0, 'show_status' => 1],
                        ['id' => $exist['id']]
                    );

                }
                else
                {

                    $action = isset($urlArr[2]) ? $urlArr[2] : 'action';

                    //添加当前操作权限
                    $this->insert([
                        'parent_id' => $lastId,
                        'type' => 3,
                        'title' => $this->menuConfig[$action] ,
                        'url' => $value,
                        'icon' => !empty($param['icon']) ? trim($param['icon']) : 'circle',
                        'show_status' => 1,
                    ]);

                }

            }
        }

        // 事务回滚
        if ($result === false)
        {
            $this->rollBack();
        }
        // 事务提交
        $this->commit();
        return 1;
    }

    //
    public function rbacToArr($data, $param)
    {

        if (false === strpos($data['url'], '/'))
        {
            return [];
        }
        else
        {

            $uriPath    = explode('/', $data['url']);

            $rbac = [];
            foreach ($this->rabcConfig as $key => $value)
            {
                if (isset($param['rbac_' . $value]))
                {
                    $rbac[$key] = $uriPath[0] . '/' . $uriPath[1] . '/' . $value;
                }
            }
            return $rbac;
        }

    }


    //编辑提交
    public function editMenu($param)
    {

        $data = [
            'parent_id' => $param['parent_id'],
            'type' => $param['menuType'],
            'title' => $param['title'],
            'url' => trim($param['url']),
            'icon' => !empty($param['icon']) ? trim($param['icon']) : 'circle',
            'remark' => $param['remark'],
            'show_status' => !empty($param['show_status']) ? 1 : 0,
        ];

        
        switch ($param['menuType'])
        {

            case 2: //作为默认菜单
                $result = $this->editChildMenu($data, $param);
                break;
            default:
                $menuData = $this->find($param['id']);
                $result = $menuData->save($data);
                break;
        }
        return $result; 

    }

    //修改逻辑
    public function editChildMenu($data,$param = [])
    {


        $this->startTrans();
        $menuData = $this->find($param['id']);
        $result = $menuData->save($data);

        //
        $rabcConfig = ['add', 'del', 'edit', 'info'];
        $uriPath    = explode('/', $data['url']);

        //更新全部的子操作
        foreach ($rabcConfig as $key => $value)
        {
            $url = $uriPath[0] . '/' . $uriPath[1] . '/' . $value;

            $this->update(
                ['show_status' => 0],
                ['url' => $url]
            );
        }

        //查询是否存在，存在及更新
        $rbacArr = $this->rbacToArr($data, $param);

        if (!empty($rbacArr) && is_array($rbacArr)) {
            

            //执行权限操作
            foreach ($rbacArr as $key => $value)
            {

                $urlArr = explode('/', $value);
                $exist = $this->getAdminMenuInfo(['url' => $value]);
                if ($exist)
                {

                    //更改为可用状态
                    $this->update(
                        ['delete_status' => 0, 'show_status' => 1],
                        ['id' => $exist['id']]
                    );

                }
                else
                {

                    $action = isset($urlArr[2]) ? $urlArr[2] : 'action';
                    //添加当前操作权限
                    $this->insert([
                        'parent_id' => $param['id'],
                        'type' => 3,
                        'title' => $this->menuConfig[$action],
                        'url' => $value,
                        'icon' => 'circle',
                        'show_status' => 1,
                    ]);

                }

            }


        }

         // 事务回滚
        if ($result === false)
        {
            $this->rollBack();
        }
        // 事务提交
        $this->commit();
        return 1;



    }

    //获取已选择的权限
    public function getUserAuthTree($roleId)
    {

        $list = $this
        ->field('id,title as name, parent_id as p_id,id as value')
        ->where('show_status', 1)
        ->where('delete_status', 0)
        ->order('sort', 'asc')
        ->select()->toArray();

        $access = $this->getActiveAuth($list,$roleId);
        //获取用户
        return Tools::listToTree($access, 'id', 'p_id','list');
    }


    //获取已经选择的权限
    public function getActiveAuth($access,$roleId)
    {

        $roleInfo = (new AdminRole)->getAdminRoleInfo($roleId,'role_auth');
        $roleAuth = isset($roleInfo['role_auth']) ? $roleInfo['role_auth']:[];
        $authArr   = !empty($roleAuth) ? explode(',', $roleAuth) : [];

        foreach ($access as $key => $value) {

            $value['checked']  = in_array($value['id'],$authArr) || $roleId == 1  ? 1:0;
            $value['disabled'] =  $roleId == 1  ? 1:0;

            $access[$key] = $value;
        }

        return $access;
    }


}
