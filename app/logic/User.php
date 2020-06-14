<?php
namespace app\logic;

use app\model\User as UserModel;
use app\service\AliSms;
use app\util\Tools;
use think\facade\Session;

class User extends UserModel
{
    //获取验证码
    public function getMobileCode($param)
    {

        if (!checkMobile($param['mobile']))
        {
            return 1;
        }
        //检测手机号是否注册
        $info = $this->getUserInfo(['mobile' => $param['mobile']]);
        $code = getRoundCode();

        //查询当天手机号发送的次数
        $log = new RecordLog();

        $count = $log->where([
            'category' => 'smsCode',
            'name' => $param['mobile']]
        )->whereDay('create_time')->count();

        if ($count >= 5)
        {
            return 2;
        }

        //发送短信添加log
        event('LogSendSMS', ['mobile' => $param['mobile'], 'code' => $code]);

        //检测今天发送次数
        if (!empty($info))
        {
            //更新用户最后登录
            event('UserLogin', ['user_id' => $info['id'], 'ip' => $param['ip']]);
        }
        else
        {
            // 新增当前手机号为用户
            $this->save(
                [
                    'mobile' => $param['mobile'],
                    'nickname' => '昵称_' . $code,
                    'password' => Tools::userMd5($code),
                    'last_login_ip' => $param['ip'],
                    'last_login_time' => time(),
                    'create_time' => time(),
                    'update_time' => time(),
                ]);
        }

        $aliSms = new AliSms();
        $res    = $aliSms->sendSms(array_merge($param, ['code' => $code]));
        return !$res ? 3 : $code;
    }

    //用户登录
    public function doLogin($param)
    {
        if ($param['type'])
        {
            //手机号验证码登录
            return $this->mobileLogin($param);
        }
        else
        {
            // 账号密码登录
            return $this->nicknameLogin($param);
        }
    }

    //昵称/手机号登录
    public function nicknameLogin($param)
    {
        // print_r($param);exit();
        $whereOr = [
            ['mobile', '=', trim($param['nickname'])],
            ['nickname', '=', trim($param['nickname'])],
        ];

        $userInfo = $this->whereOr($whereOr)->find();
        if (empty($userInfo))
        {
            return 2; //暂无此用户
        }
        // print_r(Tools::userMd5($param['password']));exit();
        if ($userInfo['password'] == Tools::userMd5($param['password']))
        {

            $this->updateExperience($userInfo);
            Session::set('UserInfo', json_encode($userInfo));
            return 1;
        }
        return 5;

    }

    //
    public function updateExperience($userInfo)
    {

        $log    = new RecordLog();
        $result = $log->where([
            'category' => 'loginExperience', 'user_id' => $userInfo['id'],
        ])->whereDay('create_time')->find();

        if (empty($result))
        {
            $log->baseSave('loginExperience', $userInfo['id']);
            $this->where('id', $userInfo['id'])->inc('empirical', 1000)->update();
        }

    }

    //手机号登录
    public function mobileLogin($param)
    {
        $userInfo = $this->getUserInfo(['mobile' => $param['mobile']]);
        if (empty($userInfo))
        {
            return 2; //暂无此用户
        }
        $log     = new RecordLog();
        $content = $log->field('value,create_time')
            ->where(['category' => 'smsCode', 'name' => $param['mobile'], 'value' => $param['smscode']])
            ->order('create_time', 'desc')->find();
        if (empty($content))
        {
            return 3; //验证码有误
        }

        //
        if ((time() - strtotime($content['create_time'])) > 300)
        {
            return 4; //验证码无效
        }

        Session::set('UserInfo', json_encode($userInfo));
        $this->updateExperience($userInfo);

        return 1;

    }

    //根据用户ID更新session
    public function updateSession($admin, $column, $value)
    {

        $key      = $admin ? 'adminUserInfo' : 'UserInfo';
        $userInfo = Session::get($key);

        if (!empty($userInfo))
        {
            $info          = json_decode($userInfo, true);
            $info[$column] = $value;
            Session::set($key, json_encode($info));
        }

    }

    //
    public function register($param)
    {
        // print_r($param);exit();
        $userInfo = $this->getUserInfo(['nickname' => $param['nickname']]);
        if (!empty($userInfo))
        {
            return 2;
        }
        //
        if ($param['password'] == $param['repassword'])
        {
            //注册并登录

            $this->save(
                [
                    'nickname' => $param['nickname'],
                    'password' => Tools::userMd5($param['password']),
                    'last_login_ip' => $param['ip'],
                    'last_login_time' => time(),
                    'create_time' => time(),
                    'update_time' => time(),
                ]);

            $userInfo = $this->getUserInfo(['nickname' => $param['nickname']]);
            if ($userInfo)
            {
                Session::set('UserInfo', json_encode($userInfo));
                return 1;
            }

        }
        else
        {
            return 3;
        }
    }

    //忘记密码
    public function forget($param)
    {
        $userInfo = $this->getUserInfo(['mobile' => $param['mobile']]);
        if (empty($userInfo))
        {
            return 2; //暂无此用户
        }
        $log     = new RecordLog();
        $content = $log->field('value,create_time')
            ->where(['category' => 'smsCode', 'name' => $param['mobile'], 'value' => $param['smscode']])
            ->order('create_time', 'desc')->find();
        if (empty($content))
        {
            return 3; //验证码有误
        }

        //
        if ((time() - strtotime($content['create_time'])) > 300)
        {
            return 4; //验证码无效
        }

        return $this->where('id', $userInfo['id'])->save([
            'password' => Tools::userMd5($param['password']),
        ]);

    }

    //获取用户历史足迹
    public function getUserHistory($param)
    {
        $userId = isset($param['user_id']) ? $param['user_id'] : getUserInfoData(0, 'id');

        $arr = ['courseView', 'comment'];

        $whereOr = [];
        foreach ($arr as $key => $value)
        {
            $whereOr[$key] = [
                ['log.category', '=', $value],
                ['log.user_id', '=', $userId],
                ['co.show_status', '=', 1],
                ['co.delete_status', '=', 0],
            ];
        }


        $result = $this->alias('u')
            ->field([
                'log.*',
                'log.name as source_id',
                'c.url',
                'c.content',
                'co.cource_image_url as image',
                'co.id as cource_id',
                'co.title',
            ])
            ->leftJoin('record_log log', 'u.id = log.user_id')
            ->leftJoin('comment c', 'c.id = log.name')
            ->leftJoin('course co', 'co.id = log.name')
            ->whereOr($whereOr)
            ->where([
                'c.show_status' => 1,
                'c.delete_status' => 0,
            ])
            ->order('log.create_time', 'desc')
            ->paginate(['query' => ['user_id' => $userId], 'list_rows' => 3])->each(function ($item)
        {
            $item['recent_updates'] = Tools::getDate(strtotime($item['create_time']));
            return $item;
        });
        // print_r($this->getLastSql());exit;
        return $result;
    }

    //获取后台用户列表
    public function getUserList($where = [], $field = '*')
    {
        return $this->field($field)->where($where)
            ->where(['delete_status' => 0])->order('create_time desc')->paginate();
    }

    //更新用户手机号
    public function updateMobile($data)
    {

        $log     = new RecordLog();
        $content = $log->field('value,create_time')
            ->where(['category' => 'smsCode', 'name' => $data['mobile'], 'value' => $data['smscode']])
            ->order('create_time', 'desc')->find();
        if (empty($content))
        {
            return 3; //验证码有误
        }

        //
        if ((time() - strtotime($content['create_time'])) > 300)
        {
            return 4; //验证码无效
        }

        $userId = getUserInfoData();

        //查询当前手机号是否已经绑定
        $exsit = $this->where('mobile', $data['mobile'])->where('id', '<>', $userId)->find();
        if ($exsit) {
            return 5;
        }
        //更新当前用户手机号
        $result = $this->where('id', $userId)->save(['mobile' => $data['mobile']]);
        if ($result)
        {

            $this->updateSession(0, 'mobile', $data['mobile']);
            return $result;
        }

        return 2;

    }

}
