<?php
declare (strict_types = 1);

namespace app\middleware;

class loginCheck
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

        if (!session('UserInfo'))
        {

            //判断用户未登录就跳转至登录页面
            if ($request->isAjax())
            {
                return json([
                    'code' => 0,
                    'msg' => '您尚未登录',
                    'url' => '/user/login/login',
                ]);

            }
            else
            {   
                //排除order控制器
                if (strtolower($request->controller()) == 'order')
                {

                    return redirect('/user/login/login');

                }
                else
                {

                    return redirect('/user/login/login')->remember();
                }
            }
        }
        //如果已登录则执行正常的请求
        return $response;
    }
}
