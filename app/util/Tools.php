<?php
namespace app\util;

use think\facade\Db;
use think\facade\Cache;

class Tools
{

    public static function getDate($timestamp, $type = 0)
    {
        $now  = time();
        $diff = $now - $timestamp;

        if ($diff / 86400 > 30)
        {
            return intval($diff / 86400) . '天';
        }
        //
        if ($diff <= 60)
        {
            return '刚刚';
        }
        elseif ($diff <= 3600)
        {
            return floor($diff / 60) . '分钟前';
        }
        elseif ($diff <= 86400)
        {
            return floor($diff / 3600) . '小时前';
        }
        elseif ($diff <= 2592000 || $type = 1)
        {
            return floor($diff / 86400) . '天前';
        }
        else
        {
            return '一个月前';
        }
    }

    /**
     * 二次封装的密码加密
     * @param $str
     * @param string $auth_key
     * @return string
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public static function userMd5($str, $auth_key = '')
    {
        // if (!$auth_key) {
        //     $auth_key = config('apiadmin.AUTH_KEY');
        // }
        // return '' === $str ? '' : md5(sha1($str) . $auth_key);
        return '' === $str ? '' : md5(sha1($str));
    }

    /**
     * 判断当前用户是否是超级管理员
     * @param string $uid
     * @return bool
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public static function isAdministrator($uid = '')
    {
        if (!empty($uid))
        {
            $adminConf = config('apiadmin.USER_ADMINISTRATOR');
            if (is_array($adminConf))
            {
                if (is_array($uid))
                {
                    $m = array_intersect($adminConf, $uid);
                    if (count($m))
                    {
                        return true;
                    }
                }
                else
                {
                    if (in_array($uid, $adminConf))
                    {
                        return true;
                    }
                }
            }
            else
            {
                if (is_array($uid))
                {
                    if (in_array($adminConf, $uid))
                    {
                        return true;
                    }
                }
                else
                {
                    if ($uid == $adminConf)
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * 将查询的二维对象转换成二维数组
     * @param array $res
     * @param string $key 允许指定索引值
     * @return array
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public static function buildArrFromObj($res, $key = '')
    {
        $arr = [];
        foreach ($res as $value)
        {
            $value = $value->toArray();
            if ($key)
            {
                $arr[$value[$key]] = $value;
            }
            else
            {
                $arr[] = $value;
            }
        }

        return $arr;
    }

    /**
     * 将二维数组变成指定key
     * @param $array
     * @param $keyName
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     * @return array
     */
    public static function buildArrByNewKey($array, $keyName = 'id')
    {
        $list = [];
        foreach ($array as $item)
        {
            $list[$item[$keyName]] = $item;
        }
        return $list;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param string $root
     * @return array
     */
    public static function listToTree($list, $pk = 'id', $pid = 'fid', $child = '_child', $root = '0')
    {
        // print_r($list);exit();
        $tree = [];
        if (is_array($list))
        {
            // print_r($list);exit();
            $refer = [];
            foreach ($list as $key => $data)
            {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data)
            {
                $parentId = $data[$pid];
                if ($root == $parentId)
                {
                    $tree[] = &$list[$key];
                }
                else
                {
                    if (isset($refer[$parentId]))
                    {
                        $parent           = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    public static function formatTree($list, $lv = 0, $title = 'name')
    {
        $formatTree = [];
        foreach ($list as $key => $val)
        {
            $title_prefix = '';
            for ($i = 0; $i < $lv; $i++)
            {
                $title_prefix .= "|---";
            }
            $val['lv']         = $lv;
            $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
            $val['showName']   = $lv == 0 ? $val[$title] : $title_prefix . $val[$title];
            if (!array_key_exists('_child', $val))
            {
                array_push($formatTree, $val);
            }
            else
            {
                $child = $val['_child'];
                unset($val['_child']);
                array_push($formatTree, $val);
                $middle     = self::formatTree($child, $lv + 1, $title); //进行下一层递归
                $formatTree = array_merge($formatTree, $middle);
            }
        }
        return $formatTree;
    }

    public static function arraySort($array, $keys, $sort = SORT_DESC)
    {
        $keysValue = [];
        foreach ($array as $k => $v)
        {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    public static function arraySequence($array, $field, $sort = 'SORT_ASC')
    {
        $arrSort = [];
        foreach ($array as $uniqid => $row)
        {
            foreach ($row as $key => $value)
            {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    /**
     * POST请求接口数据
     * @param string $url 请求的地址
     * @param array $data 发送的数据数组
     * @return Object 返回的json对象
     */
    public static function curlPostHeader($url, $header, $data, $timeOut = 8)
    {
        // print_r($data);exit;
        $curl = curl_init();
        // 在发起连接前等待的时间
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        // curl_setopt($curl, CURLOPT_HEADER,$header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeOut);
        // curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connecttimeOut);
        //跳过SSL验证
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // var_dump(curl_error($curl));exit;
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * Notes:    敏感词过滤
     * @Author   hui
     * @DateTime 2019-09-05
     * @param    [type]     $content [description]
     * @return   [type]              [description]
     */
    public static function badWordsFilter($content)
    {
        $content = trim(preg_replace('# #', '', $content));
        $key     = 'badWordsFilter';
        $fake    = Cache::get($key);
        if (empty($fake))
        {

            $fuckArr = DB::name('dict')
                ->field('value')
                ->where([
                    'category' => 'bad_words',
                    'show_status' => 1,
                    'delete_status' => 0
                ])->select()->toArray();

            if (!empty($fuckArr))
            {
                Cache::set($key, json_encode($fuckArr), 3600);
            }
            else
            {
                return 0;
            }
        }
        else
        {
            $fuckArr = json_decode($fake, true);
        }
        //
        foreach ($fuckArr as $key => $value)
        {
            if ($value['value'])
            {
                $res[] = $value['value'];
            }
        }

        $fuckArr = $res;
        
        for ($i = 0; $i < count($fuckArr); $i++)
        {
            $fuckArr[$i] = trim($fuckArr[$i]);
            if ($fuckArr[$i] == "")
            {
                continue; //如果关键字为空就跳过本次循环
            }
            if ($content == trim($fuckArr[$i]))
            {
                return $fuckArr[$i]; //如果匹配到关键字就返回关键字
            }
        }
        
        return 0;
        // print_r($fuckArr);exit;
        // // print_r($res);exit();
        // $badWord = array_combine($res, array_fill(0, count($res), '***'));
        // return strtr($content, $badWord);
    }

    public static function getDistance($lat1, $lon1, $lat2, $lon2)
    {
        $radius = 6378.137;

        $rad = floatval(M_PI / 180.0);

        $lat1 = floatval($lat1) * $rad;
        $lon1 = floatval($lon1) * $rad;
        $lat2 = floatval($lat2) * $rad;
        $lon2 = floatval($lon2) * $rad;

        $theta = $lon2 - $lon1;

        $dist = acos(sin($lat1) * sin($lat2) +
            cos($lat1) * cos($lat2) * cos($theta)
        );

        if ($dist < 0)
        {
            $dist += M_PI;
        }

        return round($dist * $radius, 2);
    }

    /**
     * @description:根据数据
     * @param {dataArr:需要分组的数据；keyStr:分组依据}
     * @return:
     */
    public static function dataGroup(array $dataArr, string $keyStr): array
    {
        $newArr = [];
        foreach ($dataArr as $k => $val)
        {

            $newArr[$val[$keyStr]][] = $val;
        }
        return $newArr;
    }

    public static function getBreadcrumb($data, $pid)
    {
        //$date:数据表
        //$pid:父id
        static $daohang = [];
        //循环原始的分类数组
        foreach ($data as $key => $value)
        {
            if ($value['id'] == $pid)
            {
                //递归调用
                self::getBreadcrumb($data, $value['parent_id']);
                //把数组放到daohang中
                $daohang[] = $value;
            }
        }
        //返回结果
        return $daohang;
    }

    // 父id查询分类下所有子id
    public static function getUpTree($array, $pid = 0, $leave = 0)
    {
        // print_r($pid);exit();
        // $f_name = __FUNCTION__;
        static $list = [];
        foreach ($array as $key => $value)
        {
            if ($value['parent_id'] == $pid)
            {
                $value['leave'] = $leave;
                $bq             = str_repeat('|--', $value['leave']);
                // echo $bq . $value['n'] . $leave . '<br>';
                $list[] = $value['parent_id'];
                unset($array[$key]);
                self::getUpTree($array, $value['id'], $leave + 1);
            }
        }
        return array_unique($list);
    }

    //子id查询以上父id
    public static function getOnTree($array, $id, $leave = 0)
    {
        static $list = [];
        foreach ($array as $key => $value)
        {
            if ($value['id'] == $id)
            {
                $list[] = $value['id'];
                unset($array[$key]);
                self::getOnTree($array, $value['parent_id'], $leave + 1);
            }
        }
        return $list;
    }

    /**
     * [buildSearchWhere 组装搜索数据]
     * @param  [type] $data        [description]
     * @param  array  $searchField [description]
     * @return [type]              [description]
     */
    public static function buildSearchWhere($data, $searchField = [],$prefix = '')
    {
        $searchWhere = [];

        if (isset($data['search_time']) && !empty($data['search_time']))
        {
            $time          = explode('-', $data['search_time']);
            $searchWhere[] = [$prefix."create_time", "between", [
                strtotime($time[0]),
                strtotime($time[1]),
            ]];
        }

        if (isset($data['keywords']) && !empty($data['keywords']))
        {

            if (is_array($searchField))
            {
                $field         = implode('|', $searchField);
                $searchWhere[] = [
                    $field,
                    'like',
                    '%' . $data['keywords'] . '%',
                ];

            }
            else
            {
                $searchWhere[] = [
                    $searchField,
                    'like',
                    '%' . $data['keywords'] . '%',
                ];
            }

        }

        return $searchWhere;
    }

}
