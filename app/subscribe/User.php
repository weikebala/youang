<?php
declare (strict_types = 1);

namespace app\subscribe;

use app\logic\User as UserLogic;

class User
{
    protected $eventPrefix = 'User';

    public function onLogin($user)
    {
        $userObj = new UserLogic();
        $userObj->where('id', $user['user_id'])->save([
            'last_login_ip' => $user['ip'],
            'last_login_time' => time(),
        ]);

        // UserLogin事件响应处理
    }

    public function onLogout($user)
    {
        // UserLogout事件响应处理
    }

}
