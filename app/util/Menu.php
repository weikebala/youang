<?php
namespace app\util;

use think\facade\Db;
use think\facade\Request;

class Menu
{

    /**
     * [getActiveStatus 获取选中状态]
     * @Author   HUI
     * @DateTime 2020-02-08
     * @version  [version]
     * @param    [type]     $access [description]
     * @return   [type]             [description]
     */
    public static function getActiveStatus($access)
    {
        $app        = strtolower(app('http')->getName());
        $controller = strtolower(Request::controller());
        $action     = strtolower(Request::action());
        $active     = $app . '/' . $controller . '/' . $action;
        $ids = [];
        foreach ($access as $key => $v)
        {
            $val = $v['url'];
            if (strtolower($active) == strtolower($val))
            {
                $v['active'] = 'active';
                $ids         = self::getActiveId($v['id']);
            }
            else
            {
                $v['active'] = '';
            }
            $access[$key] = $v;
        }

        //添加选中标识
        foreach ($access as $key => $value)
        {
            if (in_array($value['id'], $ids))
            {
                $value['active'] = 'active';
            }
            else
            {
                $value['active'] = '0';
            }
            $access[$key] = $value;
        }
        return $access;

    }

    /**
     * [buildMenus 组装目录]
     * @Author   HUI
     * @DateTime 2020-02-07
     * @version  [version]
     * @param    [type]     $formatTree [description]
     * @return   [type]                 [description]
     */
    public static function buildMenus($menus, &$str = '')
    {

        foreach ($menus as $key => $value)
        {

            //
            $hastreeview = !empty($value['type'])  ? '' : 'has-treeview';
            $icon        = !empty($value['icon']) ? $value['icon'] : 'circle';
            $left        = !empty($hastreeview) || !empty($value['_child']) ?  '<i class="right fa fa-angle-left"></i>' : '';
            $href        = !empty($hastreeview) || $left ? 'javascript:;' : '/' . $value['url'];
            $menuOpen    = !empty($value['active'])  ? ' menu-open' : '';

            //
            $str .= '<li class="nav-item ' . $hastreeview . $menuOpen . '">
            <a href="' . $href . '" class="nav-link ' . $value['active'] . '">
              <i class="nav-icon fa fa-' . $icon . '"></i>
              <p>'
                . $value['title'] . $left . '
              </p>
            </a>';
            if (isset($value['_child']))
            {
                $str .= '<ul class="nav nav-treeview">';
                self::buildMenus($value['_child'], $str);
                $str .= '</ul>';
            }
            else
            {
                $str .= '</li>';
            }

        }
        return $str;

    }

    /**
     * [getActivIDs 先获取选中状态]
     * @Author   HUI
     * @DateTime 2020-02-08
     * @version  [version]
     * @param    string     $value [description]
     * @return   [type]            [description]
     */
    public static function getActivIDs($access)
    {
        $app        = strtolower(app('http')->getName());
        $controller = strtolower(Request::controller());
        $action     = strtolower(Request::action());
        $active     = $app . '/' . $controller . '/' . $action;

        foreach ($access as $key => $v)
        {
            $val = $v['app'] . '/' . $v['controller'] . '/' . $v['action'];
            if ($active == $val)
            {
                $v['active'] = 'active';
            }
            else
            {
                $v['active'] = '';
            }
            $access[$key] = $v;
        }
        return $access;
    }
    /**
     * [getActiveId 获取当前选中id状态]
     * @Author   HUI
     * @DateTime 2020-02-08
     * @version  [version]
     * @param    [type]     $id        [description]
     * @param    [type]     $parent_id [description]
     * @return   [type]                [description]
     */
    public static function getActiveId($id)
    {
        return self::getTreeParentId($id);
    }

    /**
     * [getTreeParentId 根据id获取父节点id]
     * @Author   HUI
     * @DateTime 2020-02-08
     * @version  [version]
     * @param    [type]     $id [description]
     * @return   [type]         [description]
     */
    public static function getTreeParentId($id, &$result = [])
    {
        $result[] = $id;
        if ($id)
        {
            $res = DB::name('admin_menu')->where('id', $id)->find();
            if (!empty($res['parent_id']))
            {
                self::getTreeParentId($res['parent_id'], $result);
            }
        }
        return $result;
    }

    //
    public static function buildContentHeader($value='')
    {
        $app        = strtolower(app('http')->getName());
        $controller = strtolower(Request::controller());
        $action     = strtolower(Request::action());
        $active     = $app . '/' . $controller . '/' . $action;

        return DB::name('admin_menu')->where('url', strtolower($active))->find();
    }


}
