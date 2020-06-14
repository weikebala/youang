<?php
declare (strict_types = 1);

namespace app\middleware;

class AdminloginCheck
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
        if (!session('adminUserInfo'))
        {

            //判断用户未登录就跳转至登录页面
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
        //如果已登录则执行正常的请求
        return $response;
    }
}