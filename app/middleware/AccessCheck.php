<?php
declare (strict_types = 1);

namespace app\middleware;

use think\facade\Db;

class AccessCheck
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        $access = 0;

        $roleId = getUserInfoData(1, 'role_id');
        //超级管理员
        if ($roleId === 1)
        {
            $access = 1;
        }
        else
        {

            $roleInfo = DB::name('admin_role')
                ->field('role_auth,id')
                ->where('delete_status', 0)
                ->where('id', $roleId)
                ->find();

            if (isset($roleInfo['role_auth']) && !empty($roleInfo['role_auth']))
            {

                $module     = app('http')->getName();
                $controller = $request->controller();
                $action     = $request->action();
                $url        = $module . '/' . $controller . '/' . $action;

                $menuInfo = DB::name('admin_menu')->field('id,lower(url)')->where('url', strtolower($url))->find();

                if (in_array($menuInfo['id'], explode(',', $roleInfo['role_auth'])) || $roleInfo['id'] == 1)
                {
                    $access = 1;
                }

            }

        }
        //
        if (!$access)
        {

            if ($request->isAjax())
            {
                return json([
                    'code' => 0,
                    'msg' => '暂无权限 , authorization error',
                ]);

            }
            else
            {
                if (!session('adminUserInfo'))
                {

                    if ($request->isAjax())
                    {
                        return json([
                            'code' => 0,
                            'msg' => '您尚未登录',
                            'url' => '/admin/login/login',
                        ]);

                    }
                    else
                    {
                        return redirect('/admin/login/login');
                    }

                }
                else
                {
                    abort(401, 'authorization error');
                }
            }

        }
        //如果已登录则执行正常的请求
        return $response;
    }
}
