<?php
declare (strict_types = 1);
namespace app;

use app\BaseController;
use app\util\Menu;
use app\util\Tools;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class AdminBaseController extends BaseController
{
    //模板继承
    protected $layout = 'default';

    protected $template;

    //
    protected $webTemplateDir = 'admin';

    // 初始化
    protected function initialize()
    {

        //获取当前配置的模板
        $this->getWebTheme();
        View::assign('templateName', $this->template);
        View::assign('adminMenus', $this->getMenus());
        View::assign('contentHeader', $this->getContentHeader());
        View::assign('site_info', $this->getSeo());

        //模板继承
        if ($this->layout)
        {
            View::layout('layout/' . $this->layout);
        }
    }

    /**
     * [getWebTheme 获取当前配置的模板]
     */
    public function getWebTheme()
    {

        $template = DB::name('setting')->field('content')
            ->where(['category_name' => 'adminTemplate'])->find();

        $this->template = !empty($template['content']) ? $template['content'] : 'default';
        $path           = WEB_ROOT . DIRECTORY_SEPARATOR . config('view.view_dir_name') . DIRECTORY_SEPARATOR . $this->webTemplateDir . DIRECTORY_SEPARATOR . $this->template . DIRECTORY_SEPARATOR . app('http')->getName() . '/';
        $this->viewTplReplaceString();

        View::config(['view_path' => $path]);

    }

    //模板字符串替换
    public function viewTplReplaceString()
    {
        $viewConfig = config('view');

        $tempStr = str_replace("public", "", $viewConfig['view_dir_name']) . DIRECTORY_SEPARATOR . $this->webTemplateDir . DIRECTORY_SEPARATOR . $this->template . DIRECTORY_SEPARATOR . 'public/static';

        $viewReplaceStr = [
            '__ADMINSTATIC__' => $tempStr,
        ];

        View::config(['tpl_replace_string' => array_merge($viewConfig['tpl_replace_string'], $viewReplaceStr)]);

    }

    //contentHeader获取当前header头的内容
    public function getContentHeader()
    {
        return Menu::buildContentHeader();
    }

    final public function getMenus()
    {

        if (!empty(Session::get('adminUserInfo')))
        {

            $userInfo = json_decode(Session::get('adminUserInfo'), true);
            $role_id = $userInfo['role_id'];
            Session::set('admin_nickname', $userInfo['nickname']);
            Session::set('admin_userid', $userInfo['id']);
            // echo Session::get('admin_nickname');
            // exit;
            if ($role_id !== 1)
            {

                $role = DB::name('admin_role')->field('role_auth')->where('delete_status', 0)
                    ->where('id', $role_id)->find();

                Session::set('adminAuth', $role['role_auth']);
                if (empty($role['role_auth']))
                {
                    Session::set('adminUserInfo', null);
                    return;
                }
                $whereIn = [
                    ['id', 'in', $role['role_auth']],
                ];

            }
            else
            {

                $whereIn = [];
            }

            $access = DB::name('admin_menu')
                ->where('show_status', 1)
                ->where('delete_status', 0)
                ->where('type', 'in', '1,2')
                ->where($whereIn)
                ->order('sort', 'asc')
                ->select()->toArray();

            //组装选中状态
            $access = Menu::getActiveStatus($access);
            // //判断当前权限
            //组装目录
            $menus = Menu::buildMenus(
                Tools::listToTree($access, 'id', 'parent_id')
            );
            return $menus;
        }
    }

    //检查用户是否拥有权限
    public function checkAccess()
    {

        $roleId = getUserInfoData(1, 'role_id');

        //超级管理员
        if ($roleId === 1)
        {
            return 1;
        }
        else
        {

            $roleInfo = DB::name('admin_role')
                ->field('role_auth,id')
                ->where('delete_status', 0)
                ->where('id', $roleId)
                ->find();

            // print_r($roleInfo);exit();
            if (isset($roleInfo['role_auth']) && !empty($roleInfo['role_auth']))
            {

                $module     = app('http')->getName();
                $controller = $this->request->controller();
                $action     = $this->request->action();
                $url        = $module . '/' . $controller . '/' . $action;

                $menuInfo = DB::name('admin_menu')->field('id,lower(url)')->where('url', strtolower($url))->find();

                if (in_array($menuInfo['id'], explode(',', $roleInfo['role_auth'])) || $roleInfo['id'] == 1)
                {
                    return 1;
                }

            }

        }
        return 0;
    }

    public function getSeo()
    {
        $seo     = DB::name('setting')->field('category_name,content')->where('category', 'baseConfig')->select();
        $seoInfo = [];
        foreach ($seo as $key => $value)
        {
            $seoInfo[$value['category_name']] = $value['content'];
        }
        return $seoInfo;
        // print_r($seoInfo);exit;
    }

}
