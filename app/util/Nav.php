<?php
namespace app\util;

class Nav
{

    public static function buildNav($nav, &$str = '', $lv = 0)
    {

        foreach ($nav as $key => $value)
        {

            $js     =  $value['nav_type'] == 1  ? '/index/list/?list_id='.$value['id']: fixUrl($value['url']);
            
            $class  = !empty($value['_child']) ? ' nav-item dropdown ' : ' nav-item ';
            $toggle = !empty($value['_child']) ? ' dropdown-toggle ' : '';
            $aTag   = !empty($value['_child']) ? 'id="dropdownSubMenu' . $value['id'] . '"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : '';

            $class = $lv ? 'dropdown-submenu dropdown-hover' : $class;

            $str .= '<li class="' . $class . '">
            <a href="' . $js . '" ' . $aTag . ' class="nav-link ' . $toggle . ' " target="_blank" >'
                . $value['title'] . '</a>';

            if (isset($value['_child']))
            {
                $str .= '<ul aria-labelledby="dropdownSubMenu' . $value['id'] . '" class="dropdown-menu border-0 shadow">';
                self::buildNav($value['_child'], $str, $lv + 1);
                $str .= '</ul>';
            }
            else
            {
                $str .= '</li>';
            }

        }
        return $str;
    }



    


}
