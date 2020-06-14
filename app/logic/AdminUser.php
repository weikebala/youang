<?php
namespace app\logic;

use app\model\AdminUser as AdminUserModel;
use think\facade\Session;
use app\util\Tools;

class AdminUser extends AdminUserModel
{

    //
    public function doLogin($param)
    {   
        $result = 0;
        $userInfo = (new AdminUser())->getAdminUserInfo(['nickname'=>$param['username']]);
        if ($userInfo) {
            $userInfo = $userInfo->toArray();
            if ($userInfo['password'] == Tools::userMd5($param['password'])) {
                Session::set('adminUserInfo',json_encode($userInfo));
                $result = 1;
            }

        }
        return $result;
    }



    public function getBannerList($where = [], $field = '*')
    {
        return $this->field($field)->where($where)
        ->where(['delete_status'=>0,'show_status'=>1])
        ->paginate();
    }

    /**
     * [getUserList ]
     * @param  string $value [description]
     * @return [type]        [description]
     */
    public function getAdminUserList($where = [])
    {

        $result = $this->alias('a')
            ->field([
                'a.*',
                'r.role_name',
            ])
            ->leftJoin('admin_role r', 'a.role_id = r.id')
            ->where([
                'a.delete_status' => 0,
            ])
            ->where($where)
            ->paginate(12);
        return $result;
    }


    //保存数据
    public function saveUserData($data)
    {
        $result = $this->save($data);
        print_r($result);exit();
    }


}
