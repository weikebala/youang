<?php
use app\util\Html;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;

// 应用公共文件
function getUrlPath($url)
{
    if (!empty($url))
    {
        if (!checkUrl($url))
        {
            $url = str_replace('\\', '/', $url);
            return '/storage/' . $url;
        }
    }

    return $url;
}

/**
 * 返回带协议的域名
 */
function getDomain()
{
    $request = Request::instance();
    return $request->domain();
}

function checkUrl($url)
{

    $pattern = "/^(http|https):\/\/.*$/i";

    if (preg_match($pattern, $url))
    {
        return true;
    }
    else
    {
        return false;
    }

}

function fixUrl($url, $def = false, $prefix = false)
{

    $url = trim($url);
    if (empty($url))
    {
        return $def;
    }
    if (count(explode('://', $url)) > 1)
    {
        return $url;
    }
    else
    {
        return $prefix === false ? 'http://' . $url : $prefix . $url;
    }
}

function buildConfigHtml($value)
{
    return Html::buildHtml($value);

}

//
function checkAuth($authUrl, $authId = 0)
{
    $root       = Request::root();
    $controller = Request::controller();
    $authUrl    = $authUrl == 'delete' ? 'del' : $authUrl;
    $url        = ltrim(($root . '/' . $controller . '/' . $authUrl), '/');
    // $url        = ltrim(strtolower($root . '/' . $controller . '/' . $authUrl), '/');
    // var_dump($url.'/'.$controller.'/'.$authUrl);exit();

    $Auth     = Session::get('adminAuth');
    $userInfo = json_decode(Session::get('adminUserInfo'), true);
    $role_id  = $userInfo['role_id'];

    if ($role_id === 1)
    {

        return getAdminAuth($authUrl, $url, $authId);
    }

    if (!empty($Auth))
    {

        $AuthArr = explode(',', $Auth);
        $idArr   = DB::name('admin_menu')->field('id')->where(['url' => $url, 'delete_status' => 0])->find();
        if (isset($idArr['id']) && !empty($idArr['id']))
        {

            //
            if (in_array($idArr['id'], $AuthArr))
            {
                return getAdminAuth($authUrl, $url, $authId);
            }
            else
            {
                return '';
            }
        }
    }

}

function getAdminAuth($authUrl, $url, $authId)
{

    $config = [
        'add' => [
            'name' => '添加',
            'icon' => 'plus',
        ],
        'edit' => [
            'name' => '编辑',
            'icon' => 'edit',
        ],
        'del' => [
            'name' => '删除',
            'icon' => 'trash',
        ],
        'info' => [
            'name' => '详情',
        ],
    ];

    $class = $authUrl == 'add' ? "btn btn-success btn-sm" : '';
    $class = $authUrl == 'del' ? 'btn-dialog' : $class;
    $url   = $authId ? $url . '/id/' . $authId : $url;

    $result = '<a href=/' . $url . ' class=" ' . $class . ' btn-' . $authUrl . '" title="' . $config[$authUrl]['name'] . '" data-msg="' . $config[$authUrl]['name'] . '">
                        <i class="fa fa-' . $config[$authUrl]['icon'] . '"></i>
                        ' . $config[$authUrl]['name'] . '
                </a>';

    return $result;

}

/**
 * 检查手机格式，中国手机不带国家代码，国际手机号格式为：国家代码-手机号
 * @param $mobile
 * @return bool
 */
function checkMobile($mobile)
{
    if (preg_match('/(^(13\d|14\d|15\d|16\d|17\d|18\d|19\d)\d{8})$/', $mobile))
    {
        return true;
    }
    else
    {
        if (preg_match('/^\d{1,4}-\d{5,11}$/', $mobile))
        {
            if (preg_match('/^\d{1,4}-0+/', $mobile))
            {
                //不能以0开头
                return false;
            }

            return true;
        }

        return false;
    }
}

function getRoundCode($length = 6)
{

    switch ($length)
    {
        case 4:
            $result = rand(1000, 9999);
            break;
        case 6:
            $result = rand(100000, 999999);
            break;
        case 8:
            $result = rand(10000000, 99999999);
            break;
        default:
            $result = rand(100000, 999999);
    }
    return $result;

}

function getUserInfoData($admin = 0, $column = 'id')
{
    $key      = $admin ? 'adminUserInfo' : 'UserInfo';
    $userInfo = Session::get($key);
    if (!empty($userInfo))
    {
        $info = json_decode($userInfo, true);
        return $info[$column];
    }
    else
    {
        return false;
    }
}

//获取用户头像
function getUserAvatarUrl($isAdmin = 0)
{
    return getUrlPath(getUserInfoData($isAdmin, 'avatar_url'));
}

function getTemplate($name)
{

    $templateDir = config('view.view_dir_name');

    $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '../' . $templateDir . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR;
    $dir = str_replace('\\', '/', $dir);

    if (is_dir($dir))
    {
        return array_slice(scandir($dir), 2);
    }

    return ['default'];
}



function hideStr($string, $bengin = 0, $len = 4, $type = 0, $glue = "@") {
    if (empty($string))
        return false;
    $array = array();
    if ($type == 0 || $type == 1 || $type == 4) {
        $strlen = $length = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, 0, 1, "utf8");
            $string = mb_substr($string, 1, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
    }
    if ($type == 0) {
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = "*";
        }
        $string = implode("", $array);
    } else if ($type == 1) {
        $array = array_reverse($array);
        for ($i = $bengin; $i < ($bengin + $len); $i++) {
            if (isset($array[$i]))
                $array[$i] = "*";
        }
        $string = implode("", array_reverse($array));
    } else if ($type == 2) {
        $array = explode($glue, $string);
        $array[0] = hideStr($array[0], $bengin, $len, 1);
        $string = implode($glue, $array);
    } else if ($type == 3) {
        $array = explode($glue, $string);
        $array[1] = hideStr($array[1], $bengin, $len, 0);
        $string = implode($glue, $array);
    } else if ($type == 4) {
        $left = $bengin;
        $right = $len;
        $tem = array();
        for ($i = 0; $i < ($length - $right); $i++) {
            if (isset($array[$i]))
                $tem[] = $i >= $left ? "*" : $array[$i];
        }
        $array = array_chunk(array_reverse($array), $right);
        $array = array_reverse($array[0]);
        for ($i = 0; $i < $right; $i++) {
            $tem[] = $array[$i];
        }
        $string = implode("", $tem);
    }
    return $string;
}