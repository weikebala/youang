<?php
namespace app\user\controller;

use app\logic\Image;
use app\logic\Order;
use app\logic\User as UserLogic;
use app\user\validate\Login as LoginValidate;
use app\util\Tools;
use app\WebBaseController;
use think\facade\View;

class User extends WebBaseController
{

    protected $middleware = ['auth'];

    protected function initialize()
    {
        parent::initialize();
        $this->userInfo = $this->getUserCentor();
        View::assign('userinfo', $this->userInfo);

    }

    //用户中心
    public function centor()
    {
        $param = $this->request->param();

        $user    = new UserLogic();
        $history = $user->getUserHistory($param);

        return view('', [
            'history' => $history,
            'page' => $history->render(),
        ]);
    }

    //用户订单
    public function order()
    {
        if (!$this->userInfo['myself'])
        {
            return redirect((string) url('user/centor', ['user_id' => $this->userId]));
        }

        $order = new Order();
        $list  = $order->getUserOrderList();
        // print_r($list);exit;
        return view('', [
            'list' => $list,
            'page' => $list->render(),
        ]);

    }

    //用户学习历史
    public function history()
    {
        if (!$this->userInfo['myself'])
        {
            return redirect((string) url('user/centor', ['user_id' => $this->userId]));
        }
        return view('');
    }

    //用户消息列表
    public function message()
    {

        if (!$this->userInfo['myself'])
        {
            return redirect((string) url('user/centor', ['user_id' => $this->userId]));
        }
        return view('');
    }

    public function setting()
    {
        $param = $this->request->param();
        $user  = new UserLogic();
        if ($this->request->isPost())
        {

            $validate = new LoginValidate();
            if (!$validate->scene('setting')->check($param))
            {
                $this->error($validate->getError());
            }

            unset($param['user_id']);

            if (!empty($param['password']) && strlen($param['password']) < 8)
            {
                $this->error('请输入至少8位数密码');
            }

            $param['password'] = !empty($param['password']) ? Tools::userMd5($param['password']) : 0;

            if (!$param['password'])
            {
                unset($param['password']);
            }

            $result = $user->where('id', getUserInfoData())->save($param);

            //更新session
            if ($param['nickname'])
            {
                $user->updateSession(0, 'nickname', $param['nickname']);
            }

            $result ? $this->success('修改成功') : $this->success('保存成功');

        }
        else
        {
            if (!$this->userInfo['myself'])
            {
                return redirect((string) url('user/centor', ['user_id' => $this->userId]));
            }

            //
            if ($this->userInfo['mobile'])
            {

                $this->userInfo['mobile'] = substr($this->userInfo['mobile'], 0, 3) . '****' . substr($this->userInfo['mobile'], 7);

            }

            return view('', [
                'userinfo' => $this->userInfo,
            ]);
        }
    }

    //头像上传
    public function avatar()
    {
        $file  = $this->request->file('file');
        $param = $this->request->param();

        $image    = new Image();
        $savename = $image->uploadImage($file, $param);
        $user     = new UserLogic();
        $userId   = getUserInfoData();
        //更新Session头像
        $user->updateSession(0, 'avatar_url', $savename);
        $user->where('id', $userId)->save(['avatar_url' => $savename]);
        $this->success('上传成功');
    }

    //绑定手机号
    public function updateMobile()
    {
        $param = $this->request->param();

        $user   = new UserLogic();
        $result = $user->updateMobile($param);

        switch ($result)
        {
            case 1:
                $this->success('绑定成功');
                break;
            case 2:
                $this->error('绑定失败');
                break;
            case 3:
                $this->error('验证码有误');
                break;
            case 4:
                $this->error('验证码失效');
                break;
            case 5:
                $this->error('该手机号已绑定');
                break;
            default:
                $this->error('绑定失败');
                break;
        }

    }

}
