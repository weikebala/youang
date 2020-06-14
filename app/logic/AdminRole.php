<?php
namespace app\logic;

use app\model\AdminRole as AdminRoleModel;

class AdminRole extends AdminRoleModel
{

    public function getUserAuthMenu($roleId)
    {
        $roleAuth = $this->getAdminRoleInfo($roleId, 'role_auth');
        $roleAuth = $roleAuth->toArray();

        $menu = (new AdminMenu())
            ->field('id,parent_id as pId, title as name')
            ->where('delete_status', 0)
            ->order('sort', 'desc')
            ->select()->toArray();

        if ($roleAuth['role_auth'])
        {
            $authArr = array_filter(explode(',', $roleAuth['role_auth']));
            foreach ($menu as $key => $value)
            {
                if (in_array($value['id'], $authArr))
                {
                    $value['open'] = true;
                    $value['checked'] = true;
                }
                else
                {
                    $value['open'] = false;
                    $value['checked'] = false;
                }
                $menu[$key] = $value;
            }
        }

        return $menu;
            
    }

}
