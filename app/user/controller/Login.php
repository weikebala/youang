<?php
namespace app\user\controller;

use app\logic\User;
use app\logic\Setting;
use app\user\validate\Login as LoginValidate;
use app\WebBaseController;
use think\facade\Session;
use think\facade\View;

class Login extends WebBaseController
{

    //获取验证码
    public function getSmsCode()
    {
        $param       = $this->request->param();
        $param['ip'] = $this->request->ip();

        if ($this->request->isPost()){

            $validate    = new LoginValidate();
            if (!$validate->scene('sms')->check($param))
            {
                $this->error($validate->getError());
            }

            $user   = new User();
            //查询手机号是否注册
            $result = $user->getMobileCode($param);

            switch ($result)
            {
                case 1:
                    $this->error('手机号有误','',['token'=>token()]);
                    break;
                case 2:
                    $this->error('今日发送次数较多','',['token'=>token()]);
                    break;
                case 3:
                    $this->error('验证码获取失败','',['token'=>token()]);
                    break;
                default:
                    $this->success('验证码获取成功','',['token'=>token()]);
                    break;
            }
        }

    }


    //登录验证
    public function doLogin()
    {
        $param       = $this->request->param();
        if ($this->request->isPost())
        {
            $param['ip'] = $this->request->ip();
            $user        = new User();
            
            $validate    = new LoginValidate();
            if ($param['type']) {

                if (!$validate->scene('mobileLogin')->check($param))
                {
                    $this->error($validate->getError());
                }
            }else{
                if (!$validate->scene('userLogin')->check($param))
                {
                    $this->error($validate->getError());
                }

            }

            $result      = $user->doLogin($param);
            switch ($result)
            {
                case 1:
                    $this->success('登录成功', $this->redirectUrl());
                    break;
                case 2:
                    $this->error('暂无此用户');
                    break;
                case 3:
                    $this->error('验证码有误');
                    break;
                case 4:
                    $this->error('验证码无效');
                    break;
                default:
                    $this->error('账号或密码有误');
                    break;
            }
        }

    }

    //登录
    public function login()
    {
        if (session('userInfo'))
        {

            return redirect('/');

        }
        else
        {

            return view('', [
                'loginImage' => (new Setting())->getSettingContent('loginImage'),
            ]);

        }
    }

    // 注册
    public function register()
    {
        $param = $this->request->param();
        if ($this->request->isPost())
        {

            // print_r($param);exit();
            $param['ip'] = $this->request->ip();
            $validate    = new LoginValidate();
            // !$validate->check($param)
            if (!$validate->scene('register')->check($param))
            {
                $this->error($validate->getError());
            }

            $user   = new User();
            $result = $user->register($param);
            switch ($result)
            {
                case 2:
                    $this->error('用户名已存在');
                    break;
                case 3:
                    $this->error('两次密码输入不一致');
                    break;

                default:
                    $this->success('注册成功');
                    break;
            }

        }
        else
        {
            return view('', [
                'loginImage' => (new Setting())->getSettingContent('loginImage'),
            ]);
        }
    }

    // 忘记密码
    public function forget()
    {
        $param = $this->request->param();
        if ($this->request->isPost())
        {

            $param['ip'] = $this->request->ip();
            // print_r($param);exit();
            $validate = new LoginValidate();

            if (!$validate->scene('forget')->check($param))
            {
                $this->error($validate->getError());
            }

            $user   = new User();
            $result = $user->forget($param);
            switch ($result)
            {
                case 2:
                    $this->error('暂无此用户');
                    break;
                case 3:
                    $this->error('验证码有误');
                    break;
                case 4:
                    $this->error('验证码无效');
                    break;

                default:
                    $this->success('修改成功');
                    break;
            }

        }
        else
        {
            return view('', [
                'loginImage' => (new Setting())->getSettingContent('loginImage'),
            ]);
        }
    }

    // 忘记密码
    public function logout()
    {
        Session::delete('UserInfo');
        return redirect('/');
    }

}
